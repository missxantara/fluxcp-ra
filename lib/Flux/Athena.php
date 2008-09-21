<?php
/**
 * The Athena class is used for all database interactions with each eA server,
 * hence its name.
 *
 * All methods related to creating/modifying any data in the Ragnarok databases
 * and tables shall always go into this class.
 */
class Flux_Athena {	
	/**
	 * Connection object for saving and retrieving data to the eA databases.
	 *
	 * @access public
	 * @var Flux_Connection
	 */
	public $connection;
	
	/**
	 * Server name, normally something like 'My Cool High-Rate'.
	 *
	 * @access public
	 * @var string
	 */
	public $serverName;
	
	/**
	 * Base experience rater. Unlike eA, this value starts at 1 being 1%
	 * 200 being 200% and so on.
	 *
	 * @access public
	 * @var int
	 */
	public $baseExpRates;
	
	/**
	 * Job experience rate. Same rules as $baseExpRates apply.
	 *
	 * @access public
	 * @var int
	 */
	public $jobExpRates;
	
	/**
	 * Base MvP bonus experience rate. Same rules as $baseExpRates apply.
	 *
	 * @access public
	 * @var int
	 */
	public $mvpExpRates;
	
	/**
	 * Drop rate. Same rules as $baseExpRates apply.
	 *
	 * @access public
	 * @var int
	 */
	public $dropRates;
	
	/**
	 * Database used for the login-related SQL operations.
	 *
	 * @access public
	 * @var string
	 */
	public $loginDatabase;
	
	/**
	 * Database used for the char/map (aka everything else) SQL operations.
	 * This does not include log-related tasks.
	 *
	 * @access public
	 * @var string
	 */
	public $charMapDatabase;
	
	/**
	 * Login server object tied to this collective eA server.
	 *
	 * @access public
	 * @var Flux_LoginServer
	 */
	public $loginServer;
	
	/**
	 * Character server object tied to this collective eA server.
	 *
	 * @access public
	 * @var Flux_CharServer
	 */
	public $charServer;
	
	/**
	 * Map server object tied to this collective eA server.
	 *
	 * @access public
	 * @var Flux_MapServer
	 */
	public $mapServer;
	
	/**
	 * @param Flux_Connection $connection
	 * @param Flux_Config $charMapConfig
	 * @param Flux_LoginServer $loginServer
	 * @param Flux_CharServer $charServer
	 * @param Flux_MapServer $mapServer
	 * @access public
	 */
	public function __construct(Flux_Config $charMapConfig, Flux_LoginServer $loginServer, Flux_CharServer $charServer, Flux_MapServer $mapServer)
	{
		$this->loginServer     = $loginServer;
		$this->charServer      = $charServer;
		$this->mapServer       = $mapServer;
		$this->serverName      = $charMapConfig->getServerName();
		$this->loginDatabase   = $loginServer->config->getDatabase();
		$this->charMapDatabase = $charMapConfig->getDatabase();
		$this->baseExpRates    = (int)$charMapConfig->getBaseExpRates();
		$this->jobExpRates     = (int)$charMapConfig->getJobExpRates();
		$this->mvpExpRates     = (int))$charMapConfig->getMvpExpRates();
		$this->dropRates       = (int)$charMapConfig->getDropRates();
	}
	
	/**
	 *
	 */
	public function setConnection(Flux_Connection $connection)
	{
		$this->connection = $connection;
		return $connection;
	}
	
	/**
	 * When casted to a string, the server name should be used.
	 *
	 * @return string
	 * @access public
	 */
	public function __toString()
	{
		return $this->serverName;
	}
}
?>