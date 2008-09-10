<?php if (!$session->installerAuth): ?>
	<form action="<?php echo $this->url ?>" method="post">
		<p>
			Please enter your <em>installer password</em> to continue with the update.
		</p>
		<p>
			<label for="installer_password">
				<strong>Password:</strong>
				<input type="password" id="installer_password" name="installer_password" />
				<button type="submit">Authenticate</button>
			</label>
		</p>
	</form>
<?php else: ?>
	<p class="menu">
		<a href="<?php echo $this->url($params->get('module'), null, array('logout' => 1)) ?>" onclick="return confirm('Are you sure you want to log out?')">Logout</a> |
		<a href="<?php echo $this->url($params->get('module'), null, array('update_all' => 1)) ?>" onclick="return confirm('By performing this action, changes to your database will be made.\n\nAre you sure you want to continue installing Flux and its associated updates?')"><strong>Install or Update Everything</strong></a>
	</p>
	<p>Shown below is a list of currently installed / need-to-be-installed schemas.</p>
	<table class="schema-info">
		<?php foreach ($installer->servers as $mainServerName => $mainServer): ?>
		<tr>
			<th colspan="3"><h3><?php echo htmlspecialchars($mainServerName) ?></h3></th>
		</tr>
		<tr>
			<th>Schema Name</th>
			<th>Latest Version</th>
			<th>Version Installed</th>
		</tr>
			<?php foreach ($mainServer->schemas as $schema): ?>
		<tr>
			<td><?php echo htmlspecialchars($schema->schemaInfo['name']) ?></td>
			<td><?php echo htmlspecialchars($schema->latestVersion) ?></td>
			<td><?php echo $schema->versionInstalled ? htmlspecialchars($schema->versionInstalled) : '<span class="none">None</span>' ?></td>
		</tr>
			<?php endforeach ?>
			
			<?php foreach ($mainServer->charMapServers as $charMapServerName => $charMapServer): ?>
		<tr>
			<th colspan="3"><h4><?php echo htmlspecialchars($charMapServerName) ?></h4></th>
		</tr>
		<tr>
			<th>Schema Name</th>
			<th>Latest Version</th>
			<th>Version Installed</th>
		</tr>
				<?php foreach ($charMapServer->schemas as $schema): ?>
		<tr>
			<td><?php echo htmlspecialchars($schema->schemaInfo['name']) ?></td>
			<td><?php echo htmlspecialchars($schema->latestVersion) ?></td>
			<td><?php echo $schema->versionInstalled ? htmlspecialchars($schema->versionInstalled) : '<span class="none">None</span>' ?></td>
		</tr>
				<?php endforeach ?>
		
			<?php endforeach ?>
		<?php endforeach ?>
	</table>
<?php endif ?>