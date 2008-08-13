<?php
require_once 'Flux/Config.php';
require_once 'Flux/Error.php';
require_once 'Flux/Connection.php';
require_once 'Flux/LoginServer.php';
require_once 'Flux/CharServer.php';
require_once 'Flux/MapServer.php';
require_once 'Flux/Athena.php';

/**
 *
 */
class Flux {
	/**
	 * Application-specific configuration object.
	 *
	 * @access public
	 * @var Flux_Config
	 */
	public static $appConfig;
	
	/**
	 * Servers configuration object.
	 *
	 * @access public
	 * @var Flux_Config
	 */
	public static $serversConfig;
	
	/**
	 * Collection of Flux_Athena objects.
	 *
	 * @access public
	 * @var array
	 */
	public static $servers = array();
	
	/**
	 * Initialize Flux application. This will handle configuration parsing and
	 * instanciating of objects crucial to the control panel.
	 *
	 * @param array $options Options to pass to initializer.
	 * @throws Flux_Error Raised when missing required options.
	 * @access public
	 */
	public static function initialize($options = array())
	{
		$required = array('appConfigFile', 'serversConfigFile');
		foreach ($required as $option) {
			if (!array_key_exists($option, $options)) {
				self::raise("Missing required option `$option' in Flux::initialize()");
			}
		}
		
		// Parse application and server configuration files, this will also
		// handle configuration file normalization. See the source for the
		// below methods for more details on what's being done.
		self::$appConfig     = self::parseAppConfigFile($options['appConfigFile']);
		self::$serversConfig = self::parseServersConfigFile($options['serversConfigFile']);
		
		// Initialize server objects.
		self::initializeServerObjects();
	}
	
	/**
	 * Initialize each Login/Char/Map server object and contain them in their
	 * own collective Athena object.
	 *
	 * This is also part of the Flux initialization phase.
	 *
	 * @access public
	 */
	public static function initializeServerObjects()
	{
		foreach (self::$serversConfig->getChildrenConfigs() as $config) {
			$connection  = new Flux_Connection($config->getDbConfig(), $config->getLogsDbConfig());
			$loginServer = new Flux_LoginServer($config->getLoginServer());
			
			$serverGroup = array('LoginServer' => $loginServer, 'Athena' => array());
			self::$servers[] = &$serverGroup;
			
			foreach ($config->getCharMapServers()->getChildrenConfigs() as $charMapServer) {
				$charServer = new Flux_CharServer($charMapServer->getCharServer());
				$mapServer  = new Flux_MapServer($charMapServer->getMapServer());
				
				// Create the collective server object, Flux_Athena.
				$serverGroup['Athena'][] = new Flux_Athena($connection, $charMapServer, $loginServer, $charServer, $mapServer);
			}
		}
	}
	
	/**
	 * Wrapper method for setting and getting values from the appConfig.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param arary $options
	 * @access public
	 */
	public static function config($key, $value = null, $options = array())
	{
		if (!is_null($value)) {
			return self::$appConfig->set($key, $value, $options);
		}
		else {
			return self::$appConfig->get($key);
		}
	}
	
	/**
	 * Convenience method for raising Flux_Error exceptions.
	 *
	 * @param string $message Message to pass to constructor.
	 * @throws Flux_Error
	 * @access public
	 */
	public static function raise($message)
	{
		throw new Flux_Error($message);
	}

	/**
	 * Parse PHP array into Flux_Config instance.
	 *
	 * @param array $configArr
	 * @access public
	 */
	public static function parseConfig(array $configArr)
	{
		return new Flux_Config($configArr);
	}
	
	/**
	 * Parse a PHP array returned as the result of an included file into a
	 * Flux_Config configuration object.
	 *
	 * @param string $filename
	 * @access public
	 */
	public static function parseConfigFile($filename)
	{
		// Uses require, thus assumes the file returns an array.
		return self::parseConfig(require($filename));
	}
	
	/**
	 * Parse a file in an application-config specific manner.
	 *
	 * @param string $filename
	 * @access public
	 */
	public static function parseAppConfigFile($filename)
	{
		$config = self::parseConfigFile($filename);
		return $config; // Does nothing special for now.
	}
	
	/**
	 * Parse a file in a servers-config specific manner. This method gets a bit
	 * nasty so beware of ugly code ;)
	 *
	 * @param string $filename
	 * @access public
	 */
	public static function parseServersConfigFile($filename)
	{
		$config  = self::parseConfigFile($filename);
		$options = array('overwrite' => false, 'force' => true); // Config::set() options.
		
		foreach ($config->getChildrenConfigs() as $topConfig) {
			//
			// Top-level normalization.
			//
			
			$topConfig->setDbConfig(array(), $options);
			$topConfig->setLogsDbConfig(array(), $options);
			$topConfig->setLoginServer(array(), $options);
			$topConfig->setCharMapServers(array(), $options);
			
			$dbConfig     = $topConfig->getDbConfig();
			$logsDbConfig = $topConfig->getLogsDbConfig();
			$loginServer  = $topConfig->getLoginServer();
			
			foreach (array($dbConfig, $logsDbConfig) as $_dbConfig) {
				$_dbConfig->setHostname('localhost', $options);
				$_dbConfig->setUsername('ragnarok', $options);
				$_dbConfig->setPassword('ragnarok', $options);
				$_dbConfig->setPersistent(true, $options);
			}
			
			$loginServer->setDatabase($dbConfig->getDatabase(), $options);
			$loginServer->setUseMD5(true, $options);
			
			// Raise error if missing essential configuration directives.
			if (!$loginServer->getAddress()) {
				self::raise('Address is required for each LoginServer section in your servers configuration.');
			}
			elseif (!$loginServer->getPort()) {
				self::raise('Port is required for each LoginServer section in your servers configuration.');
			}
			
			foreach ($topConfig->getCharMapServers()->getChildrenConfigs() as $charMapServer) {
				//
				// Char/Map normalization.
				//
				
				$charMapServer->setBaseExpRates(1, $options);
				$charMapServer->setJobExpRates(1, $options);
				$charMapServer->setDropRates(1, $options);
				$charMapServer->setCharServer(array(), $options);
				$charMapServer->setMapServer(array(), $options);
				$charMapServer->setDatabase($dbConfig->getDatabase(), $options);				
				
				if (!$charMapServer->getServerName()) {
					self::raise('ServerName is required for each CharMapServers pair in your servers configuration.');
				}

				$charServer = $charMapServer->getCharServer();
				if (!$charServer->getAddress()) {
					self::raise('Address is required for each CharServer section in your servers configuration.');
				}
				elseif (!$charServer->getPort()) {
					self::raise('Port is required for each CharServer section in your servers configuration.');
				}
				
				$mapServer = $charMapServer->getMapServer();
				if (!$mapServer->getAddress()) {
					self::raise('Address is required for each MapServer section in your servers configuration.');
				}
				elseif (!$mapServer->getPort()) {
					self::raise('Port is required for each MapServer section in your servers configuration.');
				}
			}
		}
		
		return $config;
	}
}
?>