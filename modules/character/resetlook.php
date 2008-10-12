<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$charID = $params->get('id');
if (!$charID) {
	$this->deny();
}

$title = 'Reset Look';

$reset = $server->resetLook($charID);

if ($reset === -2) {
	$session->setMessageData(Flux::message('UnknownCharacter'));
	$this->redirect($this->referer);
}

$char = $server->getCharacter($charID);

if ($reset === -1) {
	$message = sprintf(Flux::message('CantResetLookWhenOnline'), $char->name);
}
elseif ($reset === true) {
	$message = sprintf(FLux::message('ResetLookSuccessful'), $char->name);
}
else {
	$message = sprintf(FLux::message('ResetLookFailed'), $char->name);
}

$session->setMessageData($message);
$this->redirect($this->referer);
?>