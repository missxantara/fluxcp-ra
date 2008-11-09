<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$account = $params->get('account');
$code    = $params->get('code');
$login   = $params->get('login');

$emailChangeTable = Flux::config('FluxTables.ChangeEmailTable');

if (!$login || !$account || !$code || strlen($code) !== 32) {
	$this->deny();
}

if ($session->loginAthenaGroup->serverName != $login) {
	$this->deny();
}

if ($session->account->account_id != $account) {
	$this->deny();
}

$sql = "SELECT id, new_email AS email FROM {$server->loginDatabase}.$emailChangeTable WHERE code = ? AND account_id = ? AND change_done = 0 LIMIT 1";
$sth = $server->connection->getStatement($sql);
$sth->execute(array($code, $account));

$row = $sth->fetch();

if (!$row || !$row->id || !$row->email) {
	$this->deny();
}

$sql = "UPDATE {$server->loginDatabase}.$emailChangeTable SET change_date = NOW(), change_ip = ?, change_done = 1 WHERE id = ?";
$sth = $server->connection->getStatement($sql);

if (!$sth->execute(array($_SERVER['REMOTE_ADDR'], $row->id))) {
	$session->setMessageData('There has been a technical difficulty while updating your e-mail address, please contact an admin.');
	$this->redirect();
}
else {
	$sql = "UPDATE {$server->loginDatabase}.login SET email = ? WHERE account_id = ?";
	$sth = $server->connection->getStatement($sql);
	
	if (!$sth->execute(array($row->email, $account))) {
		$session->setMessageData('There has been a technical difficulty while updating your e-mail address, please contact an admin.');
		$this->redirect();
	}
	else {
		$session->setMessageData('Your e-mail address has been changed!');
		$this->redirect();
	}
}
?>