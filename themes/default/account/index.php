<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Accounts</h2>
<form action="<?php echo $this->url ?>" method="get" class="search-form">
	<?php echo $this->moduleActionFormInputs($params->get('module')) ?>
	<p>Search for account(s):</p>
	<p>
		<label for="account_id">Account ID:</label>
		<input type="text" name="account_id" id="account_id" value="<?php echo htmlspecialchars($params->get('account_id')) ?>" />
		…
		<label for="username">Username:</label>
		<input type="text" name="username" id="username" value="<?php echo htmlspecialchars($params->get('username')) ?>" />
		<?php if ($searchPassword): ?>
		…
		<label for="password">Password:</label>
		<input type="text" name="password" id="password" value="<?php echo htmlspecialchars($params->get('password')) ?>" />
		<?php endif ?>
		…
		<label for="email">E-mail:</label>
		<input type="text" name="email" id="email" value="<?php echo htmlspecialchars($params->get('email')) ?>" />
		…
		<label for="last_ip">Last Used IP:</label>
		<input type="text" name="last_ip" id="last_ip" value="<?php echo htmlspecialchars($params->get('last_ip')) ?>" />
		…
		<label for="gender">Gender:</label>
		<select name="gender" id="gender">
			<option value=""<?php if (!in_array($gender=$params->get('gender'), array('M', 'F'))) echo ' selected="selected"' ?>>All</option>
			<option value="M"<?php if ($gender == 'M') echo ' selected="selected"' ?>>Male</option>
			<option value="F"<?php if ($gender == 'F') echo ' selected="selected"' ?>>Female</option>
		</select>
	</p>
	<p>
		<label for="account_state">Account State:</label>
		<select name="account_state" id="account_state">
			<option value=""<?php if (!($account_state=$params->get('account_state'))) echo ' selected="selected"' ?>>All</option>
			<option value="normal"<?php if ($account_state == 'normal') echo ' selected="selected"' ?>>Normal</option>
			<option value="banned"<?php if ($account_state == 'banned') echo ' selected="selected"' ?>>Temporarily Banned</option>
			<option value="permabanned"<?php if ($account_state == 'permabanned') echo ' selected="selected"' ?>>Permanently Banned</option>
		</select>
		…
		<label for="account_level">Account Level:</label>
		<select name="account_level_op">
			<option value="eq"<?php if (($account_level_op=$params->get('account_level_op')) == 'eq') echo ' selected="selected"' ?>>is equal to</option>
			<option value="gt"<?php if ($account_level_op == 'gt') echo ' selected="selected"' ?>>is greater than</option>
			<option value="lt"<?php if ($account_level_op == 'lt') echo ' selected="selected"' ?>>is less than</option>
		</select>
		<input type="text" name="account_level" id="account_level" value="<?php echo htmlspecialchars($params->get('account_level')) ?>" />
		…
		<label for="balance">Credit Balance:</label>
		<select name="balance_op">
			<option value="eq"<?php if (($balance_op=$params->get('balance_op')) == 'eq') echo ' selected="selected"' ?>>is equal to</option>
			<option value="gt"<?php if ($balance_op == 'gt') echo ' selected="selected"' ?>>is greater than</option>
			<option value="lt"<?php if ($balance_op == 'lt') echo ' selected="selected"' ?>>is less than</option>
		</select>
		<input type="text" name="balance" id="balance" value="<?php echo htmlspecialchars($params->get('balance')) ?>" />
	</p>
	<p>
		<label for="logincount">Login Count:</label>
		<select name="logincount_op">
			<option value="eq"<?php if (($logincount_op=$params->get('logincount_op')) == 'eq') echo ' selected="selected"' ?>>is equal to</option>
			<option value="gt"<?php if ($logincount_op == 'gt') echo ' selected="selected"' ?>>is greater than</option>
			<option value="lt"<?php if ($logincount_op == 'lt') echo ' selected="selected"' ?>>is less than</option>
		</select>
		<input type="text" name="logincount" id="logincount" value="<?php echo htmlspecialchars($params->get('logincount')) ?>" />
		…
		<label for="use_last_login">Last Login Date:</label>
		<input type="checkbox" name="use_last_login" id="use_last_login"<?php if ($params->get('use_last_login')) echo ' checked="checked"' ?> />
		<?php echo $this->dateField('last_login') ?>
		
		<input type="submit" value="Search" />
		<input type="button" value="Reset" onclick="reload()" />
	</p>
