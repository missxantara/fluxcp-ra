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
			$sql = "SELECT account_id FROM {$server->charMapDatabase}.`char` WHERE `char`.name = ? LIMIT 1";
			$sth = $server->connection->getStatement($sql);
			
			$sth->execute(array($charName));
			$row = $sth->fetch();
			
			if (!$row->account_id) {
				$errorMessage = "No such character '$charName', make sure you typed it correctly.";
			}
			else {
				$res = $session->loginServer->transferCredits($session->account->account_id, $row->account_id, $credits);
				
				if ($res === -2) {
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
	}
	else {
		$this->deny();
	}
}
?>