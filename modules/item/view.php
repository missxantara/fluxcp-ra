<?php
if (!defined('FLUX_ROOT')) exit;

//$this->loginRequired();

$title = 'Viewing Item';

require_once 'Flux/TemporaryTable.php';

$tableName  = "{$server->charMapDatabase}.items";
$fromTables = array("{$server->charMapDatabase}.item_db", "{$server->charMapDatabase}.item_db2");
$tempTable  = new Flux_TemporaryTable($server->connection, $tableName, $fromTables);
$shopTable  = Flux::config('FluxTables.ItemShopTable');

$itemID = $params->get('id');

$col  = 'items.id AS item_id, name_english AS identifier, ';
$col .= 'name_japanese AS name, type, ';
$col .= 'price_buy, price_sell, CAST(weight/10 AS UNSIGNED INTEGER) AS weight, attack, defence, `range`, slots, ';
$col .= 'equip_jobs, equip_upper, equip_genders, equip_locations, ';
$col .= 'weapon_level, equip_level, refineable, view, script, ';
$col .= 'equip_script, unequip_script, origin_table, ';
$col .= "$shopTable.cost, $shopTable.id AS shop_item_id";

$sql  = "SELECT $col FROM {$server->charMapDatabase}.items ";
$sql .= "LEFT OUTER JOIN {$server->charMapDatabase}.$shopTable ON $shopTable.nameid = items.id ";
$sql .= "WHERE items.id = ? LIMIT 1";

$sth  = $server->connection->getStatement($sql);
$sth->execute(array($itemID));

$item = $sth->fetch();
$isCustom = null;

if ($item) {
	$title = "Viewing Item ($item->name)";
	$isCustom = (bool)preg_match('/item_db2$/', $item->origin_table);
}
?>