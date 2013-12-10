<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$shopItemID = $params->get('id');

if (!$shopItemID) {
	$this->deny();
}

if (!Flux_Security::csrfValidate('Session', $_GET, $error) ) {
	$session->setMessageData($error);
	$this->redirect($this->url('purchase'));
}

require_once 'Flux/ItemShop.php';

$shop = new Flux_ItemShop($server);
$shop->deleteShopItemImage($shopItemID);

$session->setMessageData('Shop item image has been deleted.');
$this->redirect($this->referer);
?>