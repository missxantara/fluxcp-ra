<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$title = Flux::message('IpbanAddTitle');

if (count($_POST)) {
	if (!$params->get('addipban')) {
		$this->deny();
	}
	
	$list   = trim($params->get('list'));
	$reason = trim($params->get('reason'));
	$rtime  = trim($params->get('rtime_date'));
	
	if (!$list) {
		$errorMessage = Flux::message('IpbanEnterIpPattern');
	}
	elseif (!preg_match('/^(\d{1,3})\.(\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)$/', $list, $m)) {
		$errorMessage = Flux::message('IpbanInvalidPattern');
	}
	elseif (!$reason) {
		$errorMessage = Flux::message('IpbanEnterReason');
	}
	elseif (!$rtime) {
		$errorMessage = Flux::message('IpbanSelectUnbanDate');
	}
	elseif (strtotime($rtime) <= time()) {
		$errorMessage = Flux::message('IpbanFutureDate');
	}
	else {
		$listArr   = array();
		$listArr[] = sprintf('%u.*.*.*', $m[1]);
		$listArr[] = sprintf('%u.%u.*.*', $m[1], $m[2]);
		$listArr[] = sprintf('%u.%u.%u.*', $m[1], $m[2], $m[3]);
		$listArr[] = sprintf('%u.%u.%u.%u', $m[1], $m[2], $m[3], $m[4]);
		
		$sql  = "SELECT list FROM {$server->loginDatabase}.ipbanlist WHERE rtime > NOW() AND ";
		$sql .= "(list = ? OR list = ? OR list = ? OR list = ?) LIMIT 1";
		$sth  = $server->connection->getStatement($sql);
		
		$sth->execute($listArr);
		$ipban = $sth->fetch();
		
		if ($ipban && $ipban->list) {
			$errorMessage = sprintf(Flux::message('IpbanAlreadyBanned'), $ipban->list);
		}
		else {
			$sql  = "INSERT INTO {$server->loginDatabase}.ipbanlist (list, reason, rtime, btime) ";
			$sql .= "VALUES (?, ?, ?, NOW())";
			$sth  = $server->connection->getStatement($sql);
			
			if ($sth->execute(array($list, $reason, $rtime))) {
				$session->setMessageData(sprintf(Flux::message('IpbanPatternBanned'), $list));
				$this->redirect($this->url('ipban'));
			}
			else {
				$errorMessage = Flux::message('IpbanAddFailed');
			}
		}
	}
}
?>