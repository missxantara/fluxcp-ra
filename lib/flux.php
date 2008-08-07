<?php
class Flux {
	public static $config;
	public static $servers = array();
	private function __construct(){}
	
	public static function login(LoginServer $loginServer, $username, $password)
	{
		if ($loginServer->isAuth($username, $password)) {
			
		}
		else {
			
		}
	}
}
?>