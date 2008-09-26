<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Transfer Donation Credits</h2>
<?php if (!empty($errorMessage)): ?>
	<p class="red"><?php echo htmlspecialchars($errorMessage) ?></p>
<?php endif ?>
<?php if ($session->account->balance): ?>
<form action="<?php echo $this->url ?>" method="post">
	<?php echo $this->moduleActionFormInputs('account', 'transfer') ?>
	<p>You currently have <span><?php echo number_format($session->account->balance) ?></span> credit(s).</p>
	<p>
		Enter the amount you would like to transfer and character name belonging to the account you would like
		your credits transferred to:
	</p>
	<p>
		<label class="important" for="credits">Credit Amount</label><br />
		<input type="text" name="credits" id="credits" value="<?php echo htmlspecialchars($params->get('credits')) ?>" />
	</p>
	<p>
		<label class="important" for="char_name">Character Name</label><br />
		<input type="text" name="char_name" id="char_name" value="<?php echo htmlspecialchars($params->get('char_name')) ?>" />
	</p>
	<p><button type="submit" onclick="return confirm('Are you sure you want to do this?')"><strong>Transfer</strong></button></p>
</form>
<?php else: ?>
<p>You have no credits available in your account.</p>
<?php endif ?>