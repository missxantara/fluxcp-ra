<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$title = 'View Account';

require_once 'Flux/TemporaryTable.php';

$tableName  = "{$server->charMapDatabase}.items";
$fromTables = array("{$server->charMapDatabase}.item_db", "{$server->charMapDatabase}.item_db2");
$tempTable  = new Flux_TemporaryTable($server->connection, $tableName, $fromTables);

$creditsTable  = Flux::config('FluxTables.CreditsTable');
$creditColumns = 'credits.balance, credits.last_donation_date, credits.last_donation_amount';
$isMine        = false;
$accountID     = $params->get('id');
$account       = false;

if (!$accountID || $accountID == $session->account->account_id) {
	$isMine    = true;
	$accountID = $session->account->account_id;
	$account   = $session->account;
}

if (!$isMine) {
	// Allowed to view other peoples' account information?
	if (!$auth->allowedToViewAccount) {
		$this->deny();
	}
	
	$sql  = "SELECT login.*, {$creditColumns} FROM {$server->loginDatabase}.login ";
	$sql .= "LEFT OUTER JOIN {$creditsTable} AS credits ON login.account_id = credits.account_id ";
	$sql .= "WHERE login.sex != 'S' AND login.level >= 0 AND login.account_id = ? LIMIT 1";
	$sth  = $server->connection->getStatement($sql);
	$sth->execute(array($accountID));
	
	// Account object.
	$account = $sth->fetch();
	
	if ($account) {
		$title = "Viewing Account ({$account->userid})";
	}
}
else {
	$title = 'Viewing My Account';
}

$banSuperior = $account && (($account->level > $session->account->level && $auth->allowedToBanHigherPower) || $account->level <= $session->account->level);
$canTempBan  = !$isMine && $banSuperior && $auth->allowedToTempBanAccount;
$canPermBan  = !$isMine && $banSuperior && $auth->allowedToPermBanAccount;
$tempBanned  = $account && $account->unban_time > 0;
$permBanned  = $account && $account->state == 5;
$showTempBan = !$isMine && !$tempBanned && !$permBanned && $auth->allowedToTempBanAccount;
$showPermBan = !$isMine && !$permBanned && $auth->allowedToPermBanAccount;
$showUnban   = !$isMine && ($tempBanned && $auth->allowedToTempUnbanAccount) || ($permBanned && $auth->allowedToPermUnbanAccount);

if (count($_POST) && $account) {
	$reason = (string)$params->get('reason');
	
	if ($params->get('tempban') && ($tempBanDate=$params->get('tempban_date'))) {
		if ($canTempBan) {
			if ($server->loginServer->temporarilyBan($session->account->account_id, $reason, $account->account_id, $tempBanDate)) {
				$formattedDate = $this->formatDateTime($tempBanDate);
				$session->setMessageData("Account has been temporarily banned until $formattedDate.");
				$this->redirect($this->url('account', 'view', array('id' => $account->account_id)));
			}
			else {
				$errorMessage = 'Failed to temporarily ban account.';
			}
		}
		else {
			$errorMessage = 'You are unauthorized to place temporary bans on this account.';
		}
	}
	elseif ($params->get('permban')) {
		if ($canPermBan) {
			if ($server->loginServer->permanentlyBan($session->account->account_id, $reason, $account->account_id)) {
				$session->setMessageData("Account has been permanently banned.");
				$this->redirect($this->url('account', 'view', array('id' => $account->account_id)));
			}
			else {
				$errorMessage = 'Failed to permanently ban account.';
			}
		}
		else {
			$errorMessage = 'You are unauthorized to place permanent bans on this account.';
		}
	}
	elseif ($params->get('unban')) {
		if ($tempBanned && $auth->allowedToTempUnbanAccount &&
				$server->loginServer->unban($session->account->account_id, $reason, $account->account_id)) {
					
			$session->setMessageData('Account has been unbanned.');
			$this->redirect($this->url('account', 'view', array('id' => $account->account_id)));
		}
		elseif ($permBanned && $auth->allowedToPermUnbanAccount &&
				$server->loginServer->unban($session->account->account_id, $reason, $account->account_id)) {
					
			$session->setMessageData('Account has been unbanned.');
			$this->redirect($this->url('account', 'view', array('id' => $account->account_id)));
		}
		else {
			$errorMessage = "You are unauthorized to remove this account's ban status.";
		}
	}
}

$banInfo = false;
if ($account) {
	$banInfo = $server->loginServer->getBanInfo($account->account_id);
}

$characters = array();
foreach ($session->getAthenaServerNames() as $serverName) {
	$athena = $session->getAthenaServer($serverName);
	
	$sql  = "SELECT ch.*, guild.name AS guild_name ";
	$sql .= "FROM {$athena->charMapDatabase}.`char` AS ch ";
	$sql .= "LEFT OUTER JOIN guild ON guild.guild_id = ch.guild_id ";
	$sql .= "WHERE ch.account_id = ? ORDER BY ch.char_num ASC";
	$sth  = $server->connection->getStatement($sql);
	$sth->execute(array($accountID));

	$chars = $sth->fetchAll();
	$characters[$athena->serverName] = $chars;
}

$col  = "storage.*, items.name_japanese, items.type";

$sql  = "SELECT $col FROM {$server->charMapDatabase}.storage ";
$sql .= "LEFT JOIN {$server->charMapDatabase}.items ON items.id = storage.nameid ";
$sql .= "WHERE storage.account_id = ? ";
$sql .= "ORDER BY storage.nameid ASC, storage.identify DESC, ";
$sql .= "storage.attribute DESC, storage.refine ASC";

$sth  = $server->connection->getStatement($sql);
$sth->execute(array($account->account_id));

$items = $sth->fetchAll();
$cards = array();

if ($items) {
	$cardIDs = array();
	
	foreach ($items as $item) {
		if ($item->card0) {
			$cardIDs[] = $item->card0;
		}
		if ($item->card1) {
			$cardIDs[] = $item->card1;
		}
		if ($item->card2) {
			$cardIDs[] = $item->card2;
		}
		if ($item->card3) {
			$cardIDs[] = $item->card3;
		}
	}
	
	if ($cardIDs) {
		$ids = implode(',', array_fill(0, count($cardIDs), '?'));
		$sql = "SELECT id, name_japanese FROM {$server->charMapDatabase}.items WHERE id IN ($ids)";
		$sth = $server->connection->getStatement($sql);
	
		$sth->execute($cardIDs);
		$temp = $sth->fetchAll();
		if ($temp) {
			foreach ($temp as $card) {
				$cards[$card->id] = $card->name_japanese;
			}
		}
	}
}
?>