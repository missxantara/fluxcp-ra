<?php
require_once 'Flux/LogFile.php';
require_once 'Flux/Config.php';
require_once 'Flux/Error.php';

class Flux_PaymentNotifyRequest {
	private $ppLogFile;
	private $txnIsValid = false;
	
	public $ppServer;
	public $myBusinessEmail;
	public $myCurrencyCode;
	public $ipnVariables;
	
	public function __construct(array $ipnPostVars)
	{
		$this->ppLogFile       = new Flux_LogFile(realpath(FLUX_DATA_DIR.'/logs/paypal.log'));
		$this->ppServer        = Flux::config('PayPalIpnUrl');
		$this->myBusinessEmail = Flux::config('PayPalBusinessEmail');
		$this->myCurrencyCode  = strtoupper(Flux::config('DonationCurrency'));
		$this->ipnVariables    = new Flux_Config($ipnPostVars);
	}

	protected function logPayPal()
	{
		$args = func_get_args();
		$func = array($this->ppLogFile, 'puts');
		return call_user_func_array($func, $args);
	}
	
	public function process()
	{
		$this->logPayPal('Received notification from %s (%s)', $_SERVER['REMOTE_ADDR'], gethostbyaddr($_SERVER['REMOTE_ADDR']));
		
		if ($this->verify()) {
			$this->logPayPal('Proceeding to validate the authenticity of the transaction...');
			
			$accountEmails = Flux::config('PayPalReceiverEmails');
			$accountEmails = array_merge(array($this->myBusinessEmail), $accountEmails->toArray());
			$receiverEmail = $this->ipnVariables->get('receiver_email');
			$transactionID = $this->ipnVariables->get('txn_id');
			$paymentStatus = $this->ipnVariables->get('payment_status');
			$currencyCode  = strtoupper(substr($this->ipnVariables->get('mc_currency'), 0, 3));
			
			// Identify transaction number.
			$this->logPayPal('Transaction identified as %s.', $transactionID);
			
			if (!in_array($receiverEmail, $accountEmails)) {
				$this->logPayPal('Receiver e-mail (%s) is not recognized, unauthorized to continue.', $receiverEmail);
			}
			else {
				$customArray  = @unserialize(base64_decode((string)$this->ipnVariables->get('custom')));
				$customArray  = $customArray && is_array($customArray) ? $customArray : array();
				$customData   = new Flux_Config($customArray);
				$accountID    = $customData->get('account_id');
				$serverName   = $customData->get('server_name');
				
				if ($currencyCode != $this->myCurrencyCode) {
					$this->logPayPal('Transaction currency not exchangeable, accepting anyways. (recv: %s, expected: %s)',
						$currencyCode, $this->myCurrencyCode);
						
					$exchangeableCurrency = false;
				}
				else {
					$exchangeableCurrency = true;
				}
				
				// How much was received? (and in what currency?)
				$this->logPayPal('Received %s (%s).', $this->ipnVariables->get('mc_gross'), $currencyCode);
				
				// How much will be deposited?
				$settleAmount   = $this->ipnVariables->get('settle_amount');
				$settleCurrency = $this->ipnVariables->get('settle_currency');
				
				if ($settleAmount && $settleCurrency) {
					$this->logPayPal('Deposited into PayPal account: %s %s.', $settleAmount, $settleCurrency);
				}
				
				// Let's see where the donation credits should go to.
				$this->logPayPal('Game server name: %s, account ID: %s',
					($serverName ? $serverName : '(absent)'), ($accountID ? $accountID : '(absent)'));
					
				if (!$accountID || !$serverName) {
					$this->logPayPal('Account ID and/or game server name absent, cannot exchange for credits.');
				}
				elseif ($this->ipnVariables->get('txn_type') != 'web_accept') {
					$this->logPayPal('Transaction type is not web_accept, amount will not be exchanged for credits.');
				}
				elseif (!($servGroup = Flux::getServerGroupByName($serverName))) {
					$this->logPayPal('Unknown game server "%s", cannot process donation for credits.', $serverName);
				}

				if ($paymentStatus == 'Completed') {
					$this->logPayPal('Payment for txn_id#%s has been completed.', $transactionID);
					
					if ($exchangeableCurrency) {
						$sql = "SELECT COUNT(account_id) AS acc_id_count FROM {$servGroup->loginDatabase}.login WHERE sex != 'S' AND level >= 0 AND account_id = ?";
						$sth = $servGroup->connection->getStatement($sql);
						$sth->execute(array($accountID));
						$res = $sth->fetch();

						if (!$res) {
							$this->logPayPal('Unknown account #%s on server %s, cannot exchange for credits.', $accountID, $serverName);
						}
						else {
							$sql = "SELECT * FROM {$servGroup->loginDatabase}.flux_donation_credits WHERE account_id = ?";
							$sth = $servGroup->connection->getStatement($sql);
							$sth->execute(array($accountID));
							$res = $sth->fetch();

							if (!$res) {
								$this->logPayPal('Identified as first-time donation to the server from this account.');
								$sql = "INSERT INTO {$servGroup->loginDatabase}.flux_donation_credits (account_id, balance, last_donation_date, last_donation_amount) VALUES (?, 0, NULL, 0)";
								$sth = $servGroup->connection->getStatement($sql);
								$sth->execute(array($accountID));
							}

							$amount  = (float)$this->ipnVariables->get('mc_gross');
							$rate    = Flux::config('CreditExchangeRate');
							$credits = floor($amount / $rate);
							$this->logPayPal('Updating account credit balance from %s to %s', (int)$res->balance, $res->balance + $credits);

							$sql = "UPDATE {$servGroup->loginDatabase}.flux_donation_credits SET balance = balance + ?, last_donation_amount = ?, last_donation_date = NOW()";
							$sth = $servGroup->connection->getStatement($sql);

							if ($sth->execute(array($credits, $amount))) {
								$this->logPayPal('Deposited credits.');
							}
							else {
								$this->logPayPal('Failed to deposit credits.');
							}
							
							// Extra DB logging.
							$this->logToPayPalTable($servGroup, $accountID, $serverName);
						}
					}
				}
				else {
					$this->logPayPal('Incomplete payment status: %s (exchanging for credits will not take place)', $paymentStatus);
				}
				
				$this->logPayPal('Saving transaction details for %s...', $transactionID);
				
				if ($logFile=$this->saveDetailsToFile()) {
					$this->logPayPal('Saved transaction details for %s to: %s', $transactionID, $logFile);
				}
				else {
					$this->logPayPal('Failed to save transaction details for %s to file.', $transactionID);
				}
				
				$this->logPayPal('Done processing %s.', $transactionID);
			}
		}
		else {
			$this->logPayPal('Transaction invalid, aborting.');
		}
		
		return false;
	}
	
