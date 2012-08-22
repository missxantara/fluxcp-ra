<?php
/**
 * Get the GIT hash of a directory.
 *
 * @param string file name.
 * @return int Revision number
 */
function git_hash($file = null)
{
	if (!$file) {
		$file = FLUX_ROOT.'/.git/packed-refs';
	}
	
	if (file_exists($file) && is_readable($file)) {
		$fp  = fopen($file, 'r');
		$arr = explode("\n", fread($fp, 256));
		
		foreach($arr as $line) {
			$arr2 = split(' ',$line);
			if($arr2[1] == 'refs/remotes/origin/master') {
				return trim(substr($arr2[0], 0, 10));
			}
		}
		return null;
	}
}
?>