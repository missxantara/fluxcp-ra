<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Who's Online?</h2>
<?php if ($chars): ?>
<p>Showing players on-line <?php echo htmlspecialchars($server->serverName) ?>.</p>
<?php if ($auth->allowedToSearchWhosOnline): ?>
	<form action="<?php echo $this->url ?>" method="get" class="search-form">
		<?php echo $this->moduleActionFormInputs($params->get('module'), $params->get('action')) ?>
		<p>Search for on-line character(s):</p>
		<p>
			<label for="char_name">Character Name:</label>
			<input type="text" name="char_name" id="char_name" value="<?php echo htmlspecialchars($params->get('char_name')) ?>" />
			…
			<label for="char_class">Job Class:</label>
			<input type="text" name="char_class" id="char_class" value="<?php echo htmlspecialchars($params->get('char_class')) ?>" />
			…
			<label for="guild_name">Guild:</label>
			<input type="text" name="guild_name" id="guild_name" value="<?php echo htmlspecialchars($params->get('guild_name')) ?>" />

			<input type="submit" value="Search" />
			<input type="button" value="Reset" onclick="reload()" />
		</p>
	</form>
<?php endif ?>
<?php echo $paginator->infoText() ?>

<table class="vertical-table">
	<tr>
		<th><?php echo $paginator->sortableColumn('char_name', 'Character Name') ?></th>
		<th>Job Class</th>
		<th><?php echo $paginator->sortableColumn('base_level', 'Base Level') ?></th>
		<th><?php echo $paginator->sortableColumn('job_level', 'Job Level') ?></th>
		<th colspan="2"><?php echo $paginator->sortableColumn('guild_name', 'Guild') ?></th>
		<?php if ($auth->allowedToViewOnlinePosition): ?>
		<th><?php echo $paginator->sortableColumn('last_map', 'Map') ?></th>
		<?php endif ?>
	</tr>
	<?php foreach ($chars as $char): ?>
	<tr>
		<td align="right">
			<?php if ($auth->allowedToViewCharacter): ?>
				<?php echo $this->linkToCharacter($char->char_id, $char->char_name) ?>
			<?php else: ?>
				<?php echo htmlspecialchars($char->char_name) ?>
			<?php endif ?>
		</td>
		<td><?php echo $this->jobClassText($char->char_class) ?></td>
		<td><?php echo number_format($char->base_level) ?></td>
		<td><?php echo number_format($char->job_level) ?></td>
		<?php if ($char->guild_name): ?>
			<td><img src="<?php echo $this->emblem($char->guild_id) ?>" /></td>
			<td><?php echo htmlspecialchars($char->guild_name) ?></td>
		<?php else: ?>
			<td colspan="2"><span class="not-applicable">None</span></td>
		<?php endif ?>
		<?php if ($auth->allowedToViewOnlinePosition): ?>
		<td><?php echo htmlspecialchars($char->last_map) ?></td>
		<?php endif ?>
	</tr>
	<?php endforeach ?>
</table>
<?php echo $paginator->getHTML() ?>
<?php else: ?>
<p>No characters found on <?php echo htmlspecialchars($server->serverName) ?>. <a href="javascript:history.go(-1)">Go back</a>.</p>
<?php endif ?>