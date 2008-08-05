<?php
require_once 'server_group.php';

class ServerCollection {
	public $groups = array();
	
	public function __construct(array $config)
	{
		foreach ($config as $serverName => $serverConfig) {
			if (!ctype_digit($serverName) && !array_key_exists('name', $serverConfig)) {
				$serverConfig['name'] = $serverName;
			}
			$this->groups[] = new ServerGroup($serverConfig);
		}
	}
}
?>