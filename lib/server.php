<?php
require_once 'errors/server_config_error.php';

class Server {
	public $name;
	public $address;
	public $port;
	public $dbconf;
	
	public function __construct(array $config)
	{
		if (array_key_exists('name', $config)) {
			$this->name = $config['name'];
		}
		
		if (!array_key_exists('address', $config)) {
			$this->throwException('ServerConfigError', "'address' must be present.");
		}
		elseif (!array_key_exists('port', $config)) {
			$this->throwException('ServerConfigError', "'port' must be present.");
		}
		elseif (!ctype_digit($config['port'])) {
			$this->throwException('ServerConfigError', "'port' must be a number.");
		}
		elseif (!array_key_exists('dbconf', $config)) {
			$this->throwException('ServerConfigError', "'dbconf' must be present.");
		}
		elseif (!is_array($config['dbconf'])) {
			$this->throwException('ServerConfigError', "'dbconf' must be an array.");
		}
		
		$this->address = $config['address'];
		$this->port    = (int)$config['port'];
		$this->dbconf  = $config['dbconf'];
	}
	
	public function isUp()
	{
		$sock = @fsockopen($this->address, $this->port);
		$isUp = is_resource($sock);
		if ($isUp) {
			fclose($sock);
		}
		return $isUp;
	}
	
	public function isDown()
	{
		return !$this->isUp();
	}
	
	protected function throwException($exception, $errstr)
	{
		$name = $this->name ? $this->name : '(Unknown)';
		throw new $exception("[$name] $errstr");
	}
}
?>