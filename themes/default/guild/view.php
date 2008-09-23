<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Viewing Guild</h2>
<?php if ($guild): ?>
<h3>Guild information for “<?php echo htmlspecialchars($guild->name) ?>”</h3>
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


<?php else: ?>
<p>No such guild was found. <a href="javascript:history.go(-1)">Go back</a>.</p>
<?php endif ?>