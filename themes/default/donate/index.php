<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Donate</h2>
<?php if (Flux::config('AcceptDonations')): ?>
	<?php if ($auth->allowedToDonate): ?>
	<p>So, you've chosen to donate to our server?  That's great!</p>
	<p>By donating, you're supporting the costs of <em>running</em> this server and <em>maintaining</em> it.  In return, if you are logged in you will be rewarded <span class="keyword">donation credits</span> that you may use to purchase items from our <a href="<?php echo $this->url('purchase') ?>">item shop</a>.</p>
<h3>Are you ready to donate?</h3>
	<p>All donations towards us are received by PayPal, but don't worry!  Even if you don't have an account with PayPal, you can still use your credit card to donate!</p>
	<p>When you're ready to donate, click the big <span class="keyword">Donate</span> button to proceed with your transaction.  You may enter your donation amount from there.
		(You can choose to donate from your existing PayPal balance or use your credit card if you don't have an account).</p>
	<p><?php include 'button.php' ?></p>
	<?php elseif ($session->isLoggedIn()): ?>
	<p class="red">You do not meet the account level requirements to make a donation.</p>
	<?php else: ?>
	<p>We're sorry, we do not accept donations from unregistered users.  Try <a href="<?php echo $this->url('account', 'login') ?>">logging in</a>.</p>
	<?php endif ?>
<?php else: ?>
	<p><?php echo Flux::message('NotAcceptingDonations') ?></p>
<?php endif ?>