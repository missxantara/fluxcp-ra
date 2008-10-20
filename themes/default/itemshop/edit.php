<?php
if (!defined('FLUX_ROOT')) exit;
$markdownURL = 'http://daringfireball.net/projects/markdown/syntax';
?>
<h2>Item Shop</h2>
<h3>Modify Item in the Shop</h3>
<?php if ($item): ?>
<?php if (!empty($errorMessage)): ?>
<p class="red"><?php echo htmlspecialchars($errorMessage) ?></p>
<?php endif ?>
<form action="<?php echo $this->urlWithQs ?>" method="post" enctype="multipart/form-data">
<?php if (!$stackable): ?>
<input type="hidden" name="qty" value="1" />
<?php endif ?>
<table class="vertical-table">
	<tr>
		<th>Shop ID</th>
		<td><?php echo htmlspecialchars($item->shop_item_id) ?></td>
	</tr>
	<tr>
		<th>Item ID</th>
		<td><?php echo $this->linkToItem($item->shop_item_nameid, $item->shop_item_nameid) ?></td>
	</tr>
	<tr>
		<th>Name</th>
		<td><?php echo htmlspecialchars($item->shop_item_name) ?></td>
	</tr>
	<tr>
		<th><label for="cost">Credits</label></th>
		<td><input type="text" class="short" name="cost" id="cost" value="<?php echo htmlspecialchars($cost) ?>" /></td>
	</tr>
	<?php if ($stackable): ?>
	<tr>
		<th><label for="qty">Quantity</label></th>
		<td><input type="text" class="short" name="qty" id="qty" value="<?php echo htmlspecialchars($quantity) ?>" /></td>
	</tr>
	<?php endif ?>
	<tr>
		<th><label for="info">Info</label></th>
		<td>
			<textarea name="info" id="info"><?php echo htmlspecialchars($info) ?></textarea>
			<p style="font-style: italic">Info is in Markdown syntax.</p>
			<p style="font-style: italic">See: <a href="<?php echo $markdownURL ?>"><?php echo $markdownURL ?></a></p>
		</td>
	</tr>
	<tr>
		<th><label for="image">Image</label></th>
		<td>
			<input type="file" name="image" id="image" />
			<?php if ($image=$this->shopItemImage($item->shop_item_id)): ?>
			<p>Current image:</p>
			<p><img src="<?php echo $image ?>" /></p>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right">
			<input type="submit" value="Modify" />
		</td>
	</tr>
</table>
</form>
<?php else: ?>
<p>Cannot modify an unknown item to the item shop. <a href="javascript:history.go(-1)">Go back</a>.</p>
<?php endif ?>