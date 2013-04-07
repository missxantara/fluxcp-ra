<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2><?php echo htmlspecialchars(Flux::message('cpaccountViewHeading')) ?></h2>
<?php if (!empty($errorMessage)): ?>
<p class="red"><?php echo htmlspecialchars($errorMessage) ?></p>
<?php endif ?>
<?php if ($cpaccount): ?>
<table class="vertical-table">
	<?php if ($auth->allowedToSeeCpAccountID): ?>
	<tr>
		<th><?php echo htmlspecialchars(Flux::message('AccountIdLabel')) ?></th>
		<td><?php echo $cpaccount->cp_aid ?></td>
	</tr>
	<?php endif ?>
	<tr>
		<th><?php echo htmlspecialchars(Flux::message('UsernameLabel')) ?></th>
		<td><?php echo $cpaccount->username ?></td>
	</tr>
	<tr>
		<th><?php echo htmlspecialchars(Flux::message('EmailAddressLabel')) ?></th>
		<td>
			<?php if ($cpaccount->email): ?>
				<?php if ($auth->actionAllowed('cpaccount', 'index')): ?>
					<?php echo $this->linkTocpaccountSearch(array('email' => $cpaccount->email), $cpaccount->email) ?>
				<?php else: ?>
					<?php echo htmlspecialchars($cpaccount->email) ?>
				<?php endif ?>
			<?php else: ?>
				<span class="not-applicable"><?php echo htmlspecialchars(Flux::message('NoneLabel')) ?></span>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th><?php echo htmlspecialchars(Flux::message('AccountStateLabel')) ?></th>
		<td>
			<?php if (!$cpaccount->confirmed && $cpaccount->confirm_code): ?>
				<span class="account-state state-pending">
					<?php echo htmlspecialchars(Flux::message('AccountStatePending')) ?>
				</span>
			<?php elseif ($cpaccount->state == 0): ?>
				<span class="account-state state-normal">
					<?php echo htmlspecialchars(Flux::message('AccountStateNormal')) ?>
				</span>
			<?php else: ?>
				<span class="account-state state-unknown"><?php echo htmlspecialchars(Flux::message('UnknownLabel')) ?></span>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th><?php echo htmlspecialchars(Flux::message('CreditBalanceLabel')) ?></th>
		<td colspan="3">
			<?php echo number_format((int)$cpaccount->balance) ?>
			<?php if ($auth->allowedToDonate && $isMine): ?>
				<a href="<?php echo $this->url('donate') ?>"><?php echo htmlspecialchars(Flux::message('cpaccountViewDonateLink')) ?></a>
			<?php endif ?>
		</td>
	</tr>
</table>

<?php else: ?>
<p>
	<?php echo htmlspecialchars(Flux::message('cpaccountViewNotFound')) ?>
	<a href="javascript:history.go(-1)"><?php echo htmlspecialchars(Flux::message('GoBackLabel')) ?></a>
</p>
<?php endif ?>