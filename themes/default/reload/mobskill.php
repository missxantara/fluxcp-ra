<?php
if (!defined('FLUX_ROOT')) exit;
if (!is_readable($mobDB1)) {
	echo "<p>Error: '{$mobDB1}' is unreadable or does not exist.</p>";
} else if (!is_readable($mobDB2)) {
	echo "<p>Error: '{$mobDB2}' is unreadable or does not exist.</p>";
} else if (!is_writeable($mobDB)) {
	echo "<p>Error: '{$mobDB}' is unwriteable or does not exist.</p>";
} else {
	if (!is_readable($mobDB)) {
		echo "<p>Error: '{$mobDB}' is unreadable or does not exist.</p>";
		exit;
	} else {
		echo "<h3>Mob Skills (".number_format(filesize($mobDB))." B) Successfully Reloaded!</h3>";
	}
}
?>