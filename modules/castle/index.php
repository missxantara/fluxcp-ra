<?php
if (!defined('FLUX_ROOT')) exit;

$title = 'Castles';

$sql  = "SELECT castles.castle_id, castles.guild_id, guild.name AS guild_name, guild.emblem_len FROM {$server->charMapDatabase}.guild_castle AS castles ";
$sql .= "LEFT JOIN guild ON guild.guild_id = castles.guild_id ORDER BY castles.castle_id ASC";
$sth  = $server->connection->getStatement($sql);
$sth->execute();

$castles = $sth->fetchAll();
$castleNames = Flux::config('CastleNames')->toArray();

?>