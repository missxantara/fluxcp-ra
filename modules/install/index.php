<?php
require_once 'Flux/TableManager.php';
if ($session->isLoggedIn()) {
	$manager = new Flux_TableManager($server);
	$schemas = array('loginDb' => array(), 'charMapDb' => array()); // 'name' => schemaName, 'exists' => true/false
	
	foreach ($manager->getSchemas() as $__schemaType => $__schemaNames) {
		foreach ($__schemaNames as $__schemaName) {
			$method = 'has'.ucfirst($__schemaType).'Table';
			$exists = $manager->{$method}($__schemaName);
			$schemas[$__schemaType][] = array('name' => $__schemaName, 'exists' => $exists);
		}
	}
	
	if (count($_POST) && $params->get('install_missing_tables')) {
		foreach ($schemas['loginDb'] as $schema) {
			if (!$schema['exists']) {
				$manager->createLoginDbTable($schema['name']);
			}
		}
		foreach ($schemas['charMapDb'] as $schema) {
			if (!$schema['exists']) {
				$manager->createCharMapDbTable($schema['name']);
			}
		}
		$this->redirect("{$this->url}#table_status");
	}
}
?>