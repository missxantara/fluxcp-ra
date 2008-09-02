<?php
$serverStatus = array();
foreach (Flux::$loginAthenaGroupRegistry as $groupName => $loginAthenaGroup) {
	if (!array_key_exists($groupName, $serverStatus)) {
		$serverStatus[$groupName] = array();
	}
	
	$loginServerUp = $loginAthenaGroup->loginServer->isUp();
	
	foreach ($loginAthenaGroup->athenaServers as $athenaServer) {
		$serverName = $athenaServer->serverName;
		$serverStatus[$groupName][$serverName] = array(
			'loginServerUp' => $loginServerUp,
			 'charServerUp' => $athenaServer->charServer->isUp(),
			  'mapServerUp' => $athenaServer->mapServer->isUp()
		);
	}
}
?>