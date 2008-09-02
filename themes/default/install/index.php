<h2>Installation</h2>
<p>
	You may proceed to install the basic control panel tables through this interface.
	Only the tables marked as "<span class="keyword">Missing</span>" will be installed,
	while tables marked as "<span class="keyword">Created</span>" will be left as is.
</p>
<p>
	Simply click the "<strong>Install Missing Tables</strong>" button to proceed with the installation process.
</p>

<div id="table_status" name="table_status"></div>
<h3>Flux-related Login Database Tables</h3>
<?php if (empty($schemas['loginDb'])): ?>
<p>There are no schemas to install.</p>
<?php else: ?>
<table cellspacing="0" cellpadding="0" class="install_table">
	<tr>
		<th>Table</th>
		<th>Status</th>
	</tr>
	<?php foreach ($schemas['loginDb'] as $schema): ?>
	<tr>
		<td><?php echo $schema['name'] ?></td>
		<td class="<?php echo $schema['exists'] ? 'created' : 'missing' ?>"><?php echo $schema['exists'] ? 'Created' : 'Missing' ?></td>
	</tr>
	<?php endforeach ?>
</table>
<?php endif ?>

<h3>Flux-related Char/Map Database Tables (<?php echo htmlspecialchars($server->serverName) ?>)</h3>
<?php if (empty($schemas['charMapDb'])): ?>
<p>There are no schemas to install.</p>
<?php else: ?>
<table cellspacing="0" cellpadding="0" class="install_table">
	<tr>
		<th>Table</th>
		<th>Status</th>
	</tr>
	<?php foreach ($schemas['charMapDb'] as $schema): ?>
	<tr>
		<td><?php echo $schema['name'] ?></td>
		<td class="<?php echo $schema['exists'] ? 'created' : 'missing' ?>"><?php echo $schema['exists'] ? 'Created' : 'Missing' ?></td>
	</tr>
	<?php endforeach ?>
</table>
<?php endif ?>
<form action="<?php echo $this->url ?>" method="post">
	<input type="hidden" name="install_missing_tables" value="1" />
	<button type="submit" class="submit_button" style="font-weight: bold">Install Missing Tables</button>
</form>