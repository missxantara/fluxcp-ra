<?php
$pageMenu = array();
if (($isMine || $auth->allowedToModifyCharPrefs) && $auth->actionAllowed('character', 'prefs')) {
	$pageMenu['Modify Preferences'] = $this->url('character', 'prefs', array('id' => $char->char_id));
}
if (($isMine || $auth->allowedToChangeSlot) && $auth->actionAllowed('character', 'changeslot')) {
	$pageMenu['Change Slot'] = $this->url('character', 'changeslot', array('id' => $char->char_id));
}
if (($isMine || $auth->allowedToResetLook) && $auth->actionAllowed('character', 'resetlook')) {
	$pageMenu['Reset Look'] = $this->url('character', 'resetlook', array('id' => $char->char_id));
}
if (($isMine || $auth->allowedToResetPosition) && $auth->actionAllowed('character', 'resetpos')) {
	$pageMenu['Reset Position'] = $this->url('character', 'resetpos', array('id' => $char->char_id));
}
return $pageMenu;
?>