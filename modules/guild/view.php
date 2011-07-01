<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$title = 'Viewing Guild';

require_once 'Flux/TemporaryTable.php';

$tableName  = "{$server->charMapDatabase}.items";
$fromTables = array("{$server->charMapDatabase}.item_db", "{$server->charMapDatabase}.item_db2");
$tempTable  = new Flux_TemporaryTable($server->connection, $tableName, $fromTables);

$guildID = $params->get('id');

$col  = "guild.*, `char`.name AS guild_master";

$sql  = "SELECT $col FROM {$server->charMapDatabase}.guild LEFT JOIN {$server->charMapDatabase}.`char` ON `char`.char_id = guild.char_id ";
$sql .= "WHERE guild.guild_id = ?";

$sth  = $server->connection->getStatement($sql);
$sth->execute(array($guildID));

$guild = $sth->fetch();

$col  = "guild_alliance.alliance_id, guild.name";

$sql  = "SELECT $col FROM {$server->charMapDatabase}.guild_alliance LEFT JOIN {$server->charMapDatabase}.guild ON guild_alliance.alliance_id = guild.guild_id ";
$sql .= "WHERE guild_alliance.guild_id = ? AND guild_alliance.opposition = 0 ORDER BY guild_alliance.alliance_id ASC";

$sth  = $server->connection->getStatement($sql);
$sth->execute(array($guildID));

$alliances = $sth->fetchAll();

$col  = "guild_alliance.alliance_id, guild.name";

$sql  = "SELECT $col FROM {$server->charMapDatabase}.guild_alliance LEFT JOIN {$server->charMapDatabase}.guild ON guild_alliance.alliance_id = guild.guild_id ";
$sql .= "WHERE guild_alliance.guild_id = ? AND guild_alliance.opposition = 1 ORDER BY guild_alliance.alliance_id ASC";

$sth  = $server->connection->getStatement($sql);
$sth->execute(array($guildID));

$oppositions = $sth->fetchAll();

if ($guild) {
	$title = "Viewing Guild ({$guild->name})";
}

$col  = "ch.account_id, ch.char_id, ch.name, ch.class, ch.base_level, ch.job_level, ";
$col .= "IF (ch.online = 1, 'Online Now!', IF(DATE_FORMAT(acc.lastlogin, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d'), 'Today', ";
$col .= "IF (DATE_FORMAT(acc.lastlogin, '%Y-%m-%d') = DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 DAY), '%Y-%m-%d'), 'Yesterday', ";
$col .= "IF (DATE_FORMAT(acc.lastlogin, '%Y-%m-%d') > DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 WEEK), '%Y-%m-%d'), 'Several Days Ago', ";
$col .= "IF (DATE_FORMAT(acc.lastlogin, '%Y-%m-%d') > DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 2 WEEK), '%Y-%m-%d'), 'A Week Ago', ";
$col .= "CONCAT(PERIOD_DIFF(DATE_FORMAT(NOW(), '%Y%m'), DATE_FORMAT(acc.lastlogin, '%Y%m')) * 4, ' Weeks Ago')))))) AS lastlogin, ";
$col .= "roster.exp AS devotion, roster.position, ";
$col .= "pos.name AS position_name, pos.mode, pos.exp_mode";

$sql  = "SELECT $col FROM {$server->charMapDatabase}.`char` AS ch ";
$sql .= "LEFT JOIN {$server->loginDatabase}.login AS acc ON acc.account_id = ch.account_id ";
$sql .= "LEFT JOIN {$server->charMapDatabase}.guild_member AS roster ON (roster.guild_id = ch.guild_id AND roster.char_id = ch.char_id) ";
$sql .= "LEFT JOIN {$server->charMapDatabase}.guild_position AS pos ON (pos.guild_id = ch.guild_id AND pos.position = roster.position) ";
$sql .= "WHERE ch.guild_id = ? ORDER BY roster.position ASC, acc.lastlogin DESC";

$sth  = $server->connection->getStatement($sql);
$sth->execute(array($guildID));

$members = $sth->fetchAll();

$isMine = false;
foreach ($members as $member) {
	if ($guild && $member->account_id == $session->account->account_id) {
		$isMine = true;
	}
}

if (!$isMine && !$auth->allowedToViewGuild) {
	$this->deny();
}

$col  = "account_id, name, mes";

$sql  = "SELECT $col FROM {$server->charMapDatabase}.guild_expulsion ";
$sql .= "WHERE guild_id = ? ORDER BY name ASC";

$sth  = $server->connection->getStatement($sql);
$sth->execute(array($guildID));

$expulsions = $sth->fetchAll();

$col  = "guild_storage.*, items.name_japanese, items.type, items.slots, c.char_id, c.name AS char_name";

$sql  = "SELECT $col FROM {$server->charMapDatabase}.guild_storage ";
$sql .= "LEFT JOIN {$server->charMapDatabase}.items ON items.id = guild_storage.nameid ";
$sql .= "LEFT JOIN {$server->charMapDatabase}.`char` AS c ";
$sql .= "ON c.char_id = IF(guild_storage.card0 IN (254, 255), ";
$sql .= "IF(guild_storage.card0 = 255 && guild_storage.card2 < 0, ";
$sql .= "guild_storage.card2 + 65536, guild_storage.card2) ";
$sql .= "| (guild_storage.card3 << 16), NULL) ";
$sql .= "WHERE guild_storage.guild_id = ? ";

if (!$auth->allowedToSeeUnknownItems) {
	$sql .= 'AND guild_storage.identify > 0 ';
}

$sql .= "ORDER BY guild_storage.nameid ASC, guild_storage.identify DESC, ";
$sql .= "guild_storage.attribute ASC, guild_storage.refine ASC";

$sth  = $server->connection->getStatement($sql);
$sth->execute(array($guildID));

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