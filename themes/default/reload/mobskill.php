<?php if (!defined('FLUX_ROOT')) exit; ?>
if (!is_readable($mobDB1)) {
	echo "Error: '{$mobDB1}' is unreadable or does not exist.";
	exit;
} else if (!is_readable($mobDB2)) {
	echo "Error: '{$mobDB2}' is unreadable or does not exist.";
	exit;
} else if (!is_writeable($mobDB)) {
	echo "Error: '{$mobDB}' is unwriteable or does not exist.";
	exit;
} else {
	if (!is_readable($mobDB)) {
		echo "Error: '{$mobDB}' is unreadable or does not exist.";
		exit;
	} else {
		echo "<h3>Mob Skills Successfully Reloaded!</h3>";
	}
}
?>