<?php
define('FLUX_ROOT',       str_replace('\\', '/', dirname(__FILE__)));
define('FLUX_CONFIG_DIR', FLUX_ROOT.'/config');
define('FLUX_LIB_DIR',    FLUX_ROOT.'/lib');

set_include_path(FLUX_LIB_DIR.PATH_SEPARATOR.get_include_path());
//ini_set('session.save_path', FLUX_ROOT.'/data/sessions');

session_start();
require_once 'Flux.php';

// Initialize Flux.
Flux::initialize(array(
	'appConfigFile'     => FLUX_CONFIG_DIR.'/application.php',
	'serversConfigFile' => FLUX_CONFIG_DIR.'/servers.php'
));

printf('<pre>%s</pre>', print_r(Flux::$servers, true));
?>