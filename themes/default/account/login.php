<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Log In</h2>
<?php if (isset($errorMessage)): ?>
<p class="red"><?php echo htmlspecialchars($errorMessage) ?></p>
<?php else: ?>

<?php if ($auth->actionAllowed('account', 'create')): ?>
<p><?php printf(Flux::message('LoginPageMakeAccount'), $this->url('account', 'create')); ?></p>
<?php endif ?>

<?php endif ?>
<form action="<?php echo $this->url('account', 'login', array('return_url' => $params->get('return_url'))) ?>" method="post" class="generic-form">
	<?php if (count($serverNames) === 1): ?>
	<input type="hidden" name="server" value="<?php echo htmlspecialchars($session->loginAthenaGroup->serverName) ?>">
	<?php endif ?>
	<table class="generic-form-table">
		<tr>
			<th><label for="login_username">Your Username</label></th>
			<td><input type="text" name="username" id="login_username" value="<?php echo htmlspecialchars($params->get('username')) ?>" /></td>
		</tr>
		<tr>
			<th><label for="login_password">Your Password</label></th>
			<td><input type="password" name="password" id="login_password" /></td>
		</tr>
		<?php if (count($serverNames) > 1): ?>
		<tr>
			<th><label for="login_server">Log into</label></th>
			<td>
				<select name="server" id="login_server"<?php if (count($serverNames) === 1) echo ' disabled="disabled"' ?>>
					<?php foreach ($serverNames as $serverName): ?>
					<option value="<?php echo htmlspecialchars($serverName) ?>"><?php echo htmlspecialchars($serverName) ?></option>
					<?php endforeach ?>
				</select>
			</td>
		</tr>
		<?php endif ?>
		<?php if (Flux::config('UseLoginCaptcha')): ?>
		<tr>
			<th><label for="register_security_code">Security Code</label></th>
			<td>
				<div class="security-code">
					<img src="<?php echo $this->url('captcha') ?>" />
				</div>
				
				<input type="text" name="security_code" id="register_security_code" />
				<div style="font-size: smaller;" class="action">
					<strong><a href="javascript:refreshSecurityCode('.security-code img')">Refresh Security Code</a></strong>
				</div>
			</td>
		</tr>
		<?php endif ?>
		<tr>
			<td align="right" colspan="2">
				<input type="submit" value="Log In" />
			</td>
		</tr>
	</table>
</form>