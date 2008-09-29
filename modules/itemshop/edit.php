<?php
if (!defined('FLUX_ROOT')) exit; 

$this->loginRequired();

$title = 'Modify Item in the Shop';

require_once 'Flux/ItemShop.php';

$shopItemID = $params->get('id');
$shop = new Flux_ItemShop($server);
$item = $shop->getItem($shopItemID);

if ($item) {
	if (count($_POST)) {
		$maxCost  = (int)Flux::config('ItemShopMaxCost');
		$maxQty   = (int)Flux::config('ItemShopMaxQuantity');
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
			if ($shop->edit($shopItemID, $cost, $quantity, $info)) {
				if ($image && $image->get('size') && !$shop->uploadShopItemImage($shopItemID, $image)) {
					$errorMessage = 'Failed to upload image.';
				}
				else {
					$session->setMessageData('Item has been successfully modified.');
					$this->redirect($this->url('purchase'));
				}
			}
			else {
				$errorMessage = 'Failed to modify the item.';
			}
		}
	}
	
	if (empty($cost)) {
		$cost = $item->shop_item_cost;
	}
	if (empty($quantity)) {
		$quantity = $item->shop_item_qty;
	}
	if (empty($info)) {
		$info = $item->shop_item_info;
	}
}
?>