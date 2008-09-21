<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

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
		
		if ($monsterName) {
			$sqlpartial .= "AND ((kName LIKE ? OR kName = ?) OR (iName LIKE ? OR iName = ?)) ";
			$bind[]      = "%$monsterName%";
			$bind[]      = $monsterName;
			$bind[]      = "%$monsterName%";
			$bind[]      = $monsterName;
		}
	}
	
	// Get total count and feed back to the paginator.
	$sth = $server->connection->getStatement("SELECT COUNT(monsters.ID) AS total FROM $tableName $sqlpartial");
	$sth->execute($bind);
	
	$paginator = $this->getPaginator($sth->fetch()->total);
	$paginator->setSortableColumns(array(
		'monster_id' => 'asc', 'kName', 'iName', 'LV', 'HP', 'SP', 'EXP', 'JEXP'
	));
	
	$col  = "origin_table, monsters.ID AS monster_id, kName, iName, LV, HP, SP, EXP, JEXP";
	
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