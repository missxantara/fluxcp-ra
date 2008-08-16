<?php
define('FLUX_ROOT',       str_replace('\\', '/', dirname(__FILE__)));
define('FLUX_CONFIG_DIR', 'config');
define('FLUX_LIB_DIR',    'lib');
define('FLUX_MODULE_DIR', 'modules');
define('FLUX_THEME_DIR',  'themes');

set_include_path(FLUX_LIB_DIR.PATH_SEPARATOR.get_include_path());
//ini_set('session.save_path', 'data/sessions');

session_start();
require_once 'Flux.php';
require_once 'Flux/Dispatcher.php';

try {
	// Initialize Flux.
	Flux::initialize(array(
		'appConfigFile'     => FLUX_CONFIG_DIR.'/application.php',
		'serversConfigFile' => FLUX_CONFIG_DIR.'/servers.php',
	));

	// Dispatch requests->modules->actions->views.
	$dispatcher = Flux_Dispatcher::getInstance();
	$dispatcher->setDefaultModule(Flux::config('DefaultModule'));
	$dispatcher->setDefaultAction(Flux::config('DefaultAction'));
	$dispatcher->dispatch(array(
		'basePath'                  => Flux::config('BaseURI'),
		'useCleanUrls'              => Flux::config('UseCleanUrls'),
		'modulePath'                => FLUX_MODULE_DIR,
		'themePath'                 => FLUX_THEME_DIR.'/'.Flux::config('ThemeName'),
		'missingActionModuleAction' => Flux::config('debugMode') ? array('errors', 'missing_action') : array('main', 'page_not_found'),
		'missingViewModuleAction'   => Flux::config('debugMode') ? array('errors', 'missing_view')   : array('main', 'page_not_found')
	));
}
catch (Exception $e) {
	define('__ERROR__', 1);
	include 'error.php';
}
?>