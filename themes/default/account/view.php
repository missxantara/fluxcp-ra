<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Viewing Account</h2>
<?php if (!empty($errorMessage)): ?>
<p class="red"><?php echo htmlspecialchars($errorMessage) ?></p>
<?php endif ?>
<?php if ($account): ?>
<p class="action">
	<?php if (($account->level <= $session->account->level || $auth->allowedToEditHigherPower) && $auth->actionAllowed('account', 'edit')): ?>
	<a href="<?php echo $this->url('account', 'edit', array('id' => $account->account_id)) ?>">Modify Account</a>
	<?php endif ?>
</p>
<table class="vertical-table">
	<tr>
		<th>Username</th>
		<td><?php echo $account->userid ?></td>
		<th>Account ID</th>
		<td><?php echo $account->account_id ?></td>
	</tr>
	<tr>
		<th>E-mail</th>
		<td>
			<?php if ($account->email): ?>
				<?php echo htmlspecialchars($account->email) ?>
			<?php else: ?>
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
		<th>Account Level</th>
		<td><?php echo (int)$account->level ?></td>
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
		<th>Login Count</th>
		<td><?php echo number_format((int)$account->logincount) ?></td>
		<th>Credit Balance</th>
		<td>
			<?php echo number_format((int)$account->balance) ?>
			<?php if ($auth->allowedToDonate && $isMine): ?>
				<a href="<?php echo $this->url('donate') ?>">(Donate!)</a>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Last Login Date</th>
		<td colspan="3">
			<?php if (!$account->lastlogin || $account->lastlogin == '0000-00-00 00:00:00'): ?>
				<span class="not-applicable">Never</span>
			<?php else: ?>
				<?php echo $this->formatDateTime($account->lastlogin) ?>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Last Used IP</th>
		<td colspan="3">
			<?php if ($account->last_ip): ?>
				<?php echo $account->last_ip ?>
			<?php else: ?>
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
	</tr>
	<?php if ($showTempBan): ?>
	<tr>
		<th>Temporary Ban</th>
		<td colspan="3">
			<form action="<?php echo $this->urlWithQs ?>" method="post">
				<input type="hidden" name="tempban" value="1" />
				<label>Reason:<br /><textarea name="reason" class="block reason"></textarea></label>
				<label>Ban Until:</label>
				<?php echo $this->dateTimeField('tempban'); ?>
				<input type="submit" value="Ban Account" onclick="return confirm('Are you sure?')" />
			</form>
		</td>
	</tr>
	<?php endif ?>
	<?php if ($showPermBan): ?>
	<tr>
		<th>Permanent Ban</th>
		<td colspan="3">
			<form action="<?php echo $this->urlWithQs ?>" method="post">
				<input type="hidden" name="permban" value="1" />
				<label>Reason:<br /><textarea name="reason" class="block reason"></textarea></label>
				<input type="submit" value="Permanently Ban Account" onclick="return confirm('Are you sure?')" />
			</form>
		</td>
	</tr>
	<?php endif ?>
	<?php if ($showUnban): ?>
	<tr>
		<th>Remove Ban</th>
		<td colspan="3">
			<form action="<?php echo $this->urlWithQs ?>" method="post">
				<input type="hidden" name="unban" value="1" />
			<?php if ($tempBanned && $auth->allowedToTempUnbanAccount): ?>
				<label>Reason:<br /><textarea name="reason" class="block reason"></textarea></label>
				<input type="submit" value="Remove Temporary Ban" />
			<?php elseif ($permBanned && $auth->allowedToPermUnbanAccount): ?>
				<label>Reason:<br /><textarea name="reason" class="block reason"></textarea></label>
				<input type="submit" value="Remove Permanent Ban" />
			<?php endif ?>
			</form>
		</td>
	</tr>
	<?php endif ?>
</table>

<?php if ($auth->allowedToViewAccountBanLog && $banInfo): ?>
<h3>Ban Log for “<?php echo htmlspecialchars($account->userid) ?>” (recent to oldest)</h3>
<table class="vertical-table">
	<tr>
		<th>Ban Type</th>
		<th>Ban Date</th>
		<th>Ban Reason</th>
		<th>Banned By</th>
	</tr>
	<?php foreach ($banInfo as $ban): ?>
	<tr>
		<td align="right"><?php echo htmlspecialchars($this->banTypeText($ban->ban_type)) ?></td>
		<td><?php echo htmlspecialchars($this->formatDateTime($ban->ban_date)) ?></td>
		<td><?php echo nl2br(htmlspecialchars($ban->ban_reason)) ?></td>
		<td>
			<?php if ($ban->userid): ?>
				<?php if ($auth->allowedToViewAccount): ?>
					<?php echo $this->linkToAccount($ban->banned_by, $ban->userid) ?>
				<?php else: ?>
					<?php echo htmlspecialchars($ban->userid) ?>
				<?php endif ?>
			<?php else: ?>
				<span class="not-applicable">Unknown</span>
			<?php endif ?>
		</td>
	</tr>
	<?php endforeach ?>
