<?php
/**
 * The Athena class is used for all database interactions with each eA server,
 * hence its name.
 *
 * All methods related to creating/modifying any data in the Ragnarok databases
 * and tables shall always go into this class.
 */
class Flux_Athena {	
	/**
	 * Connection object for saving and retrieving data to the eA databases.
	 *
	 * @access public
	 * @var Flux_Connection
	 */
	public $connection;
	
	/**
	 * Server name, normally something like 'My Cool High-Rate'.
	 *
	 * @access public
	 * @var string
	 */
	public $serverName;
	
	/**
	 * Base experience rater. Unlike eA, this value starts at 1 being 1%
	 * 200 being 200% and so on.
	 *
	 * @access public
	 * @var int
	 */
	public $baseExpRates;
	
	/**
	 * Job experience rate. Same rules as $baseExpRates apply.
	 *
	 * @access public
	 * @var int
	 */
	public $jobExpRates;
	
	/**
	 * Base MvP bonus experience rate. Same rules as $baseExpRates apply.
	 *
	 * @access public
	 * @var int
	 */
	public $mvpExpRates;
	
	/**
	 * Drop rate. Same rules as $baseExpRates apply.
	 *
	 * @access public
	 * @var int
	 */
	public $dropRates;

	/**
	 * MvP Drop rate. Same rules as $baseExpRates apply.
	 *
	 * @access public
	 * @var int
	 */
	public $mvpDropRates;

	/**
	 * Card Drop rate. Same rules as $baseExpRates apply.
	 *
	 * @access public
	 * @var int
	 */
	public $cardDropRates;
	
	/**
	 * Database used for the login-related SQL operations.
	 *
	 * @access public
	 * @var string
	 */
	public $loginDatabase;
	
	/**
	 * Database used for the char/map (aka everything else) SQL operations.
	 * This does not include log-related tasks.
	 *
	 * @access public
	 * @var string
	 */
	public $charMapDatabase;
	
	/**
	 * Login server object tied to this collective eA server.
	 *
	 * @access public
	 * @var Flux_LoginServer
	 */
	public $loginServer;
	
	/**
	 * Character server object tied to this collective eA server.
	 *
	 * @access public
	 * @var Flux_CharServer
	 */
	public $charServer;
	
	/**
	 * Map server object tied to this collective eA server.
	 *
	 * @access public
	 * @var Flux_MapServer
	 */
	public $mapServer;
	
	/**
	 * Item shop cart.
	 *
	 * @access public
	 * @var Flux_ItemShop_Cart
	 */
	public $cart;
	
	/**
	 *
	 */
	public $loginAthenaGroup;
	
	/**
	 *
	 */
	public $maxCharSlots;
	
	/**
	 * @param Flux_Connection $connection
	 * @param Flux_Config $charMapConfig
	 * @param Flux_LoginServer $loginServer
	 * @param Flux_CharServer $charServer
	 * @param Flux_MapServer $mapServer
	 * @access public
	 */
	public function __construct(Flux_Config $charMapConfig, Flux_LoginServer $loginServer, Flux_CharServer $charServer, Flux_MapServer $mapServer)
	{
		$this->loginServer     = $loginServer;
		$this->charServer      = $charServer;
		$this->mapServer       = $mapServer;
		$this->serverName      = $charMapConfig->getServerName();
		$this->loginDatabase   = $loginServer->config->getDatabase();
		$this->charMapDatabase = $charMapConfig->getDatabase();
		$this->baseExpRates    = (int)$charMapConfig->getBaseExpRates();
		$this->jobExpRates     = (int)$charMapConfig->getJobExpRates();
		$this->mvpExpRates     = (int)$charMapConfig->getMvpExpRates();
		$this->dropRates       = (int)$charMapConfig->getDropRates();
		$this->mvpDropRates    = (int)$charMapConfig->getMvpDropRates();
		$this->cardDropRates   = (int)$charMapConfig->getCardDropRates();
		$this->maxCharSlots    = (int)$charMapConfig->getMaxCharSlots();
	}
	
	/**
	 *
	 */
	public function setConnection(Flux_Connection $connection)
	{
		$this->connection = $connection;
		return $connection;
	}
	
	/**
	 *
	 */
	public function setCart(Flux_ItemShop_Cart $cart)
	{
		$this->cart = $cart;
		return $cart;
	}
	
	/**
	 * When casted to a string, the server name should be used.
	 *
	 * @return string
	 * @access public
	 */
	public function __toString()
	{
		return $this->serverName;
	}
	
	/**
	 *
	 */
	public function transferCredits($fromAccountID, $targetCharName, $credits)
	{
		//
		// Return values:
		// -1 = From or to account, one or the other does not exist. (likely the latter.)
		// -2 = Sender has an insufficient balance.
		// -3 = Unknown character.
		// true = Successful transfer
		// false = Error
		//
		
		$sql = "SELECT account_id, char_id, name AS char_name FROM {$this->charMapDatabase}.`char` WHERE `char`.name = ? LIMIT 1";
		$sth = $this->connection->getStatement($sql);
		
		if (!$sth->execute(array($targetCharName)) || !($char=$sth->fetch())) {
			// Unknown character.
			return -3;
		}
		
		$targetAccountID = $char->account_id;
		$targetCharID    = $char->char_id;
		
		
		$sql  = "SELECT COUNT(account_id) AS accounts FROM {$this->loginDatabase}.login WHERE ";
		$sql .= "account_id = ? OR account_id = ? LIMIT 2";
		$sth  = $this->connection->getStatement($sql);
		
		if (!$sth->execute(array($fromAccountID, $targetAccountID)) || $sth->fetch()->accounts != 2) {
			// One or the other, from or to, are non-existent accounts.
			return -1;
		}
		
		if (!$this->loginServer->hasCreditsRecord($fromAccountID)) {
			// Sender has a zero balance.
			return -2;
		}
		
		$creditsTable = Flux::config('FluxTables.CreditsTable');
		$xferTable    = Flux::config('FluxTables.CreditTransferTable');
		
		// Get balance of sender.
		$sql = "SELECT balance FROM {$this->charMapDatabase}.$creditsTable WHERE account_id = ? LIMIT 1";
		$sth = $this->connection->getStatement($sql);
		
		if (!$sth->execute(array($fromAccountID))) {
			// Error.
			return false;
		}
		
		if ($sth->fetch()->balance < $credits) {
			// Insufficient balance.
			return -2;
		}
		
		// Take credits from fromAccount first.
		if ($this->loginServer->depositCredits($fromAccountID, -$credits)) {
			// Then deposit to targetAccount next.
			if (!$this->loginServer->depositCredits($targetAccountID, $credits)) {
				// Attempt to restore credits if deposit to toAccount failed.
				$this->loginServer->depositCredits($fromAccountID, $credits);
				return false;
			}
			else {
				$sql  = "INSERT INTO {$this->charMapDatabase}.$xferTable ";
				$sql .= "(from_account_id, target_account_id, target_char_id, amount, transfer_date) ";
				$sql .= "VALUES (?, ?, ?, ?, NOW())";
				$sth  = $this->connection->getStatement($sql);
				
				// Log transfer.
				$sth->execute(array($fromAccountID, $targetAccountID, $targetCharID, $credits));
				
				return true;
			}
		}
		else {
			return false;
		}
	}
	
	/**
	 *
	 */
	public function setLoginAthenaGroup(Flux_LoginAthenaGroup $loginAthenaGroup)
	{
		$this->loginAthenaGroup = $loginAthenaGroup;
		return $loginAthenaGroup;
	}
}
?>