<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('FLUX_ROOT',       str_replace('\\', '/', dirname(__FILE__)));
define('FLUX_DATA_DIR',   'data');
define('FLUX_CONFIG_DIR', 'config');
define('FLUX_LIB_DIR',    'lib');
define('FLUX_MODULE_DIR', 'modules');
define('FLUX_THEME_DIR',  'themes');

set_include_path(FLUX_LIB_DIR.PATH_SEPARATOR.get_include_path());
//ini_set('session.save_path', 'data/sessions');

// Default account levels.
require_once FLUX_CONFIG_DIR.'/levels.php';

// Some necessary Flux core libraries.
require_once 'Flux.php';
require_once 'Flux/Dispatcher.php';
require_once 'Flux/SessionData.php';
require_once 'Flux/Authorization.php';
require_once 'Flux/PermissionError.php';

try {	
	// Initialize Flux.
	Flux::initialize(array(
		'appConfigFile'      => FLUX_CONFIG_DIR.'/application.php',
		'serversConfigFile'  => FLUX_CONFIG_DIR.'/servers.php',
		'messagesConfigFile' => FLUX_CONFIG_DIR.'/messages.php'
	));
	
	session_save_path(realpath(FLUX_DATA_DIR.'/sessions'));
	if (!is_writable($dir=session_save_path())) {
		throw new Flux_PermissionError("The session storage directory '$dir' is not writable.  Remedy with `chmod 0707 $dir`");
	}
	elseif (!is_writable($dir=realpath(FLUX_DATA_DIR.'/logs'))) {
		throw new Flux_PermissionError("The log storage directory '$dir' is not writable.  Remedy with `chmod 0707 $dir`");
	}
	else {
		session_start();
	}
	
	$sessionKey = Flux::config('SessionKey');
	if (empty($_SESSION[$sessionKey]) || !is_array($_SESSION[$sessionKey])) {
		$_SESSION[$sessionKey] = array();
	}
	
	// Initialize session data.
	Flux::$sessionData = new Flux_SessionData($_SESSION[$sessionKey]);
	
	// Initialize authorization component.
	$accessConfig = new Flux_Config(include(FLUX_CONFIG_DIR.'/access.php'));
	$accessConfig->set('unauthorized.index', AccountLevel::ANYONE);
	$authComponent = Flux_Authorization::getInstance($accessConfig, Flux::$sessionData);
	
	if (!Flux::config('DebugMode')) {
		ini_set('display_errors', 0);
	}

	// Dispatch requests->modules->actions->views.
	$dispatcher = Flux_Dispatcher::getInstance();
	$dispatcher->setDefaultModule(Flux::config('DefaultModule'));
	$dispatcher->dispatch(array(
		'basePath'                  => Flux::config('BaseURI'),
		'useCleanUrls'              => Flux::config('UseCleanUrls'),
		'modulePath'                => FLUX_MODULE_DIR,
		'themePath'                 => FLUX_THEME_DIR.'/'.Flux::config('ThemeName'),
		'missingActionModuleAction' => Flux::config('DebugMode') ? array('errors', 'missing_action') : array('main', 'page_not_found'),
		'missingViewModuleAction'   => Flux::config('DebugMode') ? array('errors', 'missing_view')   : array('main', 'page_not_found')
	));
}
catch (Exception $e) {
	define('__ERROR__', 1);
	include 'error.php';
}
?>