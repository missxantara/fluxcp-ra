<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Export Guild Emblems</h2>
<p>Please select the servers for which you would like to have the guild emblems exported as an archived ZIP file.</p>
<form action="<?php echo $this->url ?>" method="post">
	<?php foreach ($serverNames as $serverName): ?>
	<p style="margin: 0; padding: 0"><label>
		&raquo;
		<input type="checkbox" name="server[]" checked="checked" value="<?php echo htmlspecialchars($serverName) ?>" />
		<?php echo htmlspecialchars($serverName) ?>
	</label></p>
	<?php endforeach ?>
	<button type="submit" class="submit_button">Export</button>
</form>