<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>IP Ban List</h2>
<?php if ($banlist): ?>
<?php echo $paginator->infoText() ?>
<form action="<?php echo $this->url('ipban', 'unban') ?>" method="post">
	<input type="hidden" name="unban" value="1" />
	<table class="horizontal-table">
		<tr>
			<th><input type="checkbox" onclick="$('.unban-cb').attr('checked', this.checked)" /></th>
			<th><?php echo $paginator->sortableColumn('list', 'Banned IP') ?></th>
			<th><?php echo $paginator->sortableColumn('btime', 'Ban Date') ?></th>
			<th><?php echo $paginator->sortableColumn('reason', 'Ban Reason') ?></th>
			<th><?php echo $paginator->sortableColumn('rtime', 'Ban Expiration Date') ?></th>
			<?php if ($auth->allowedToModifyIpBan && $auth->actionAllowed('ipban', 'edit')): ?>
			<th>Modify</th>
			<?php endif ?>
		</tr>
		<?php foreach ($banlist as $list): ?>
		<tr>
			<td align="center">
				<input type="checkbox" class="unban-cb" name="unban_list[]" value="<?php echo htmlspecialchars($list->list) ?>" />
			</td>
			<td><?php echo htmlspecialchars($list->list) ?></td>
			<td><?php echo $this->formatDateTime($list->btime) ?></td>
			<td>
				<?php if ($list->reason): ?>
					<?php echo htmlspecialchars($list->reason) ?>
				<?php else: ?>
					<span class="not-applicable">None</span>
				<?php endif ?>
			</td>
			<td>
				<?php if (!$list->rtime || $list->rtime == '0000-00-00 00:00:00'): ?>
					<span class="not-applicable">Never</span>
				<?php else: ?>
					<?php echo $this->formatDateTime($list->rtime) ?>
				<?php endif ?>
			</td>
			<?php if ($auth->allowedToModifyIpBan && $auth->actionAllowed('ipban', 'edit')): ?>
			<td class="td-action action"><a href="<?php echo $this->url('ipban', 'edit', array('list' => $list->list)) ?>">Modify</a></td>
			<?php endif ?>
		</tr>
		<?php endforeach ?>
	</table>
	<p class="button-action">
		<input type="submit" value="Unban Selected" />
	</p>
</form>
<?php echo $paginator->getHTML() ?>
<?php else: ?>
<p>There are currently no IP bans. <a href="javascript:history.go(-1)">Go back</a>.</p>
<?php endif ?>