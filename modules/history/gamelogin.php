<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$title = Flux::message('HistoryGameLoginTitle');

$user = ($noCase=$server->loginServer->config->getNoCase()) ? "LOWER(user)" : "CAST(user AS BINARY)";
$sql  = "SELECT COUNT(*) AS total FROM {$server->logsDatabase}.loginlog WHERE $user = ?";
$sth  = $server->connection->getStatementForLogs($sql);
$sth->execute(array($username=($noCase ? strtolower($session->account->userid) : $session->account->userid)));

$paginator = $this->getPaginator($sth->fetch()->total);
$paginator->setSortableColumns(array('time' => 'desc', 'ip', 'rcode', 'log'));

$sql = "SELECT time, ip, rcode, log FROM {$server->logsDatabase}.loginlog WHERE $user = ?";
$sql = $paginator->getSQL($sql);
$sth = $server->connection->getStatementForLogs($sql);
$sth->execute(array($username));

$logins = $sth->fetchAll();
?>