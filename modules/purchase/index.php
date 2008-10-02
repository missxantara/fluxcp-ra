<?php
if (!defined('FLUX_ROOT')) exit;

$title = 'Item Shop';

require_once 'Flux/ItemShop.php';

$shop  = new Flux_ItemShop($server);
$items = $shop->getItems();
?>