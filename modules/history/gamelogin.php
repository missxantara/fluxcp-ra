<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$title = Flux::message('HistoryGameLoginTitle');

if ($server->loginServer->config->getNoCase() && $server->connection->isCaseSensitive($server->logsDatabase, 'loginlog', 'user', true)) {
	$user = 'LOWER(user)';
}
else {
	$user = 'user';
}

$bin = $server->loginServer->config->getNoCase() ? '' : 'BINARY';
$sql = "SELECT COUNT(*) AS total FROM {$server->logsDatabase}.loginlog WHERE $user = $bin ?";
$sth = $server->connection->getStatementForLogs($sql);
$sth->execute(array($session->account->userid));

$paginator = $this->getPaginator($sth->fetch()->total);
$paginator->setSortableColumns(array('time' => 'desc', 'ip', 'rcode', 'log'));

$sql = "SELECT time, ip, rcode, log FROM {$server->logsDatabase}.loginlog WHERE $user = $bin ?";
$sql = $paginator->getSQL($sql);
$sth = $server->connection->getStatementForLogs($sql);
$sth->execute(array($session->account->userid));

$logins = $sth->fetchAll();
?>