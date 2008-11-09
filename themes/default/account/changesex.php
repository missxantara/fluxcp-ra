<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Change Your Gender</h2>
<?php if ($cost): ?>
<p>
	Gender changes cost <span class="remaining-balance"><?php echo number_format((int)$cost) ?></span> credit(s).
	Your current balance is <span class="remaining-balance"><?php echo number_format((int)$session->account->balance) ?></span> credit(s).
</p>
<?php if (!$hasNecessaryFunds): ?>
<p>You do not have enough credits to perform a gender change at this time.</p>
<?php endif ?>
<?php endif ?>

<?php if ($hasNecessaryFunds): ?>
<?php if (empty($errorMessage)): ?>
<p><strong>Note:</strong> You cannot change gender if you have the follow character jobs: <em><?php echo implode(', ', $badJobs) ?>.</em></p>
<h3>Please make sure you want to really change!</h3>
<?php else: ?>
<p class="red"><?php echo htmlspecialchars($errorMessage) ?></p>
<?php endif ?>
<form action="<?php echo $this->urlWithQs ?>" method="post" class="generic-form">
	<input type="hidden" name="changegender" value="1" />
	<table class="generic-form-table">
		<tr>
			<td>
				<p>
					Would you like to change your gender to
					<strong><?php echo strtolower($this->genderText($session->account->sex == 'M' ? 'F' : 'M')) ?></strong>?
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p>
					<button type="submit"
						onclick="return confirm('Are you absolutely sure you want to change your gender?')">
							<strong>Yes, do it please.</strong>
					</button>
				</p>
			</td>
		</tr>
	</table>
</form>
<?php endif ?>