<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$title = 'Viewing Guild';

require_once 'Flux/TemporaryTable.php';

$tableName  = "{$server->charMapDatabase}.items";
$fromTables = array("{$server->charMapDatabase}.item_db", "{$server->charMapDatabase}.item_db2");
$tempTable  = new Flux_TemporaryTable($server->connection, $tableName, $fromTables);

$guildID = $params->get('id');

$col  = "guild.*, roster.name AS char_names";

$sql  = "SELECT $col FROM {$server->charMapDatabase}.guild ";
$sql .= "LEFT JOIN {$server->charMapDatabase}.guild_member AS roster ON roster.guild_id = guild.guild_id ";
$sql .= "WHERE guild.guild_id = ?";

$sth  = $server->connection->getStatement($sql);
$sth->execute(array($guildID));

$guild = $sth->fetch();

$col  = "alliance_id, name";

$sql  = "SELECT $col FROM {$server->charMapDatabase}.guild_alliance ";
$sql .= "WHERE guild_id = ? AND opposition = 0 ORDER BY alliance_id ASC";

$sth  = $server->connection->getStatement($sql);
$sth->execute(array($guildID));

$alliances = $sth->fetchAll();

$col  = "alliance_id, name";

$sql  = "SELECT $col FROM {$server->charMapDatabase}.guild_alliance ";
$sql .= "WHERE guild_id = ? AND opposition = 1 ORDER BY alliance_id ASC";

$sth  = $server->connection->getStatement($sql);
$sth->execute(array($guildID));

$oppositions = $sth->fetchAll();

if ($guild) {
	$title = "Viewing Guild ({$guild->name})";
}

$col  = "ch.account_id, ch.char_id, ch.name, ch.class, ch.base_level, ch.job_level, ";
$col .= "roster.exp AS devotion, roster.position, ";
$col .= "pos.name AS position_name, pos.mode, pos.exp_mode";

$sql  = "SELECT $col FROM {$server->charMapDatabase}.`char` AS ch ";
$sql .= "LEFT JOIN {$server->charMapDatabase}.guild_member AS roster ON (roster.guild_id = ch.guild_id AND roster.char_id = ch.char_id) ";
$sql .= "LEFT JOIN {$server->charMapDatabase}.guild_position AS pos ON (pos.guild_id = ch.guild_id AND pos.position = roster.position) ";
$sql .= "WHERE ch.guild_id = ? ORDER BY position ASC, devotion DESC";

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

$col  = "guild_storage.*, items.name_japanese, items.type";

$sql  = "SELECT $col FROM {$server->charMapDatabase}.guild_storage ";
$sql .= "LEFT JOIN {$server->charMapDatabase}.items ON items.id = guild_storage.nameid ";
$sql .= "WHERE guild_storage.guild_id = ? ";
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