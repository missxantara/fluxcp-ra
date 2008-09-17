<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Viewing Character</h2>
<?php if ($char): ?>
<h3>Character information for “<?php echo htmlspecialchars($char->char_name) ?>”</h3>
<table class="vertical-table">
	<tr>
		<th>Character ID</th>
		<td><?php echo htmlspecialchars($char->char_id) ?></td>
		<th>Account ID</th>
		<td><?php echo htmlspecialchars($char->char_account_id) ?></td>
		<th>Zeny</th>
		<td><?php echo number_format((int)$char->char_zeny) ?></td>
	</tr>
	<tr>
		<th>Character</th>
		<td><?php echo htmlspecialchars($char->char_name) ?></td>
		<th>Account</th>
		<td><?php echo $this->linkToAccount($char->char_account_id, $char->userid) ?></td>
		<th>Job Class</th>
		<td>
			<?php if ($job=$this->jobClassText($char->char_class)): ?>
				<?php echo htmlspecialchars($job) ?>
			<?php else: ?>
				<span class="not-applicable">Unknown</span>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Base Level</th>
		<td><?php echo number_format((int)$char->char_base_level) ?></td>
		<th>B. Experience</th>
		<td><?php echo number_format((int)$char->char_base_exp) ?></td>
		<th>Partner</th>
		<td>
			<?php if ($char->partner_name): ?>
				<?php if ($auth->allowedToViewCharacter): ?>
					<?php echo $this->linkToCharacter($char->partner_id, $char->partner_name) ?>
				<?php else: ?>
					<?php echo htmlspecialchars($char->parter_name) ?>
				<?php endif ?>
			<?php else: ?>
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Job Level</th>
		<td><?php echo number_format((int)$char->char_job_level) ?></td>
		<th>J. Experience</th>
		<td><?php echo number_format((int)$char->char_job_exp) ?></td>
		<th>Child</th>
		<td>
			<?php if ($char->child_name): ?>
				<?php if ($auth->allowedToViewCharacter): ?>
					<?php echo $this->linkToCharacter($char->child_id, $char->child_name) ?>
				<?php else: ?>
					<?php echo htmlspecialchars($char->child_name) ?>
				<?php endif ?>
			<?php else: ?>
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Current HP</th>
		<td><?php echo number_format((int)$char->char_hp) ?></td>
		<th>Max HP</th>
		<td><?php echo number_format((int)$char->char_max_hp) ?></td>
		<th>Mother</th>
		<td>
			<?php if ($char->mother_name): ?>
				<?php if ($auth->allowedToViewCharacter): ?>
					<?php echo $this->linkToCharacter($char->mother_id, $char->mother_name) ?>
				<?php else: ?>
					<?php echo htmlspecialchars($char->mother_name) ?>
				<?php endif ?>
			<?php else: ?>
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Current SP</th>
		<td><?php echo number_format((int)$char->char_sp) ?></td>
		<th>Max SP</th>
		<td><?php echo number_format((int)$char->char_max_sp) ?></td>
		<th>Father</th>
		<td>
			<?php if ($char->father_name): ?>
				<?php if ($auth->allowedToViewCharacter): ?>
					<?php echo $this->linkToCharacter($char->father_id, $char->father_name) ?>
				<?php else: ?>
					<?php echo htmlspecialchars($char->father_name) ?>
				<?php endif ?>
			<?php else: ?>
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Status Points</th>
		<td><?php echo number_format((int)$char->char_status_point) ?></td>
		<th>Skill Points</th>
		<td><?php echo number_format((int)$char->char_skill_point) ?></td>
		<th>Pet</th>
		<td>
			<?php if ($char->pet_name): ?>
				<?php echo htmlspecialchars($char->pet_name) ?>
				(<?php echo htmlspecialchars($char->pet_mob_name) ?>)
			<?php else: ?>
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Guild Name</th>
		<td>
			<?php if ($char->guild_name): ?>
				<img src="<?php echo $this->emblem($char->guild_id) ?>" />
				<?php echo htmlspecialchars($char->guild_name) ?>
			<?php else: ?>	
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
		<th>Guild Position</th>
		<td>
			<?php if ($char->guild_position): ?>
				<?php echo htmlspecialchars($char->guild_position) ?>
			<?php else: ?>
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
		<th>Homunculus</th>
		<td>
			<?php if ($char->homun_name): ?>
				<?php echo htmlspecialchars($char->homun_name) ?>
				(<?php echo htmlspecialchars($this->homunClassText($char->homun_class)) ?>)
			<?php else: ?>
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Party Name</th>
		<td>
			<?php if ($char->party_name): ?>
				<?php echo htmlspecialchars($char->party_name) ?>
			<?php else: ?>	
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
		<th>Party Leader</th>
		<td>
			<?php if ($char->party_leader_name): ?>
				<?php if ($auth->allowedToViewCharacter): ?>
					<?php echo $this->linkToCharacter($char->party_leader_id, $char->party_leader_name) ?>
				<?php else: ?>
					<?php echo htmlspecialchars($char->party_leader_name) ?>
				<?php endif ?>
			<?php else: ?>	
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
		<th>Online Status</th>
		<td>
			<?php if ($char->char_online): ?>
				<span class="online">Online</span>
			<?php else: ?>
				<span class="offline">Offline</span>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Character Stats</th>
		<td colspan="5">
			<table class="character-stats">
				<tr>
					<td><span class="stat-name">STR</span></td>
					<td><span class="stat-value"><?php echo number_format((int)$char->char_str) ?></span></td>
					<td><span class="stat-name">AGI</span></td>
					<td><span class="stat-value"><?php echo number_format((int)$char->char_agi) ?></span></td>
					<td><span class="stat-name">VIT</span></td>
					<td><span class="stat-value"><?php echo number_format((int)$char->char_vit) ?></span></td>
				</tr>
				<tr>
					<td><span class="stat-name">INT</span></td>
					<td><span class="stat-value"><?php echo number_format((int)$char->char_int) ?></span></td>
					<td><span class="stat-name">DEX</span></td>
					<td><span class="stat-value"><?php echo number_format((int)$char->char_dex) ?></span></td>
					<td><span class="stat-name">LUK</span></td>
					<td><span class="stat-value"><?php echo number_format((int)$char->char_luk) ?></span></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php if ($char->party_name): ?>
