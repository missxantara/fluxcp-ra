<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Characters</h2>
<?php if ($characters): ?>
<?php echo $paginator->infoText() ?>
<table class="vertical-table">
	<tr>
		<th><?php echo $paginator->sortableColumn('ch.char_id', 'Character ID') ?></th>
		<th><?php echo $paginator->sortableColumn('userid', 'Account') ?></th>
		<th><?php echo $paginator->sortableColumn('char_name', 'Character') ?></th>
		<th>Job Class</th>
		<th><?php echo $paginator->sortableColumn('base_level', 'Base Level') ?></th>
		<th><?php echo $paginator->sortableColumn('job_level', 'Job Level') ?></th>
		<th><?php echo $paginator->sortableColumn('zeny', 'Zeny') ?></th>
		<th><?php echo $paginator->sortableColumn('guild_name', 'Guild') ?></th>
		<th><?php echo $paginator->sortableColumn('partner_name', 'Partner') ?></th>
		<th><?php echo $paginator->sortableColumn('online', 'Online') ?></th>
		<th><?php echo $paginator->sortableColumn('char_num', 'Slot') ?></th>
	</tr>
	<?php foreach ($characters as $char): ?>
	<tr>
		<td align="right"><?php echo htmlspecialchars($char->char_id) ?></td>
		<td><?php echo $this->linkToAccount($char->account_id, $char->userid) ?></td>
		<td><?php echo htmlspecialchars($char->char_name) ?></td>
		<td>
			<?php if ($job=$this->jobClassText($char->class)): ?>
				<?php echo htmlspecialchars($job) ?>
			<?php else: ?>
				<span class="not-applicable">Unknown</span>
			<?php endif ?>
		</td>
		<td><?php echo number_format((int)$char->base_level) ?></td>
		<td><?php echo number_format((int)$char->job_level) ?></td>
		<td><?php echo number_format((int)$char->zeny) ?></td>
		<td>
			<?php if ($char->guild_name): ?>
				<?php echo htmlspecialchars($char->guild_name) ?>
			<?php else: ?>
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
		<td>
			<?php if ($char->partner_name): ?>
				<?php echo htmlspecialchars($char->partner_name) ?>
			<?php else: ?>
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
		<td>
			<?php if ($char->online): ?>
				<span class="online">Online</span>
			<?php else: ?>
				<span class="offline">Offline</span>
			<?php endif ?>
		</td>
		<td><?php echo $char->char_num + 1 ?></td>
	</tr>
	<?php endforeach ?>
</table>
<?php echo $paginator->getHTML() ?>
<?php else: ?>
<p>No characters found. <a href="javascript:history.go(-1)">Go back</a>.</p>
<?php endif ?>