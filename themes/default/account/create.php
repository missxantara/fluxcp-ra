<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Register</h2>
<p>Please read our <a href="<?php echo $this->url('service', 'tos') ?>">Terms of Service</a> (ToS) before registering for an account, to ensure that you understand the rules of holding an account with our private Ragnarok Online game server.</p>
<?php if (isset($errorMessage)): ?>
<p class="red" style="font-weight: bold"><?php echo htmlspecialchars($errorMessage) ?></p>
<?php endif ?>
<form action="<?php echo $this->url ?>" method="post" class="generic-form">
	<?php if (count($serverNames) === 1): ?>
	<input type="hidden" name="server" value="<?php echo htmlspecialchars($session->loginAthenaGroup->serverName) ?>">
	<?php endif ?>
	<table class="generic-form-table">
		<?php if (count($serverNames) > 1): ?>
		<tr>
			<th><label for="register_server">Choose a Server</label></th>
			<td>
				<select name="server" id="register_server"<?php if (count($serverNames) === 1) echo ' disabled="disabled"' ?>>
				<?php foreach ($serverNames as $serverName): ?>
					<option value="<?php echo htmlspecialchars($serverName) ?>"<?php if ($params->get('server') == $serverName) echo ' selected="selected"' ?>><?php echo htmlspecialchars($serverName) ?></option>
				<?php endforeach ?>
				</select>
			</td>
		</tr>
		<?php endif ?>
		
		<tr>
			<th><label for="register_username">Your Username</label></th>
			<td><input type="text" name="username" id="register_username" value="<?php echo htmlspecialchars($params->get('username')) ?>" /></td>
		</tr>
		
		<tr>
			<th><label for="register_password">Your Password</label></th>
			<td><input type="password" name="password" id="register_password" /></td>
		</tr>
		
		<tr>
			<th><label for="register_confirm_password">Confirm Password</label></th>
			<td><input type="password" name="confirm_password" id="register_confirm_password" /></td>
		</tr>
		
		<tr>
			<th><label for="register_email_address">Email Address</label></th>
			<td><input type="text" name="email_address" id="register_email_address" value="<?php echo htmlspecialchars($params->get('email_address')) ?>" /></td>
		</tr>
		
		<tr>
			<th><label>Gender</label></th>
			<td>
				<p>
					<label><input type="radio" name="gender" id="register_gender_m" value="M"<?php if ($params->get('gender') === 'M') echo ' checked="checked"' ?> /> Male</label>
					<label><input type="radio" name="gender" id="register_gender_f" value="F"<?php if ($params->get('gender') === 'F') echo ' checked="checked"' ?> /> Female</label>
					<strong title="The gender you choose here will affect your in-game character's gender!">?</strong>
				</p>
			</td>
		</tr>
		
		<?php if (Flux::config('UseCaptcha')): ?>
		<tr>
			<th><label for="register_security_code">Security Code</label></th>
			<td>
				<div class="security-code">
					<img src="<?php echo $this->url('captcha') ?>" />
				</div>
				<input type="text" name="security_code" id="register_security_code" />
			</td>
		</tr>
		<?php endif ?>
		
		<tr>
			<td></td>
			<td>
				<div style="margin-bottom: 5px">
					By clicking "Create My Account",<br />
					<strong>you agree to be bound</strong> by our <a href="<?php echo $this->url('service', 'tos') ?>">Terms of Service</a>.
				</div>
				<div>
					<button type="submit"><strong>Create My Account</strong></button>
				</div>
			</td>
		</tr>
	</table>
</form>