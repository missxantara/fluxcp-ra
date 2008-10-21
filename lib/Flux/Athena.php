<?php
require_once 'Flux/Config.php';

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
	 *
	 */
	public $resetDenyMaps;
	
	/**
	 *
	 */
	public $dateTimezone;
	
	/**
	 *
	 */
	public $woeDayTimes = array();
	
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
		$this->dateTimezone    = $charMapConfig->getDateTimezone();
		
		$resetDenyMaps = $charMapConfig->getResetDenyMaps();
		if (!$resetDenyMaps) {
			$this->resetDenyMaps = array('sec_pri');
		}
		elseif (!is_array($resetDenyMaps)) {
			$this->resetDenyMaps = array($resetDenyMaps);
		}
		else {
			$this->resetDenyMaps = $resetDenyMaps->toArray();
		}
		
		// Get WoE times specific in servers config.
		$woeDayTimes = $charMapConfig->getWoeDayTimes();
		if ($woeDayTimes instanceOf Flux_Config) {
			$woeDayTimes = $woeDayTimes->toArray();
			foreach ($woeDayTimes as $dayTime) {
				if (!is_array($dayTime) || count($dayTime) < 4) {
					continue;
				}
				
				list ($sDay, $sTime, $eDay, $eTime) = array_slice($dayTime, 0, 4);
				$sTime = trim($sTime);
				$eTime = trim($eTime);
				
				if ($sDay < 0 || $sDay > 6 || $eDay < 0 || $eDay > 6 ||
					!preg_match('/^\d{2}:\d{2}$/', $sTime) || !preg_match('/^\d{2}:\d{2}$/', $eTime)) {	
					continue;
				}
				
				$this->woeDayTimes[] = array(
					'startingDay'  => $sDay,
					'startingTime' => $sTime,
					'endingDay'    => $eDay,
					'endingTime'   => $eTime
				);
			}
		}
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
	
	/**
	 *
	 */
	public function charExists($charID)
	{
		$sql  = "SELECT char_id FROM {$this->charMapDatabase}.`char` WHERE ";
		$sql .= "`char`.char_id = ? LIMIT 1";
		$sth  = $this->connection->getStatement($sql);
		
		if ($sth->execute(array($charID)) && ($char=$sth->fetch()) && $char->char_id) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 *
	 */
	public function charBelongsToAccount($charID, $accountID)
	{
		$sql  = "SELECT char_id FROM {$this->charMapDatabase}.`char` WHERE ";
		$sql .= "`char`.char_id = ? AND `char`.account_id = ? LIMIT 1";
		$sth  = $this->connection->getStatement($sql);
		
		if ($sth->execute(array($charID, $accountID)) && ($char=$sth->fetch()) && $char->char_id) {
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 *
	 */
	public function charIsOnline($charID)
	{
		$sql  = "SELECT char_id FROM {$this->charMapDatabase}.`char` WHERE `char`.online > 0 ";
		$sql .= "AND `char`.char_id = ? LIMIT 1";
		$sth  = $this->connection->getStatement($sql);
		
		if ($sth->execute(array($charID)) && ($char=$sth->fetch()) && $char->char_id) {
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 *
	 */
	public function accountHasOnlineChars($accountID)
	{
		$sql  = "SELECT char_id FROM {$this->charMapDatabase}.`char` WHERE `char`.online > 0 ";
		$sql .= "AND `char`.account_id = ? ORDER BY `char`.online DESC LIMIT 1";
		$sth  = $this->connection->getStatement($sql);
		
		if ($sth->execute(array($accountID)) && ($char=$sth->fetch()) && $char->char_id) {
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 *
	 */
	public function getCharacter($charID)
	{
		$sql  = "SELECT `char`.* FROM {$this->charMapDatabase}.`char` WHERE ";
		$sql .= "`char`.char_id = ? LIMIT 1";
		$sth  = $this->connection->getStatement($sql);
		
		if ($sth->execute(array($charID)) && ($char=$sth->fetch())) {
			return $char;
		}
		else {
			return false;
		}
	}
	
	/**
	 *
	 */
	public function getPrefs($charID, array $prefs = array())
	{
		$sql = "SELECT account_id FROM {$this->charMapDatabase}.`char` WHERE char_id = ? LIMIT 1";
		$sth = $this->connection->getStatement($sql);
		
		if ($sth->execute(array($charID)) && ($char=$sth->fetch())) {
			$charPrefsTable = Flux::config('FluxTables.CharacterPrefsTable');
			
			$pref = array();
			$bind = array($char->account_id, $charID);
			$sql  = "SELECT name, value FROM {$this->charMapDatabase}.$charPrefsTable ";
			$sql .= "WHERE account_id = ? AND char_id = ?";
			
			if ($prefs) {
				foreach ($prefs as $p) {
					$pref[] = "name = ?";
					$bind[] = $p;
				}
				$sql .= sprintf(' AND (%s)', implode(' OR ', $pref));
			}
			
			$sth = $this->connection->getStatement($sql);
			
			if ($sth->execute($bind)) {
				$prefsArray = array();
				foreach ($sth->fetchAll() as $p) {
					$prefsArray[$p->name] = $p->value;
				}
				
				return new Flux_Config($prefsArray);
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}
	
	/**
	 *
	 */
	public function setPrefs($charID, array $prefsArray)
	{
		$sql = "SELECT account_id FROM {$this->charMapDatabase}.`char` WHERE char_id = ? LIMIT 1";
		$sth = $this->connection->getStatement($sql);
		
		if ($sth->execute(array($charID)) && ($char=$sth->fetch())) {
			$charPrefsTable = Flux::config('FluxTables.CharacterPrefsTable');
			
			$pref = array();
			$bind = array($char->account_id, $charID);
			$sql  = "SELECT id, name, value FROM {$this->charMapDatabase}.$charPrefsTable ";
			$sql .= "WHERE account_id = ? AND char_id = ?";
			
			if ($prefsArray) {
				foreach ($prefsArray as $prefName => $prefValue) {
					$pref[] = "name = ?";
					$bind[] = $prefName;
				}
				$sql .= sprintf(' AND (%s)', implode(' OR ', $pref));
			}
			
			$sth = $this->connection->getStatement($sql);
			
			if ($sth->execute($bind)) {
				$prefs  = $sth->fetchAll();
				$update = array();
				
				$usql   = "UPDATE {$this->charMapDatabase}.$charPrefsTable ";
				$usql  .= "SET value = ? WHERE id = ?";
				$usth   = $this->connection->getStatement($usql);
				       
				$isql   = "INSERT INTO {$this->charMapDatabase}.$charPrefsTable ";
				$isql  .= "(account_id, char_id, name, value, create_date) ";
				$isql  .= "VALUES (?, ?, ?, ?, NOW())";
				$isth   = $this->connection->getStatement($isql);
				
				foreach ($prefs as $p) {
					$update[$p->name] = $p->id;
				}
				
				foreach ($prefsArray as $pref => $value) {
					if (array_key_exists($pref, $update)) {
						$id = $update[$pref];
						$usth->execute(array($value, $id));
					}
					else {
						$isth->execute(array($char->account_id, $charID, $pref, $value));
					}
				}
				
				return true;
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}
	
	/**
	 *
	 */
	public function getPref($charID, $pref)
	{
		$prefs = $this->getPrefs($charID, array($pref));
		if ($prefs instanceOf Flux_Config) {
			return $prefs->get($pref);
		}
		else {
			return false;
		}
	}
	
	/**
	 *
	 */
	public function setPref($charID, $pref, $value)
	{
		return $this->setPrefs($charID, array($pref => $value));
	}
	
	/**
	 *
	 */
	public function resetLook($charID)
	{
		// Return values:
		// -1 = Character is online, cannot reset.
		// -2 = Unknown character.
		// false = Failed to reset.
		// true  = Successfully reset.
		
		$char = $this->getCharacter($charID);
		
		if (!$char) {
			return -2;
		}
		if ($char->online) {
			return -1;
		}
		
		$sql  = "UPDATE {$this->charMapDatabase}.inventory SET ";
		$sql .= "equip = 0 WHERE char_id = ?";
		$sth  = $this->connection->getStatement($sql);
		
		if (!$sth->execute(array($charID))) {
			return false;
		}
		
		$sql  = "UPDATE {$this->charMapDatabase}.`char` SET ";
		$sql .= "hair = 0, hair_color = 0, clothes_color = 0, weapon = 0, shield = 0, ";
		$sql .= "head_top = 0, head_mid = 0, head_bottom = 0 ";
		$sql .= "WHERE char_id = ?";
		$sth  = $this->connection->getStatement($sql);
		
		if (!$sth->execute(array($charID))) {
			return false;
		}
		else {
			return true;
		}
	}
	
	/**
	 *
	 */
	public function resetPosition($charID)
	{
		// Return values:
		// -1 = Character is online, cannot reset.
		// -2 = Reset cannot be done from current map.
		// -3 = Unknown character.
		// false = Failed to reset.
		// true  = Successfully reset.
		
		$char = $this->getCharacter($charID);
		
		if (!$char) {
			return -3;
		}
		if ($char->online) {
			return -1;
		}
		
		$charMap = basename($char->last_map, '.gat');
		foreach ($this->resetDenyMaps as $map) {
			$denyMap = basename($map, '.gat');
			if ($charMap == $denyMap) {
				return -2;
			}
		}
		
		$sql  = "UPDATE {$this->charMapDatabase}.`char` AS ch SET ";
		$sql .= "ch.last_map = ch.save_map, ch.last_x = ch.save_x, ch.last_y = ch.save_y ";
		$sql .= "WHERE ch.char_id = ?";
		$sth  = $this->connection->getStatement($sql);
		
		if ($sth->execute(array($charID))) {
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 *
	 */
	public function getServerTime($format = 'U')
	{
		$dateTime = date_create('now');
		if ($this->dateTimezone) {
			$dateTime->setTimeZone(new DateTimeZone($this->dateTimezone));
		}
		return $dateTime->format($format);
	}
	
	/**
	 *
	 */
	public function isWoe()
	{
		$serverTime = (int)$this->getServerTime();
		$dayNames   = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
			
		foreach ($this->woeDayTimes as $woeDayTime) {
			$sDay  = $dayNames[$woeDayTime['startingDay']];
			$eDay  = $dayNames[$woeDayTime['endingDay']];
			$start = strtotime("$sDay {$woeDayTime['startingTime']}");
			$end   = strtotime("$eDay {$woeDayTime['endingTime']}");
			
			if ($serverTime > $start && $serverTime < $end) {
				return true;
			}
		}
		
		return false;
	}
}
?>