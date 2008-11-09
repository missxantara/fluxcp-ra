<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Change E-mail</h2>

<?php if (!empty($errorMessage)): ?>
<p class="red"><?php echo htmlspecialchars($errorMessage) ?></p>
<?php endif ?>

<p>If you would like to change the e-mail address registered under your account, you can fill out the below form.</p>

<?php if (Flux::config('RequireChangeConfirm')): ?>
<p>After submitting the form, you will be required to confirm your new e-mail address (an e-mail will be sent to the new address with a link).</p>
<?php endif ?>

<form action="<?php echo $this->urlWithQs ?>" method="post" class="generic-form">
	<table class="generic-form-table">
		<tr>
			<th><label for="email">New E-mail Address</label></th>
			<td><input type="text" name="email" id="email" /></td>
			<td><p>Must be a valid e-mail address!</p></td>
		</tr>
		<tr>
			<td colspan="2" align="right">
				<input type="submit" value="Change E-mail Address" />
			</td>
			<td></td>
		</tr>
	</table>
</form>