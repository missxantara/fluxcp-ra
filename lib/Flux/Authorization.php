<?php
require_once 'Flux/Error.php';

class Flux_Authorization {
	private static $auth;
	private $config;
	private $session;
	
	private function __construct(Flux_Config $accessConfig, Flux_SessionData $sessionData)
	{
		$this->config  = $accessConfig;
		$this->session = $sessionData;
	}
	
	public static function getInstance($accessConfig = null, $sessionData = null)
	{
		if (!self::$auth) {
			self::$auth = new Flux_Authorization($accessConfig, $sessionData);
		}
		return self::$auth;	
	}
	
	public function actionAllowed($moduleName, $actionName = 'index')
	{
		$accessConfig = $this->config->get('modules');
		$accessKeys   = array("$moduleName.$actionName", "$moduleName.*");
		$accountLevel = $this->session->account->level;
		
		if ($accessConfig instanceOf Flux_Config) {
			foreach ($accessKeys as $accessKey) {
				$accessLevel = $accessConfig->get($accessKey);
			
				if (!is_null($accessLevel) &&
					($accessLevel == AccountLevel::ANYONE || $accessLevel == $accountLevel ||
					($accessLevel != AccountLevel::UNAUTH && $accessLevel <= $accountLevel))) {
					
					return true;
				}
			}
		}
		return false;
	}
	
	public function featureAllowed($featureName)
	{
		$accessConfig = $this->config->get('features');
		$accountLevel = $this->session->account->level;
		
		if (($accessConfig instanceOf Flux_Config)) {
			$accessLevel = $accessConfig->get($featureName);
			
			if (!is_null($accessLevel) &&
				($accessLevel == AccountLevel::ANYONE || $accessLevel == $accountLevel ||
				($accessLevel != AccountLevel::UNAUTH && $accessLevel <= $accountLevel))) {
			
				return true;
			}
		}
		return false;
	}
	
	public function __get($prop)
	{
		if (preg_match("/^allowedTo(.+)/i", $prop, $m)) {
			return $this->featureAllowed($m[1]);
		}
		elseif (preg_match("/^getLevelTo(.+)/i", $prop, $m)) {
			$accessConfig = $this->config->get('features');
			if ($accessConfig instanceOf Flux_Config) {
				return $accessConfig->get($m[1]);
			}
		}
	}
}
?>