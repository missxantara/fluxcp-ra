<?php
/**
 * Basically acts as an uppermost container holding the LoginServer and Athena
 * instances on a top level.
 */
class Flux_LoginAthenaGroup {
	/**
	 * Global server name, representing all Athena servers.
	 *
	 * @access public
	 * @var string
	 */
	public $serverName;
	
	/**
	 * Main login server for the contained Athena servers.
	 *
	 * @access public
	 * @var Flux_LoginServer
	 */
	public $loginServer;
	
	/**
	 * Array of Flux_Athena instances.
	 *
	 * @access public
	 * @var array
	 */
	public $athenaServers = array();
	
	/**
	 * Construct new Flux_LoginAthenaGroup instance.
	 *
	 * @access public
	 */
	public function __construct($serverName, Flux_LoginServer $loginServer, array $athenaServers = array())
	{
		$this->serverName  = $serverName;
		$this->loginServer = $loginServer;
		foreach ($athenaServers as $athenaServer) {
			$this->addAthenaServer($athenaServer);
		}
	}
	
	/**
	 * Add an Athena instance to the current collection.
	 *
	 * @return mixed Returns false if login servers aren't identical.
	 * @access public
	 */
	public function addAthenaServer(Flux_Athena $athenaServer)
	{
		if ($athenaServer->loginServer === $this->loginServer) {
			$this->athenaServers[] = $athenaServer;
			return $this->athenaServers;
		}
		else {
			return false;
		}
	}
}
?>