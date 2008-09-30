<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Modify Account</h2>
<?php if ($account): ?>
	<?php if (!empty($errorMessage)): ?>
		<p class="red"><?php echo htmlspecialchars($errorMessage) ?></p>
	<?php endif ?>
	<form action="<?php echo $this->urlWithQs ?>" method="post">
		<table class="vertical-table">
			<tr>
				<th>Username</th>
				<td><?php echo $account->userid ?></td>
				<th>Account ID</th>
				<td><?php echo $account->account_id ?></td>
			</tr>
			<tr>
				<th><label for="email">E-mail</label></th>
				<td><input type="text" name="email" id="email" value="<?php echo htmlspecialchars($account->email) ?>" /></td>
				<?php if ($auth->allowedToEditAccountLevel && !$isMine): ?>
					<th><label for="level">Account Level</label></th>
					<td><input type="text" name="level" id="level" value="<?php echo (int)$account->level ?>" /></td>
				<?php else: ?>
					<th>Account Level</th>
					<td>
						<input type="hidden" name="level" value="<?php echo (int)$account->level ?>" />
						<?php echo number_format((int)$account->level) ?>
					</td>
				<?php endif ?>
			</tr>
			<tr>
				<th><label for="gender">Gender</label></th>
				<td>
					<select name="gender" id="gender">
						<option value="M"<?php if ($account->sex == 'M') echo ' selected="selected"' ?>>Male</option>
						<option value="F"<?php if ($account->sex == 'F') echo ' selected="selected"' ?>>Female</option>
					</select>
				</td>
				<th>Account State</th>
				<td>
					<?php if (($state = $this->accountStateText($account->state)) && !$account->unban_time): ?>
						<?php echo $state ?>
					<?php elseif ($account->unban_time): ?>
						<span class="account-state state-banned">
							Banned Until
							<?php echo htmlspecialchars(date(Flux::config('DateTimeFormat'), $account->unban_time)) ?>
						</span>
					<?php else: ?>
						<span class="account-state state-unknown">Unknown</span>
					<?php endif ?>
				</td>
			</tr>
			<tr>
				<th><label for="logincount">Login Count</label></th>
				<td><input type="text" name="logincount" id="logincount" value="<?php echo (int)$account->logincount ?>" /></td>
				<?php if ($auth->allowedToEditAccountBalance): ?>
					<th><label for="balance">Credit Balance</label></th>
					<td><input type="text" name="balance" id="balance" value="<?php echo (int)$account->balance ?>" /></td>
				<?php else: ?>
					<th>Credit Balance</th>
					<td><?php echo number_format((int)$account->balance) ?></td>
				<?php endif ?>
			</tr>
			<tr>
				<th><label for="use_lastlogin">Last Login Date</label></th>
				<td colspan="3">
					<input type="checkbox" name="use_lastlogin" id="use_lastlogin" />
					<?php echo $this->dateTimeField('lastlogin', $account->lastlogin) ?>
				</td>
			</tr>
			<tr>
				<th><label for="last_ip">Last Used IP</label></th>
				<td colspan="3"><input type="text" name="last_ip" id="last_ip" value="<?php echo htmlspecialchars($account->last_ip) ?>" /></td>
			</tr>
			<tr>
				<td colspan="4" align="right">
					<input type="submit" value="Modify" />
				</td>
			</tr>
		</table>
	</form>
<?php else: ?>
<p>No such account. <a href="javascript:history.go(-1)">Go back</a>.</p>
<?php endif ?>