<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$title = 'List PayPal Transactions';

$txnLogTable = Flux::config('FluxTables.TransactionTable');
$sqlpartial  = "{$server->loginDatabase}.{$txnLogTable} AS p ";
$sqlpartial .= "LEFT OUTER JOIN {$server->loginDatabase}.login AS l ON p.account_id = l.account_id ";
$sqlpartial .= "LEFT OUTER JOIN {$server->loginDatabase}.$txnLogTable AS pp ON pp.txn_id = p.parent_txn_id ";
$sqlpartial .= "WHERE (p.server_name = ? OR p.server_name IS NULL OR p.server_name = '')";

$sth = $server->connection->getStatement("SELECT COUNT(p.id) AS total FROM $sqlpartial");
$sth->execute(array($session->loginAthenaGroup->serverName));

$paginator = $this->getPaginator($sth->fetch()->total);
$paginator->setSortableColumns(
	array(
		'txn_id',
		'parent_txn_id',
		'process_date' => 'DESC',
		'payment_date',
		'payment_status',
		'payer_email',
		'mc_gross',
		'credits',
		'server_name',
		'userid'
	)
);

$col  = "p.id, p.txn_id, p.parent_txn_id, p.process_date, p.payment_date, p.payment_status, p.mc_currency, ";
$col .=  "p.payer_email, p.mc_gross, p.credits, p.server_name, pp.id AS parent_id, p.account_id, l.userid";

$sql  = "SELECT $col FROM $sqlpartial";
$sql  = $paginator->getSQL($sql);
$sth  = $server->connection->getStatement($sql);

$sth->execute(array($session->loginAthenaGroup->serverName));
$transactions = $sth->fetchAll();
?>