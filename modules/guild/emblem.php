<?php
if (!defined('FLUX_ROOT')) exit;

function flux_get_default_bmp_data()
{
	$filename = sprintf('%s/emblem/%s', FLUX_DATA_DIR, Flux::config('MissingEmblemBMP'));
	if (file_exists($filename)) {
		return file_get_contents($filename);
	}
}

$res = false;
if (!Flux::config('ForceEmptyEmblem')) {
	$serverName       = $params->get('login');
	$athenaServerName = $params->get('charmap');
	$guildID          = $params->get('id');
	$athenaServer     = Flux::getAthenaServerByName($serverName, $athenaServerName);

	if (!$athenaServer || !$guildID) {
		$data = flux_get_default_bmp_data();
	}

	$db  = $athenaServer->charMapDatabase;
	$sql = "SELECT emblem_len, emblem_data FROM $db.guild WHERE guild_id = ? LIMIT 1";
	$sth = $athenaServer->connection->getStatement($sql);

	$sth->execute(array($guildID));
	$res = $sth->fetch();
}

if (!$res || !$res->emblem_len) {
	$data = flux_get_default_bmp_data();
}

$data = @gzuncompress(pack('H*', $res->emblem_data));
$type = 'image/bmp';

if (!$data) {
	$data = flux_get_default_bmp_data();
}
else {
	require_once 'functions/imagecreatefrombmpstring.php';
	
	$image = imagecreatefrombmpstring($data);
	$type  = 'image/png';

	ob_start();
	imagepng($image);
	$data = ob_get_clean();
	
	imagedestroy($image);
}

header("Content-Type: $type");
header('Content-Length: '.strlen($data));
echo $data;
exit;
?>