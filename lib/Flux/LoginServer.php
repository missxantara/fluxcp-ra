<?php
require_once 'Flux/BaseServer.php';
require_once 'Flux/RegisterError.php';

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
	 * @param Flux_Connection $connection
	 * @return Flux_Connection
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
		
		$sql = "SELECT userid FROM {$this->loginDatabase}.login WHERE sex != 'S' AND level >= 0 AND userid = ? AND user_pass = ?";
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
	
	/**
	 *
	 */
	public function register($username, $password, $confirmPassword, $email, $gender, $securityCode)
	{
		if (strlen($username) < Flux::config('MinUsernameLength')) {
			throw new Flux_RegisterError('Username is too short', Flux_RegisterError::USERNAME_TOO_SHORT);
		}
		elseif (strlen($username) > Flux::config('MaxUsernameLength')) {
			throw new Flux_RegisterError('Username is too long', Flux_RegisterError::USERNAME_TOO_LONG);
		}
		elseif (strlen($password) < Flux::config('MinPasswordLength')) {
			throw new Flux_RegisterError('Password is too short', Flux_RegisterError::PASSWORD_TOO_SHORT);
		}
		elseif (strlen($password) > Flux::config('MaxPasswordLength')) {
			throw new Flux_RegisterError('Password is too long', Flux_RegisterError::PASSWORD_TOO_LONG);
		}
		elseif ($password !== $confirmPassword) {
			throw new Flux_RegisterError('Passwords do not match', Flux_RegisterError::PASSWORD_MISMATCH);
		}
		elseif (!preg_match('/(.+?)@(.+?)/', $email)) {
			throw new Flux_RegisterError('Invalid e-mail address', Flux_RegisterError::INVALID_EMAIL_ADDRESS);
		}
		elseif (!in_array(strtoupper($gender), array('M', 'F'))) {
			throw new Flux_RegisterError('Invalid gender', Flux_RegisterError::INVALID_GENDER);
		}
		elseif (Flux::config('UseCaptcha') && $securityCode !== Flux::$sessionData->securityCode) {
			throw new Flux_RegisterError('Invalid security code', Flux_RegisterError::INVALID_SECURITY_CODE);
		}
		
		$sql = "SELECT userid FROM {$this->loginDatabase}.login WHERE userid = ? LIMIT 1";
		$sth = $this->connection->getStatement($sql);
		$sth->execute(array($username));
		
		$res = $sth->fetch();
		if ($res) {
			throw new Flux_RegisterError('Username is already taken', Flux_RegisterError::USERNAME_ALREADY_TAKEN);
		}
		
		if (!Flux::config('AllowDuplicateEmails')) {
			$sql = "SELECT email FROM {$this->loginDatabase}.login WHERE email = ? LIMIT 1";
			$sth = $this->connection->getStatement($sql);
			$sth->execute(array($email));

			$res = $sth->fetch();
			if ($res) {
				throw new Flux_RegisterError('E-mail address is already in use', Flux_RegisterError::EMAIL_ADDRESS_IN_USE);
			}
		}
		
		if ($this->config->getUseMD5()) {
			$password = md5($password);
		}
		
		$sql = "INSERT INTO {$this->loginDatabase}.login (userid, user_pass, email, sex) VALUES (?, ?, ?, ?)";
		$sth = $this->connection->getStatement($sql);
		return $sth->execute(array($username, $password, $email, $gender));
	}
}
?>