<?php
class Flux_TableManager {
	public $athena;
	public $loginDbSchemaDir;
	public $charMapDbSchemaDir;
	public $loginDbSchemaFiles = array();
	public $charMapDbSchemaFiles = array();
	public $loginDbTables = array();
	public $charMapDbTables = array();
	
	const CREATE_IN_LOGIN_DB = 0;
	const CREATE_IN_CHAR_MAP_DB = 1;
	
	const SEARCH_LOGIN_TABLES = 0;
	const SEARCH_CHAR_MAP_TABLES = 1;
	
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
	
	public function getSchemas()
	{
		return array(
			'loginDb'   => array_keys($this->loginDbSchemaFiles),
			'charMapDb' => array_keys($this->charMapDbSchemaFiles)
		);
	}
	
	public function createLoginDbTable($schemaName)
	{
		return $this->createTable($schemaName, self::CREATE_IN_LOGIN_DB);
	}
	
	public function createCharMapDbTable($schemaName)
	{
		return $this->createTable($schemaName, self::CREATE_IN_CHAR_MAP_DB);
	}
	
	public function hasLoginDbTable($tableName)
	{
		return $this->hasTable($tableName, self::SEARCH_LOGIN_TABLES);
	}
	
	public function hasCharMapDbTable($tableName)
	{
		return $this->hasTable($tableName, self::SEARCH_CHAR_MAP_TABLES);
	}
	
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