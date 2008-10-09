<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Transactions</h2>
<?php if ($transactions): ?>
<?php echo $paginator->infoText() ?>
<table class="horizontal-table">
	<tr>
		<th><?php echo $paginator->sortableColumn('txn_id', 'Transaction') ?></th>
		<th><?php echo $paginator->sortableColumn('parent_txn_id', 'Parent') ?></th>
		<th><?php echo $paginator->sortableColumn('process_date', 'Processed') ?></th>
		<th><?php echo $paginator->sortableColumn('payment_date', 'Received') ?></th>
		<th><?php echo $paginator->sortableColumn('payment_status', 'Status') ?></th>
		<th><?php echo $paginator->sortableColumn('payer_email', 'E-mail') ?></th>
		<th><?php echo $paginator->sortableColumn('mc_gross', 'Amount') ?></th>
		<th><?php echo $paginator->sortableColumn('credits', 'Credits') ?></th>
		<!--<th><?php echo $paginator->sortableColumn('server_name', 'Server') ?></th>-->
		<th><?php echo $paginator->sortableColumn('userid', 'Account') ?></th>
	</tr>
	<?php foreach ($transactions as $txn): ?>
	<tr>
		<td align="right">
			<strong>
				<?php if ($auth->actionAllowed('logdata', 'txnview')): ?>
					<a href="<?php echo $this->url($params->get('module'), 'txnview', array('id' => $txn->id)) ?>">
						<?php echo $txn->txn_id ?>
					</a>
				<?php else: ?>
					<?php echo $txn->txn_id ?>
				<?php endif ?>
			</strong>
		</td>
		<td>
			<?php if ($txn->parent_id): ?>
				<?php if ($auth->actionAllowed('logdata', 'txnview')): ?>
					<a href="<?php echo $this->url($params->get('module'), 'txnview', array('id' => $txn->parent_id)) ?>"><?php echo $txn->parent_txn_id ?></a>
				<?php else: ?>
					<?php echo $txn->parent_txn_id ?>
				<?php endif ?>
			<?php else: ?>
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
		<td><?php echo $this->formatDateTime($txn->process_date) ?></td>
		<td><?php echo $this->formatDateTime($txn->payment_date) ?></td>
		<td><?php echo $txn->payment_status ?></td>
		<td><?php echo htmlspecialchars($txn->payer_email) ?></td>
		<td><?php echo $txn->mc_gross ?> <?php echo $txn->mc_currency ?></td>
		<td><?php echo number_format((int)$txn->credits) ?></td>
		<!--<td><?php echo htmlspecialchars($txn->server_name) ?></td>-->
		<td>
			<?php if ($txn->account_id): ?>
				<?php if ($auth->actionAllowed('account', 'view') && $auth->allowedToViewAccount): ?>
					<?php echo $this->linkToAccount($txn->account_id, $txn->userid) ?>
				<?php else: ?>
					<?php echo htmlspecialchars($txn->userid) ?>
				<?php endif ?>
			<?php else: ?>
				<span class="not-applicable">Unknown</span>
			<?php endif ?>
		</td>
	</tr>
	<?php endforeach ?>
</table>
<?php echo $paginator->getHTML() ?>
<?php else: ?>
<p>There are currently no logged transactions.</p>
<?php endif ?>