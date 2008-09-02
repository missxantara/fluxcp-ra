<h2>Server Status</h2>
<table id="server_status" style="display: none">
<?php foreach ($serverStatus as $privServerName => $gameServers): ?>
	<tr>
		<td colspan="3" style="font-size: 18pt"><?php echo htmlspecialchars($privServerName) ?></td>
	</tr>
	<?php foreach ($gameServers as $serverName => $gameServer): ?>
	<tr>
		<th rowspan="3" style="padding-left: 10px"><?php echo htmlspecialchars($serverName) ?></th>
		<td>Login</td>
		<td><?php echo $this->serverUpDown($gameServer['loginServerUp']) ?></td>
	</tr>
	<tr>
		<td>Char</td>
		<td><?php echo $this->serverUpDown($gameServer['charServerUp']) ?></td>
	</tr>
	<tr>
		<td>Map</td>
		<td><?php echo $this->serverUpDown($gameServer['mapServerUp']) ?></td>
	</tr>
	<?php endforeach ?>
<?php endforeach ?>
</table>

<table id="server_status">
<?php foreach ($serverStatus as $privServerName => $gameServers): ?>
	<tr>
		<td colspan="4"><h3><?php echo htmlspecialchars($privServerName) ?></h3></td>
	</tr>
	<tr>
		<th>Servers</th>
		<td class="status">Login</td>
		<td class="status">Char</td>
		<td class="status">Map</td>
	</tr>
	<?php foreach ($gameServers as $serverName => $gameServer): ?>
	<tr>
		<th class="server"><?php echo htmlspecialchars($serverName) ?></th>
		<td class="status"><?php echo $this->serverUpDown($gameServer['loginServerUp']) ?></td>
		<td class="status"><?php echo $this->serverUpDown($gameServer['charServerUp']) ?></td>
		<td class="status"><?php echo $this->serverUpDown($gameServer['mapServerUp']) ?></td>
	</tr>
	<?php endforeach ?>
<?php endforeach ?>
</table>