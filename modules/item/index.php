<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

require_once 'Flux/TemporaryTable.php';

try {
	$tableName  = "{$server->charMapDatabase}.items";
	$fromTables = array("{$server->charMapDatabase}.item_db", "{$server->charMapDatabase}.item_db2");
	$tempTable  = new Flux_TemporaryTable($server->connection, $tableName, $fromTables);
	
	// Statement paramters, joins and conditions.
	$bind       = array();
	$sqlpartial = "WHERE 1=1 ";
	$itemID     = $params->get('item_id');
	
	if ($itemID) {
		$sqlpartial .= "AND id = ? ";
		$bind[]      = $itemID;
	}
	else {
		$opMapping    = array('eq' => '=', 'gt' => '>', 'lt' => '<');
		$opValues     = array_keys($opMapping);
		$itemName     = $params->get('name');
		$npcBuy       = $params->get('npc_buy');
		$npcBuyOp     = $params->get('npc_buy_op');
		$npcSell      = $params->get('npc_sell');
		$npcSellOp    = $params->get('npc_sell_op');
		$weight       = $params->get('weight');
		$weightOp     = $params->get('weight_op');
		$attack       = $params->get('attack');
		$attackOp     = $params->get('attack_op');
		$defense      = $params->get('defense');
		$defenseOp    = $params->get('defense_op');
		$range        = $params->get('range');
		$rangeOp      = $params->get('range_op');
		$slots        = $params->get('slots');
		$slotsOp      = $params->get('slots_op');
		$refineable   = $params->get('refineable');
		
		if ($itemName) {
			$sqlpartial .= "AND (name_japanese LIKE ? OR name_japanese = ?) ";
			$bind[]      = "%$itemName%";
			$bind[]      = $itemName;
		}
		
		if (in_array($npcBuyOp, $opValues) && trim($npcBuy) != '') {
			$op = $opMapping[$npcBuyOp];
			if ($op == '=' && $npcBuy === '0') {
				$sqlpartial .= "AND (price_buy IS NULL OR price_buy = 0) ";
			}
			else {
				$sqlpartial .= "AND price_buy $op ? ";
				$bind[]      = $npcBuy;
			}
		}
		
		if (in_array($npcSellOp, $opValues) && trim($npcSell) != '') {
			$op = $opMapping[$npcSellOp];
			if ($op == '=' && $npcSell === '0') {
				$sqlpartial .= "AND (price_sell IS NULL OR price_sell = 0) ";
			}
			else {
				$sqlpartial .= "AND price_sell $op ? ";
				$bind[]      = $npcSell;
			}
		}
		
		if (in_array($weightOp, $opValues) && trim($weight) != '') {
			$op = $opMapping[$weightOp];
			if ($op == '=' && $weight === '0') {
				$sqlpartial .= "AND (weight IS NULL OR weight = 0) ";
			}
			else {
				$sqlpartial .= "AND weight $op ? ";
				$bind[]      = $weight;
			}
		}
		
		if (in_array($attackOp, $opValues) && trim($attack) != '') {
			$op = $opMapping[$attackOp];
			if ($op == '=' && $attack === '0') {
				$sqlpartial .= "AND (attack IS NULL OR attack = 0) ";
			}
			else {
				$sqlpartial .= "AND attack $op ? ";
				$bind[]      = $attack;
			}
		}
		
		if (in_array($defenseOp, $opValues) && trim($defense) != '') {
			$op = $opMapping[$defenseOp];
			if ($op == '=' && $defense === '0') {
				$sqlpartial .= "AND (defence IS NULL OR defence = 0) ";
			}
			else {
				$sqlpartial .= "AND defence $op ? ";
				$bind[]      = $defense;
			}
		}
		
		if (in_array($rangeOp, $opValues) && trim($range) != '') {
			$op = $opMapping[$rangeOp];
			if ($op == '=' && $attack === '0') {
				$sqlpartial .= "AND (range IS NULL OR range = 0) ";
			}
			else {
				$sqlpartial .= "AND range $op ? ";
				$bind[]      = $range;
			}
		}
		
		if (in_array($slotsOp, $opValues) && trim($slots) != '') {
			$op = $opMapping[$slotsOp];
			if ($op == '=' && $attack === '0') {
				$sqlpartial .= "AND (slots IS NULL OR slots = 0) ";
			}
			else {
				$sqlpartial .= "AND slots $op ? ";
				$bind[]      = $attack;
			}
		}
		
		if ($refineable) {
			if ($refineable == 'yes') {
				$sqlpartial .= "AND refineable > 0 ";
			}
			elseif ($refienable == 'no') {
				$sqlpartial .= "AND refineable < 1 ";
			}
		}
	}
	
	// Get total count and feed back to the paginator.
	$sth = $server->connection->getStatement("SELECT COUNT(items.id) AS total FROM $tableName $sqlpartial");
	$sth->execute($bind);
	
	$paginator = $this->getPaginator($sth->fetch()->total);
	$paginator->setSortableColumns(array(
		'id' => 'asc', 'name', 'price_buy', 'price_sell', 'weight', 'attack', 'defense',
		'range', 'slots', 'refineable'
	));
	
	$col  = "origin_table, id, name_japanese AS name, price_buy, price_sell, weight, attack, defence AS defense, ";
	$col .= "range, slots, refineable";
	
	$sql  = $paginator->getSQL("SELECT $col FROM $tableName $sqlpartial");
	$sth  = $server->connection->getStatement($sql);
	
	$sth->execute($bind); var_dump($sth->errorInfo());
	$items = $sth->fetchAll();
}
catch (Exception $e) {
	// Ensure table gets dropped.
	$tempTable->drop();
	
	// Raise the original exception.
	$class = get_class($e);
	throw new $class($e->getMessage());
}
?>