</table>
<?php endif ?>

<?php foreach ($characters as $serverName => $chars): $zeny = 0; ?>
	<h3>Characters on <?php echo htmlspecialchars($serverName) ?></h3>
	<?php if ($chars): ?>
	<table class="vertical-table">
		<tr>
			<th>Slot</th>
			<th>Character Name</th>
			<th>Job Class</th>
			<th>Base Level</th>
			<th>Job Level</th>
			<th>Zeny</th>
			<th colspan="2">Guild</th>
			<th>Status</th>
			<th>Preferences</th>
		</tr>
		<?php foreach ($chars as $char): $zeny += $char->zeny; ?>
		<tr>
			<td align="right"><?php echo $char->char_num+1 ?></td>
			<td>
				<?php if ($auth->actionAllowed('character', 'view') && ($isMine || (!$isMine && $auth->allowedToViewCharacter))): ?>
					<?php echo $this->linkToCharacter($char->char_id, $char->name, $serverName) ?>
				<?php else: ?>
					<?php echo htmlspecialchars($char->name) ?>
				<?php endif ?>
			</td>
			<td><?php echo htmlspecialchars($this->jobClassText($char->class)) ?></td>
			<td><?php echo (int)$char->base_level ?></td>
			<td><?php echo (int)$char->job_level ?></td>
			<td><?php echo number_format((int)$char->zeny) ?></td>
			<?php if ($char->guild_name): ?>
				<td><img src="<?php echo $this->emblem($char->guild_id) ?>" /></td>
				<td>
					<?php if ($auth->actionAllowed('guild', 'view') && $auth->allowedToViewGuild): ?>
						<?php echo $this->linkToGuild($char->guild_id, $char->guild_name) ?>
					<?php else: ?>
						<?php echo htmlspecialchars($char->guild_name) ?>
					<?php endif ?>
				</td>
			<?php else: ?>	
				<td colspan="2" align="center"><span class="not-applicable">None</span></td>
			<?php endif ?>
			<td>
				<?php if ($char->online): ?>
					<span class="online">Online</span>
				<?php else: ?>
					<span class="offline">Offline</span>
				<?php endif ?>
			</td>
			<td>
				<?php if (($isMine || $auth->allowedToModifyCharPrefs) && $auth->actionAllowed('character', 'prefs')): ?>
				<a href="<?php echo $this->url('character', 'prefs', array('id' => $char->char_id)) ?>"
					class="block-link">
					Modify Preferences
				</a>
				<?php endif ?>
			</td>
		</tr>
		<?php endforeach ?>
		</table>
		<p>Total Zeny: <strong><?php echo number_format($zeny) ?></strong></p>
	<?php else: ?>
	<p>This account has no characters on <?php echo htmlspecialchars($serverName) ?>.</p>
	<?php endif ?>
<?php endforeach ?>

