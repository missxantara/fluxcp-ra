<?php
if (!defined('FLUX_ROOT')) exit;

//$this->loginRequired();

$title = 'Viewing Monster';

require_once 'Flux/TemporaryTable.php';

$tableName  = "{$server->charMapDatabase}.monsters";
$fromTables = array("{$server->charMapDatabase}.mob_db", "{$server->charMapDatabase}.mob_db2");
$tempTable  = new Flux_TemporaryTable($server->connection, $tableName, $fromTables);
$tableName2  = "{$server->charMapDatabase}.items";
$fromTables2 = array("{$server->charMapDatabase}.item_db", "{$server->charMapDatabase}.item_db2");
$tempTable2  = new Flux_TemporaryTable($server->connection, $tableName2, $fromTables2);

$monsterID = $params->get('id');

$col      = array();

$col[]    = "origin_table, monsters.ID AS monster_id, Sprite, kName, iName, LV, HP, SP, EXP, JEXP, Range1, Range2, Range3, ";
$col[0]  .= "ATK1, ATK2, DEF, MDEF, STR, AGI, VIT, `INT`, DEX, LUK, Scale, Race, Element, Mode, Speed, aDelay, aMotion, ";
$col[0]  .= "dMotion, MEXP, ExpPer, MVP1id, MVP1per, MVP2id, MVP2per, MVP3id, MVP3per, Drop1id, Drop1per, Drop2id, Drop2per, ";
$col[0]  .= "Drop3id, Drop3per, Drop4id, Drop4per, Drop5id, Drop5per, Drop6id, Drop6per, Drop7id, Drop7per, Drop8id, Drop8per, ";
$col[0]  .= "Drop9id, Drop9per, DropCardid, DropCardper, ";

// Calculate actual element and level.
$col[0]  .= "(Element % 10) AS DefEle, (Element / 20) AS EleLv";

for ($loop = 1; $loop <= 3; $loop++)
	$col[]  = "MVP".$loop.".name_japanese AS MVP".$loop."name";

for ($loop = 1; $loop <= 9; $loop++)
	$col[]  = "Drop".$loop.".name_japanese AS Drop".$loop."name";

$col[]    = "DropCard.name_japanese AS DropCardname";

$sql      = array();
$sql[]    = "SELECT $col[0] FROM {$server->charMapDatabase}.`monsters` WHERE monsters.ID = ? LIMIT 1";

for ($loop = 1; $loop <= 3; $loop++)
	$sql[]  = "SELECT {$col[$loop]} FROM {$server->charMapDatabase}.`monsters` LEFT OUTER JOIN {$server->charMapDatabase}.`items` AS MVP".$loop." ON MVP".$loop.".id = monsters.MVP".$loop."id WHERE monsters.ID = ? LIMIT 1";

for ($loop = 1; $loop <= 9; $loop++)
	$sql[]  = "SELECT {$col[$loop+3]} FROM {$server->charMapDatabase}.`monsters` LEFT OUTER JOIN {$server->charMapDatabase}.`items` AS Drop".$loop." ON Drop".$loop.".id = monsters.Drop".$loop."id WHERE monsters.ID = ? LIMIT 1";

$sql[]    = "SELECT $col[13] FROM {$server->charMapDatabase}.`monsters` LEFT OUTER JOIN {$server->charMapDatabase}.`items` AS DropCard ON DropCard.id = monsters.DropCardid WHERE monsters.ID = ? LIMIT 1";

$sth      = array();
$monster  = array();
for ($loop = 0; $loop <= 13; $loop++) {
	$sth[]   = $server->connection->getStatement($sql[$loop]);
	$sth[$loop]->execute(array($monsterID));
	$monster[$loop]   = $sth[$loop]->fetch();
}

if (!empty($monster[0])) {
	$title = "Viewing Monster ({$monster[0]->kName})";
}

$mobDB = Flux::config('MobSkillDb');

if (is_readable($mobDB)) {
	$fdb    = fopen($mobDB, 'r');
	$skills = array();
	
	while ($lines = fgets($fdb)) {
		$parts = explode(',', $lines);
		if ($parts[0] == $monsterID)
			$skills[] = $lines;
	}
	fclose($fdb);
}
?>