<?php
if (!defined('FLUX_ROOT')) exit;

// Check for "special" date fields.
$__dates = array();
foreach ($params->toArray() as $key => $value) {
	if (preg_match('&^(.+?)_(year|month|day|hour|minute|second)$&', $key, $m)) {
		$__dateParam = $m[1];
		$__dateType  = $m[2];
		
		if (!array_key_exists($__dateParam, $__dates)) {
			$__dateArray = array();
			$__dates[$__dateParam] = new Flux_Config($__dateArray);
		}
		
		$__dates[$__dateParam]->set($__dateType, $value);
	}
	
	foreach ($__dates as $__dateName => $__date) {
		$_year   = (int)$__date->get('year');
		$_month  = (int)$__date->get('month');
		$_day    = (int)$__date->get('day');
		$_hour   = (int)$__date->get('hour');
		$_minute = (int)$__date->get('minute');
		$_second = (int)$__date->get('second');
		$_format = sprintf('%04d-%02d-%02d %02d:%02d:%02d', $_year, $_month, $_day, $_hour, $_minute, $_second);
		$params->set("{$__dateName}_date", $_format);
	}
}

$installer = Flux_Installer::getInstance();
if ($installer->updateNeeded() && $params->get('module') != 'install') {
	$this->redirect($this->url('install'));
}

if (Flux::config('HoldUntrustedAccount') && Flux::config('AutoUnholdAccount')) {
	Flux::processHeldCredits();
}

if ($session->isLoggedIn()) {
	// Update preferred server.
	if (($preferred_server = $params->get('preferred_server')) && $session->getAthenaServer($preferred_server)) {
		$session->setAthenaServerNameData($params->get('preferred_server'));
		if (!array_key_exists('preferred_server', $_GET)) {
			$this->redirect($this->url);
		}
	}

	// Preferred server.
	$server = $session->getAthenaServer();
}
?>