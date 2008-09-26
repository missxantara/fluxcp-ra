<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Credit Transfer History</h2>
<?php if ($transfers): ?>
<table class="vertical-table">
	<tr>
		<th>Transfer</th>
		<th>Credits</th>
		<?php if ($auth->allowedToViewAccount): ?><th>To/From</th><?php endif ?>
		<th>Transfer Date</th>
	</tr>
	<?php foreach ($transfers as $xfer): ?>
	<tr>
		<td align="right">
			<?php if ($xfer->from_account_id == $session->account->account_id): ?>
				Sent
			<?php else: ?>
				Received
			<?php endif ?>
		</td>
		<td><?php echo number_format($xfer->amount) ?></td>
		<?php if ($auth->allowedToViewAccount): ?>
		<td>
			<?php if ($xfer->from_account_id != $session->account->account_id): ?>
				<?php if ($xfer->from_userid): ?>
					<?php echo $this->linkToAccount($xfer->from_account_id, $xfer->from_userid) ?>
				<?php else: ?>
					<span class="not-applicable"><?php echo htmlspecialchars($xfer->from_account_id) ?></span>
				<?php endif ?>
			<?php else: ?>
				<?php if ($xfer->target_userid): ?>
					<?php echo $this->linkToAccount($xfer->target_account_id, $xfer->target_userid) ?>
				<?php else: ?>
					<span class="not-applicable"><?php echo htmlspecialchars($xfer->target_account_id) ?></span>
				<?php endif ?>
			<?php endif ?>
		</td>
		<?php endif ?>
		<td><?php echo $this->formatDateTime($xfer->transfer_date) ?></td>
	</tr>
	<?php endforeach ?>
</table>
<?php else: ?>
<p>You have not transferred nor have you received any credits.</p>
<?php endif ?>