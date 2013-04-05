<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2><?php echo htmlspecialchars(Flux::message('cpaccountViewHeading')) ?></h2>
<?php if (!empty($errorMessage)): ?>
<p class="red"><?php echo htmlspecialchars($errorMessage) ?></p>
<?php endif ?>
<?php if ($cpaccount): ?>
<table class="vertical-table">
	<tr>
		<th><?php echo htmlspecialchars(Flux::message('UsernameLabel')) ?></th>
		<td><?php echo $cpaccount->username ?></td>
		<th><?php echo htmlspecialchars(Flux::message('AccountIdLabel')) ?></th>
		<td>
			<?php if ($auth->allowedToSeeCpAccountID): ?>
				<?php echo $cpaccount->cp_aid ?>
			<?php else: ?>
				<span class="not-applicable"><?php echo htmlspecialchars(Flux::message('NotApplicableLabel')) ?></span>
			<?php endif ?>
		</td>
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
		<th><?php echo htmlspecialchars(Flux::message('AccountStateLabel')) ?></th>
		<td>
			<?php echo $cpaccount->state ?>
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