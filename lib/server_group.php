<?php
require_once 'login_server.php';
require_once 'server_pair.php';

class ServerGroup {
	public $loginServer;
	public $serverPairs = array();
	
	public function __construct(array $config)
	{
		$this->loginServer = new LoginServer($config);
		
		if (array_key_exists('char_map_servers', $config)) {
			if (!is_array($config['char_map_servers'])) {
				$this->loginServer->throwException("'char_map_servers' must be an array.");
			}
			else {
				foreach ($config['char_map_servers'] as $serverPair) {
					$this->serverPairs[] = new ServerPair($this->loginServer, $serverPair);
				}
			}
		}
	}
}
?>