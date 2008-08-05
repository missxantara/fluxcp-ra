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
require_once 'server_collection.php';

Flux::$config           = require_once dirname(__FILE__).'/config.php';
Flux::$serverCollection = new ServerCollection(Flux::$config['servers']);
Flux::$serverGroups     = Flux::$serverCollection->groups;

// Sample server status page.
// TODO:  Do the actual bootstrap/dispatcher.
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Example Server Status Page</title>
		<style type="text/css">
			body {
				margin: 0;
				padding: 0;
			}
		
			h2 {
				margin: 0 0 20px 0;
				padding: 10px 0;
				font-family: Georgia, serif;
				font-weight: normal;
				letter-spacing: 0;
				text-align: center;
				color: #222;
				background-color: #eee;
				border-bottom: 1px solid #bbb;
			}
		
			#ServerStatus {
				margin: 0 auto;
				border-spacing: 0;
				border-collapse: collapse;
				font-family: Tahoma, Verdana, Helvetica, Arial, sans-serif;
				font-size: 90%;
				width: 250px;
			}
			
			#ServerStatus td {
				padding: 4px 6px;
				border: 1px solid #ddd;
			}
			
			.server-status {
				text-align: right;
				color: #333;
			}
			
			.login-server-status {
				/*font-weight: bold;*/
				/*font-style: italic;*/
			}
			
			.up, .down {
				font-weight: bold;
				font-style: italic;
				text-align: left;
			}
			
			.up {
				color: green;
			}
			
			.down {
				color: red;
			}
		</style>
	</head>
	
	<body>
		<h2>Server Status</h2>
		<table id="ServerStatus">
		<?php foreach (Flux::$serverGroups as $group): ?>
		<?php $status = $group->loginServer->isUp() ? 'up' : 'down' ?>
				<tr>
					<td class="login-server-status server-status"><?php echo $group->loginServer->name ?>:</td>
					<td class="login-server-status server-status <?php echo $status ?>"><?php echo ucfirst($status) ?></td>
				</tr>
			<?php foreach (array_merge($group->mapServers, $group->charServers) as $server): ?>
			<?php $status = $server->isUp() ? 'up' : 'down' ?>
				<tr>
					<td class="server-status"><?php echo $server->name ?>:</td>
					<td class="server-status <?php echo $status ?>"><?php echo ucfirst($status) ?></td>
				</tr>			
			<?php endforeach ?>
		<?php endforeach ?>
		</table>
		
		<h3>Parsed Server Configuration</h3>
		<pre><?php echo htmlentities(print_r(Flux::$serverCollection, true)) ?></pre>
	</body>
</html>