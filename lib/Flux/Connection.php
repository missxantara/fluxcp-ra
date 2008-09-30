<?php
require_once 'Flux/Connection/Statement.php';
require_once 'Flux/DataObject.php';

/**
 * The connection class acts more like a container, or connection manager and
 * anything else, really. It's true that it does establish connections to the
 * database, but it exists for the purpose of containing and separating the
 * connections to TWO databases, the logs database from which all the eA logs
 * are stored, and the main database where everything else is stored.
 */
class Flux_Connection {
	/**
	 * Main database configuration object.
	 *
	 * @access private
	 * @var Flux_Config
	 */
	private $dbConfig;
	
	/**
	 * Logs database configuration object.
	 *
	 * @access private
	 * @var Flux_Config
	 */
	private $logsDbConfig;
	
	/**
	 * @access private
	 * @var PDO
	 */
	private $pdoMain;
	
	/**
	 * @access private
	 * @var PDO
	 */
	private $pdoLogs;
	
	/**
	 * @param Flux_Config $dbConfig
	 * @param Flux_Config $logsDbConfig
	 * @access public
	 */
	public function __construct(Flux_Config $dbConfig, Flux_Config $logsDbConfig)
	{
		$this->dbConfig     = $dbConfig;
		$this->logsDbConfig = $logsDbConfig;
	}
	
	/**
	 * Establish connection to server based on config.
	 *
	 * @param Flux_Config $dbConfig
	 * @return PDO
	 * @access private
	 */
	private function connect(Flux_Config $dbConfig)
	{
		$dsn = 'mysql:';
		
		// Differentiate between a socket-type connection or an ip:port
		// connection.
		if ($sock=$dbConfig->getSocket()) {
			$dsn .= "unix_socket=$sock";
		}
		else {
			$dsn .= 'host='.$dbConfig->getHostname();
			if ($port=$dbConfig->getPort()) {
				$dsn .= ";port=$port";
			}
		}
		
		// May or may not have a database name specified.
		if ($dbName=$dbConfig->getDatabase()) {
			$dsn .= ";dbname=$dbName";
		}
		
		$persistent = array(PDO::ATTR_PERSISTENT => (bool)$dbConfig->getPersistent());
		return new PDO($dsn, $dbConfig->getUsername(), $dbConfig->getPassword(), $persistent);
	}
	
	/**
	 * Get the PDO instance for the main database server connection.
	 *
	 * @return PDO
	 * @access private
	 */
	private function getConnection()
	{
		if (!$this->pdoMain) {
			// Establish connection for main databases.
			$pdoMain       = $this->connect($this->dbConfig);
			$this->pdoMain = $pdoMain;
		}
		return $this->pdoMain;
	}
	
	/**
	 * Get the PDO instance for the logs database server connection.
	 *
	 * @return PDO
	 * @access private
	 */
	private function getLogsConnection()
	{
		if (!$this->pdoLogs) {
			// Establish separate connection just for the log database.
			$pdoLogs       = $this->connect($this->logsDbConfig);
			$this->pdoLogs = $pdoLogs;
		}
		return $this->pdoLogs;
	}
	
	/**
	 * Select database to use.
	 *
	 * @param string $dbName
	 * @return mixed
	 * @access public
	 */
	public function useDatabase($dbName)
	{
		if ($this->pdoMain) {
			return $this->getStatement("USE $dbName")->execute();
		}
		else {
			return false;
		}
	}
	
	/**
	 * Instanciate a PDOStatement without obtaining a PDO handler before-hand.
	 *
	 * @return PDOStatement
	 * @access public
	 */
	public function getStatement($statement, $options = array())
	{
		$dbh = $this->getConnection();
		$sth = $dbh->prepare($statement, $options);
		$sth->setFetchMode(PDO::FETCH_CLASS, 'Flux_DataObject', array(null));
		
		if ($sth) {
			return new Flux_Connection_Statement($sth);
		}
		else {
			return false;
		}
	}
	
	/**
	 * Instanciate a PDOStatement without obtaining a PDO handler before-hand.
	 *
	 * @return PDOStatement
	 * @access public
	 */
	public function getStatementForLogs($statement, $options = array())
	{
		$dbh = $this->getLogsConnection();
		$sth = $dbh->prepare($statement, $options);
		$sth->setFetchMode(PDO::FETCH_CLASS, 'Flux_DataObject', array(null));
		
		if ($sth) {
			return new Flux_Connection_Statement($sth);
		}
		else {
			return false;
		}
	}
	
	/**
	 *
	 */
	public function reconnectAs($username, $password)
	{
		if ($this->pdoMain) {
			$this->pdoMain = null;
		}
		
		$this->dbConfig->setPersistent(false);
		$this->dbConfig->setUsername($username);
		$this->dbConfig->setPassword($password);

		return true;
	}
}
?>