<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Transfer Donation Credits</h2>
<?php if (!empty($errorMessage)): ?>
	<p class="red"><?php echo htmlspecialchars($errorMessage) ?></p>
<?php endif ?>
<?php if ($session->account->balance): ?>
<h3>Credits will be transferred to character on the <?php echo htmlspecialchars($server->serverName) ?> server.</h3>
<p>You currently have <span class="remaining-balance"><?php echo number_format($session->account->balance) ?></span> credit(s).</p>
<p>
	Enter the amount you would like to transfer and character name belonging to the account you would like
	your credits transferred to:
</p>
<form action="<?php echo $this->url ?>" method="post" class="generic-form">
	<?php echo $this->moduleActionFormInputs('account', 'transfer') ?>

	<table class="generic-form-table">
		<tr>
			<th><label for="credits">Amount of Credits</label></th>
			<td><input type="text" name="credits" id="credits" value="<?php echo htmlspecialchars($params->get('credits')) ?>" /></td>
			<td><p>This is the amount of credits you would like to send.</p></td>
		</tr>
		<tr>
			<th><label for="char_name">Character Name</label></th>
			<td><input type="text" name="char_name" id="char_name" value="<?php echo htmlspecialchars($params->get('char_name')) ?>" /></td>
			<td><p>This is the character name of who will be receiving the credits.</p></td>
		</tr>
		<tr>
			<td colspan="2" align="right">
				<button type="submit" onclick="return confirm('Are you sure you want to do this?')"><strong>Transfer</strong></button>
			</td>
		</tr>
	</table>
</form>
<?php else: ?>
<p>You have no credits available in your account.</p>
<?php endif ?>