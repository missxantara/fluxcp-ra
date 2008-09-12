<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Login</h2>
<?php if (isset($errorMessage)): ?>
<p class="red"><?php echo htmlspecialchars($errorMessage) ?></p>
<?php else: ?>
<p>If you don't have an account, you may go to the <a href="<?php echo $this->url('account', 'create') ?>">registration page</a> to create one.</p>
<?php endif ?>
<form action="<?php echo $this->url('account', 'login', array('return_url' => $params->get('return_url'))) ?>" method="post" id="login_form">
	<?php if (count($serverNames) === 1): ?>
	<input type="hidden" name="server" value="<?php echo current($serverNames) ?>">
	<?php endif ?>
	<table cellspacing="0" cellpadding="0">
		<tr>
			<th valign="middle"><label for="login_username">Username</label></th>
			<td><input type="text" name="username" id="login_username" value="<?php echo htmlspecialchars($params->get('username')) ?>" /></td>
		</tr>
		<tr>
			<th valign="middle"><label for="login_password">Password</label></th>
			<td><input type="password" name="password" id="login_password" /></td>
		</tr>
		<tr>
			<th valign="middle"><label for="login_server">Log into</label></th>
			<td>
				<select name="server" id="login_server"<?php if (count($serverNames) === 1) echo ' disabled="disabled"' ?>>
					<?php foreach ($serverNames as $serverName): ?>
					<option value="<?php echo htmlspecialchars($serverName) ?>"><?php echo htmlspecialchars($serverName) ?></option>
					<?php endforeach ?>
				</select>
			</td>
		</tr>
		<tr style="display: none">
			<!--<td></td>-->
			<td align="right" colspan="2"><label><input type="checkbox"> Remember who I am</label></td>
		</tr>
		<tr>
			<!--<td></td>-->
			<td align="center" colspan="2"><button type="submit" class="submit_button">Login</button></td>
		</tr>
	</table>
</form>