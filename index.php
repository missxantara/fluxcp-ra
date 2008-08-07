<?php
/**
 * This is the bootstrap and front controller script.
 *
 * It does all the application bootstrapping as well as handle incoming
 * requests and where they should go.
 *
 * You shouldn't have to modify with this file yourself.
 */

set_include_path(dirname(__FILE__).'/lib'.PATH_SEPARATOR.get_include_path());

require_once 'errors/config_error.php';

require_once 'flux.php';
require_once 'server_group.php';

Flux::$config = require_once dirname(__FILE__).'/config.php';

if (!is_array(Flux::$config)) {
	throw new ConfigError('Flux configuration must be an array!  Please double-check your config.php');
}
if (!array_key_exists('servers', Flux::$config)) {
	throw new ConfigError("'servers' configuration array must exist and contain server configurations for Flux to be active.  Please edit your config.php");
}

foreach (Flux::$config['servers'] as $server) {
	Flux::$servers[] = new ServerGroup($server);
}

printf('<pre>%s</pre>', print_r(Flux::$servers, true));
?>