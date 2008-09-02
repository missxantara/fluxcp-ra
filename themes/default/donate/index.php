<h2>Donate</h2>
<?php if (Flux::config('AcceptDonations')): ?>
	<?php if ($auth->allowedToDonate): ?>
	<p>So, you've chosen to donate to our server?  That's great!</p>
	<p>By donating, you're supporting the costs of <em>running</em> this server and <em>maintaining</em> it.  In return, if you are logged in you will be rewarded <span class="keyword">cash points</span> that you may use to purchase items from our <a href="<?php echo $this->url('purchase') ?>">item shop</a>.</p>
	<p>Current cash point exchange rate: <span style="font-size: 12pt"><strong>1</strong> CP (Cash Point) = <strong>$<?php echo $this->formatDollar(Flux::config('CashPointRate')) ?> USD (United States Dollar)</strong></span></p>
	<p>When the amount in dollars is not a whole number, the resulting cash points will be rounded down.  For example, if the exchange rate was that 1 CP equals $2.50, then donating $2.00 will give you nothing, and donating $3.00 will only get you 1 CP, so be sure to donate the exact amount you want cash points for.</p>
<h3>Cash Point to Dollars Converter:</h3>
	<p>
		<label style="display: block">Enter the number of cash points you would like to receive:
			<input type="text" size="4" style="text-align: center" onkeyup="convertCashPointsToDollars(this, document.getElementById('cp2usd_result'))" /><strong>CP</strong>
		</label>
		<span id="cp2usd_result" style="visibility: hidden"></span>
	</p>
	<p>After figuring out the total amount you will need, you may enter it below to proceed to the final step of the donation.</p>
	<p id="cashpoint_result"></p>
<h3>Are you ready to donate?</h3>
	<p>All donations towards us are received by PayPal, but don't worry!  Even if you don't have an account with PayPal, you can still use your credit card to donate!</p>
	<?php elseif ($session->isLoggedIn()): ?>
	<p class="red">You do not meet the account level requirements to make a donation.</p>
	<?php else: ?>
	<p>We're sorry.  However, we do not accept donations from unregistered users.  Try <a href="<?php echo $this->url('account', 'login') ?>">logging in</a>.</p>
	<?php endif ?>
<?php else: ?>
	<p><?php echo Flux::message('NotAcceptingDonations') ?></p>
<?php endif ?>