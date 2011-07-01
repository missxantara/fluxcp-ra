<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Monsters</h2>
<p class="toggler"><a href="javascript:toggleSearchForm()">Search...</a></p>
<form class="search-form" method="get">
	<?php echo $this->moduleActionFormInputs($params->get('module')) ?>
	<p>
		<label for="monster_id">Monster ID:</label>
		<input type="text" name="monster_id" id="monster_id" value="<?php echo htmlspecialchars($params->get('monster_id')) ?>" />
		...
		<label for="name">Name:</label>
		<input type="text" name="name" id="name" value="<?php echo htmlspecialchars($params->get('name')) ?>" />
		...
		<label for="card_id">Card ID:</label>
		<input type="text" name="card_id" id="card_id" value="<?php echo htmlspecialchars($params->get('card_id')) ?>" />
		...
		<label for="mvp">MVP:</label>
		<select name="mvp" id="mvp">
			<option value="all"<?php if (!($mvpParam=strtolower($params->get('mvp'))) || $mvpParam == 'all') echo ' selected="selected"' ?>>All</option>
			<option value="yes"<?php if ($mvpParam == 'yes') echo ' selected="selected"' ?>>Yes</option>
			<option value="no"<?php if ($mvpParam == 'no') echo ' selected="selected"' ?>>No</option>
		</select>
		...
		<label for="custom">Custom:</label>
		<select name="custom" id="custom">
			<option value=""<?php if (!($custom=$params->get('custom'))) echo ' selected="selected"' ?>>All</option>
			<option value="yes"<?php if ($custom == 'yes') echo ' selected="selected"' ?>>Yes</option>
			<option value="no"<?php if ($custom == 'no') echo ' selected="selected"' ?>>No</option>
		</select>
		
		<input type="submit" value="Search" />
		<input type="button" value="Reset" onclick="reload()" />
	</p>
</form>
<?php if ($monsters): ?>
<?php echo $paginator->infoText() ?>
<table class="horizontal-table">
	<tr>
		<th><?php echo $paginator->sortableColumn('monster_id', 'Monster ID') ?></th>
		<th><?php echo $paginator->sortableColumn('kro_name', 'kRO Name') ?></th>
		<th><?php echo $paginator->sortableColumn('iro_name', 'iRO Name') ?></th>
		<th><?php echo $paginator->sortableColumn('level', 'Level') ?></th>
		<th><?php echo $paginator->sortableColumn('hp', 'HP') ?></th>
		<th><?php echo $paginator->sortableColumn('exp', 'Base EXP') ?></th>
		<th><?php echo $paginator->sortableColumn('jexp', 'Job EXP') ?></th>
		<th><?php echo $paginator->sortableColumn('dropcard_id', 'Card ID') ?></th>
		<th><?php echo $paginator->sortableColumn('origin_table', 'Custom') ?></th>
	</tr>
	<?php foreach ($monsters as $monster): ?>
	<tr>
		<td align="right">
			<?php if ($auth->actionAllowed('monster', 'view')): ?>
				<?php echo $this->linkToMonster($monster->monster_id, $monster->monster_id) ?>
			<?php else: ?>
				<?php echo htmlspecialchars($monster->monster_id) ?>
			<?php endif ?>
		</td>
		<td>
			<?php if ($monster->mvp_exp): ?>
			<span class="mvp">MVP!</span>
			<?php endif ?>
			<?php echo htmlspecialchars($monster->kro_name) ?>
		</td>
		<td><?php echo htmlspecialchars($monster->iro_name) ?></td>
		<td><?php echo number_format($monster->level) ?></td>
		<td><?php echo number_format($monster->hp) ?></td>
		<td><?php echo number_format($monster->exp * $server->baseExpRates) ?></td>
		<td><?php echo number_format($monster->jexp * $server->jobExpRates) ?></td>
		<?php if ($monster->dropcard_id): ?>
			<td>
				<?php if ($auth->actionAllowed('item', 'view')): ?>
					<?php echo $this->linkToItem($monster->dropcard_id, $monster->dropcard_id) ?>
				<?php else: ?>
					<?php echo htmlspecialchars($monster->dropcard_id) ?>
				<?php endif ?>
			</td>
		<?php else: ?>
			<td><span class="not-applicable">None</span></td>
		<?php endif ?>
		<td>
			<?php if (preg_match('/mob_db2$/', $monster->origin_table)): ?>
				Yes
			<?php else: ?>
				No
			<?php endif ?>
		</td>
	</tr>
	<?php endforeach ?>
</table>
<?php echo $paginator->getHTML() ?>
<?php else: ?>
<p>No monsters found. <a href="javascript:history.go(-1)">Go back</a>.</p>
<?php endif ?>