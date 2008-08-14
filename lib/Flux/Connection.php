<?php
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
		// Establish connection for main databases.
		$dsnMain = sprintf('mysql:host=%s', $dbConfig->getHostname());
		$pdoMain = new PDO($dsnMain, $dbConfig->getUsername(), $dbConfig->getPassword());
		
		// Establish separate connection just for the log database.
		$dsnLogs = sprintf('mysql:host=%s;dbname=%s', $logsDbConfig->getHostname(), $logsDbConfig->getDatabase());
		$pdoLogs = new PDO($dsnLogs, $logsDbConfig->getUsername(), $logsDbConfig->getPassword());
		
		$this->pdoMain = $pdoMain;
		$this->pdoLogs = $pdoLogs;
	}
	
	/**
	 * Get the PDO instance for the main database server connection.
	 *
	 * @return PDO
	 * @access public
	 */
	public function getConnection()
	{
		return $this->pdoMain;
	}
	
	/**
	 * Get the PDO instance for the logs database server connection.
	 *
	 * @return PDO
	 * @access public
	 */
	public function getLogsConnection()
	{
		return $this->pdoLogs;
	}
	
	/**
	 * Instanciate a PDOStatement without obtaining a PDO handler before-hand.
	 *
	 * @return PDOStatement
	 * @access public
	 */
	public function getStatement($statement, $options = array())
	{
		$sth = $this->pdoMain->prepare($statement, $options);
		return $sth;
	}
	
	/**
	 * Instanciate a PDOStatement without obtaining a PDO handler before-hand.
	 *
	 * @return PDOStatement
	 * @access public
	 */
	public function getStatementForLogs($statement, $options = array())
	{
		$sth = $this->pdoLogs->prepare($statement, $options);
		return $sth;
	}
}
?>