<h3>Other Party Members of “<?php echo htmlspecialchars($char->party_name) ?>”</h3>
	<?php if ($partyMembers): ?>
		<p><?php echo htmlspecialchars($char->party_name) ?> has <?php echo count($partyMembers) ?> other party member(s) besides <?php echo htmlspecialchars($char->char_name) ?>.</p>
		<table class="vertical-table">
			<tr>
				<th>Character Name</th>
				<th>Job Class</th>
				<th>Base Level</th>
				<th>Job Level</th>
				<th>Status</th>
			</tr>
			<?php foreach ($partyMembers as $partyMember): ?>
			<tr>
				<td align="right">
					<?php if ($auth->allowedToViewCharacter): ?>
						<?php echo $this->linkToCharacter($partyMember->char_id, $partyMember->name) ?>
					<?php else: ?>
						<?php echo htmlspecialchars($partyMember->name) ?>
					<?php endif ?>
				</td>
				<td>
					<?php if ($job=$this->jobClassText($partyMember->class)): ?>
						<?php echo htmlspecialchars($job) ?>
					<?php else: ?>
						<span class="not-applicable">Unknown</span>
					<?php endif ?>
				</td>
				<td><?php echo number_format((int)$partyMember->base_level) ?></td>
				<td><?php echo number_format((int)$partyMember->job_level) ?></td>
				<td>
					<?php if ($partyMember->online): ?>
						<span class="online">Online</span>
					<?php else: ?>
						<span class="offline">Offline</span>
					<?php endif ?>
				</td>
			</tr>
			<?php endforeach ?>
		</table>
	<?php else: ?>
		<p>There are no other members in this party.</p>
	<?php endif ?>
<?php endif ?>
<h3>Friends of “<?php echo htmlspecialchars($char->char_name) ?>”</h3>
<?php if ($friends): ?>
	<p><?php echo htmlspecialchars($char->char_name) ?> has <?php echo count($friends) ?> friend(s).</p>
	<table class="vertical-table">
		<tr>
			<th>Character Name</th>
			<th>Job Class</th>
			<th>Base Level</th>
			<th>Job Level</th>
			<th>Status</th>
		</tr>
		<?php foreach ($friends as $friend): ?>
		<tr>
			<td align="right">
				<?php if ($auth->allowedToViewCharacter): ?>
					<?php echo $this->linkToCharacter($friend->char_id, $friend->name) ?>
				<?php else: ?>
					<?php echo htmlspecialchars($friend->name) ?>
				<?php endif ?>
			</td>
			<td>
				<?php if ($job=$this->jobClassText($friend->class)): ?>
					<?php echo htmlspecialchars($job) ?>
				<?php else: ?>
					<span class="not-applicable">Unknown</span>
				<?php endif ?>
			</td>
			<td><?php echo number_format((int)$friend->base_level) ?></td>
			<td><?php echo number_format((int)$friend->job_level) ?></td>
			<td>
				<?php if ($friend->online): ?>
					<span class="online">Online</span>
				<?php else: ?>
					<span class="offline">Offline</span>
				<?php endif ?>
			</td>
		</tr>
		<?php endforeach ?>
	</table>
<?php else: ?>
	<p><?php echo htmlspecialchars($char->char_name) ?> has no friends.</p>
<?php endif ?>
<?php else: ?>
<p>No such character was found. <a href="javascript:history.go(-1)">Go back</a>.</p>
<?php endif ?>