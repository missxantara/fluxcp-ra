<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Server Status</h2>
<table id="server_status">
<?php foreach ($serverStatus as $privServerName => $gameServers): ?>
	<tr>
		<td class="status">Server</td>
		<td class="status">Login Server</td>
		<td class="status">Character Server</td>
		<td class="status">Map Server</td>
		<td class="status">Players Online</td>
	</tr>
	<?php foreach ($gameServers as $serverName => $gameServer): ?>
	<tr>
		<th class="server"><?php echo htmlspecialchars($serverName) ?></th>
		<td class="status"><?php echo $this->serverUpDown($gameServer['loginServerUp']) ?></td>
		<td class="status"><?php echo $this->serverUpDown($gameServer['charServerUp']) ?></td>
		<td class="status"><?php echo $this->serverUpDown($gameServer['mapServerUp']) ?></td>
		<td class="status"><?php echo $gameServer['playersOnline'] ?></td>
	</tr>
	<?php endforeach ?>
<?php endforeach ?>
</table>