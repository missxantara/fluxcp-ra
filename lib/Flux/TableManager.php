<?php
/**
 * Manages the creation of tables that need to be created.
 */
class Flux_TableManager {
	/**
	 * Flux_Athena instances.
	 *
	 * @access public
	 * @var Flux_Athena
	 */
	public $athena;
	
	/**
	 * Directory where the logindb schemas are located.
	 *
	 * @access public
	 * @var string
	 */
	public $loginDbSchemaDir;
	
	/**
	 * Directory where the charmapdb schemas are located.
	 *
	 * @access public
	 * @var string
	 */
	public $charMapDbSchemaDir;
	
	/**
	 * Schema files located in logindb schema dir.
	 *
	 * @access public
	 * @var array
	 */
	public $loginDbSchemaFiles = array();
	
	/**
	 * Schema files located in charmapdb schema dir.
	 *
	 * @access public
	 * @var array
	 */
	public $charMapDbSchemaFiles = array();
	
	/**
	 * Logindb table names.
	 *
	 * @access public
	 * @var array
	 */
	public $loginDbTables = array();
	
	/**
	 * Charmapdb table names.
	 *
	 * @access public
	 * @var array
	 */
	public $charMapDbTables = array();
	
	/**
	 * Flag: create table(s) in logindb.
	 */
	const CREATE_IN_LOGIN_DB = 0;
	
	/**
	 * Flag: create table(s) in charmapdb.
	 */
	const CREATE_IN_CHAR_MAP_DB = 1;
	
	/**
	 * Flag: search logindb tables.
	 */
	const SEARCH_LOGIN_TABLES = 0;
	
	/**
	 * Flag: search charmapdb tables.
	 */
	const SEARCH_CHAR_MAP_TABLES = 1;
	
	/**
	 * Create new TableManager instance.
	 *
	 * @param Flux_Athena $athena
	 * @param string $loginDbSchemaDir
	 * @param string $charMapDbSchemaDir
	 */
	public function __construct(Flux_Athena $athena, $loginDbSchemaDir = null, $charMapDbSchemaDir = null)
	{
		if (is_null($loginDbSchemaDir)) {
			$loginDbSchemaDir = FLUX_DATA_DIR.'/schemas/logindb';
		}
		
		if (is_null($charMapDbSchemaDir)) {
			$charMapDbSchemaDir = FLUX_DATA_DIR.'/schemas/charmapdb';
		}
		
		$this->loginDbSchemaDir   = $loginDbSchemaDir;
		$this->charMapDbSchemaDir = $charMapDbSchemaDir;
		
		$loginDbSchemaFiles   = glob("{$loginDbSchemaDir}/*.sql");
		$charMapDbSchemaFiles = glob("{$charMapDbSchemaDir}/*.sql");
		
		foreach ($loginDbSchemaFiles as $schemaFile) {
			$schemaName = basename($schemaFile, '.sql');
			$this->loginDbSchemaFiles[$schemaName] = $schemaFile;
		}
		
		foreach ($charMapDbSchemaFiles as $schemaFile) {
			$schemaName = basename($schemaFile, '.sql');
			$this->charMapDbSchemaFiles[$schemaName] = $schemaFile;
		}
		
		$this->athena = $athena;
		$this->getExistingTables();
	}
	
	/**
	 * Get available schemas.
	 *
	 * @return array
	 * @access public
	 */
	public function getSchemas()
	{
		return array(
			'loginDb'   => array_keys($this->loginDbSchemaFiles),
			'charMapDb' => array_keys($this->charMapDbSchemaFiles)
		);
	}
	
	/**
	 * Create logindb table.
	 *
	 * @param string $schemaName
	 * @access public
	 */
	public function createLoginDbTable($schemaName)
	{
		return $this->createTable($schemaName, self::CREATE_IN_LOGIN_DB);
	}
	
	/**
	 * Create charmapdb table.
	 *
	 * @param string $schemaName
	 * @access public
	 */
	public function createCharMapDbTable($schemaName)
	{
		return $this->createTable($schemaName, self::CREATE_IN_CHAR_MAP_DB);
	}
	
	/**
	 * Check if logindb has a particular table.
	 *
	 * @param string $tableName
	 * @return bool
	 * @access public
	 */
	public function hasLoginDbTable($tableName)
	{
		return $this->hasTable($tableName, self::SEARCH_LOGIN_TABLES);
	}
	
	/**
	 * Check if charmapdb has a particular table.
	 *
	 * @param string $tableName
	 * @return bool
	 * @access public
	 */
	public function hasCharMapDbTable($tableName)
	{
		return $this->hasTable($tableName, self::SEARCH_CHAR_MAP_TABLES);
	}
	
	/**
	 * Create table.
	 *
	 * @param string $schemaName
	 * @param int $whichDatabase
	 * @access public
	 */
	public function createTable($schemaName, $whichDatabase)
	{
		switch ($whichDatabase) {
			case self::CREATE_IN_LOGIN_DB:
				if (array_key_exists($schemaName, $this->loginDbSchemaFiles)) {
					$this->athena->connection->useDatabase($this->athena->loginDatabase);
					$sql = file_get_contents($this->loginDbSchemaFiles[$schemaName]);
					$sth = $this->athena->connection->getStatement($sql);
					return $sth->execute();
				}
				break;
			case self::CREATE_IN_CHAR_MAP_DB:
				if (array_key_exists($schemaName, $this->charMapDbSchemaFiles)) {
					$this->athena->connection->useDatabase($this->athena->charMapDatabase);
					$sql = file_get_contents($this->charMapDbSchemaFiles[$schemaName]);
					$sth = $this->athena->connection->getStatement($sql);
					return $sth->execute();
				}
				break;
			default:
				return false;
				break;
		}
	}
	
	/**
	 * Check if a table exists.
	 *
	 * @param string $tableName
	 * @param int $whichTables
	 * @access public
	 */
	protected function hasTable($tableName, $whichTables)
	{
		switch ($whichTables) {
			case self::SEARCH_LOGIN_TABLES:
				return in_array($tableName, $this->loginDbTables);
				break;
			case self::SEARCH_CHAR_MAP_TABLES:
				return in_array($tableName, $this->charMapDbTables);
				break;
			default:
				return false;
				break;
		}
	}
	
	/**
	 * Get existing table names.
	 *
	 * @retun array
	 * @access public
	 */
	protected function getExistingTables()
	{
		$databases = array(
			'loginDbTables'   => $this->athena->loginDatabase,
			'charMapDbTables' => $this->athena->charMapDatabase
		);
		
		foreach ($databases as $tableArray => $database) {
			$sql = "SHOW TABLES FROM $database";
			$sth = $this->athena->connection->getStatement($sql);
			$sth->execute(array($database));
			
			$res = $sth->fetchAll();
			if ($res) {
				foreach ($res as $obj) {
					array_push($this->{$tableArray}, $obj->{"Tables_in_$database"});
				}
			}
		}
		
		return array('loginDbTables' => $this->loginDbTables, 'charMapDbTables' => $this->charMapDbTables);
	}
}
?>