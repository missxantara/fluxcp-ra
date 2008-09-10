<?php
if (!defined('FLUX_ROOT')) exit;

if ($session->installerAuth) {
	if ($params->get('logout')) {
		$session->setInstallerAuthData(false);
	}
	elseif ($params->get('update_all')) {
		$installer->updateAll();
		
		if (!$installer->updateNeeded()) {
			$session->setMessageData('Updates have been installed.');
			$session->setInstallerAuthData(false);
			$this->redirect();
		}
	}
}

if (count($_POST) && !$session->installerAuth) {
	$inputPassword  = $params->get('installer_password');
	$actualPassword = Flux::config('InstallerPassword');
	
	if ($inputPassword == $actualPassword) {
		$session->setInstallerAuthData(true);
	}
	else {
		$errorMessage = 'Incorrect password.';
	}
}
?>