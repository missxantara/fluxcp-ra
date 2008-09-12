<?php
if (!defined('FLUX_ROOT')) exit;

$bind          = array();
$creditsTable  = Flux::config('FluxTables.CreditsTable');
$creditColumns = 'credits.balance, credits.last_donation_date, credits.last_donation_amount';
$sqlpartial    = "LEFT OUTER JOIN {$server->loginDatabase}.{$creditsTable} AS credits ON login.account_id = credits.account_id ";
$sqlpartial   .= "WHERE login.sex != 'S' AND login.level >= 0 ";

$accountID = $params->get('account_id');
if ($accountID) {
	$sqlpartial .= "AND login.account_id = ?";
	$bind[]      = $accountID;
}
else {
	$opMapping      = array('eq' => '=', 'gt' => '>', 'lt' => '<');
	$opValues       = array_keys($opMapping);
	$username       = $params->get('username');
	$email          = $params->get('email');
	$lastIP         = $params->get('last_ip');
	$gender         = $params->get('gender');
	$accountState   = $params->get('account_state');
	$accountLevelOp = $params->get('account_level_op');
	$accountLevel   = $params->get('account_level');
	$balanceOp      = $params->get('balance_op');
	$balance        = $params->get('balance');
	$loginCountOp   = $params->get('logincount_op');
	$loginCount     = $params->get('logincount');
	$lastLoginDate  = $params->get('last_login_date');
	
	if ($username) {
		$sqlpartial .= "AND (login.userid LIKE ? OR login.userid = ?) ";
		$bind[]      = "%$username%";
		$bind[]      = $username;
	}
	
	if ($email) {
		$sqlpartial .= "AND (login.email LIKE ? OR login.email = ?) ";
		$bind[]      = "%$email%";
		$bind[]      = $email;
	}
	
	if ($lastIP) {
		$sqlpartial .= "AND (login.last_ip LIKE ? OR login.last_ip = ?) ";
		$bind[]      = "%$lastIP%";
		$bind[]      = $lastIP;
	}
	
	if (in_array($gender, array('M', 'F'))) {
		$sqlpartial .= "AND login.sex = ? ";
		$bind[]      = $gender;
	}
	
	if ($accountState) {
		if ($accountState == 'normal') {
			$sqlpartial .= 'AND (login.state = 0 AND login.unban_time = 0) ';
		}
		elseif ($accountState == 'permabanned') {
			$sqlpartial .= 'AND (login.state = 5 AND login.unban_time = 0) ';
		}
		elseif ($accountState == 'banned') {
			$sqlpartial .= 'AND login.unban_time > 0 ';
		}
	}
	
	if (in_array($accountLevelOp, $opValues) && trim($accountLevel) != '') {
		$op          = $opMapping[$accountLevelOp];
		$sqlpartial .= "AND login.level $op ? ";
		$bind[]      = $accountLevel;
	}
	
	if (in_array($balanceOp, $opValues) && trim($balance) != '') {
		$op          = $opMapping[$balanceOp];
		$sqlpartial .= "AND credits.balance $op ? ";
		$bind[]      = $balance;
	}
	
	if (in_array($loginCountOp, $opValues) && trim($loginCount) != '') {
		$op          = $opMapping[$loginCountOp];
		$sqlpartial .= "AND login.logincount $op ? ";
		$bind[]      = $loginCount;
	}
	
	if ($lastLoginDate && ($timestamp = strtotime($lastLoginDate))) {
		$year        = date('Y', $timestamp);
		$month       = date('m', $timestamp);
		$day         = date('d', $timestamp);
		$sqlpartial .= 'AND (YEAR(login.lastlogin) = ? AND MONTH(login.lastlogin) = ? AND DAY(login.lastlogin) = ?) ';
		$bind[]      = $year;
		$bind[]      = $month;
		$bind[]      = $day;
	}
}

$sql  = "SELECT COUNT(login.account_id) AS total FROM {$server->loginDatabase}.login $sqlpartial";
$sth  = $server->connection->getStatement($sql);
$sth->execute($bind);

$paginator = $this->getPaginator($sth->fetch()->total);
$paginator->setSortableColumns(array('account_id', 'userid' => 'asc', 'sex', 'level', 'state', 'balance', 'email', 'logincount', 'lastlogin', 'last_ip'));

$sql  = $paginator->getSQL("SELECT login.*, {$creditColumns} FROM {$server->loginDatabase}.login $sqlpartial");
$sth  = $server->connection->getStatement($sql);
$sth->execute($bind);

$accounts = $sth->fetchAll();

if ($accounts && count($accounts) === 1) {
	$this->redirect($this->url('account', 'view', array('id' => $accounts[0]->account_id)));
}
?>