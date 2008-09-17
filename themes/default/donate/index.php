<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Donate</h2>
<?php if (Flux::config('AcceptDonations')): ?>
	<p>By donating, you're supporting the costs of <em>running</em> this server and <em>maintaining</em> it.  In return, you will be rewarded <span class="keyword">donation credits</span> that you may use to purchase items from our <a href="<?php echo $this->url('purchase') ?>">item shop</a>.</p>
<h3>Are you ready to donate?</h3>
	<p>All donations towards us are received by PayPal, but don't worry!  Even if you don't have an account with PayPal, you can still use your credit card to donate!</p>
	<p>When you're ready to donate, click the big <span class="keyword">Donate</span> button to proceed with your transaction.  You may enter your donation amount from there.
		(You can choose to donate from your existing PayPal balance or use your credit card if you don't have an account).</p>
	<p><?php include 'button.php' ?></p>
<?php else: ?>
	<p><?php echo Flux::message('NotAcceptingDonations') ?></p>
<?php endif ?>