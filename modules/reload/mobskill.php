<?php
if (!defined('FLUX_ROOT')) exit;

$title = 'Reload Mob Skills';

$mobDB1 = Flux::config('MobSkillDb1');
$mobDB2 = Flux::config('MobSkillDb2');
$mobDB  = Flux::config('MobSkillDb');

if (is_readable($mobDB1) && is_readable($mobDB2) && is_writeable($mobDB)) {
	$fdb1 = fopen($mobDB1, 'r');
	$fdb2 = fopen($mobDB2, 'r');
	$fdb = fopen($mobDB, 'w');

	$write1 = array();
	while($text1 = fgets($fdb1)) {
		if (substr($text1, 0, 2) != '//' && !empty($text1)) {
			$text1 = explode("//", $text1, 2);
			$read1 = trim($text1[0]);
			if (!empty($read1))
				$write1[] = $read1."\r\n";
		}
	}
	fclose($fdb1);
	
	$write2 = array();
	while($text2 = fgets($fdb2)) {
		if (substr($text2, 0, 2) != '//' && !empty($text2)) {
			$text2 = explode("//", $text2, 2);
			$read2 = trim($text2[0]);
			if (!empty($read2))
				$write2[] = $read2."\r\n";
		}
	}
	fclose($fdb2);
	
	natsort($write1);
	foreach ($write1 as $line1)
		fwrite($fdb, preg_replace('/@+/','@',$line1));
	
	natsort($write2);
	foreach ($write2 as $line2)
		fwrite($fdb, preg_replace('/@+/','@',$line2));
	
	fclose($fdb);
}
?>