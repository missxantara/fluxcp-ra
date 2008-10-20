<?php
if (!defined('FLUX_ROOT')) exit;

//$this->loginRequired();

$title = 'List Items';

require_once 'Flux/TemporaryTable.php';

try {
	$tableName  = "{$server->charMapDatabase}.items";
	$fromTables = array("{$server->charMapDatabase}.item_db", "{$server->charMapDatabase}.item_db2");
	$tempTable  = new Flux_TemporaryTable($server->connection, $tableName, $fromTables);
	$shopTable  = Flux::config('FluxTables.ItemShopTable');
	
	// Statement parameters, joins and conditions.
	$bind        = array();
	$sqlpartial  = "LEFT OUTER JOIN {$server->charMapDatabase}.$shopTable ON $shopTable.nameid = items.id ";
	$sqlpartial .= "WHERE 1=1 ";
	$itemID      = $params->get('item_id');
	
	if ($itemID) {
		$sqlpartial .= "AND items.id = ? ";
		$bind[]      = $itemID;
	}
	else {
		$opMapping    = array('eq' => '=', 'gt' => '>', 'lt' => '<');
		$opValues     = array_keys($opMapping);
		$itemName     = $params->get('name');
		$itemType     = $params->get('type');
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
		$forSale      = $params->get('for_sale');
		$custom       = $params->get('custom');
		
		if ($itemName) {
			$sqlpartial .= "AND (name_japanese LIKE ? OR name_japanese = ?) ";
			$bind[]      = "%$itemName%";
			$bind[]      = $itemName;
		}

		if ($itemType) {
			if(is_numeric($itemType) && (floatval($itemType) == intval($itemType))) {
				$itemTypes = Flux::config('ItemTypes')->toArray();
				if ($itemType < count($itemTypes) && $itemTypes[$itemType]) {
					$sqlpartial .= "AND type = ? ";
					$bind[]      = $itemType;
				} else {
					$sqlpartial .= 'AND type IS NULL ';
				}
			} else {
					
				$typeName   = preg_quote($itemType);
				$itemTypes  = preg_grep("/.*?$typeName.*?/i", Flux::config('ItemTypes')->toArray());
				
				if (count($itemTypes)) {
					$itemTypes    = array_keys($itemTypes);
					$sqlpartial .= "AND (";
					$partial     = '';
					
					foreach ($itemTypes as $id) {
						$partial .= "type = ? OR ";
						$bind[]   = $id;
					}
					
					$partial     = preg_replace('/\s*OR\s*$/', '', $partial);
					$sqlpartial .= "$partial) ";
				} else {
					$sqlpartial .= 'AND type IS NULL ';
				}
			}
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
				$sqlpartial .= "AND IFNULL(price_sell, FLOOR(price_buy/2)) = 0 ";
			}
			else {
				$sqlpartial .= "AND IFNULL(price_sell, FLOOR(price_buy/2)) $op ? ";
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
			if ($op == '=' && $range === '0') {
				$sqlpartial .= "AND (range IS NULL OR range = 0) ";
			}
			else {
				$sqlpartial .= "AND range $op ? ";
				$bind[]      = $range;
			}
		}
		
		if (in_array($slotsOp, $opValues) && trim($slots) != '') {
			$op = $opMapping[$slotsOp];
			if ($op == '=' && $slots === '0') {
				$sqlpartial .= "AND (slots IS NULL OR slots = 0) ";
			}
			else {
				$sqlpartial .= "AND slots $op ? ";
				$bind[]      = $slots;
			}
		}
		
		if ($refineable) {
			if ($refineable == 'yes') {
				$sqlpartial .= "AND refineable > 0 ";
			}
			elseif ($refineable == 'no') {
				$sqlpartial .= "AND IFNULL(refineable, 0) < 1 ";
			}
		}
		
		if ($forSale) {
			if ($forSale == 'yes') {
				$sqlpartial .= "AND $shopTable.cost > 0 ";
			}
			elseif ($forSale == 'no') {
				$sqlpartial .= "AND IFNULL($shopTable.cost, 0) < 1 ";
			}
		}
		
		if ($custom) {
			if ($custom == 'yes') {
				$sqlpartial .= "AND origin_table LIKE '%item_db2' ";
			}
			elseif ($custom == 'no') {
				$sqlpartial .= "AND origin_table LIKE '%item_db' ";
			}
		}
	}
	
	// Get total count and feed back to the paginator.
	$sth = $server->connection->getStatement("SELECT COUNT(items.id) AS total FROM $tableName $sqlpartial");
	$sth->execute($bind);
	
	$paginator = $this->getPaginator($sth->fetch()->total);
	$paginator->setSortableColumns(array(
		'item_id' => 'asc', 'name', 'type', 'price_buy', 'price_sell', 'weight', 'attack', 'defense',
		'range', 'slots', 'refineable', 'cost', 'origin_table'
	));
	
	$col  = "origin_table, items.id AS item_id, name_japanese AS name, type, price_buy, weight, attack,  ";
	$col .= "defence AS defense, `range`, slots, refineable, cost, $shopTable.id AS shop_item_id, ";
	$col .= "IFNULL(price_sell, FLOOR(price_buy/2)) AS price_sell";
	
	$sql  = $paginator->getSQL("SELECT $col FROM $tableName $sqlpartial");
	$sth  = $server->connection->getStatement($sql);
	
	$sth->execute($bind);
	$items = $sth->fetchAll();
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