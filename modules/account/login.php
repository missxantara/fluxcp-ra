<?php
if (count($_POST)) {
	$server   = $params->get('server');
	$username = $params->get('username');
	$password = $params->get('password');
	
	try {
		$session->login($server, $username, $password);
		$this->redirect();
	}
	catch (Flux_LoginError $e) {
		switch ($e->getCode()) {
			case Flux_LoginError::UNEXPECTED:
				$errorMessage = Flux::message('UnexpectedLoginError');
				break;
				
			case Flux_LoginError::INVALID_SERVER:
				$errorMessage = Flux::message('InvalidLoginServer');
				break;
				
			case Flux_LoginError::INVALID_LOGIN:
				$errorMessage = Flux::message('InvalidLoginCredentials');
				break;
				
			default:
				$errorMessage = Flux::message('CriticalLoginError');
				break;
		}
	}
}

$serverNames = $this->getServerNames();
?>