<?php
if (!defined('FLUX_ROOT')) exit;

$title = Flux::message('ServerInfoTitle');
$info  = array(
		'accounts'   => 0,
		'characters' => 0,
		'guilds'     => 0,
		'parties'    => 0,
		'zeny'       => 0,
		'classes'    => array()
);

// Accounts.
$sql = "SELECT COUNT(account_id) AS total FROM {$server->loginDatabase}.login";
$sth = $server->connection->getStatement($sql);
$sth->execute();
$info['accounts'] += $sth->fetch()->total;

// Characters.
$sql = "SELECT COUNT(char_id) AS total FROM {$server->charMapDatabase}.`char`";
$sth = $server->connection->getStatement($sql);
$sth->execute();
$info['characters'] += $sth->fetch()->total;

// Guilds.
$sql = "SELECT COUNT(guild_id) AS total FROM {$server->charMapDatabase}.guild";
$sth = $server->connection->getStatement($sql);
$sth->execute();
$info['guilds'] += $sth->fetch()->total;

// Parties.
$sql = "SELECT COUNT(party_id) AS total FROM {$server->charMapDatabase}.party";
$sth = $server->connection->getStatement($sql);
$sth->execute();
$info['parties'] += $sth->fetch()->total;

// Zeny.
$sql = "SELECT SUM(zeny) AS total FROM {$server->charMapDatabase}.`char` ";
if ($hideLevel=Flux::config('InfoHideZenyLevel')) {
	$sql .= "LEFT JOIN {$server->loginDatabase}.login ON login.account_id = `char`.account_id ";
	$sql .= "WHERE login.level < ?";
	$bind = array($hideLevel);
}
$sth = $server->connection->getStatement($sql);
$sth->execute($hideLevel ? $bind : array());
$info['zeny'] += $sth->fetch()->total;

// Job classes.
$sql = "SELECT class, COUNT(class) AS total FROM {$server->charMapDatabase}.`char` GROUP BY class";
$sth = $server->connection->getStatement($sql);
$sth->execute();

$classes = $sth->fetchAll();
if ($classes) {
	foreach ($classes as $class) {
		$classnum = (int)$class->class;
		$info['classes'][Flux::config("JobClasses.$classnum")] = $class->total;
		if (!Flux::config("JobClasses.$classnum")) { var_dump($class->class); }
	}
}

arsort($info['classes']);
?>