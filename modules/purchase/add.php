<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

require_once 'Flux/ItemShop.php';

$id   = $params->get('id');
$shop = new Flux_ItemShop($server);
$item = $shop->getItem($id);

if ($item) {
	$server->cart->add($item);
	$session->setMessageData("{$item->shop_item_name} has been added to your cart.");
}
else {
	$session->setMessageData("Couldn't add item to your cart.");
}

$this->redirect($this->url('purchase'));
?>