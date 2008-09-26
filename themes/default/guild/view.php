<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Viewing Guild</h2>
<?php if ($guild): ?>
<h3>Guild Information for “<?php echo htmlspecialchars($guild->name) ?>”</h3>
<table class="vertical-table">
	<tr>
		<th>Guild ID</th>
		<td><?php echo htmlspecialchars($guild->guild_id) ?></td>
		<th>Guild Name</th>
		<td><?php echo htmlspecialchars($guild->name) ?></td>
		<th>Emblem ID</th>
		<td><?php echo number_format($guild->emblem_id) ?></td>
		<td><img src="<?php echo $this->emblem($guild->guild_id) ?>" /></td>
	</tr>
	<tr>
		<th>Leader ID</th>
		<td><?php echo htmlspecialchars($guild->char_id) ?></td>
		<th>Leader Name</th>
		<td>
			<?php if ($auth->allowedToViewCharacter): ?>
				<?php echo $this->linkToCharacter($guild->char_id, $guild->master) ?>
			<?php else: ?>
				<?php echo htmlspecialchars($guild->master) ?>
			<?php endif ?>
		</td>
		<th>Guild Level</th>
		<td colspan="2"><?php echo number_format($guild->guild_lv) ?></td>
	</tr>
	<tr>
		<th>Online Members</th>
		<td><?php echo number_format($guild->connect_member) ?></td>
		<th>Capacity</th>
		<td><?php echo number_format($guild->max_member) ?></td>
		<th>Average Level</th>
		<td colspan="2"><?php echo number_format($guild->average_lv) ?></td>
	</tr>
	<tr>
		<th>Guild EXP</th>
		<td><?php echo number_format($guild->exp) ?></td>
		<th>EXP until Level Up</th>
		<td><?php echo number_format($guild->next_exp) ?></td>
		<th>Skill Point</th>
		<td colspan="2"><?php echo number_format($guild->skill_point) ?></td>
	</tr>
	<tr>
		<th>Guild Notice 1</th>
		<td colspan="6">
			<?php if (trim($guild->mes1)): ?>
				<?php echo htmlspecialchars($guild->mes1) ?>
			<?php else: ?>
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Guild Notice 2</th>
		<td colspan="6">
			<?php if (trim($guild->mes2)): ?>
				<?php echo htmlspecialchars($guild->mes2) ?></td>
			<?php else: ?>
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
	</tr>
</table>
<h3>Guild Members of “<?php echo htmlspecialchars($guild->name) ?>”</h3>
<?php if ($members): ?>
	<p><?php echo htmlspecialchars($guild->name) ?> has <?php echo count($members) ?> guild member(s).</p>
	<table class="vertical-table">
		<tr>
			<th>Name</th>
			<th>Job Class</th>
			<th>Base Level</th>
			<th>Job Level</th>
			<th>EXP Devotion</th>
			<th>Position ID</th>
			<th>Position Name</th>
			<th>Guild Rights</th>
			<th>Tax Level</th>
		</tr>
		<?php foreach ($members AS $member): ?>
		<tr>
			<td align="right">
				<?php if ($auth->allowedToViewCharacter): ?>
					<?php echo $this->linkToCharacter($member->char_id, $member->name) ?>
				<?php else: ?>
					<?php echo htmlspecialchars($member->name) ?>
				<?php endif ?>
			</td>
			<td>
				<?php if ($job=$this->jobClassText($member->class)): ?>
					<?php echo htmlspecialchars($job) ?>
				<?php else: ?>
					<span class="not-applicable">Unknown</span>
				<?php endif ?>
			</td>
			<td><?php echo htmlspecialchars($member->base_level) ?></td>
			<td><?php echo htmlspecialchars($member->job_level) ?></td>
			<td><?php echo number_format($member->devotion) ?></td>
			<td><?php echo htmlspecialchars($member->position) ?></td>
			<td><?php echo htmlspecialchars($member->position_name) ?></td>
			<td>
				<?php if ($member->mode == 17): ?>
					<?php echo htmlspecialchars("Invite/Expel") ?>
				<?php elseif ($member->mode == 16): ?>
					<?php echo htmlspecialchars("Expel") ?>
				<?php elseif ($member->mode == 1): ?>
					<?php echo htmlspecialchars("Invite") ?>
				<?php elseif ($member->mode == 0): ?>
					<span class="not-applicable">None</span>
				<?php else: ?>
					<span class="not-applicable">Unknown</span>
				<?php endif ?>
			</td>
			<td><?php echo number_format($member->exp_mode) ?>%</td>
		</tr>
		<?php endforeach ?>
	</table>
<?php else: ?>
	<p>There are no members in this guild.</p>
<?php endif ?>
<h3>Alliances of “<?php echo htmlspecialchars($guild->name) ?>”</h3>
<?php if ($alliances): ?>
	<p><?php echo htmlspecialchars($guild->name) ?> has <?php echo count($alliances) ?> Alliance(s).</p>
	<table class="vertical-table">
		<tr>
			<td>Guild ID</td>
			<td>Guild Name</td>
		</tr>
		<?php foreach ($alliances AS $alliance): ?>
		<tr>
			<td align="right">
				<?php if ($auth->allowedToViewGuild): ?>
					<?php echo $this->linkToGuild($alliance->alliance_id, $alliance->alliance_id) ?>
				<?php else: ?>
					<?php echo htmlspecialchars($alliance->alliance_id) ?>
				<?php endif ?>
			</td>
			<td><?php echo htmlspecialchars($alliance->name) ?></td>
		</tr>
		<?php endforeach ?>
	</table>
<?php else: ?>
	<p>There are no alliances for this guild.</p>
<?php endif ?>
<h3>Oppositions of “<?php echo htmlspecialchars($guild->name) ?>”</h3>
<?php if ($oppositions): ?>
	<p><?php echo htmlspecialchars($guild->name) ?> has <?php echo count($oppositions) ?> Opposition(s).</p>
	<table class="vertical-table">
		<tr>
			<td>Guild ID</td>
			<td>Guild Name</td>
		</tr>
		<?php foreach ($oppositions AS $opposition): ?>
		<tr>
			<td align="right">
				<?php if ($auth->allowedToViewGuild): ?>
					<?php echo $this->linkToGuild($opposition->alliance_id, $opposition->alliance_id) ?>
				<?php else: ?>
					<?php echo htmlspecialchars($opposition->alliance_id) ?>
				<?php endif ?>
			</td>
			<td><?php echo htmlspecialchars($opposition->name) ?></td>
		</tr>
		<?php endforeach ?>
	</table>
<?php else: ?>
	<p>There are no oppositions for this guild.</p>
<?php endif ?>
<?php else: ?>
<p>No such guild was found. <a href="javascript:history.go(-1)">Go back</a>.</p>
<?php endif ?>