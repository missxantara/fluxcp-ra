<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Viewing Item</h2>
<?php if ($item): ?>
<?php
$actions = array();
if ($auth->actionAllowed('item', 'edit')) {
	$actions[] = sprintf('<a href="%s">Modify Item</a>', $this->url('item', 'edit', array('id' => $item->item_id)));
}
if ($auth->actionAllowed('item', 'copy')) {
	$actions[] = sprintf('<a href="%s">Duplicate Item</a>', $this->url('item', 'copy', array('id' => $item->item_id)));
}
?>
<h3>#<?php echo htmlspecialchars($item->item_id) ?>: <?php echo htmlspecialchars($item->name) ?></h3>
<p class="action"><?php echo implode(' • ', $actions) ?></p>
<table class="vertical-table">
	<tr>
		<th>Item ID</th>
		<td><?php echo htmlspecialchars($item->item_id) ?></td>
		<th>For Sale</th>
		<td>
			<?php if ($item->cost): ?>
				<span class="for-sale yes">
					Yes
					<a href="<?php echo $this->url('purchase') ?>">(Go to Item Shop)</a>
					<?php if ($auth->allowedToAddShopItem): ?>
					<a href="<?php echo $this->url('itemshop', 'add', array('id' => $item->item_id)) ?>">(Add Again)</a>
					<?php endif ?>
				</span>
			<?php else: ?>
				<span class="for-sale no">
					No
					<?php if ($auth->allowedToAddShopItem): ?>
					<a href="<?php echo $this->url('itemshop', 'add', array('id' => $item->item_id)) ?>">(Add to Item Shop)</a>
					<?php endif ?>
				</span>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Identifier</th>
		<td><?php echo htmlspecialchars($item->identifier) ?></td>
		<th>Credit Price</th>
		<td>
			<?php if ($item->cost): ?>
				<?php echo number_format((int)$item->cost) ?>
			<?php else: ?>
				<span class="not-applicable">Not For Sale</span>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Name</th>
		<td><?php echo htmlspecialchars($item->name) ?></td>
		<th>Type</th>
		<td><?php echo $this->itemTypeText($item->type) ?></td>
	</tr>
	<tr>
		<th>NPC Buy</th>
		<td><?php echo number_format((int)$item->price_buy) ?></td>
		<th>Weight</th>
		<td><?php echo number_format((int)$item->weight) ?></td>
	</tr>
	<tr>
		<th>NPC Sell</th>
		<td><?php echo number_format((int)$item->price_sell) ?></td>
		<th>Attack</th>
		<td><?php echo number_format((int)$item->attack) ?></td>
	</tr>
	<tr>
		<th>Range</th>
		<td><?php echo number_format((int)$item->range) ?></td>
		<th>Defense</th>
		<td><?php echo number_format((int)$item->defence) ?></td>
	</tr>
	<tr>
		<th>Slots</th>
		<td><?php echo number_format((int)$item->slots) ?></td>
		<th>Refineable</th>
		<td><?php echo number_format((int)$item->refineable) ?></td>
	</tr>
	<tr>
		<th>Equip Level</th>
		<td><?php echo number_format((int)$item->equip_level) ?></td>
		<th>Weapon Level</th>
		<td><?php echo number_format((int)$item->weapon_level) ?></td>
	</tr>
	<tr>
		<th>Equip Locations</th>
		<td colspan="3">
			<?php if ($locs=$this->equipLocations($item->equip_locations)): ?>
				<?php echo htmlspecialchars(implode(' + ', $locs)) ?>
			<?php else: ?>
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Equip Upper</th>
		<td colspan="3">
			<?php if ($upper=$this->equipUpper($item->equip_upper)): ?>
				<?php echo htmlspecialchars(implode(' / ', $upper)) ?>
			<?php else: ?>
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Equippable Jobs</th>
		<td colspan="3">
			<?php if ($jobs=$this->equippableJobs($item->equip_jobs)): ?>
				<?php echo htmlspecialchars(implode(' / ', $jobs)) ?>
			<?php else: ?>
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Equip Gender</th>
		<td colspan="3">
			<?php if ($item->equip_genders === '0'): ?>
				Female
			<?php elseif ($item->equip_genders === '1'): ?>
				Male
			<?php elseif ($item->equip_genders === '2'): ?>
				Both (Male and Female)
			<?php else: ?>
				<span class="not-applicable">Unknown</span>
			<?php endif ?>
		</td>
	</tr>
	<?php if (($isCustom && $auth->allowedToSeeItemDb2Scripts) || (!$isCustom && $auth->allowedToSeeItemDbScripts)): ?>
	<tr>
		<th>Item Use Script</th>
		<td colspan="3">
			<?php if ($script=$this->displayScript($item->script)): ?>
				<?php echo $script ?>
			<?php else: ?>
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Equip Script</th>
		<td colspan="3">
			<?php if ($script=$this->displayScript($item->equip_script)): ?>
				<?php echo $script ?>
			<?php else: ?>
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Unequip Script</th>
		<td colspan="3">
			<?php if ($script=$this->displayScript($item->unequip_script)): ?>
				<?php echo $script ?>
			<?php else: ?>
				<span class="not-applicable">None</span>
			<?php endif ?>
		</td>
	</tr>
	<?php endif ?>
</table>
<?php else: ?>
<p>No such item was found. <a href="javascript:history.go(-1)">Go back</a>.</p>
<?php endif ?>