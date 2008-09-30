<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

if (!count($_POST) || !$params->get('unban') ) {
	$this->deny();
}

if (!(($unbanList=$params->get('unban_list')) instanceOf Flux_Config) || !count($unbanList=$unbanList->toArray())) {
	$session->setMessageData('Nothing to unban.');
	$this->redirect($this->url('ipban'));
}

$list = array();
$bind = array();

foreach ($unbanList as $unban) {
	$list[] = "list = ?";
	$bind[] = $unban;
}

$cond = implode(' OR ', $list);
$sql  = "UPDATE {$server->loginDatabase}.ipbanlist SET rtime = '0000-00-00 00:00:00' WHERE $cond";
$sth  = $server->connection->getStatement($sql);

if ($sth->execute($bind)) {
	$session->setMessageData('Lifted selected IP ban(s)!');
	$this->redirect($this->url('ipban'));
}
else {
	$session->setMessageData('Nothing to unban.');
	$this->redirect($this->url('ipban'));
}
?>