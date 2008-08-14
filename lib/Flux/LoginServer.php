<?php
require_once 'Flux/BaseServer.php';

/**
 * Represents an eAthena Login Server.
 */
class Flux_LoginServer extends Flux_BaseServer {
	/**
	 * Connection to the MySQL server.
	 *
	 * @access public
	 * @var Flux_Connection
	 */
	public $connection;
	
	/**
	 * Login server database.
	 *
	 * @access public
	 * @var string
	 */
	public $loginDatabase;
	
	/**
	 * Overridden to add custom properties.
	 *
	 * @access public
	 */
	public function __construct(Flux_Config $config)
	{
		parent::__construct($config);
		$this->loginDatabase = $config->getDatabase();
	}
	
	/**
	 * Set the connection object to be used for this LoginServer instance.
	 *
	 * @access public
	 */
	public function setConnection(Flux_Connection $connection)
	{
		$this->connection = $connection;
		return $connection;
	}
	
	/**
	 * Validate credentials against the login server's database information.
	 *
	 * @param string $username Ragnarok account username.
	 * @param string $password Ragnarok account password.
	 * @return bool True/false if valid or invalid.
	 * @access public
	 */
	public function isAuth($username, $password)
	{
		if ($this->config->get('UseMD5')) {
			$password = md5($password);
		}
		
		$sql = "SELECT userid FROM {$this->loginDatabase}.login WHERE userid = ? AND user_pass = ?";
		$sth = $this->connection->getStatement($sql);
		$sth->execute(array($username, $password));
		
		$res = $sth->fetch();
		if ($res) {
			return true;
		}
		else {
			return false;
		}
	}
}
?>