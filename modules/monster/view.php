<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$monsterID = $params->get('id');

$col  = "origin_table, monsters.ID AS monster_id, Sprite, kName, iName, LV, HP, SP, EXP, JEXP, Range1, Range2, Range3, ";
$col .= "ATK1, ATK2, DEF, MDEF, STR, AGI, VIT, `INT`, DEX, LUK, Scale, Race, Element, Mode, Speed, aDelay, aMotion, ";
$col .= "dMotion, MEXP, ExpPer, MVP1id, MVP1per, MVP2id, MVP2per, MVP3id, MVP3per, Drop1id, Drop1per, Drop2id, Drop2per, ";
$col .= "Drop3id, Drop3per, Drop4id, Drop4per, Drop5id, Drop5per, Drop6id, Drop6per, Drop7id, Drop7per, Drop8id, Drop8per, ";
$col .= "Drop9id, Drop9per, DropCardid, DropCardper";

$sql  = "SELECT $col FROM {$server->charMapDatabase}.`monsters` ";
$sql .= "WHERE monsters.ID = ? LIMIT 1";

$sth  = $server->connection->getStatement($sql);
$sth->execute(array($monsterID));

$monster = $sth->fetch();
?>