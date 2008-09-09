<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Viewing Account</h2>
<?php if ($account): ?>
<table class="vertical-table">
	<tr>
		<th>Username</th>
		<td><?php echo $account->userid ?></td>
		<th>Account ID</th>
		<td><?php echo $account->account_id ?></td>
	</tr>
	<tr>
		<th>E-mail</th>
		<td><?php echo $account->email ?></td>
		<th>Credit Balance</th>
		<td>
			0
			<?php if ($auth->allowedToDonate && $isMine): ?>
				<a href="<?php echo $this->url('donate') ?>">(Donate!)</a>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Gender</th>
		<td>
			<?php if ($gender = $this->genderText($account->sex)): ?>
				<?php echo $gender ?>
			<?php else: ?>
				<span class="not-applicable">Unknown</span>
			<?php endif ?>
		</td>
		<th>Login Count</th>
		<td><?php echo $account->logincount ?></td>
	</tr>
	<tr>
		<th>Last Login Date</th>
		<td colspan="3">
			<?php if ($account->lastlogin == '0000-00-00 00:00:00'): ?>
				<span class="not-applicable">Never</span>
			<?php else: ?>
				<?php echo $this->formatDateTime($account->lastlogin) ?>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Last Used IP</th>
		<td colspan="3">
			<?php if (empty($account->last_ip)): ?>
				<span class="not-applicable">None</span>
			<?php else: ?>
				<?php echo $account->last_ip ?>
			<?php endif ?>
		</td>
	</tr>
</table>
<?php else: ?>
<p>
	Records indicate that the account you're trying to view does not exist.
	<a href="javascript:history.go(-1)">Go back</a>.
</p>
<?php endif ?>