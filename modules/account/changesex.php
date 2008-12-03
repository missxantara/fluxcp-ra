<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$title = Flux::message('GenderChangeTitle');

$cost     = +(int)Flux::config('ChargeGenderChange');
$jobIndex = Flux::config('JobClassIndex');
$badJobs  = array('Bard', 'Clown', 'Gypsy', 'Dancer');

if ($cost && $session->account->balance < $cost) {
	$hasNecessaryFunds = false;
}
else {
	$hasNecessaryFunds = true;
}

if (count($_POST)) {
	if (!$hasNecessaryFunds || !$params->get('changegender')) {
		$this->deny();
	}
	
	$classes = array();
	foreach ($session->loginAthenaGroup->athenaServers as $athenaServer) {
		$sql = "SELECT `class` FROM {$athenaServer->charMapDatabase}.`char` WHERE account_id = ?";
		$sth = $athenaServer->connection->getStatement($sql);
		$sth->execute(array($session->account->account_id));
		$chars = $sth->fetchAll();
		
		if ($chars) {
			foreach ($chars as $char) {
				$classes[] = $char->class;
			}
		}
	}
	
	$bad = array();
	foreach ($badJobs as $badJob) {
		if ($index=$jobIndex->get($badJob)) {
			$bad[] = $index;
		}
	}
	
	foreach ($classes as $class) {
		if (in_array($class, $bad)) {
			$errorMessage = sprintf(Flux::message('GenderChangeBadChars'), implode(', ', $badJobs));
			break;
		}
	}
	
	if (empty($errorMessage)) {
		$sex = $session->account->sex == 'M' ? 'F' : 'M';
		$sql = "UPDATE {$server->loginDatabase}.login SET sex = ? WHERE account_id = ?";
		$sth = $server->connection->getStatement($sql);

		$sth->execute(array($sex, $session->account->account_id));

		$changeTimes = (int)$session->loginServer->getPref($session->account->account_id, 'NumberOfGenderChanges');
		$session->loginServer->setPref($session->account->account_id, 'NumberOfGenderChanges', $changeTimes + 1);

		if ($cost) {
			$session->loginServer->depositCredits($session->account->account_id, -$cost);
			$session->setMessageData(sprintf(Flux::message('GenderChanged'), $cost));
		}
		else {
			$session->setMessageData(Flux::message('GenderChangedForFree'));
		}

		$this->redirect($this->url('account', 'view'));
	}
}
?>