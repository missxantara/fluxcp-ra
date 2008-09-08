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
	 * Get an SQL query with the "LIMIT offset,num" appended to the end.
	 *
	 * @param string $sql
	 * @return string
	 * @access public
	 */
	public function getSQL($sql)
	{
		$offset = ($this->perPage * $this->currentPage) - $this->perPage;
		return "$sql LIMIT $offset,{$this->perPage}";
	}
	
	/**
	 * Generate some basic HTML which creates a list of page numbers.
	 *
	 * @return string
	 * @access public
	 */
	public function getHTML()
	{
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
}
?>