</form>
<?php if ($accounts): ?>
<?php echo $paginator->infoText() ?>
<table class="horizontal-table">
	<tr>
		<th><?php echo $paginator->sortableColumn('login.account_id', 'Account ID') ?></th>
		<th><?php echo $paginator->sortableColumn('userid', 'Username') ?></th>
		<?php if ($showPassword): ?><th><?php echo $paginator->sortableColumn('user_pass', 'Password') ?></th><?php endif ?>
		<th><?php echo $paginator->sortableColumn('sex', 'Gender') ?></th>
		<th><?php echo $paginator->sortableColumn('level', 'Account Level') ?></th>
		<th><?php echo $paginator->sortableColumn('state', 'Account State') ?></th>
		<th><?php echo $paginator->sortableColumn('balance', 'Credit Balance') ?></th>
		<th><?php echo $paginator->sortableColumn('email', 'E-mail') ?></th>
		<th><?php echo $paginator->sortableColumn('logincount', 'Login Count') ?></th>
		<th><?php echo $paginator->sortableColumn('lastlogin', 'Last Login Date') ?></th>
		<th><?php echo $paginator->sortableColumn('last_ip', 'Last Used IP') ?></th>
		<!-- <th><?php echo $paginator->sortableColumn('reg_date', 'Register Date') ?></th> -->
	</tr>
	<?php foreach ($accounts as $account): ?>
	<tr>
		<td align="right">
			<?php if ($auth->actionAllowed('account', 'view') && $auth->allowedToViewAccount): ?>
				<?php echo $this->linkToAccount($account->account_id, $account->account_id) ?>
			<?php else: ?>
				<?php echo htmlspecialchars($account->account_id) ?>
			<?php endif ?>
		</td>
		<td><?php echo htmlspecialchars($account->userid) ?></td>
		<?php if ($showPassword): ?><td><?php echo htmlspecialchars($account->user_pass) ?></td><?php endif ?>
		<td>
			<?php if ($gender = $this->genderText($account->sex)): ?>
				<?php echo htmlspecialchars($gender) ?>
			<?php else: ?>
				<span class="not-applicable">Unknown</span>
			<?php endif ?>
		</td>
		<td><?php echo (int)$account->level ?></td>
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
		<td><?php echo number_format((int)$account->balance) ?></td>
		<td>
			<?php if ($account->email): ?>
				<?php echo htmlspecialchars($account->email) ?>
			<?php else: ?>
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
		<td><?php echo number_format((int)$account->logincount) ?></td>
		<td>
			<?php if (!$account->lastlogin || $account->lastlogin == '0000-00-00 00:00:00'): ?>
				<span class="not-applicable">Never</span>
			<?php else: ?>
				<?php echo $this->formatDateTime($account->lastlogin) ?>
			<?php endif ?>
		</td>
		<td>
			<?php if ($account->last_ip): ?>
				<?php echo htmlspecialchars($account->last_ip) ?>
			<?php else: ?>
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
		<!-- <td>
			<?php if (!$account->reg_date || $account->reg_date == '0000-00-00 00:00:00'): ?>
				<span class="not-applicable">Unknown</span>
			<?php else: ?>
				<?php echo $this->formatDateTime($account->reg_date) ?>
			<?php endif ?>
		</td> -->
	</tr>
	<?php endforeach ?>
</table>
<?php echo $paginator->getHTML() ?>
<?php else: ?>
<p>No accounts found. <a href="javascript:history.go(-1)">Go back</a>.</p>
<?php endif ?>