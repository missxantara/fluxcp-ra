<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Transactions</h2>
<?php if ($transactions): ?>
<?php echo $paginator->infoText() ?>
<table class="horizontal-table">
	<tr>
		<th><?php echo $paginator->sortableColumn('txn_id', 'Transaction ID') ?></th>
		<th><?php echo $paginator->sortableColumn('process_date', 'Date Processed') ?></th>
		<th><?php echo $paginator->sortableColumn('payment_date', 'Payment Date') ?></th>
		<th><?php echo $paginator->sortableColumn('payment_status', 'Status') ?></th>
		<th><?php echo $paginator->sortableColumn('payer_email', 'Payer E-mail') ?></th>
		<th><?php echo $paginator->sortableColumn('mc_gross', 'Donation Amount') ?></th>
		<th><?php echo $paginator->sortableColumn('credits', 'Credits Earned') ?></th>
		<th><?php echo $paginator->sortableColumn('server_name', 'Server') ?></th>
		<th><?php echo $paginator->sortableColumn('userid', 'Account') ?></th>
	</tr>
	<?php foreach ($transactions as $txn): ?>
	<tr>
		<td align="right">
			<strong>
				<a href="<?php echo $this->url($params->get('module'), 'txnview', array('id' => $txn->txn_id)) ?>">
					<?php echo $txn->txn_id ?>
				</a>
			</strong>
		</td>
		<td><?php echo $this->formatDateTime($txn->process_date) ?></td>
		<td><?php echo $this->formatDateTime($txn->payment_date) ?></td>
		<td><?php echo $txn->payment_status ?></td>
		<td><?php echo htmlspecialchars($txn->payer_email) ?></td>
		<td><?php echo $txn->mc_gross ?> <?php echo $txn->mc_currency ?></td>
		<td><?php echo $txn->credits ?></td>
		<td><?php echo $txn->server_name ?></td>
		<td><?php echo $this->linkToAccount($txn->account_id, $txn->userid) ?></td>
	</tr>
	<?php endforeach ?>
</table>
<?php echo $paginator->getHTML() ?>
<?php else: ?>
<p>There are currently no logged transactions.</p>
<?php endif ?>