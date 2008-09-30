<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Add IP Ban</h2>
<?php if (!empty($errorMessage)): ?>
	<p class="red"><?php echo htmlspecialchars($errorMessage) ?></p>
<?php endif ?>
<form action="<?php echo $this->urlWithQs ?>" method="post" class="generic-form">
	<input type="hidden" name="addipban" value="1" />
	<table class="generic-form-table">
		<tr>
			<th><label for="list">IP Address</label></th>
			<td><input type="text" name="list" id="list" value="<?php echo htmlspecialchars($params->get('list')) ?>" /></td>
			<td><p>You may specify a pattern such as 218.139.*.*</p></td>
		</tr>
		<tr>
			<th><label for="reason">Ban Reason</label></th>
			<td>
				<textarea name="reason" id="reason" class="reason"><?php echo htmlspecialchars($params->get('reason')) ?></textarea>
			</td>
			<td></td>
		</tr>
		<tr>
			<th><label>Unban Date</label></th>
			<td><?php echo $this->dateTimeField('rtime', ($rtime=$params->get('rtime')) ? $rtime : null) ?></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td colspan="2"><input type="submit" value="Add IP Ban" /></td>
		</tr>
	</table>
</form>