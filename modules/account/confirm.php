<?php
if (!defined('FLUX_ROOT')) exit;

$title = 'Confirm Account';

$user  = $params->get('user');
$code  = $params->get('code');
$login = $params->get('login');

$createTable = Flux::config('FluxTables.AccountCreateTable');

if (!$login || !$user || !$code || strlen($code) !== 32) {
	$this->deny();
}

$loginAthenaGroup = Flux::getServerGroupByName($login);
if (!$loginAthenaGroup) {
	$this->deny();
}

$sql  = "SELECT account_id FROM {$loginAthenaGroup->loginDatabase}.$createTable WHERE ";
$sql .= "userid = ? AND confirm_code = ? AND confirmed = 0 AND confirm_expire > NOW() LIMIT 1";
$sth  = $loginAthenaGroup->connection->getStatement($sql);

if (!$sth->execute(array($user, $code)) || !($account=$sth->fetch())) {
	$this->deny();
}

$sql  = "UPDATE {$loginAthenaGroup->loginDatabase}.$createTable SET ";
$sql .= "confirmed = 1, confirm_expire = NULL WHERE account_id = ?";
$sth  = $loginAthenaGroup->connection->getStatement($sql);

$sth->execute(array($account->account_id));

$loginAthenaGroup->loginServer->unban(null, 'Account has been confirmed and activated.', $account->account_id);

$session->setMessageData('Your account has been confirmed and activated, you may now log-in.');
$this->redirect();
?>