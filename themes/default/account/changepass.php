<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Change Your Password</h2>
<?php if (!empty($errorMessage)): ?>
	<p class="red"><?php echo htmlspecialchars($errorMessage) ?></p>
<?php else: ?>
	<p>Please enter your current password, then enter the new password you would like to use and re-enter it to confirm.</p>
<?php endif ?>
<br />
<form action="<?php echo $this->urlWithQs ?>" method="post" class="generic-form">
	<table class="generic-form-table">
		<tr>
			<th><label for="currentpass">Current Password</label></th>
			<td><input type="password" name="currentpass" id="currentpass" value="" /></td>
			<td rowspan="3">
				<p>Please be sure to enter the correct information.</p>
				<p class="important">After changing your password, you will be logged out.</p>
			</td>
		</tr>
		<tr>
			<th><label for="newpass">New Password</label></th>
			<td><input type="password" name="newpass" id="newpass" value="" /></td>
		</tr>
		<tr>
			<th><label for="confirmnewpass">Re-enter New Password</label></th>
			<td><input type="password" name="confirmnewpass" id="confirmnewpass" value="" /></td>
		</tr>
		<tr>
			<td colspan="2" align="right">
				<input type="submit" value="Change Password" />
			</td>
		</tr>
	</table>
</form>