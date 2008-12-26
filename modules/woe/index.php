<?php
if (!defined('FLUX_ROOT')) exit;

$title     = Flux::message('WoeTitle');
$sunday    = date('l', $sun=strtotime('Sunday'));
$monday    = date('l', $mon=($sun+86400));
$tuesday   = date('l', $tue=($mon+86400));
$wednesday = date('l', $wed=($tue+86400));
$thursday  = date('l', $thu=($wed+86400));
$friday    = date('l', $fri=($thu+86400));
$saturday  = date('l', $sat=($fri+86400));
$dayNames  = array($sunday, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday);
$woeTimes  = array();

foreach ($session->loginAthenaGroup->athenaServers as $athenaServer) {
	$times = $athenaServer->woeDayTimes;
	if ($times) {
		$woeTimes[$athenaServer->serverName] = array();
		foreach ($times as $time) {
			$woeTimes[$athenaServer->serverName][] = array(
				'startingDay'  => $dayNames[$time['startingDay']],
				'startingHour' => $time['startingTime'],
				'endingDay'    => $dayNames[$time['endingDay']],
				'endingHour'   => $time['endingTime']
			);
		}
	}
}
?>