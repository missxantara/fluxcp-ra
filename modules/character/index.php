<?php
if (!defined('FLUX_ROOT')) exit;

$sqlpartial  = "LEFT OUTER JOIN guild_member ON guild_member.char_id = ch.char_id ";
$sqlpartial .= "LEFT OUTER JOIN guild ON guild.guild_id = guild_member.guild_id ";
$sqlpartial .= "LEFT OUTER JOIN login ON login.account_id = ch.account_id ";
$sqlpartial .= "LEFT OUTER JOIN `char` AS partner ON partner.char_id = ch.partner_id ";

$sql  = "SELECT COUNT(ch.char_id) AS total FROM {$server->charMapDatabase}.`char` AS ch $sqlpartial";
$sth  = $server->connection->getStatement($sql);

$sth->execute();
$paginator = $this->getPaginator($sth->fetch()->total);
$paginator->setSortableColumns(array(
	'ch.char_id' => 'asc', 'userid', 'char_name', 'base_level', 'job_level',
	'zeny', 'guild_name', 'partner_name', 'online', 'ch.char_num'
));

$col  = "ch.account_id, ch.char_id, ch.name AS char_name, ch.char_num, ";
$col .= "ch.online, ch.base_level, ch.job_level, ch.class, ch.zeny, ";
$col .= "guild.guild_id, guild.name AS guild_name, ";
$col .= "login.userid, partner.name AS partner_name, partner.char_id AS partner_id ";
$sql  = "SELECT $col FROM {$server->charMapDatabase}.`char` AS ch $sqlpartial";
$sql  = $paginator->getSQL($sql);
$sth  = $server->connection->getStatement($sql);

$sth->execute();

$characters = $sth->fetchAll();
?>