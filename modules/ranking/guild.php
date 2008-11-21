<?php
if (!defined('FLUX_ROOT')) exit;

$title = 'Guild Ranking';

$col  = "g.guild_id, g.name, g.guild_lv, g.exp, g.average_lv, g.emblem_len, ";
$col .= "(SELECT COUNT(char_id) FROM {$server->charMapDatabase}.`char` WHERE `char`.guild_id = g.guild_id) AS members";

$sql  = "SELECT $col FROM {$server->charMapDatabase}.guild AS g ";
$sql .= "LEFT JOIN {$server->charMapDatabase}.`char` AS ch ON ch.char_id = g.char_id ";
$sql .= "LEFT JOIN {$server->loginDatabase}.login ON login.account_id = ch.account_id ";
$sql .= "WHERE login.level < ? ";
$sql .= "ORDER BY g.guild_lv DESC, g.exp DESC, members DESC, g.average_lv DESC, g.max_member DESC, g.next_exp ASC ";
$sql .= "LIMIT ".(int)Flux::config('GuildRankingLimit');
$sth  = $server->connection->getStatement($sql);

$sth->execute(array((int)Flux::config('RankingHideLevel')));
$guilds = $sth->fetchAll();
?>