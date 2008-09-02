<?php
require_once 'Flux/DataObject.php';
require_once 'Flux/LoginError.php';

/**
 * Contains all of Flux's session data.
 */
class Flux_SessionData {
	private $sessionData;
	private $dataFilters = array();
	
	public $loginAthenaGroup;
	public $loginServer;
	
	public function __construct(array &$sessionData)
	{
		$this->sessionData = &$sessionData;
		$this->addDataFilter('account', array($this, 'accountSetterFilter'));
		$this->initialize();
	}
	
	private function initialize($force = false)
	{
		$keysToInit = array('account', 'serverName', 'athenaServerName', 'securityCode');
		foreach ($keysToInit as $key) {
			if ($force || !$this->{$key}) {
				$method = ucfirst($key);
				$method = "set{$method}Data";
				$this->$method(null);
			}
		}
		
		if ($this->serverName && ($this->loginAthenaGroup = Flux::getServerGroupByName($this->serverName))) {
			$this->loginServer = $this->loginAthenaGroup->loginServer;
			if (!$this->athenaServerName) {
				$this->setAthenaServerNameData(current($this->getAthenaServerNames()));
			}
		}
		
		return true;
	}
	
	public function logout()
	{
		$this->loginAthenaGroup = null;
		$this->loginServer = null;
		return $this->initialize(true);
	}
	
	public function __call($method, $args)
	{
		if (count($args) && preg_match('/set(.+?)Data/', $method, $m)) {
			$arg     = current($args);
			$meth    = $m[1];
			$meth[0] = strtolower($meth[0]);
			
			if (array_key_exists($meth, $this->dataFilters)) {
				foreach ($this->dataFilters[$meth] as $callback) {
					$arg = call_user_func($callback, $arg);
				}
			}
			
			$this->sessionData[$meth] = $arg;
		}
	}
	
	public function __get($prop)
	{
		if (array_key_exists($prop, $this->sessionData)) {
			return $this->sessionData[$prop];
		}
	}
	
	public function setData(array $keys, $value)
	{
		foreach ($keys as $key) {
			$key = ucfirst($key);
			$this->{"set{$key}Data"}($value);
		}
		return $value;
	}
	
	public function addDataFilter($key, $callback)
	{
		if (!array_key_exists($key, $this->dataFilters)) {
			$this->dataFilters[$key] = array();
		}
		
		$this->dataFilters[$key][] = $callback;
		return $callback;
	}
	
	private function accountSetterFilter($arg)
	{
		if (!($arg instanceOf Flux_DataObject)) {
			$arg = new Flux_DataObject(null, array('level' => AccountLevel::UNAUTH));
		}
		return $arg;
	}
	
	public function isLoggedIn()
	{
		return $this->account->level >= AccountLevel::NORMAL;
	}
	
	public function login($server, $username, $password)
	{
		$loginAthenaGroup = Flux::getServerGroupByName($server);
		if (!$loginAthenaGroup) {
			throw new Flux_LoginError('Invalid server.', Flux_LoginError::INVALID_SERVER);
		}
		
		if (!$loginAthenaGroup->isAuth($username, $password)) {
			throw new Flux_LoginError('Invalid login', Flux_LoginError::INVALID_LOGIN);
		}
		
		$sql = "SELECT * FROM {$loginAthenaGroup->loginDatabase}.login WHERE sex != 'S' AND level >= 0 AND userid = ? LIMIT 1";
		$smt = $loginAthenaGroup->connection->getStatement($sql);
		$res = $smt->execute(array($username));
		
		if ($res && ($row = $smt->fetch())) {
			$this->setAccountData(new Flux_DataObject($row));
			$this->setServerNameData($server);
			$this->initialize(false);
			unset($this->account->user_pass);
		}
		else {
			throw new Flux_LoginError('Unexpected error during login.', Flux_LoginError::UNEXPECTED);
		}
		
		return true;
	}
	
	public function getAthenaServerNames()
	{
		if ($this->loginAthenaGroup) {
			$names = array();
			foreach ($this->loginAthenaGroup->athenaServers as $server) {
				$names[] = $server->serverName;
			}
			return $names;
		}
		else {
			return array();
		}
	}
	
	public function getAthenaServer($name = null)
	{
		if (is_null($name) && $this->athenaServerName) {
			return $this->getAthenaServer($this->athenaServerName);
		}
		
		if ($this->loginAthenaGroup && ($server = Flux::getAthenaServerByName($this->serverName, $name))) {
			return $server;
		}
		else {
			return false;
		}
	}
}
?>