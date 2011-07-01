<?php
if (!defined('FLUX_ROOT')) exit;

//$this->loginRequired();

$title = 'List Monsters';

require_once 'Flux/TemporaryTable.php';

try {
	$tableName  = "{$server->charMapDatabase}.monsters";
	$fromTables = array("{$server->charMapDatabase}.mob_db", "{$server->charMapDatabase}.mob_db2");
	$tempTable  = new Flux_TemporaryTable($server->connection, $tableName, $fromTables);
	
	// Statement parameters, joins and conditions.
	$bind        = array();
	$sqlpartial  = "WHERE 1=1 ";
	$monsterID   = $params->get('monster_id');
	
	if ($monsterID) {
		$sqlpartial .= "AND monsters.ID = ? ";
		$bind[]      = $monsterID;
	}
	else {
		$opMapping      = array('eq' => '=', 'gt' => '>', 'lt' => '<');
		$opValues       = array_keys($opMapping);
		$monsterName    = $params->get('name');
		$cardID         = $params->get('card_id');
		$mvp            = strtolower($params->get('mvp'));
		$custom         = $params->get('custom');
		
		if ($monsterName) {
			$sqlpartial .= "AND ((kName LIKE ? OR kName = ?) OR (iName LIKE ? OR iName = ?)) ";
			$bind[]      = "%$monsterName%";
			$bind[]      = $monsterName;
			$bind[]      = "%$monsterName%";
			$bind[]      = $monsterName;
		}
		
		if ($cardID) {
			$sqlpartial .= "AND DropCardid = ? ";
			$bind[]      = $cardID;
		}
		
		if ($mvp == 'yes') {
			$sqlpartial .= 'AND MEXP > 0 ';
		}
		elseif ($mvp == 'no') {
			$sqlpartial .= 'AND MEXP = 0 ';
		}
		
		if ($custom) {
			if ($custom == 'yes') {
				$sqlpartial .= "AND origin_table LIKE '%mob_db2' ";
			}
			elseif ($custom == 'no') {
				$sqlpartial .= "AND origin_table LIKE '%mob_db' ";
			}
		}
	}
	
	// Get total count and feed back to the paginator.
	$sth = $server->connection->getStatement("SELECT COUNT(monsters.ID) AS total FROM $tableName $sqlpartial");
	$sth->execute($bind);
	
	$paginator = $this->getPaginator($sth->fetch()->total);
	$paginator->setSortableColumns(array(
		'monster_id' => 'asc', 'kro_name', 'iro_name', 'level', 'hp', 'exp', 'jexp', 'dropcard_id', 'origin_table'
	));
	
	$col  = "origin_table, monsters.ID AS monster_id, kName AS kro_name, iName AS iro_name, ";
	$col .= "LV AS level, HP AS hp, EXP AS exp, JEXP AS jexp, DropCardid AS dropcard_id, mexp AS mvp_exp";
	
	$sql  = $paginator->getSQL("SELECT $col FROM $tableName $sqlpartial");
	$sth  = $server->connection->getStatement($sql);
	
	$sth->execute($bind);
	$monsters = $sth->fetchAll();
}
catch (Exception $e) {
	if (isset($tempTable) && $tempTable) {
		// Ensure table gets dropped.
		$tempTable->drop();
	}
	
	// Raise the original exception.
	$class = get_class($e);
	throw new $class($e->getMessage());
}
?>