	private function ipnVarsToQueryString()
	{
		$ipnVars = $this->ipnVariables->toArray();
		$qString = '';
		foreach ($ipnVars as $key => $value) {
			$qString .= sprintf('&%s=%s', $key, urlencode($value));
		}
		$qString = ltrim($qString, '&');
		return $qString;
	}
	
	private function verify()
	{
		$qString  = 'cmd=_notify-validate&'.$this->ipnVarsToQueryString();
		$request  = "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$request .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$request .= 'Content-Length: '.strlen($qString)."\r\n\r\n";
		$request .= $qString;
		
		$this->logPayPal('Query string: %s', $qString);
		$this->logPayPal('Establishing connection to PayPal server at %s:80...', $this->ppServer);
		
		$fp = @fsockopen($this->ppServer, 80, $errno, $errstr, 20);
		if (!$fp) {
			$this->logPayPal("Failed to connect to PayPal server: [%d] %s", $errno, $errstr);
			return false;
		}
		else {
			$this->logPayPal('Connected. Sending request back to PayPal...');
			
			// Send POST request just as PayPal sent it.

			$this->logPayPal('Sent %d bytes of transaction data. Request size: %d bytes.', strlen($qString), fputs($fp, $request));
			$this->logPayPal('Reading back response from PayPal...');
			
			// Read until EOF, last line contains VERIFIED or INVALID.
			while (!feof($fp)) {
				$line = trim(fgets($fp));
			}
			
			// Close connection.
			fclose($fp);
			
			// Check verification status of the notify request.
			if (strtoupper($line) == 'VERIFIED') {
				$this->logPayPal('Notification verified. (recv: VERIFIED)');
				$this->txnIsValid = true;
				return true;
			}
			else {
				$this->logPayPal('Notification failed to verify. (recv: %s)', strtoupper($line));
				return false;
			}
		}
	}
	
