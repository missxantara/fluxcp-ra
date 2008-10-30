<?php
require_once 'Flux/Config.php';
require_once 'Flux/Error.php';
require_once 'Flux/Connection.php';
require_once 'Flux/LoginServer.php';
require_once 'Flux/CharServer.php';
require_once 'Flux/MapServer.php';
require_once 'Flux/Athena.php';
require_once 'Flux/LoginAthenaGroup.php';
require_once 'Flux/Addon.php';
require_once 'functions/svn_version.php';

// Get the SVN revision of the top-level directory (FLUX_ROOT).
define('FLUX_SVNVERSION', svn_version());

/**
 * The Flux class contains methods related to the application on the larger
 * scale. For the most part, it handles application initialization such as
 * parsing the configuration files and whatnot.
 */
class Flux {
	/**
	 * Current version.
	 */
	const VERSION = '1.0.0';
	
	/**
	 * Top-level revision.
	 */
	const SVNVERSION = FLUX_SVNVERSION;
	
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
	 * Messages configuration object.
	 *
	 * @access public
	 * @var Flux_Config
	 */
	public static $messagesConfig;
	
	/**
	 * Collection of Flux_Athena objects.
	 *
	 * @access public
	 * @var array
	 */
	public static $servers = array();
	
	/**
	 * Registry where Flux_LoginAthenaGroup instances are kept for easy
	 * searching.
	 *
	 * @access public
	 * @var array
	 */
	public static $loginAthenaGroupRegistry = array();
	
	/**
	 * Registry where Flux_Athena instances are kept for easy searching.
	 *
	 * @access public
	 * @var array
	 */
	public static $athenaServerRegistry = array();
	
	/**
	 * Object containing all of Flux's session data.
	 *
	 * @access public
	 * @var Flux_SessionData
	 */
	public static $sessionData;
	
	/**
	 *
	 */
	public static $numberOfQueries = 0;
	
	/**
	 *
	 */
	public static $addons = array();
	
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
		$required = array('appConfigFile', 'serversConfigFile', 'messagesConfigFile');
		foreach ($required as $option) {
			if (!array_key_exists($option, $options)) {
				self::raise("Missing required option `$option' in Flux::initialize()");
			}
		}
		
		// Parse application and server configuration files, this will also
		// handle configuration file normalization. See the source for the
		// below methods for more details on what's being done.
		self::$appConfig      = self::parseAppConfigFile($options['appConfigFile']);
		self::$serversConfig  = self::parseServersConfigFile($options['serversConfigFile']);
		self::$messagesConfig = self::parseMessagesConfigFile($options['messagesConfigFile']);
		
		// Initialize server objects.
		self::initializeServerObjects();
		
