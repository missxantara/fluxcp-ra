<?php if (!defined('FLUX_ROOT')) exit; ?>
<table cellspacing="0" cellpadding="0" width="198" id="sidebar">
	<tr>
		<td colspan="3"><img src="<?php echo $this->themePath('img/sidebar_complete_top.gif') ?>" alt="[SIDEBAR_TOP]" /></td>
	</tr>
	<?php foreach ($this->getMenuItems() as $menuItem): ?>
	<tr>
		<td bgcolor="#e6f0fa" width="13"></td>
		<td bgcolor="#d7e8f9" class="menuitem"><a href="<?php echo $this->url($menuItem['module'], $menuItem['action']) ?>"><?php echo htmlspecialchars($menuItem['name']) ?></a></td>
		<td bgcolor="#e6f0fa" width="14"></td>
	</tr>
	<?php endforeach ?>
	<tr>
		<td colspan="3"><img src="<?php echo $this->themePath('img/sidebar_complete_bottom.gif') ?>" alt="[SIDEBAR_BOTTOM]" /></td>
	</tr>
</table>