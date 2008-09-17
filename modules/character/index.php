<?php
if (!defined('FLUX_ROOT')) exit;

$sqlpartial  = "LEFT OUTER JOIN {$server->charMapDatabase}.guild_member ON guild_member.char_id = ch.char_id ";
$sqlpartial .= "LEFT OUTER JOIN {$server->charMapDatabase}.guild ON guild.guild_id = guild_member.guild_id ";
$sqlpartial .= "LEFT OUTER JOIN {$server->loginDatabase}.login ON login.account_id = ch.account_id ";
$sqlpartial .= "LEFT OUTER JOIN {$server->charMapDatabase}.`char` AS partner ON partner.char_id = ch.partner_id ";
$sqlpartial .= "LEFT OUTER JOIN {$server->charMapDatabase}.`char` AS mother ON mother.char_id = ch.mother ";
$sqlpartial .= "LEFT OUTER JOIN {$server->charMapDatabase}.`char` AS father ON father.char_id = ch.father ";
$sqlpartial .= "LEFT OUTER JOIN {$server->charMapDatabase}.`char` AS child ON child.char_id = ch.child ";

$sql  = "SELECT COUNT(ch.char_id) AS total FROM {$server->charMapDatabase}.`char` AS ch $sqlpartial";
$sth  = $server->connection->getStatement($sql);

$sth->execute();
$paginator = $this->getPaginator($sth->fetch()->total);
$paginator->setSortableColumns(array(
	'ch.char_id' => 'asc', 'userid', 'char_name', 'ch.base_level', 'ch.job_level',
	'ch.zeny', 'guild_name', 'partner_name', 'mother_name', 'father_name', 'child_name',
	'ch.online', 'ch.char_num'
));

$col  = "ch.account_id, ch.char_id, ch.name AS char_name, ch.char_num, ";
$col .= "ch.online, ch.base_level, ch.job_level, ch.class, ch.zeny, ";
$col .= "guild.guild_id, guild.name AS guild_name, ";
$col .= "login.userid, partner.name AS partner_name, partner.char_id AS partner_id, ";
$col .= "mother.name AS mother_name, mother.char_id AS mother_id, ";
$col .= "father.name AS father_name, father.char_id AS father_id, ";
$col .= "child.name AS child_name, child.char_id AS child_id ";
$sql  = "SELECT $col FROM {$server->charMapDatabase}.`char` AS ch $sqlpartial";
$sql  = $paginator->getSQL($sql);
$sth  = $server->connection->getStatement($sql);

$sth->execute();

$characters = $sth->fetchAll();
?>