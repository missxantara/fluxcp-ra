<?php
require_once 'server.php';

class LoginServer extends Server {
	public $md5auth = true;
	
	public function __construct(array $config)
	{
		if (array_key_exists('md5auth', $config)) {
			$this->md5auth = (bool)$config['md5auth'];
		}
		parent::__construct($config);
	}
	
	public function isAuth($username, $password)
	{
		if ($this->md5auth) {
			$password = md5($password);
		}
		// TODO: actually implement the rest of this shit.
	}
}
?>