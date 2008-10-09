<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$charID = $params->get('id');
if (!$charID) {
	$this->deny();
}

$title = 'Reset Position';

$reset = $server->resetPosition($charID);

if ($reset === -3) {
	$session->setMessageData(Flux::message('UnknownCharacter'));
	$this->redirect($this->referer);
}

$char = $server->getCharacter($charID);

if ($reset === -1) {
	$message = sprintf(Flux::message('CantResetPosWhenOnline'), $char->name);
}
elseif ($reset === -2) {
	$message = sprintf(FLux::message('CantResetFromCurrentMap'), $char->name);
}
elseif ($reset === true) {
	$message = sprintf(FLux::message('ResetPositionSuccessful'), $char->name);
}
else {
	$message = sprintf(FLux::message('ResetPositionFailed'), $char->name);
}

$session->setMessageData($message);
$this->redirect($this->referer);
?>