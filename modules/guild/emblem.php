<?php
if (!defined('FLUX_ROOT')) exit;

function flux_get_default_bmp_data()
{
	$filename = sprintf('%s/emblem/%s', FLUX_DATA_DIR, Flux::config('MissingEmblemBMP'));
	if (file_exists($filename)) {
		return file_get_contents($filename);
	}
}

function flux_display_empty_emblem()
{
	$data = flux_get_default_bmp_data();
	header("Content-Type: image/bmp");
	header('Content-Length: '.strlen($data));
	echo $data;
	exit;
}

// 1. Force displaying of empty emblem.
if (Flux::config('ForceEmptyEmblem')) {
	flux_display_empty_emblem();
}

// 2. Attempt to pull emblem from database.
$serverName       = $params->get('login');
$athenaServerName = $params->get('charmap');
$guildID          = $params->get('id');
$athenaServer     = Flux::getAthenaServerByName($serverName, $athenaServerName);

if (!$athenaServer || !$guildID) {
	// 2-1. Uh oh, incorrect request paramters :(
	flux_display_empty_emblem();
}
else {
	// 2-2. Caching enabled?
	if ($interval=Flux::config('EmblemCacheInterval')) {
		$interval *= 60;
		$dirname   = FLUX_DATA_DIR."/tmp/emblems/$serverName/$athenaServerName";
		$filename  = "$dirname/$guildID.png";
		
		if (!is_dir($dirname)) {
			mkdir($dirname, 0777, true);
		}
		elseif (file_exists($filename) && (time() - filemtime($filename)) < $interval) {
			header("Content-Type: image/png");
			header('Content-Length: '.filesize($filename));
			readfile($filename);
			exit;
		}
	}
	
	$db  = $athenaServer->charMapDatabase;
	$sql = "SELECT emblem_len, emblem_data FROM $db.guild WHERE guild_id = ? LIMIT 1";
	$sth = $athenaServer->connection->getStatement($sql);
	$sth->execute(array($guildID));
	$res = $sth->fetch();
	
	// 2-3. Apparently no emblem was found.
	if (!$res || !$res->emblem_len) {
		flux_display_empty_emblem();
	}
	else {
		require_once 'functions/imagecreatefrombmpstring.php';
		
		$data  = @gzuncompress(pack('H*', $res->emblem_data));
		$image = imagecreatefrombmpstring($data);
		
		header("Content-Type: image/png");
		//header('Content-Length: '.strlen($data)); // -- Too unsafe;  Can never be sure of the size.
		
		if ($interval) {
			imagepng($image, $filename);
		}
		
		imagepng($image);
		exit;
	}
}
?>