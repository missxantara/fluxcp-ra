<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$title = 'Modify Account';

$accountID = $params->get('id');

$creditsTable  = Flux::config('FluxTables.CreditsTable');
$creditColumns = 'credits.balance, credits.last_donation_date, credits.last_donation_amount';


$sql  = "SELECT login.*, {$creditColumns} FROM {$server->loginDatabase}.login ";
$sql .= "LEFT OUTER JOIN {$creditsTable} AS credits ON login.account_id = credits.account_id ";
$sql .= "WHERE login.sex != 'S' AND login.level >= 0 AND login.account_id = ? LIMIT 1";
$sth  = $server->connection->getStatement($sql);
$sth->execute(array($accountID));

// Account object.
$account = $sth->fetch();
$isMine  = false;

if ($account) {
	if ($account->level > $session->account->level && !$auth->allowedToEditHigherPower) {
		$this->deny();
	}
	
	$isMine = $account->account_id == $session->account->account_id;
	
	if ($isMine) {
		$title = "Modifying My Account";
	}
	else {
		$title = "Modifying Account ({$account->userid})";
	}
	
	if (count($_POST)) {
		$email      = trim($params->get('email'));
		$gender     = trim($params->get('gender'));
		$loginCount = (int)$params->get('logincount');
		$lastLogin  = $params->get('lastlogin_date');
		$lastIP     = trim($params->get('last_ip'));
		$level      = (int)$params->get('level');
		$balance    = (int)$params->get('balance');
		
		if ($isMine && $account->level != $level) {
			$errorMessage = 'You cannot modify your own account level.';
		}
		elseif ($account->level != $level && !$auth->allowedToEditAccountLevel) {
			$errorMessage = 'You cannot modify account levels.';
		}
		elseif ($level > $session->account->level) {
			$errorMessage = 'You cannot set an account level to be higher than your own.';
		}
		elseif (!in_array($gender, array('M', 'F'))) {
			$errorMessage = 'Gender must be male or female.';
		}
		elseif ($account->balance != $balance && !$auth->allowedToEditAccountBalance) {
			$errorMessage = 'You cannot modify account balances.';
		}
		elseif ($lastLogin && !preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $lastLogin)) {
			$errorMessage = 'Invalid last login date.';
		}
		else {
			$bind = array(
				'email'      => $email,
				'sex'        => $gender,
				'logincount' => $loginCount,
				'lastlogin'  => $lastLogin ? $lastLogin : $account->lastlogin,
				'last_ip'    => $lastIP
			);
			
			$sql  = "UPDATE {$server->loginDatabase}.login SET email = :email, ";
			$sql .= "sex = :sex, logincount = :logincount, lastlogin = :lastlogin, last_ip = :last_ip";
			
			if ($auth->allowedToEditAccountLevel) {
				$sql .= ", level = :level";
				$bind['level'] = $level;
			}
			
			$bind['account_id'] = $account->account_id;
			
			$sql .= " WHERE account_id = :account_id";
			$sth  = $server->connection->getStatement($sql);
			$sth->execute($bind);

			if ($auth->allowedToEditAccountBalance) {
				$deposit = $balance - $account->balance;
				$session->loginServer->depositCredits($account->account_id, $deposit);
			}
			
			$session->setMessageData('Account has been modified.');
			$this->redirect($this->url('account', 'view', array('id' => $account->account_id)));
		}
	}
}
?>