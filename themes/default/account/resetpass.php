<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Reset Password</h2>
<p>If you lost your password, you can re-set it by entering the e-mail address you used to register your account.</p>
<p>An e-mail will then be sent to the specified address with a link allowing you to reset your password, therefore a valid e-mail address is required.</p>
<form action="<?php echo $this->urlWithQs ?>" method="post" class="generic-form">
	<table class="generic-form-table">
		<?php if (count($serverNames) > 1): ?>
		<tr>
			<th><label for="login">Registered Server</label></th>
			<td>
				<select name="login" id="login"<?php if (count($serverNames) === 1) echo ' disabled="disabled"' ?>>
				<?php foreach ($serverNames as $serverName): ?>
					<option value="<?php echo htmlspecialchars($serverName) ?>"<?php if ($params->get('server') == $serverName) echo ' selected="selected"' ?>><?php echo htmlspecialchars($serverName) ?></option>
				<?php endforeach ?>
				</select>
			</td>
			<td><p>This is the server the account was registered on.</p></td>
		</tr>
		<?php endif ?>
		<tr>
			<th><label for="userid">Account Username</label></th>
			<td><input type="text" name="userid" id="userid" /></td>
			<td><p>This is the account name you registered.</p></td>
		</tr>
		<tr>
			<th><label for="email">E-mail Address</label></th>
			<td><input type="text" name="email" id="email" /></td>
			<td><p>This is the e-mail address you used during the registration of the above account.</p></td>
		</tr>
		<tr>
			<td colspan="2" align="right"><input type="submit" value="Send Reset Password E-mail" /></td>
			<td></td>
		</tr>
	</table>
</form>