		// Initialize add-ons.
		self::initializeAddons();
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
		foreach (self::$serversConfig->getChildrenConfigs() as $key => $config) {
			$connection  = new Flux_Connection($config->getDbConfig(), $config->getLogsDbConfig());
			$loginServer = new Flux_LoginServer($config->getLoginServer());
			
			// LoginAthenaGroup maintains the grouping of a central login
			// server and its underlying Athena objects.
			self::$servers[$key] = new Flux_LoginAthenaGroup($config->getServerName(), $connection, $loginServer);
			
			// Add into registry.
			self::registerServerGroup($config->getServerName(), self::$servers[$key]);
			
			foreach ($config->getCharMapServers()->getChildrenConfigs() as $charMapServer) {
				$charServer = new Flux_CharServer($charMapServer->getCharServer());
				$mapServer  = new Flux_MapServer($charMapServer->getMapServer());
				
				// Create the collective server object, Flux_Athena.
				$athena = new Flux_Athena($charMapServer, $loginServer, $charServer, $mapServer);
				self::$servers[$key]->addAthenaServer($athena);
				
				// Add into registry.
				self::registerAthenaServer($config->getServerName(), $charMapServer->getServerName(), $athena);
			}
		}
	}
	
	/**
	 *
	 */
	public static function initializeAddons()
	{
		if (!is_dir(FLUX_ADDON_DIR)) {
			return false;
		}
			
		foreach (glob(FLUX_ADDON_DIR.'/*') as $addonDir) {
			if (is_dir($addonDir)) {
				$addonName   = basename($addonDir);
				$addonObject = new Flux_Addon($addonName, $addonDir);
				self::$addons[$addonName] = $addonObject;
				
				// Merge configurations.
				self::$appConfig->merge($addonObject->addonConfig);
				self::$messagesConfig->merge($addonObject->messagesConfig);
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
	 * Wrapper method for setting and getting values from the messagesConfig.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param arary $options
	 * @access public
	 */
	public static function message($key, $value = null, $options = array())
	{
		if (!is_null($value)) {
			return self::$messagesConfig->set($key, $value, $options);
		}
		else {
			return self::$messagesConfig->get($key);
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
		ob_start();
		// Uses require, thus assumes the file returns an array.
		$config = require $filename;
		ob_end_clean();
		return self::parseConfig($config);
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
		
		if (!$config->getThemeName()) {
			self::raise('ThemeName is required in application configuration.');
		}
		elseif (!self::themeExists($themeName=$config->getThemeName())) {
			self::raise("The selected theme '$themeName' does not exist.");
		}
		elseif (!($config->getPayPalReceiverEmails() instanceOf Flux_Config)) {
			self::raise("PayPalReceiverEmails must be an array.");
		}
		
		// Sanitize BaseURI. (leading forward slash is mandatory.)
		$baseURI = $config->get('BaseURI');
		if (strlen($baseURI) && $baseURI[0] != '/') {
			$config->set('BaseURI', "/$baseURI");
		}
		
		return $config;
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
		$config            = self::parseConfigFile($filename);
		$options           = array('overwrite' => false, 'force' => true); // Config::set() options.
		$serverNames       = array();
		$athenaServerNames = array();
		
		if (!count($config->toArray())) {
			self::raise('At least one server configuration must be present.');
		}
		
		foreach ($config->getChildrenConfigs() as $topConfig) {
			//
			// Top-level normalization.
			//
			
			if (!($serverName = $topConfig->getServerName())) {
				self::raise('ServerName is required for each top-level server configuration, check your servers configuration file.');
			}
			elseif (in_array($serverName, $serverNames)) {
				self::raise("The server name '$serverName' has already been configured. Please use another name.");
			}
			
			$serverNames[] = $serverName;
			$athenaServerNames[$serverName] = array();
			
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
			
			if (!$topConfig->getCharMapServers() || !count($topConfig->getCharMapServers()->toArray())) {
				self::raise('CharMapServers must be an array and contain at least 1 char/map server entry.');
			}
			
			foreach ($topConfig->getCharMapServers()->getChildrenConfigs() as $charMapServer) {
				//
				// Char/Map normalization.
				//
				
				$charMapServer->setBaseExpRates(1, $options);
				$charMapServer->setJobExpRates(1, $options);
				$charMapServer->setMvpExpRates(1, $options);
				$charMapServer->setDropRates(1, $options);
				$charMapServer->setMvpDropRates(1, $options);
				$charMapServer->setCardDropRates(1, $options);
				$charMapServer->setCharServer(array(), $options);
				$charMapServer->setMapServer(array(), $options);
				$charMapServer->setDatabase($dbConfig->getDatabase(), $options);				
				
				if (!($athenaServerName = $charMapServer->getServerName())) {
					self::raise('ServerName is required for each CharMapServers pair in your servers configuration.');
				}
				elseif (in_array($athenaServerName, $athenaServerNames[$serverName])) {
					self::raise("The server name '$athenaServerName' under '$serverName' has already been configured. Please use another name.");
				}
				
				$athenaServerNames[$serverName][] = $athenaServerName;
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
	
	/**
	 * Parses a messages configuration file.
	 *
	 * @param string $filename
	 * @access public
	 */
	public static function parseMessagesConfigFile($filename)
	{
		$config = self::parseConfigFile($filename);
		// Nothing yet.
		return $config;
	}
	
	/**
	 * Check whether or not a theme exists.
	 *
	 * @return bool
	 * @access public
	 */
	public static function themeExists($themeName)
	{
		return is_dir(FLUX_THEME_DIR."/$themeName");
	}
	
	/**
	 * Register the server group into the registry.
	 *
	 * @param string $serverName Server group's name.
	 * @param Flux_LoginAthenaGroup Server group object.
	 * @return Flux_LoginAthenaGroup
	 * @access private
	 */
	private function registerServerGroup($serverName, Flux_LoginAthenaGroup $serverGroup)
	{
		self::$loginAthenaGroupRegistry[$serverName] = $serverGroup;
		return $serverGroup;
	}
	
	/**
	 * Register the Athena server into the registry.
	 *
	 * @param string $serverName Server group's name.
	 * @param string $athenaServerName Athena server's name.
	 * @param Flux_Athena $athenaServer Athena server object.
	 * @return Flux_Athena
	 * @access private
	 */
	private function registerAthenaServer($serverName, $athenaServerName, Flux_Athena $athenaServer)
	{
		if (!array_key_exists($serverName, self::$athenaServerRegistry) || !is_array(self::$athenaServerRegistry[$serverName])) {
			self::$athenaServerRegistry[$serverName] = array();
		}
		
		self::$athenaServerRegistry[$serverName][$athenaServerName] = $athenaServer;
		return $athenaServer;
	}
	
	/**
	 * Get Flux_LoginAthenaGroup server object by its ServerName.
	 *
	 * @param string
	 * @return mixed Returns Flux_LoginAthenaGroup instance or false on failure.
	 * @access public
	 */
	public static function getServerGroupByName($serverName)
	{
		$registry = &self::$loginAthenaGroupRegistry;
		
		if (array_key_exists($serverName, $registry) && $registry[$serverName] instanceOf Flux_LoginAthenaGroup) {
			return $registry[$serverName];
		}
		else {
			return false;
		}
	}
	
	/**
	 * Get Flux_Athena instance by its group/server names.
	 *
	 * @param string $serverName Server group name.
	 * @param string $athenaServerName Athena server name.
	 * @return mixed Returns Flux_Athena instance or false on failure.
	 * @access public
	 */
	public static function getAthenaServerByName($serverName, $athenaServerName)
	{
		$registry = &self::$athenaServerRegistry;
		if (array_key_exists($serverName, $registry) && array_key_exists($athenaServerName, $registry[$serverName]) &&
			$registry[$serverName][$athenaServerName] instanceOf Flux_Athena) {
		
			return $registry[$serverName][$athenaServerName];
		}
		else {
			return false;
		}
	}
	
	/**
	 * Get the job class name from a job ID.
	 *
	 * @param int $id
	 * @return mixed Job class or false.
	 * @access public
	 */
	public static function getJobClass($id)
	{
		$key   = "JobClasses.$id";
		$class = self::config($key);
		
		if ($class) {
			return $class;
		}
		else {
			return false;
		}
	}
	
	/**
	 * Get the job ID from a job class name.
	 *
	 * @param string $class
	 * @return mixed Job ID or false.
	 * @access public
	 */
	public static function getJobID($class)
	{
		$index = self::config('JobClassIndex')->toArray();
		if (array_key_exists($class, $index)) {
			return $index[$class];
		}
		else {
			return false;
		}
	}
	
	/**
	 * Get the homunculus class name from a homun class ID.
	 *
	 * @param int $id
	 * @return mixed Class name or false.
	 * @access public
	 */
	public static function getHomunClass($id)
	{
		$key   = "HomunClasses.$id";
		$class = self::config($key);
		
		if ($class) {
			return $class;
		}
		else {
			return false;
		}
	}

	/**
	 * Get the item type name from an item type.
	 *
	 * @param int $id
	 * @return mixed Item Type or false.
	 * @access public
	 */
	public static function getItemType($id)
	{
		$key   = "ItemTypes.$id";
		$type = self::config($key);
		
		if ($type) {
			return $type;
		}
		else {
			return false;
		}
	}
	
	/**
	 * Process donations that have been put on hold.
	 */
	public static function processHeldCredits()
	{
		$txnLogTable            = self::config('FluxTables.TransactionTable');
		$creditsTable           = self::config('FluxTables.CreditsTable');
		$trustTable             = self::config('FluxTables.DonationTrustTable');
		$loginAthenaGroups      = self::$loginAthenaGroupRegistry;
		list ($cancel, $accept) = array(array(), array());
		
		foreach ($loginAthenaGroups as $loginAthenaGroup) {
			$sql  = "SELECT account_id, payer_email, credits, mc_gross, txn_id, hold_until ";
			$sql .= "FROM {$loginAthenaGroup->loginDatabase}.$txnLogTable ";
			$sql .= "WHERE account_id > 0 AND hold_until IS NOT NULL AND payment_status = 'Completed'";
			$sth  = $loginAthenaGroup->connection->getStatement($sql);
			
			if ($sth->execute() && ($txn=$sth->fetchAll())) {
				foreach ($txn as $t) {
					$sql  = "SELECT id FROM {$loginAthenaGroup->loginDatabase}.$txnLogTable ";
					$sql .= "WHERE payment_status IN ('Cancelled_Reversed', 'Reversed', 'Refunded') AND parent_txn_id = ? LIMIT 1";
					$sth  = $loginAthenaGroup->connection->getStatement($sql);
					
					if ($sth->execute(array($t->txn_id)) && ($r=$sth->fetch()) && $r->id) {
						$cancel[] = $t->txn_id;
					}
					elseif (strtotime($t->hold_until) <= time()) {
						$accept[] = $t;
					}
				}
			}
			
			if (!empty($cancel)) {
				$ids  = implode(', ', array_fill(0, count($cancel), '?'));
				$sql  = "UPDATE {$loginAthenaGroup->loginDatabase}.$txnLogTable ";
				$sql .= "SET credits = 0, hold_until = NULL WHERE txn_id IN ($ids)";
				$sth  = $loginAthenaGroup->connection->getStatement($sql);
				$sth->execute($cancel);
			}
			
			$sql2   = "INSERT INTO {$loginAthenaGroup->loginDatabase}.$trustTable (account_id, email, create_date)";
			$sql2  .= "VALUES (?, ?, NOW())";
			$sth2   = $loginAthenaGroup->connection->getStatement($sql2);
			
			$idvals = array();
			
			foreach ($accept as $txn) {
				if ($loginAthenaGroup->loginServer->depositCredits($txn->account_id, $txn->credits, $txn->mc_gross) &&
					$sth2->execute(array($txn->account_id, $txn->payer_email))) {
						
					$idvals[] = $txn->txn_id;
				}
			}
			
			if (!empty($idvals)) {
				$ids  = implode(', ', array_fill(0, count($idvals), '?'));
				$sql  = "UPDATE {$loginAthenaGroup->loginDatabase}.$txnLogTable ";
				$sql .= "SET hold_until = NULL WHERE txn_id IN ($ids)";
				$sth  = $loginAthenaGroup->connection->getStatement($sql);

				$sth->execute($idvals);
			}
		}
	}
	
	/**
	 * Get array of equip_location bits. (bit => loc_name pairs)
	 * @return array
	 */
	public static function getEquipLocationList()
	{
		return array(
			256 => 'Upper Headgear',
			512 => 'Middle Headgear',
			  1 => 'Lower Headgear',
			 16 => 'Armor',
			  2 => 'Weapon',
			 32 => 'Shield',
			  4 => 'Garment',
			 64 => 'Footgear',
			  8 => 'Accessory 1',
			128 => 'Accessory 2'
		);
	}	
	
	/**
	 * Get array of equip_upper bits. (bit => upper_name pairs)
	 * @return array
	 */
	public static function getEquipUpperList()
	{
		return array(
			1 => 'Normal',
			2 => 'Upper',
			4 => 'Baby'
		);
	}
	
	/**
	 * Get array of equip_jobs bits. (bit => job_name pairs)
	 */
	public static function getEquipJobsList()
	{
		return array(
			pow(2,  0) => 'Novice',
			pow(2,  1) => 'Swordman',
			pow(2,  2) => 'Mage',
			pow(2,  3) => 'Archer',
			pow(2,  4) => 'Acolyte',
			pow(2,  5) => 'Merchant',
			pow(2,  6) => 'Thief',
			pow(2,  7) => 'Knight',
			pow(2,  8) => 'Priest',
			pow(2,  9) => 'Wizard',
			pow(2, 10) => 'Blacksmith',
			pow(2, 11) => 'Hunter',
			pow(2, 12) => 'Assassin',
			pow(2, 13) => 'Unused',
			pow(2, 14) => 'Crusader',
			pow(2, 15) => 'Monk',
			pow(2, 16) => 'Sage',
			pow(2, 17) => 'Rogue',
			pow(2, 18) => 'Alchemist',
			pow(2, 19) => 'Bard/Dancer',
			pow(2, 20) => 'Unused',
			pow(2, 21) => 'Taekwon',
			pow(2, 22) => 'Star Gladiator',
			pow(2, 23) => 'Soul Linker',
			pow(2, 24) => 'Gunslinger',
			pow(2, 25) => 'Ninja'
		);
	}
	
	/**
	 * Check whether a particular item type is stackable.
	 * @param int $type
	 * @return bool
	 */
	public static function isStackableItemType($type)
	{
		$nonstackables = array(1, 4, 5, 7, 8, 9);
		return !in_array($type, $nonstackables);
	}
	
	/**
	 * Perform a bitwise AND from each bit in getEquipLocationList() on $bitmask
	 * to determine which bits have been set.
	 * @param int $bitmask
	 * @return array
	 */
	public static function equipLocationsToArray($bitmask)
	{
		$arr  = array();
		$bits = self::getEquipLocationList();
		
		foreach ($bits as $bit => $name) {
			if ($bitmask & $bit) {
				$arr[] = $bit;
			}
		}
		
		return $arr;
	}
	
	/**
	 * Perform a bitwise AND from each bit in getEquipUpperList() on $bitmask
	 * to determine which bits have been set.
	 * @param int $bitmask
	 * @return array
	 */
	public static function equipUpperToArray($bitmask)
	{
		$arr  = array();
		$bits = self::getEquipUpperList();
		
		foreach ($bits as $bit => $name) {
			if ($bitmask & $bit) {
				$arr[] = $bit;
			}
		}
		
		return $arr;
	}
	
	/**
	 * Perform a bitwise AND from each bit in getEquipJobsList() on $bitmask
	 * to determine which bits have been set.
	 * @param int $bitmask
	 * @return array
	 */
	public static function equipJobsToArray($bitmask)
	{
		$arr  = array();
		$bits = self::getEquipJobsList();
		
		foreach ($bits as $bit => $name) {
			if ($bitmask & $bit) {
				$arr[] = $bit;
			}
		}
		
		return $arr;
	}
}
?>