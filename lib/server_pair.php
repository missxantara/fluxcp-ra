<?php
require_once 'char_server.php';
require_once 'map_server.php';

class ServerPair {
	var $name;
	var $loginServer;
	var $charServer;
	var $mapServer;
	var $baseLevelRate;
	var $jobLevelRate;
	var $dropRate;
	
	public function __construct(LoginServer $loginServer, array $config)
	{
		$this->loginServer = $loginServer;
		
		if (!array_key_exists('name', $config)) {
			$loginServer->throwException("'name' is required in a char/map server pair.");
		}
		elseif (!array_key_exists('char_server', $config)) {
			$loginServer->throwException("'char_server' is required in a char/map server pair.");
		}
		elseif (!array_key_exists('map_server', $config)) {
			$loginServer->throwException("'map_server' is required in a char/map server pair.");
		}
		else {
			if (!array_key_exists('dbconf', $config['char_server'])) {
				$config['char_server']['dbconf'] = $loginServer->dbconf;
			}
			if (!array_key_exists('dbconf', $config['map_server'])) {
				$config['map_server']['dbconf'] = $loginServer->dbconf;
			}
			
			$this->charServer = new CharServer($config['char_server']);
			$this->mapServer  = new MapServer($config['map_server']);
		}
		
		if (array_key_exists('blvl_rate', $config)) {
			$this->baseLevelRate = $config['blvl_rate'];
		}
		if (array_key_exists('jlvl_rate', $config)) {
			$this->jobLevelRate = $config['jlvl_rate'];
		}
		if (array_key_exists('drop_rate', $config)) {
			$this->dropRate = $config['drop_rate'];
		}
		$this->name = $config['name'];
	}
	
	public function getRates($appendX = false)
	{
		$blvl = $this->baseLevelRate ? '(Unknown)' : $this->baseLevelRate;
		$jlvl = $this->jobLevelRate  ? '(Unknown)' : $this->jobLevelRate;
		$drop = $this->dropRate      ? '(Unknown)' : $this->dropRate;
		$xstr = $appendX             ? 'x'         : '';
		return sprintf('%s%s/%s%s/%s%s', $blvl, $xstr, $jlvl, $xstr, $drop, $xstr);
	}
}
?>