<?php
require_once 'login_server.php';
require_once 'map_server.php';
require_once 'char_server.php';

class ServerGroup {
	public $loginServer;
	public $mapServers = array();
	public $charServers = array();
	
	public function __construct(array $config)
	{
		$this->loginServer = new LoginServer($config);
		
		if (array_key_exists('map_servers', $config)) {
			if (!is_array($config['map_servers'])) {
				$this->loginServer->throwException("'map_servers' must be an array of map server definitions.");
			}
			else {
				foreach ($config['map_servers'] as $serverName => $serverConfig) {
					if (!ctype_digit($serverName) && !array_key_exists('name', $serverConfig)) {
						$serverConfig['name'] = $serverName;
					}
					if (!array_key_exists('dbconf', $serverConfig) || $serverConfig['dbconf'] == '[LoginServer]') {
						$serverConfig['dbconf'] = $this->loginServer->dbconf;
					}
					$this->mapServers[] = new MapServer($serverConfig);
				}
			}
		}
		
		if (array_key_exists('char_servers', $config)) {
			if (!is_array($config['char_servers'])) {
				$this->loginServer->throwException("'char_servers' must be an array of character server definitions.");
			}
			else {
				foreach ($config['char_servers'] as $serverName => $serverConfig) {
					if (!ctype_digit($serverName) && !array_key_exists('name', $serverConfig)) {
						$serverConfig['name'] = $serverName;
					}
					if (!array_key_exists('dbconf', $serverConfig) || $serverConfig['dbconf'] == '[LoginServer]') {
						$serverConfig['dbconf'] = $this->loginServer->dbconf;
					}
					$this->charServers[] = new CharServer($serverConfig);
				}
			}
		}
	}
}
?>