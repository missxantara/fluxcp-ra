<?php
if (!defined('FLUX_ROOT')) exit;

$txnLogTable = Flux::config('FluxTables.TransactionTable');
$txnID       = $params->get('id');
$txnFileLog  = '';

$sql  = "SELECT * FROM {$server->loginDatabase}.{$txnLogTable} AS txnlog ";
$sql .= "LEFT JOIN {$server->loginDatabase}.login ON txnlog.account_id = login.account_id ";
$sql .= "WHERE txn_id = ? LIMIT 1";
$sth  = $server->connection->getStatement($sql);
$sth->execute(array($txnID));

$txn = $sth->fetch();

if ($txn) {
	$txnLogFile = FLUX_DATA_DIR."/logs/transactions/{$txn->txn_type}/{$txn->payment_status}/{$txn->txn_id}.log";
	if (file_exists($txnLogFile)) {
		$txnFileLog = file_get_contents($txnLogFile);
	}
}
?>