<h3>Storage Items of “<?php echo htmlspecialchars($account->userid) ?>”</h3>
<?php if ($items): ?>
	<p><?php echo htmlspecialchars($account->userid) ?> has <?php echo count($items) ?> storage item(s).</p>
	<table class="vertical-table">
		<tr>
			<th>Item ID</th>
			<th>Name</th>
			<th>Amount</th>
			<th>Identified</th>
			<th>Refine Level</th>
			<th>Broken</th>
			<th>Card0</th>
			<th>Card1</th>
			<th>Card2</th>
			<th>Card3</th>
			</th>
		</tr>
		<?php foreach ($items AS $item): ?>
		<tr>
			<td align="right">
				<?php if ($auth->actionAllowed('item', 'view')): ?>
					<?php echo $this->linkToItem($item->nameid, $item->nameid) ?>
				<?php else: ?>
					<?php echo htmlspecialchars($item->nameid) ?>
				<?php endif ?>
			</td>
			<td>
				<?php if ($item->name_japanese): ?>
					<span class="item_name"><?php echo htmlspecialchars($item->name_japanese) ?></span>
				<?php else: ?>
					<span class="not-applicable">Unknown Item</span>
				<?php endif ?>
			</td>
			<td><?php echo number_format($item->amount) ?></td>
			<td>
				<?php if ($item->identify): ?>
					<span class="identified yes">Yes</span>
				<?php else: ?>
					<span class="identified no">No</span>
				<?php endif ?>
			</td>
			<td><?php echo htmlspecialchars($item->refine) ?></td>
			<td>
				<?php if ($item->attribute): ?>
					<span class="broken yes">Yes</span>
				<?php else: ?>
					<span class="broken no">No</span>
				<?php endif ?>
			</td>
			<td>
				<?php if($item->card0 && ($item->type == 4 || $item->type == 5)): ?>
					<?php if (!empty($cards[$item->card0])): ?>
						<?php if ($auth->actionAllowed('item', 'view')): ?>
							<?php echo $this->linkToItem($item->card0, $cards[$item->card0]) ?>
						<?php else: ?>
							<?php echo htmlspecialchars($cards[$item->card0]) ?>
						<?php endif ?>
					<?php else: ?>
						<?php if ($auth->actionAllowed('item', 'view')): ?>
							<?php echo $this->linkToItem($item->card0, $item->card0) ?>
						<?php else: ?>
							<?php echo htmlspecialchars($item->card0) ?>
						<?php endif ?>
					<?php endif ?>
				<?php else: ?>
					<span class="not-applicable">None</span>
				<?php endif ?>
			</td>
			<td>
				<?php if($item->card1 && ($item->type == 4 || $item->type == 5)): ?>
					<?php if (!empty($cards[$item->card1])): ?>
						<?php if ($auth->actionAllowed('item', 'view')): ?>
							<?php echo $this->linkToItem($item->card1, $cards[$item->card1]) ?>
						<?php else: ?>
							<?php echo htmlspecialchars($cards[$item->card1]) ?>
						<?php endif ?>
					<?php else: ?>
						<?php if ($auth->actionAllowed('item', 'view')): ?>
							<?php echo $this->linkToItem($item->card1, $item->card1) ?>
						<?php else: ?>
							<?php echo htmlspecialchars($item->card1) ?>
						<?php endif ?>
					<?php endif ?>
				<?php else: ?>
					<span class="not-applicable">None</span>
				<?php endif ?>
			</td>
			<td>
				<?php if($item->card2 && ($item->type == 4 || $item->type == 5)): ?>
					<?php if (!empty($cards[$item->card0])): ?>
						<?php if ($auth->actionAllowed('item', 'view')): ?>
							<?php echo $this->linkToItem($item->card2, $cards[$item->card2]) ?>
						<?php else: ?>
							<?php echo htmlspecialchars($cards[$item->card2]) ?>
						<?php endif ?>
					<?php else: ?>
						<?php if ($auth->actionAllowed('item', 'view')): ?>
							<?php echo $this->linkToItem($item->card2, $item->card2) ?>
						<?php else: ?>
							<?php echo htmlspecialchars($item->card2) ?>
						<?php endif ?>
					<?php endif ?>
				<?php else: ?>
					<span class="not-applicable">None</span>
				<?php endif ?>
			</td>
			<td>
				<?php if($item->card3 && ($item->type == 4 || $item->type == 5)): ?>
					<?php if (!empty($cards[$item->card0])): ?>
						<?php if ($auth->actionAllowed('item', 'view')): ?>
							<?php echo $this->linkToItem($item->card3, $cards[$item->card3]) ?>
						<?php else: ?>
							<?php echo htmlspecialchars($cards[$item->card3]) ?>
						<?php endif ?>
					<?php else: ?>
						<?php if ($auth->actionAllowed('item', 'view')): ?>
							<?php echo $this->linkToItem($item->card3, $item->card3) ?>
						<?php else: ?>
							<?php echo htmlspecialchars($item->card3) ?>
						<?php endif ?>
					<?php endif ?>
				<?php else: ?>
					<span class="not-applicable">None</span>
				<?php endif ?>
			</td>
		</tr>
		<?php endforeach ?>
	</table>
<?php else: ?>
	<p>There are no storage items on this account.</p>
<?php endif ?>

<?php else: ?>
<p>
	Records indicate that the account you're trying to view does not exist.
	<a href="javascript:history.go(-1)">Go back</a>.
</p>
<?php endif ?>