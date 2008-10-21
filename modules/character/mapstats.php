<?php
if (!defined('FLUX_ROOT')) exit;

$title = 'Map Statistics';

$sql  = "SELECT last_map AS map_name, COUNT(last_map) AS player_count, online FROM {$server->charMapDatabase}.`char` ";
$sql .= "GROUP BY map_name, online HAVING player_count > 0 AND online > 0 ORDER BY map_name ASC";
$sth  = $server->connection->getStatement($sql);

$sth->execute();
$maps = $sth->fetchAll();
?>