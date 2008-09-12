<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Accounts</h2>

<?php if ($accounts): ?>
<?php echo $paginator->infoText() ?>
<table class="horizontal-table">
	<tr>
		<th><?php echo $paginator->sortableColumn('account_id', 'Account ID') ?></th>
		<th><?php echo $paginator->sortableColumn('userid', 'Username') ?></th>
		<th><?php echo $paginator->sortableColumn('sex', 'Gender') ?></th>
		<!-- <th><?php echo $paginator->sortableColumn('email', 'E-Mail Address') ?></th> -->
		<th><?php echo $paginator->sortableColumn('level', 'Account Level') ?></th>
		<th><?php echo $paginator->sortableColumn('state', 'Account State') ?></th>
		<th><?php echo $paginator->sortableColumn('balance', 'Credit Balance') ?></th>
		<th><?php echo $paginator->sortableColumn('email', 'E-mail') ?></th>
		<th><?php echo $paginator->sortableColumn('logincount', 'Login Count') ?></th>
		<th><?php echo $paginator->sortableColumn('lastlogin', 'Last Login Date') ?></th>
		<th><?php echo $paginator->sortableColumn('last_ip', 'Last Used IP') ?></th>
	</tr>
	<?php foreach ($accounts as $account): ?>
	<tr>
		<td align="right">
			<?php if ($auth->allowedToViewAccount): ?>
				<?php echo $this->linkToAccount($account->account_id, $account->account_id) ?>
			<?php else: ?>
				<?php echo htmlspecialchars($account->account_id) ?>
			<?php endif ?>
		</td>
		<td><?php echo htmlspecialchars($account->userid) ?></td>
		<td>
			<?php if ($gender = $this->genderText($account->sex)): ?>
				<?php echo htmlspecialchars($gender) ?>
			<?php else: ?>
				<span class="not-applicable">Unknown</span>
			<?php endif ?>
		</td>
		<!-- <td>
			<?php if ($account->email): ?>
				<?php echo htmlspecialchars($account->email) ?>
			<?php else: ?>
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td> -->
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
	</tr>
	<?php endforeach ?>
</table>
<?php echo $paginator->getHTML() ?>
<?php endif ?>