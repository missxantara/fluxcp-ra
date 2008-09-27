<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$title = "Who's Online";

$sqlpartial  = "LEFT JOIN {$server->loginDatabase}.login ON login.account_id = ch.account_id ";
$sqlpartial .= "LEFT JOIN {$server->charMapDatabase}.guild ON guild.guild_id = ch.guild_id ";
$sqlpartial .= "WHERE ch.online > 0 ";
$bind        = array();

$charName  = $params->get('char_name');
$charClass = $params->get('char_class');
$guildName = $params->get('guild_name');

if ($charName) {
	$sqlpartial .= "AND (ch.name LIKE ? OR ch.name = ?) ";
	$bind[]      = "%$charName%";
	$bind[]      = $charName;
}

if ($guildName) {
	$sqlpartial .= "AND (guild.name LIKE ? OR guild.name = ?) ";
	$bind[]      = "%$guildName%";
	$bind[]      = $guildName;
}

if ($charClass) {
	$className = preg_quote($charClass);
	$classIDs  = preg_grep("/.*?$className.*?/i", Flux::config('JobClasses')->toArray());
	
	if (count($classIDs)) {
		$classIDs    = array_keys($classIDs);
		$sqlpartial .= "AND (";
		$partial     = '';
		
		foreach ($classIDs as $id) {
			$partial .= "ch.class = ? OR ";
			$bind[]   = $id;
		}
		
		$partial     = preg_replace('/\s*OR\s*$/', '', $partial);
		$sqlpartial .= "$partial) ";
	}
	else {
		$sqlpartial .= 'AND ch.class IS NULL ';
	}
}

if ($hideLevel=Flux::config('HideFromWhosOnline')) {
	$sqlpartial .= "AND login.level < ? ";
	$bind[] = $hideLevel;
}

$sql  = "SELECT COUNT(ch.char_id) AS total FROM {$server->charMapDatabase}.`char` AS ch $sqlpartial";
$sth  = $server->connection->getStatement($sql);

$sth->execute($bind);
$paginator = $this->getPaginator($sth->fetch()->total);
$paginator->setSortableColumns(array('char_name' => 'asc', 'base_level', 'job_level', 'guild_name'));

$col  = "ch.char_id, ch.name AS char_name, ch.class AS char_class, ch.base_level, ch.job_level, ";
$col .= "guild.name AS guild_name, guild.guild_id";

$sql  = $paginator->getSQL("SELECT $col FROM {$server->charMapDatabase}.`char` AS ch $sqlpartial");
$sth  = $server->connection->getStatement($sql);

$sth->execute($bind);

$chars = $sth->fetchAll();

?>