<?php
if (!defined('FLUX_ROOT')) exit;

// Force debug mode off here.
Flux::config('DebugMode', false);

if ($session->installerAuth) {
	if ($params->get('logout')) {
		$session->setInstallerAuthData(false);
	}
	else {
		$requiredMySqlVersion = '5.0';
		$requiredDbPrivileges = array('SELECT', 'UPDATE', 'INSERT', 'DELETE', 'CREATE', 'ALTER', 'DROP');
		
		foreach (Flux::$loginAthenaGroupRegistry as $serverName => $loginAthenaGroup) {
			$sth = $loginAthenaGroup->connection->getStatement("SELECT VERSION() AS mysql_version, CURRENT_USER() AS mysql_user");
			$sth->execute();
			
			$res = $sth->fetch();
			if (!$res || version_compare($res->mysql_version, $requiredMySqlVersion, '<')) {
				$message  = "MySQL version $requiredMySqlVersion or greater is required for Flux.";
				$message .= $res ? " You are running version {$res->mysql_version}" : "You are running an unknown version";
				$message .= " on the server '$serverName'"; 
				throw new Flux_Error($message);
			}
			
			list($user, $host) = explode('@', $res->mysql_user);
			$myUser = "'$user'@'$host'";
			$bind   = array($myUser, $loginAthenaGroup->loginDatabase);
			$sql    = "SELECT PRIVILEGE_TYPE AS priv, TABLE_SCHEMA AS dbname FROM INFORMATION_SCHEMA.SCHEMA_PRIVILEGES ";
			$sql   .= "WHERE GRANTEE = ? AND (TABLE_SCHEMA = ?";
			
			foreach ($loginAthenaGroup->athenaServers as $athenaServer) {
				$sql   .= " OR TABLE_SCHEMA = ?";
				$bind[] = $athenaServer->charMapDatabase;
			}
			$sql .= ")";
			$sth  = $loginAthenaGroup->connection->getStatement($sql);
			$sth->execute($bind);
			
			$privs = $sth->fetchAll();
			if (!$privs) {
				throw new Flux_Error('Unable to get database privileges from the INFORMATION_SCHEMA.SCHEMA_PRIVILEGES view.');
			}
			
			$existingPrivs = array();
			foreach ($privs as $priv) {
				if (in_array($priv->priv, $requiredDbPrivileges)) {
					if (!array_key_exists($priv->dbname, $existingPrivs)) {
						$existingPrivs[$priv->dbname] = array();
					}
					
					$existingPrivs[$priv->dbname][] = $priv->priv;
				}
			}
			
			foreach ($existingPrivs as $dbName => $privArr) {
				$missingPrivs = array_diff($requiredDbPrivileges, $privArr);
				
				if (count($missingPrivs)) {
					$message  = "Flux has detected that the $myUser user lacks all the necessary privileges on the '$dbName' database. ";
					$message .= sprintf("Please ensure you have %s privileges. Currently you are missing the %s privilege(s).",
						implode(', ', $requiredDbPrivileges), implode(', ', $missingPrivs));
					
					throw new Flux_Error($message);
				}
			}
		}
		
		if ($params->get('update_all')) {
			$installer->updateAll();

			if (!$installer->updateNeeded()) {
				$session->setMessageData('Updates have been installed.');
				$session->setInstallerAuthData(false);
				$this->redirect();
			}
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