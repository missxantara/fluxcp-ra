<?php
if (!defined('FLUX_ROOT')) exit;

$creditsTable  = Flux::config('FluxTables.CreditsTable');
$creditColumns = 'credits.balance, credits.last_donation_date, credits.last_donation_amount';
$sqlpartial    = "LEFT OUTER JOIN {$server->loginDatabase}.{$creditsTable} AS credits ON login.account_id = credits.account_id ";
$sqlpartial   .= "WHERE login.sex != 'S' AND login.level >= 0";

$sql  = "SELECT COUNT(login.account_id) AS total FROM {$server->loginDatabase}.login $sqlpartial";
$sth  = $server->connection->getStatement($sql);
$sth->execute();

$paginator = $this->getPaginator($sth->fetch()->total);
$paginator->setSortableColumns(array('account_id', 'userid' => 'asc', 'sex', 'level', 'state', 'balance', 'email', 'lastlogin'));

$sql  = $paginator->getSQL("SELECT login.*, {$creditColumns} FROM {$server->loginDatabase}.login $sqlpartial");
$sth  = $server->connection->getStatement($sql);
$sth->execute();

$accounts = $sth->fetchAll();
?>