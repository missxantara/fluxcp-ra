<?php
/**
 * The paginator helps in creating pages for SQL-stored data.
 */
class Flux_Paginator {
	/**
	 * Number of records.
	 *
	 * @access public
	 * @var int
	 */
	public $total = 0;
	
	/**
	 * Current page.
	 *
	 * @access public
	 * @var int
	 */
	public $currentPage = 1;
	
	/**
	 * Total number of pages.
	 *
	 * @access public
	 * @var int
	 */
	public $numberOfPages = 1;
	
	/**
	 * Records per-age.
	 *
	 * @access public
	 * @var int
	 */
	public $perPage;
	
	/**
	 * GET variable holding the current page number.
	 *
	 * @access public
	 * @var string
	 */
	public $pageVariable;
	
	/**
	 * Page separator used in the HTML pages generator.
	 *
	 * @access public
	 * @var string
	 */
	public $pageSeparator;
	
	/**
	 * Array of sortable column names.
	 *
	 * @access protected
	 * @var array
	 */
	protected $sortableColumns = array();
	
	/**
	 * Current column sort order.
	 *
	 * @access public
	 * @var array
	 */
	public $currentSortOrder = array();
	
	/**
	 * Create new paginator instance.
	 *
	 * @param int $total Number of record.
	 * @param array $options Paginator options.
	 * @access public
	 */
	public function __construct($total, array $options = array())
	{
		$options = array_merge(
			array('perPage' => 25, 'pageVariable' => 'p', 'pageSeparator' => '|'),
			$options
		);
		
		$this->total         = (int)$total;
		$this->perPage       = $options['perPage'];
		$this->pageVariable  = $options['pageVariable'];
		$this->pageSeparator = $options['pageSeparator'];
		$this->currentPage   = isset($_GET[$this->pageVariable]) ? $_GET[$this->pageVariable] : 1;
		
		$this->calculatePages();
	}
	
	/**
	 * Calculate the number of pages.
	 *
	 * @access private
	 */
	private function calculatePages()
	{
		$this->numberOfPages = (int)ceil($this->total / $this->perPage);
	}
	
	/**
	 * Get an SQL query with the "LIMIT offset,num" and appropriate "ORDER BY"
	 * strings appended to the end.
	 *
	 * @param string $sql
	 * @return string
	 * @access public
	 */
	public function getSQL($sql)
	{
		$orderBy = false;
		
		foreach ($this->sortableColumns as $column => $value) {
			if (strpos($column, '.') !== false) {
				list ($table, $column) = explode('.', $column, 2);
				$param = "{$table}_{$column}_order";
				$columnName = "{$table}.{$column}";
			}
			else {
				$table = false;
				$param = "{$column}_order";
				$columnName = $column;
			}
			
			$sortValues = array('ASC', 'DESC', 'NONE');
			
			// First, check if a GET parameter was passed for this column.
			if (isset($_GET[$param]) && in_array(strtoupper($_GET[$param]), $sortValues)) {
				$value = $_GET[$param];
			}
			
			// Check again just in case we're working with the default here.
			if (!is_null($value) && in_array( ($value=strtoupper($value)), $sortValues ) && $value != 'NONE') {
				$this->currentSortOrder[$columnName] = $value;
				
				if (!$orderBy) {
					$sql .= ' ORDER BY';
					$orderBy = true;
				}
				
				$sql .= " $columnName $value,";
			}
		}
		
		if ($orderBy) {
			$sql = rtrim($sql, ',');
		}
		
		$offset = ($this->perPage * $this->currentPage) - $this->perPage;
		return "$sql LIMIT $offset,{$this->perPage}";
	}
	
	/**
	 * Generate some basic HTML which creates a list of page numbers. Will
	 * return an empty string if DisplaySinglePages config is set to false.
	 *
	 * @return string
	 * @access public
	 */
	public function getHTML()
	{
		if (!Flux::config('DisplaySinglePages') && $this->numberOfPages === 1) {
			return '';
		}
		
		$pages = array();
		
		for ($i = 1; $i < $this->numberOfPages+1; ++$i) {
			$request = $_SERVER['REQUEST_URI'];
			$pageVar = preg_quote($this->pageVariable);
			
			if (preg_match("/$pageVar=(\w*)/", $request)) {
				$request = preg_replace("/$pageVar=(\w*)/", "{$this->pageVariable}={$i}", $request);
			}
			elseif (empty($_SERVER['QUERY_STRING'])) {
				$request = "$request?{$this->pageVariable}=$i";
			}
			else {
				$request = "$request&{$this->pageVariable}=$i";
			}
			
			if ($i == $this->currentPage) {
				$pages[] = sprintf(
					'<a href="%s" title="Page #%d" class="page-num current-page">%d</a>',
					$request, $i, $i
				);
			}
			else {
				$pages[] = sprintf(
					'<a href="%s" title="Page #%d" class="page-num">%d</a>',
					$request, $i, $i
				);
			}
		}
		
		$links = sprintf('<div class="pages">%s</div>', implode(" {$this->pageSeparator} ", $pages));
		return $links;
	}
	
	/**
	 * Specify an array (or a string single column name) of columns that are
	 * sortable by the paginator's features.
	 *
	 * @param array $columns
	 * @return array
	 * @access public
	 */
	public function setSortableColumns($columns)
	{
		if (!is_array($columns)) {
			$columns = array($columns);
		}
		
		foreach ($columns as $key => $column) {
			
			if (!is_numeric($key)) {
				$value  = $column;
				$column = $key;
			}
			else {
				$value  = null;
			}
			
			$this->sortableColumns[$column] = $value;
		}
		
		return $this->sortableColumns;
	}
	
	/**
	 * Get an HTML anchor which automatically links to the current request
	 * based on current sorting conditions and sets ascending/descending
	 * sorting parameters accordingly.
	 *
	 * @param string $column
	 * @param string $name
	 * @return string
	 * @access public
	 */
	public function sortableColumn($column, $name = null)
	{
		if (!$name) {
			$name = $column;
		}
		
		if (!array_key_exists($column, $this->sortableColumns)) {
			return htmlspecialchars($name);
		}
		else {
			if (strpos($column, '.') !== false) {
				list ($_table, $_column) = explode('.', $column, 2);
				$param = "{$_table}_{$_column}_order";
			}
			else {
				$table = false;
				$param = "{$column}_order";
			}
			
			$order   = 'asc';
			$format  = '<a href="%s" class="sortable">%s</a>';
			$name    = htmlspecialchars($name);
			$request = $_SERVER['REQUEST_URI'];
			
			if (isset($this->currentSortOrder[$column])) {
				switch (strtolower($this->currentSortOrder[$column])) {
					case 'asc':
						$order = 'desc';
						$name .= Flux::config('ColumnSortAscending');
						break;
					case 'desc':
						$order = is_null($this->sortableColumns[$column]) ? false : 'none';
						$name .= Flux::config('ColumnSortDescending');
						break;
					default:
						$order = 'asc';
						break;
				}
			}
			
			if ($order) {
				$value = "$param=$order";
				if (preg_match("/$param=(\w*)/", $request)) {
					$request = preg_replace("/$param=(\w*)/", $value, $request);
				}
				elseif (empty($_SERVER['QUERY_STRING'])) {
					$request = "$request?$value";
				}
				else {
					$request = "$request&$value";
				}
				return sprintf($format, $request, $name);
			}
			else {
				$request = rtrim(preg_replace("%(?:(\?)$param=(?:\w*)&?|&?$param=(?:\w*))%", '$1', $request), '?');
				return sprintf($format, $request, $name);
			}
		}
	}
}
?>