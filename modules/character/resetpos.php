<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$title = 'Reset Position';

$charID = $params->get('id');
if (!$charID) {
	$this->deny();
}

$char = $server->getCharacter($charID);
if (!$char || ($char->account_id != $session->account->account_id && !$auth->allowedToResetPosition)) {
	$this->deny();
}

if (!Flux_Security::csrfValidate('Session', $_GET, $error) ) {
	$session->setMessageData($error);
	$this->redirect($this->url('character', 'view', array('id' => $charID)));
}

$reset = $server->resetPosition($charID);
if ($reset === -1) {
	$message = sprintf(Flux::message('CantResetPosWhenOnline'), $char->name);
}
elseif ($reset === -2) {
	$message = sprintf(Flux::message('CantResetFromCurrentMap'), $char->name);
}
elseif ($reset === true) {
	$message = sprintf(Flux::message('ResetPositionSuccessful'), $char->name);
}
else {
	$message = sprintf(Flux::message('ResetPositionFailed'), $char->name);
}

$session->setMessageData($message);
$this->redirect($this->url('character', 'view', array('id' => $charID)));
?>