	private function saveDetailsToFile()
	{
		if ($this->txnIsValid) {
			$logDir1 = realpath(FLUX_DATA_DIR.'/logs/transactions');
			$logDir2 = $logDir1.'/'.$this->ipnVariables->get('txn_type');
			$logDir3 = $logDir2.'/'.$this->ipnVariables->get('payment_status');
			$logFile = $logDir3.'/'.$this->ipnVariables->get('txn_id').'.log';
			
			if (!is_dir($logDir2)) {
				mkdir($logDir2, 0755);
			}
			if (!is_dir($logDir3)) {
				mkdir($logDir3, 0755);
			}
			
			$fp = fopen($logFile, 'w');
			if ($fp) {
				foreach ($this->ipnVariables->toArray() as $key => $value) {
					fwrite($fp, "$key: $value\n");
				}
				fclose($fp);
				return $logFile;
			}
		}
		return false;
	}
	
	private function logToPayPalTable(Flux_LoginAthenaGroup $servGroup, $accountID, $serverName)
	{
		if ($this->txnIsValid) {
			$this->logPayPal('Saving transaction details to PayPal transactions table...');
			$sql = "
				INSERT INTO {$servGroup->loginDatabase}.flux_paypal_transactions (
					account_id,
					server_name,
					receiver_email,
					item_name,
					item_number,
					quantity,
					payment_status,
					pending_reason,
					payment_date,
					mc_gross,
					mc_fee,
					tax,
					mc_currency,
					txn_id,
					txn_type,
					first_name,
					last_name,
					address_street,
					address_city,
					address_state,
					address_zip,
					address_country,
					address_status,
					payer_email,
					payer_status,
					payment_type,
					notify_version,
					verify_sign,
					referrer_id
				) VALUES (
					?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
					?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
				)
			";
			$var = $this->ipnVariables;
			$sth = $servGroup->connection->getStatement($sql);
			$ret = $sth->execute(array(
				$accountID,
				$serverName,
				$var->get('receiver_email'),
				$var->get('item_name'),
				$var->get('item_number'),
				$var->get('quantity'),
				$var->get('payment_status'),
				$var->get('pending_reason'),
				$var->get('payment_date'),
				$var->get('mc_gross'),
				$var->get('mc_fee'),
				$var->get('tax'),
				$var->get('mc_currency'),
				$var->get('txn_id'),
				$var->get('txn_type'),
				$var->get('first_name'),
				$var->get('last_name'),
				$var->get('address_street'),
				$var->get('address_city'),
				$var->get('address_state'),
				$var->get('address_zip'),
				$var->get('address_country'),
				$var->get('address_status'),
				$var->get('payer_email'),
				$var->get('payer_status'),
				$var->get('payment_type'),
				$var->get('notify_version'),
				$var->get('verify_sign'),
				$var->get('receiver_id')
			));
			
			if ($ret) {
				$this->logPayPal('Stored information in PayPal transactions table for server %s.', $serverName);
			}
			else {
				$errorInfo = implode('/', $sth->errorInfo());
				$this->logPayPal('Failed to save information in PayPal transactions table. (%s)', $errorInfo);
			}
		}
	}
}
?>