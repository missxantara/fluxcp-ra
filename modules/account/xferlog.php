<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$title = 'Credit Transfer History';

$xferTable = Flux::config('FluxTables.CreditTransferTable');

$col  = "from_account_id, target_account_id, amount, transfer_date, ";
$col .= "fa.userid AS from_userid, ta.userid AS target_userid";

$sql  = "SELECT $col FROM {$server->loginDatabase}.$xferTable ";
$sql .= "LEFT OUTER JOIN {$server->charMapDatabase}.login AS fa ON $xferTable.from_account_id = fa.account_id ";
$sql .= "LEFT OUTER JOIN {$server->charMapDatabase}.login AS ta ON $xferTable.target_account_id = ta.account_id ";
$sql .= "WHERE from_account_id = ? OR target_account_id = ? ORDER BY transfer_date DESC";
$sth  = $server->connection->getStatement($sql);

$sth->execute(array($session->account->account_id, $session->account->account_id));
$transfers = $sth->fetchAll();
?>