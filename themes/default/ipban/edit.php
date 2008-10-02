<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Modify IP Ban</h2>
<?php if ($ipban): ?>
	<?php if (!empty($errorMessage)): ?>
		<p class="red"><?php echo htmlspecialchars($errorMessage) ?></p>
	<?php endif ?>
	<form action="<?php echo $this->urlWithQs ?>" method="post" class="generic-form">
		<input type="hidden" name="modipban" value="1" />
		<table class="generic-form-table">
			<tr>
				<th><label for="list">IP Address</label></th>
				<td><input type="text" name="newlist" id="list"
						value="<?php echo htmlspecialchars(($list=$params->get('newlist')) ? $list : $ipban->list) ?>" /></td>
				<td><p>You may specify a pattern such as 218.139.*.*</p></td>
			</tr>
			<tr>
				<th><label for="reason">Ban Reason</label></th>
				<td>
					<textarea name="reason" id="reason" class="reason">
						<?php echo htmlspecialchars(($reason=$params->get('reason')) ? $reason : $ipban->reason) ?>
					</textarea>
				</td>
				<td></td>
			</tr>
			<tr>
				<th><label>Unban Date</label></th>
				<td><?php echo $this->dateTimeField('rtime', ($rtime=$params->get('rtime')) ? $rtime : $ipban->rtime) ?></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td colspan="2"><input type="submit" value="Modify IP Ban" /></td>
			</tr>
		</table>
	</form>
<?php else: ?>
<p>No such IP ban. <a href="javascript:history.go(-1)">Go back</a>.</p>
<?php endif ?>