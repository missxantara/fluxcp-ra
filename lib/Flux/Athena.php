<?php
/**
 *
 */
class Flux_Athena {	
	/**
	 * Connection object for saving and retrieving data to the eA databases.
	 *
	 * @access private
	 * @var Flux_Connection
	 */
	private $connection;
	
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
	 * Base job experience rate. Same rules as $baseExpRates applies.
	 *
	 * @access public
	 * @var int
	 */
	public $jobExpRates;
	
	/**
	 * Drop rate. Same rules as $baseExpRates and $jobExpRates applies.
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
	public function __construct(Flux_Connection $connection, Flux_Config $charMapConfig, Flux_LoginServer $loginServer, Flux_CharServer $charServer, Flux_MapServer $mapServer)
	{
		$this->connection      = $connection;
		$this->loginServer     = $loginServer;
		$this->charServer      = $charServer;
		$this->mapServer       = $mapServer;
		$this->serverName      = $charMapConfig->getServerName();
		$this->loginDatabase   = $loginServer->config->getDatabase();
		$this->charMapDatabase = $charMapConfig->getDatabase();
		$this->baseExpRates    = (int)$charMapConfig->getBaseExpRates();
		$this->jobExpRates     = (int)$charMapConfig->getJobExpRates();
		$this->dropRates       = (int)$charMapConfig->getDropRates();
	}
	
	/**
	 * Validate credentials against the login server's database information.
	 *
	 * @access public
	 */
	public function isAuth($username, $password)
	{
		if ($this->loginServer->config->get('UseMD5')) {
			$password = md5($password);
		}
		// TODO: finish.
	}
}
?>