<?php
if (count($_POST)) {
	// Update preferred server.
	if ($params->get('preferred_server')) {
		$session->setAthenaServerNameData($params->get('preferred_server'));
		$this->redirect($this->url);
	}
}

// Preferred server.
$server = $session->getAthenaServer();
?>