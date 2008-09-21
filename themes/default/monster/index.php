<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Monsters</h2>
<form class="search-form" method="get">
	<?php echo $this->moduleActionFormInputs($params->get('module')) ?>
	<p>Search for monster(s):</p>
	<p>
		<label for="monster_id">Monster ID:</label>
		<input type="text" name="monster_id" id="monster_id" value="<?php echo htmlspecialchars($params->get('monster_id')) ?>" />
		…
		<label for="name">Name:</label>
		<input type="text" name="name" id="name" value="<?php echo htmlspecialchars($params->get('name')) ?>" />		
		<input type="submit" value="Search" />
		<input type="button" value="Reset" onclick="reload()" />
	</p>
</form>
<?php if ($monsters): ?>
<?php echo $paginator->infoText() ?>
<table class="horizontal-table">
	<tr>
		<th><?php echo $paginator->sortableColumn('id', 'Monster ID') ?></th>
		<th><?php echo $paginator->sortableColumn('kName', 'kName') ?></th>
		<th><?php echo $paginator->sortableColumn('iName', 'iName') ?></th>
		<th><?php echo $paginator->sortableColumn('LV', 'Level') ?></th>
		<th><?php echo $paginator->sortableColumn('HP', 'HP') ?></th>
		<th><?php echo $paginator->sortableColumn('SP', 'SP') ?></th>
		<th><?php echo $paginator->sortableColumn('EXP', 'Base EXP') ?></th>
		<th><?php echo $paginator->sortableColumn('JEXP', 'Job EXP') ?></th>
	</tr>
	<?php foreach ($monsters as $monster): ?>
	<tr>
		<td align="right"><?php echo $this->linkToMonster($monster->monster_id, $monster->monster_id) ?></td>
		<td><?php echo htmlspecialchars($monster->kName) ?></td>
		<td><?php echo htmlspecialchars($monster->iName) ?></td>
		<td><?php echo htmlspecialchars($monster->LV) ?></td>
		<td><?php echo htmlspecialchars($monster->HP) ?></td>
		<td><?php echo htmlspecialchars($monster->SP) ?></td>
		<td><?php echo htmlspecialchars($monster->EXP) ?></td>
		<td><?php echo htmlspecialchars($monster->JEXP) ?></td>
	</tr>
	<?php endforeach ?>
</table>
<?php echo $paginator->getHTML() ?>
<?php else: ?>
<p>No monsters found. <a href="javascript:history.go(-1)">Go back</a>.</p>
<?php endif ?>