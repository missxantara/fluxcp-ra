<?php
/**
 *
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
}
?>