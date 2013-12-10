<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

if (!$auth->allowedToDeleteShopItem) {
	$this->deny();
}

if (!Flux_Security::csrfValidate('Session', $_GET, $error) ) {
	$session->setMessageData($error);
	$this->redirect($this->url('purchase'));
}

require_once 'Flux/ItemShop.php';

$shop       = new Flux_ItemShop($server);
$shopItemID = $params->get('id');
$deleted    = $shopItemID ? $shop->delete($shopItemID) : false;

if ($deleted) {
	$session->setMessageData('Item successfully deleted from the item shop.');
	$this->redirect($this->url('purchase'));
}
?>