<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$title = 'Modify IP Ban';

$banID = $params->get('list');

if (!$auth->allowedToModifyIpBan || !$banID) {
	$this->deny();
}

$sql  = "SELECT list, reason, rtime FROM {$server->loginDatabase}.ipbanlist ";
$sql .= "WHERE rtime > NOW() AND list = ? LIMIT 1";
$sth  = $server->connection->getStatement($sql);
$sth->execute(array($banID));

$ipban = $sth->fetch();

if (count($_POST)) {
	if (!$params->get('modipban')) {
		$this->deny();
	}
	
	$list   = trim($params->get('newlist'));
	$reason = trim($params->get('reason'));
	$rtime  = trim($params->get('rtime_date'));
	
	if (!$list) {
		$errorMessage = 'Please input an IP address or pattern.';
	}
	elseif (!preg_match('/^(\d{1,3})\.(\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)$/', $list, $m)) {
		$errorMessage = 'Invalid IP address or pattern.';
	}
	elseif (!$reason) {
		$errorMessage = 'Please enter a reason for the IP ban.';
	}
	elseif (!$rtime) {
		$errorMessage = 'Unban date is required.';
	}
	elseif (strtotime($rtime) <= time()) {
		$errorMessage = 'Unban date must be specified to a future date.';
	}
	else {
		if ($list != $ipban->list) {
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
				$errorMessage = "A matching IP ({$ipban->list}) has already been banned.";
			}
		}
		
		if (empty($errorMessage)) {
			$sql  = "UPDATE {$server->loginDatabase}.ipbanlist SET ";
			$sql .= "list = ?, reason = ?, rtime = ? WHERE list = ?";
			$sth  = $server->connection->getStatement($sql);
			
			if ($sth->execute(array($list, $reason, $rtime, $banID))) {
				$session->setMessageData("The IP address/pattern '$list' has been modified.");
				$this->redirect($this->url('ipban'));
			}
			else {
				$errorMessage = 'Failed to modify IP ban.';
			}
		}
	}
}
?>