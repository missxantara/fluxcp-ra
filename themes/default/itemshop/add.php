<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Item Shop</h2>
<h3>Add Item to the Shop</h3>
<?php if ($item): ?>
<?php if (!empty($errorMessage)): ?>
<p class="red"><?php echo htmlspecialchars($errorMessage) ?></p>
<?php endif ?>
<form action="<?php echo $this->urlWithQs ?>" method="post" enctype="multipart/form-data">
<table class="vertical-table">
	<tr>
		<th>Item ID</th>
		<td><?php echo $this->linkToItem($item->item_id, $item->item_id) ?></td>
	</tr>
	<tr>
		<th>Name</th>
		<td><?php echo htmlspecialchars($item->item_name) ?></td>
	</tr>
	<tr>
		<th><label for="cost">Credits</label></th>
		<td><input type="text" class="short" name="cost" id="cost" value="<?php echo htmlspecialchars($params->get('cost')) ?>" /></td>
	</tr>
	<tr>
		<th><label for="qty">Quantity</label></th>
		<td><input type="text" class="short" name="qty" id="qty" value="<?php echo htmlspecialchars($params->get('qty')) ?>" /></td>
	</tr>
	<tr>
		<th><label for="info">Info</label></th>
		<td>
			<textarea name="info" id="info">
				<?php echo htmlspecialchars($params->get('info')) ?>
			</textarea>
		</td>
	</tr>
	<tr>
		<th><label for="image">Image</label></th>
		<td>
			<input type="file" name="image" id="image" />
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right">
			<input type="submit" value="Add" />
		</td>
	</tr>
</table>
</form>
<?php else: ?>
<p>Cannot add an unknown item to the item shop. <a href="javascript:history.go(-1)">Go back</a>.</p>
<?php endif ?>