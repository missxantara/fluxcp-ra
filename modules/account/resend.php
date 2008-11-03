<?php
if (!defined('FLUX_ROOT')) exit;

//if (!Flux::config('RequireEmailConfirm')) {
//	$this->deny();
//}

$title = 'Resend Confirmation E-mail';

$serverNames = $this->getServerNames();
$createTable = Flux::config('FluxTables.AccountCreateTable');

if (count($_POST)) {
	$userid    = $params->get('userid');
	$email     = $params->get('email');
	$groupName = $params->get('login');
	
	if (!$userid) {
		$errorMessage = 'Please enter your account username.';
	}
	elseif (!$email) {
		$errorMessage = 'Please enter your e-mail address.';
	}
	else {
		if (!$groupName || !($loginAthenaGroup=Flux::getServerGroupByName($groupName))) {
			$loginAthenaGroup = $session->loginAthenaGroup;
		}

		$sql  = "SELECT confirm_code FROM {$loginAthenaGroup->loginDatabase}.$createTable WHERE ";
		$sql .= "userid = ? AND email = ? AND confirmed = 0 AND confirm_expire > NOW() LIMIT 1";
		$sth  = $loginAthenaGroup->connection->getStatement($sql);
		$sth->execute(array($userid, $email));

		$row  = $sth->fetch();
		if ($row) {
			require_once 'Flux/Mailer.php';
			$code = $row->confirm_code;
			$name = $loginAthenaGroup->serverName;
			$link = $this->url('account', 'confirm', array('_host' => true, 'code' => $code, 'user' => $userid, 'login' => $name));
			$mail = new Flux_Mailer();
			$sent = $mail->send($email, 'Account Confirmation', 'confirm', array('AccountUsername' => $userid, 'ConfirmationLink' => htmlspecialchars($link)));
		}

		if (empty($sent)) {
			$errorMessage = 'Failed to resend confirmation code.';
		}
		else {
			$session->setMessageData('Your confirmation code has been resent, please check your e-mail and proceed to activate your account.');
			$this->redirect();
		}
	}
}
?>