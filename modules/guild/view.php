<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$title = 'Viewing Guild';

$guildID = $params->get('id');

$col  = "guild.*";

$sql  = "SELECT $col FROM {$server->charMapDatabase}.guild ";
$sql .= "WHERE guild.guild_id = ?";

$sth  = $server->connection->getStatement($sql);
$sth->execute(array($guildID));

$guild = $sth->fetch();

if ($guild) {
	$title = "Viewing Guild ({$guild->name})";
}
?>