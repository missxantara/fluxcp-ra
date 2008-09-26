<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$title = 'Transfer Donation Credits';

if (count($_POST)) {
	if ($session->account->balance) {
		$credits  = (int)$params->get('credits'); 
		$charName = trim($params->get('char_name'));
		
		if (!$credits || $credits < 1) {
			$errorMessage = 'You can only transfer credits in amounts greater than 1.';
		}
		elseif (!$charName) {
			$errorMessage = 'You must input a character name whom will receive the credits.';
		}
		else {
			$res = $server->transferCredits($session->account->account_id, $charName, $credits);
			
			if ($res === -3) {
				$errorMessage = "Character '$charName' does not exist. Please make sure you typed it correctly.";
			}
			elseif ($res === -2) {
				$errorMessage = 'You do not have a sufficient balance to make the transfer.';
			}
			elseif ($res !== true) {
				$errorMessage = 'Unexpected error occurred.';
			}
			else {
				$session->setMessageData('Credits have been transferred!');
				$this->redirect();
			}
		}
	}
	else {
		$this->deny();
	}
}
?>