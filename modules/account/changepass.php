<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$title = 'Change Password';

if (count($_POST)) {
	$currentPassword    = $params->get('currentpass');
	$newPassword        = trim($params->get('newpass'));
	$confirmNewPassword = trim($params->get('confirmnewpass'));
	
	if (!$currentPassword) {
		$errorMessage = 'Please enter your current password.';
	}
	elseif (!$newPassword) {
		$errorMessage = 'Please enter your new password.';
	}
	elseif (strlen($newPassword) < Flux::config('MinPasswordLength')) {
		$errorMessage = Flux::message('PasswordTooShort');
	}
	elseif (strlen($newPassword) > Flux::config('MaxPasswordLength')) {
		$errorMessage = Flux::message('PasswordTooLong');
	}
	elseif (!$confirmNewPassword) {
		$errorMessage = 'Please confirm your new password.';
	}
	elseif ($newPassword != $confirmNewPassword) {
		$errorMessage = 'New password and confirmation do not match.';
	}
	elseif ($newPassword == $currentPassword) {
		$errorMessage = 'New password cannot be the same as your current password.';
	}
	else {
		$sql = "SELECT user_pass AS currentPassword FROM {$server->loginDatabase}.login WHERE account_id = ?";
		$sth = $server->connection->getStatement($sql);
		$sth->execute(array($session->account->account_id));
		
		$account         = $sth->fetch();
		$useMD5          = $session->loginServer->config->getUseMD5();
		$currentPassword = $useMD5 ? md5($currentPassword) : $currentPassword;
		$newPassword     = $useMD5 ? md5($newPassword) : $newPassword;
		
		if ($currentPassword != $account->currentPassword) {
			$errorMessage = "The password you provided doesn't match the one we have on record.";
		}
		else {
			$sql = "UPDATE {$server->loginDatabase}.login SET user_pass = ? WHERE account_id = ?";
			$sth = $server->connection->getStatement($sql);
			
			if ($sth->execute(array($newPassword, $session->account->account_id))) {
				$session->setMessageData('Your password has been changed, please log-in again.');
				$session->logout();
				$this->redirect($this->url('account', 'login'));
			}
			else {
				$errorMessage = 'Failed to change your password.  Please contact an admin.';
			}
		}
	}
}
?>