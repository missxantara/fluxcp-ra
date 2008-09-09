<?php
$isMine    = false;
$accountID = $params->get('id');

if (!$accountID || $accountID == $session->account->account_id) {
	$isMine    = true;
	$accountID = $session->account->account_id;
	$account   = $session->account;
}

if (!$isMine) {
	if (!$auth->allowedToViewAccount) {
		$this->deny();
	}
	
	$sql = "SELECT * FROM {$server->loginDatabase}.login WHERE account_id = ? AND sex != 'S' AND level >= 0 LIMIT 1";
	$sth = $server->connection->getStatement($sql);
	$sth->execute(array($accountID));
	
	$account = $sth->fetch();
}

if (empty($account)) {
	$account = false;
}
?>