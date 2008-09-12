<?php if (!defined('FLUX_ROOT')) exit; ?>
<?php if ($session->isLoggedIn()): ?>
<table cellspacing="0" cellpadding="0" width="100%" id="loginbox">
	<tr>
		<td width="18"><img src="<?php echo $this->themePath('img/loginbox_tl.gif') ?>" /></td>
		<td bgcolor="#e1eaf3"></td>
		<td width="18"><img src="<?php echo $this->themePath('img/loginbox_tr.gif') ?>" /></td>
	</tr>
	<tr>
		<td bgcolor="#e1eaf3"></td>
		<td bgcolor="#e1eaf3" valign="middle">
			<span style="display: inline-block; margin: 2px 2px 2px 0">
				You are currently logged in as <strong><a href="<?php echo $this->url('account', 'view') ?>" title="View account"><?php echo htmlspecialchars($session->account->userid) ?></a></strong>
				on <?php echo htmlspecialchars($session->serverName) ?>.  Your preferred server is:
			</span>
			<select name="preferred_server" onchange="updatePreferredServer(this)">
				<?php foreach ($session->getAthenaServerNames() as $serverName): ?>
				<option value="<?php echo htmlspecialchars($serverName) ?>"<?php if ($server->serverName == $serverName) echo ' selected="selected"' ?>><?php echo htmlspecialchars($serverName) ?></option>
				<?php endforeach ?>
			</select>.
			<form action="<?php echo $this->url ?>" method="post" name="preferred_server_form" style="display: none">
				<input type="hidden" name="preferred_server" value="">
			</form>
		</td>
		<td bgcolor="#e1eaf3"></td>
	</tr>
	<tr>
		<td><img src="<?php echo $this->themePath('img/loginbox_bl.gif') ?>" /></td>
		<td bgcolor="#e1eaf3"></td>
		<td><img src="<?php echo $this->themePath('img/loginbox_br.gif') ?>" /></td>
	</tr>
</table>
<?php endif ?>