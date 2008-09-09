<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Server Status</h2>
<table id="server_status">
<?php foreach ($serverStatus as $privServerName => $gameServers): ?>
	<tr>
		<td colspan="5"><h3><?php echo htmlspecialchars($privServerName) ?></h3></td>
	</tr>
	<tr>
		<th>Servers</th>
		<td class="status">Login</td>
		<td class="status">Char</td>
		<td class="status">Map</td>
		<td class="status">Online</td>
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