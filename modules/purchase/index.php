<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired(Flux::message('LoginToPurchase'));

$title = 'Item Shop';

require_once 'Flux/ItemShop.php';

$shop  = new Flux_ItemShop($server);
$items = $shop->getItems();
?>