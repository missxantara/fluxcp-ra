<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('FLUX_ROOT',       str_replace('\\', '/', dirname(__FILE__)));
define('FLUX_CONFIG_DIR', 'config');
define('FLUX_LIB_DIR',    'lib');
define('FLUX_MODULE_DIR', 'modules');
define('FLUX_THEME_DIR',  'themes');

set_include_path(FLUX_LIB_DIR.PATH_SEPARATOR.get_include_path());
//ini_set('session.save_path', 'data/sessions');

require_once 'Flux.php';
require_once 'Flux/Dispatcher.php';
require_once 'Flux/SessionData.php';

try {
	session_start();
	if (empty($_SESSION['FLUX_SESSION_DATA']) || !is_array($_SESSION['FLUX_SESSION_DATA'])) {
		$_SESSION['FLUX_SESSION_DATA'] = array();
	}
	
	// Initialize session data.
	Flux::$sessionData = new Flux_SessionData($_SESSION['FLUX_SESSION_DATA']);
	
	// Initialize Flux.
	Flux::initialize(array(
		'appConfigFile'     => FLUX_CONFIG_DIR.'/application.php',
		'serversConfigFile' => FLUX_CONFIG_DIR.'/servers.php',
	));
	
	if (!Flux::config('DebugMode')) {
		ini_set('display_errors', 0);
	}

	// Dispatch requests->modules->actions->views.
	$dispatcher = Flux_Dispatcher::getInstance();
	$dispatcher->setDefaultModule(Flux::config('DefaultModule'));
	$dispatcher->setDefaultAction(Flux::config('DefaultAction'));
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