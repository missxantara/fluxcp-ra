<h2>Purchase</h2>
<p>Items in this shop are purchased using <span class="keyword">donation credits</span> and not real money.  Donation Credits are rewarded to players who <a href="<?php echo $this->url('donate') ?>">make a donation to our server</a>, helping us cover the costs of maintaining and running the server.</p>
<?php if (!$session->isLoggedIn()): ?>
<p>If you would like to see items in the shop, please <a href="<?php echo $this->url('account', 'login') ?>">login</a> first.</p>
<?php else: ?>
<?php endif ?>