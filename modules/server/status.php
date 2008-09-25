<?php
if (!defined('FLUX_ROOT')) exit;

$title = 'Current Server Status';

$serverStatus = array();
foreach (Flux::$loginAthenaGroupRegistry as $groupName => $loginAthenaGroup) {
	if (!array_key_exists($groupName, $serverStatus)) {
		$serverStatus[$groupName] = array();
	}
	
	$loginServerUp = $loginAthenaGroup->loginServer->isUp();
	
	foreach ($loginAthenaGroup->athenaServers as $athenaServer) {
		$serverName = $athenaServer->serverName;
		
		$sql = "SELECT COUNT(char_id) AS players_online FROM {$athenaServer->charMapDatabase}.char WHERE online > 0";
		$sth = $loginAthenaGroup->connection->getStatement($sql);
		$sth->execute();
		$res = $sth->fetch();
		
		$serverStatus[$groupName][$serverName] = array(
			'loginServerUp' => $loginServerUp,
			 'charServerUp' => $athenaServer->charServer->isUp(),
			  'mapServerUp' => $athenaServer->mapServer->isUp(),
			'playersOnline' => intval($res ? $res->players_online : 0)
		);
	}
}
?>