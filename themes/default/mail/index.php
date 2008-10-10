<?php
if (!defined('FLUX_ROOT')) exit;
$markdownURL = 'http://daringfireball.net/projects/markdown/syntax';
?>
<h2>Form Mailer</h2>
<?php if (!empty($errorMessage)): ?>
<p class="red"><?php echo htmlspecialchars($errorMessage) ?></p>
<?php else: ?>
<p>You may use the below mail form to send an e-mail using the control panel.</p>
<?php endif ?>
<form action="<?php echo $this->urlWithQs ?>" method="post" name="mailerform" class="generic-form">
	<input type="hidden" name="_preview" value="0" />
	<table class="generic-form-table">
		<tr>
			<th><label>From</label></th>
			<td><p>
				<strong><?php echo htmlspecialchars(Flux::config('MailerFromName')) ?></strong>
				(<?php echo htmlspecialchars(Flux::config('MailerFromAddress')) ?>)
			</p></td>
		</tr>
		<tr>
			<th><label for="to">To</label></th>
			<td><input type="text" name="to" id="to" value="<?php echo htmlspecialchars($params->get('to')) ?>" /></td>
		</tr>
		<tr>
			<th><label for="subject">Subject</label></th>
			<td><input type="text" name="subject" id="subject" value="<?php echo htmlspecialchars($params->get('subject')) ?>" /></td>
		</tr>
		<tr>
			<th><label for="body">Body</label></th>
			<td>
				<textarea name="body" id="body"><?php echo htmlspecialchars($params->get('body')) ?></textarea>
				<p style="font-style: italic">Body is in Markdown syntax.</p>
				<p style="font-style: italic">See: <a href="<?php echo $markdownURL ?>"><?php echo $markdownURL ?></a></p>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right">
				<input type="submit" value="Send E-mail" />
				<input type="button" value="Preview" onclick="document.mailerform._preview.value = 1; document.mailerform.submit()" />
			</td>
		</tr>
	</table>
</form>
<?php if ($preview): ?>
<h3>Preview</h3>
<div class="generic-form-div">
<?php echo $preview ?>
</div>
<?php endif ?>