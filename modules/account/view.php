<?php
if (!defined('FLUX_ROOT')) exit;

$creditsTable  = Flux::config('FluxTables.CreditsTable');
$creditColumns = 'credits.balance, credits.last_donation_date, credits.last_donation_amount';
$isMine        = false;
$accountID     = $params->get('id');
$account       = false;

if (!$accountID || $accountID == $session->account->account_id) {
	$isMine    = true;
	$accountID = $session->account->account_id;
	$account   = $session->account;
}

if (!$isMine) {
	// Allowed to view other peoples' account information?
	if (!$auth->allowedToViewAccount) {
		$this->deny();
	}
	
	$sql  = "SELECT login.*, {$creditColumns} FROM {$server->loginDatabase}.login ";
	$sql .= "LEFT OUTER JOIN {$creditsTable} AS credits ON login.account_id = credits.account_id ";
	$sql .= "WHERE login.sex != 'S' AND login.level >= 0 AND login.account_id = ? LIMIT 1";
	$sth  = $server->connection->getStatement($sql);
	$sth->execute(array($accountID));
	
	// Account object.
	$account = $sth->fetch();
}

$characters = array();
foreach ($session->getAthenaServerNames() as $serverName) {
	$athena = $session->getAthenaServer($serverName);
	
	$sql = "SELECT ch.* FROM {$athena->charMapDatabase}.`char` AS ch WHERE ch.account_id = ? ORDER BY ch.char_num ASC";
	$sth = $server->connection->getStatement($sql);
	$sth->execute(array($accountID));

	$chars = $sth->fetchAll();
	$characters[$athena->serverName] = $chars;
}
?>