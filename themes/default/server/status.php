<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Server Status</h2>
<p>
	Understanding the online and offline status of each server can help you understand how an issue can relate to your problem.
	For example, if the login server is offline it means that you won't be able to log into the game.
	The character server and map servers are necessary for the actual gameplay past the point of logging in.
</p>
<?php foreach ($serverStatus as $privServerName => $gameServers): ?>
<h3>Server Status for <?php echo htmlspecialchars($privServerName) ?></h3>
<table id="server_status">
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
</table>
<?php endforeach ?>