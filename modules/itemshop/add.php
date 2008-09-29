<?php
if (!defined('FLUX_ROOT')) exit; 

$this->loginRequired();

$title = 'Add Item to Shop';

require_once 'Flux/TemporaryTable.php';
require_once 'Flux/ItemShop.php';

$itemID = $params->get('id');

$tableName  = "{$server->charMapDatabase}.items";
$fromTables = array("{$server->charMapDatabase}.item_db", "{$server->charMapDatabase}.item_db2");
$tempTable  = new Flux_TemporaryTable($server->connection, $tableName, $fromTables);
$shopTable  = Flux::config('FluxTables.ItemShopTable');

$col = "id AS item_id, name_japanese AS item_name";
$sql = "SELECT $col FROM $tableName WHERE items.id = ?";
$sth = $server->connection->getStatement($sql);

$sth->execute(array($itemID));
$item = $sth->fetch();

if ($item && count($_POST)) {
	$maxCost  = (int)Flux::config('ItemShopMaxCost');
	$maxQty   = (int)Flux::config('ItemShopMaxQuantity');
	$shop     = new Flux_ItemShop($server);
	$cost     = (int)$params->get('cost');
	$quantity = (int)$params->get('qty');
	$info     = trim($params->get('info'));
	$image    = $files->get('image');
	
	if (!$cost) {
		$errorMessage = 'You must input a credit cost greater than zero.';
	}
	elseif ($cost > $maxCost) {
		$errorMessage = "The credit cost must not exceed $maxCost.";
	}
	elseif (!$quantity) {
		$errorMessage = 'You must input a quantity greater than zero.';
	}
	elseif ($quantity > $maxQty) {
		$errorMessage = "The item quantity must not exceed $maxQty.";
	}
	elseif (!$info) {
		$errorMessage = 'You must input at least some info text.';
	}
	else {
		if ($id=$shop->add($itemID, $cost, $quantity, $info)) {
			if ($image && $image->get('size') && !$shop->uploadShopItemImage($id, $image)) {
				$errorMessage = 'Failed to upload image.';
			}
			else {
				$session->setMessageData('Item has been successfully added to the shop.');
				$this->redirect($this->url('purchase'));	
			}
		}
		else {
			$errorMessage = 'Failed to add the item to the shop.';
		}
	}
}
?>