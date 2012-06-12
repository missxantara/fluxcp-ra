<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$title = Flux::message('PasswordChangeTitle');

if (count($_POST)) {
	$currentPassword    = $params->get('currentpass');
	$newPassword        = $params->get('newpass');
	$confirmNewPassword = $params->get('confirmnewpass');
	
	if (!$currentPassword) {
		$errorMessage = Flux::message('NeedCurrentPassword');
	}
	elseif (!$newPassword) {
		$errorMessage = Flux::message('NeedNewPassword');
	}
	elseif (!Flux::config('AllowUserInPassword') && stripos($newPassword, $session->account->userid) !== false) {
		$errorMessage = Flux::message('PasswordContainsUser');
	}
	elseif (!ctype_graph($newPassword)) {
		$errorMessage = Flux::message('NewPasswordInvalid');
	}
	elseif (strlen($newPassword) < Flux::config('MinPasswordLength')) {
		$errorMessage = Flux::message('PasswordTooShort');
	}
	elseif (strlen($newPassword) > Flux::config('MaxPasswordLength')) {
		$errorMessage = Flux::message('PasswordTooLong');
	}
	elseif (!$confirmNewPassword) {
		$errorMessage = Flux::message('ConfirmNewPassword');
	}
	elseif ($newPassword != $confirmNewPassword) {
		$errorMessage = Flux::message('PasswordsDoNotMatch');
	}
	elseif ($newPassword == $currentPassword) {
		$errorMessage = Flux::message('NewPasswordSameAsOld');
	}
	elseif (Flux::config('PasswordMinUpper') > 0 && preg_match_all('/[A-Z]/', $password, $matches) < Flux::config('PasswordMinUpper')) {
		$errorMessage = Flux::message('NewPasswordNeedUpper');
	}
	elseif (Flux::config('PasswordMinLower') > 0 && preg_match_all('/[a-z]/', $password, $matches) < Flux::config('PasswordMinLower')) {
		$errorMessage = Flux::message('NewPasswordNeedLower');
	}
	elseif (Flux::config('PasswordMinNumber') > 0 && preg_match_all('/[0-9]/', $password, $matches) < Flux::config('PasswordMinNumber')) {
		$errorMessage = Flux::message('NewPasswordNeedNumber');
	}
	elseif (Flux::config('PasswordMinSymbol') > 0 && preg_match_all('/[^A-Za-z0-9]/', $password, $matches) < Flux::config('PasswordMinSymbol')) {
		$errorMessage = Flux::message('NewPasswordNeedSymbol');
	}
	else {
		$sql = "SELECT user_pass AS currentPassword FROM {$server->loginDatabase}.login WHERE account_id = ?";
		$sth = $server->connection->getStatement($sql);
		$sth->execute(array($session->account->account_id));
		
		$account         = $sth->fetch();
		$useMD5          = $session->loginServer->config->getUseMD5();
		$currentPassword = $useMD5 ? Flux::hashPassword($currentPassword) : $currentPassword;
		$newPassword     = $useMD5 ? Flux::hashPassword($newPassword) : $newPassword;
		
		if ($currentPassword != $account->currentPassword) {
			$errorMessage = Flux::message('OldPasswordInvalid');
		}
		else {
			$sql = "UPDATE {$server->loginDatabase}.login SET user_pass = ? WHERE account_id = ?";
			$sth = $server->connection->getStatement($sql);
			
			if ($sth->execute(array($newPassword, $session->account->account_id))) {
				$pwChangeTable = Flux::config('FluxTables.ChangePasswordTable');
				
				$sql  = "INSERT INTO {$server->loginDatabase}.$pwChangeTable ";
				$sql .= "(account_id, old_password, new_password, change_ip, change_date) ";
				$sql .= "VALUES (?, ?, ?, ?, NOW())";
				$sth  = $server->connection->getStatement($sql);
				$sth->execute(array($session->account->account_id, $currentPassword, $newPassword, $_SERVER['REMOTE_ADDR']));
				
				$session->setMessageData(Flux::message('PasswordHasBeenChanged'));
				$session->logout();
				$this->redirect($this->url('account', 'login'));
			}
			else {
				$errorMessage = Flux::message('FailedToChangePassword');
			}
		}
	}
}
?>