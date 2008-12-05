<?php
$pageMenu = array();
if ($auth->actionAllowed('item', 'edit')) {
	$pageMenu['Modify Item'] = $this->url('item', 'edit', array('id' => $item->item_id));
}
if ($auth->actionAllowed('item', 'copy')) {
	$pageMenu['Duplicate Item'] = $this->url('item', 'copy', array('id' => $item->item_id));
}
return $pageMenu;
?>