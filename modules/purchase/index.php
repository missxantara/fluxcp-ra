<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired('Please login to continue to the item shop.');

$title = 'Item Shop';

require_once 'Flux/ItemShop.php';

$shop  = new Flux_ItemShop($server);
$items = $shop->getItems();
?>