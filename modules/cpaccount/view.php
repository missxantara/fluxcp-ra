<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$title = Flux::message('AccountViewTitle');

$creditsTable  = Flux::config('FluxTables.CreditsTable');
$creditColumns = 'credits.balance, credits.last_donation_date, credits.last_donation_amount';
$createTable   = Flux::config('FluxTables.AccountCreateTable');
$createColumns = 'created.confirmed, created.confirm_code, created.reg_date';
$isMine        = false;
$accountID     = $params->get('id');
$cpaccount     = false;

if (!$accountID || $accountID == $session->cpaccount->cp_aid) {
	$isMine    = true;
	$accountID = $session->cpaccount->cp_aid;
	$cpaccount = $session->cpaccount;
}

if (!$isMine) {
	// Allowed to view other peoples' account information?
	if (!$auth->allowedToViewAccount) {
		$this->deny();
	}
	
	$cpAccountTable = Flux::config('FluxTables.CPAccountTable');
	
	$sql  = "SELECT cp_account.*, {$creditColumns}, {$createColumns} FROM {$server->loginDatabase}.{$cpAccountTable} AS cp_account ";
	$sql .= "LEFT OUTER JOIN {$server->loginDatabase}.{$creditsTable} AS credits ON cp_account.cp_aid = credits.cp_aid ";
	$sql .= "LEFT OUTER JOIN {$server->loginDatabase}.{$createTable} AS created ON cp_account.cp_aid = created.cp_aid ";
	$sql .= "WHERE cp_account.cp_aid = ? LIMIT 1";
	$sth  = $server->connection->getStatement($sql);
	$sth->execute(array($accountID));
	
	// Account object.
	$cpaccount = $sth->fetch();
	
	if ($cpaccount) {
		$title = sprintf(Flux::message('AccountViewTitle2'), $cpaccount->cp_aid);
	}
}
else {
	$title = Flux::message('AccountViewTitle3');
}

// Account ban

?>