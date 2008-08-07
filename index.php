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

require_once 'flux.php';
require_once 'server_group.php';

Flux::$config = require_once dirname(__FILE__).'/config.php';
foreach (Flux::$config['servers'] as $server) {
	Flux::$servers[] = new ServerGroup($server);
}

printf('<pre>%s</pre>', print_r(Flux::$servers, true));
?>