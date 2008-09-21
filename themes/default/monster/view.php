<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Viewing Monster</h2>
<?php if ($monster): ?>
<h3>#<?php echo htmlspecialchars($monster->monster_id) ?>: “<?php echo htmlspecialchars($monster->kName) ?>” / “<?php echo htmlspecialchars($monster->iName) ?>”</h3>
<table class="vertical-table">
	<tr>
		<th>Monster ID</th>
		<td><?php echo htmlspecialchars($monster->monster_id) ?></td>
		<th>kRO Name</th>
		<td><?php echo htmlspecialchars($monster->kName) ?></td>
		<th>iRO Name</th>
		<td><?php echo htmlspecialchars($monster->iName) ?></td>
	</tr>
	<tr>
		<th>Sprite</th>
		<td><?php echo htmlspecialchars($monster->Sprite) ?></td>
		<th>HP</th>
		<td><?php echo number_format((int)$monster->HP) ?></td>
		<th>SP</th>
		<td><?php echo number_format((int)$monster->SP) ?></td>
	</tr>
	<tr>
		<th>Level</th>
		<td><?php echo number_format((int)$monster->LV) ?></td>
		<th>ATK1</th>
		<td><?php echo number_format((int)$monster->ATK1) ?></td>
		<th>ATK2</th>
		<td><?php echo number_format((int)$monster->ATK2) ?></td>
	</tr>
	<tr>
		<th>Range1</th>
		<td><?php echo number_format((int)$monster->Range1) ?></td>
		<th>Range2</th>
		<td><?php echo number_format((int)$monster->Range2) ?></td>
		<th>Range3</th>
		<td><?php echo number_format((int)$monster->Range3) ?></td>
	</tr>
	<tr>
		<th>STR</th>
		<td><?php echo number_format((int)$monster->STR) ?></td>
		<th>AGI</th>
		<td><?php echo number_format((int)$monster->AGI) ?></td>
		<th>VIT</th>
		<td><?php echo number_format((int)$monster->VIT) ?></td>
	</tr>
	<tr>
		<th>INT</th>
		<td><?php echo number_format((int)$monster->INT) ?></td>
		<th>DEX</th>
		<td><?php echo number_format((int)$monster->DEX) ?></td>
		<th>LUK</th>
		<td><?php echo number_format((int)$monster->LUK) ?></td>
	</tr>
	<tr>
		<th>DEF</th>
		<td><?php echo number_format((int)$monster->DEF) ?></td>
		<th>MDEF</th>
		<td><?php echo number_format((int)$monster->MDEF) ?></td>
		<th>Scale</th>
		<td><?php echo number_format((int)$monster->Scale) ?></td>
	</tr>
	<tr>
		<th>Base EXP</th>
		<td><?php echo number_format((int)$monster->EXP * $server->baseExpRates) ?></td>
		<th>Job EXP</th>
		<td><?php echo number_format((int)$monster->JEXP * $server->jobExpRates) ?></td>
		<th>Race</th>
		<td><?php echo number_format((int)$monster->Race) ?></td>
	</tr>
	<tr>
		<th>Element</th>
		<td><?php echo number_format((int)$monster->Element) ?></td>
		<th>Mode</th>
		<td><?php echo number_format((int)$monster->Mode) ?></td>
		<th>Speed</th>
		<td><?php echo number_format((int)$monster->Speed) ?></td>
	</tr>
	<tr>
		<th>aDelay</th>
		<td><?php echo number_format((int)$monster->aDelay) ?></td>
		<th>aMotion</th>
		<td><?php echo number_format((int)$monster->aMotion) ?></td>
		<th>dMotion</th>
		<td><?php echo number_format((int)$monster->dMotion) ?></td>
	</tr>
	<tr>
		<th colspan="2">MvP EXP Reward</th>
		<td><?php echo number_format((int)$monster->MEXP * $server->mvpExpRates) ?></td>
		<th colspan="2">Reward Chance</th>
		<td><?php echo number_format((int)$monster->ExpPer/100)."%" ?></td>
	</tr>
	<tr>
		<th colspan="2">MvP Reward 1 ID</th>
		<td><?php echo $this->linkToItem($monster->MVP1id, $monster->MVP1id) ?></td>
		<th colspan="2">MvP Reward 1 Chance</th>
		<td><?php echo number_format((int)$monster->MVP1per * $server->mvpDropRates / 100)."%" ?></td>
	</tr>
	<tr>
		<th colspan="2">MvP Reward 2 ID</th>
		<td><?php echo $this->linkToItem($monster->MVP2id, $monster->MVP2id) ?></td>
		<th colspan="2">MvP Reward 2 Chance</th>
		<td><?php echo number_format((int)$monster->MVP2per * $server->mvpDropRates / 100)."%" ?></td>
	</tr>
	<tr>
		<th colspan="2">MvP Reward 3 ID</th>
		<td><?php echo $this->linkToItem($monster->MVP3id, $monster->MVP3id) ?></td>
		<th colspan="2">MvP Reward 3 Chance</th>
		<td><?php echo number_format((int)$monster->MVP3per * $server->mvpDropRates / 100)."%" ?></td>
	</tr>
	<tr>
		<th colspan="2">Reward 1 ID</th>
		<td><?php echo $this->linkToItem($monster->Drop1id, $monster->Drop1id) ?></td>
		<th colspan="2">Reward 1 Chance</th>
		<td><?php echo number_format((int)$monster->Drop1per * $server->dropRates / 100)."%" ?></td>
	</tr>
	<tr>
		<th colspan="2">Reward 2 ID</th>
		<td><?php echo $this->linkToItem($monster->Drop2id, $monster->Drop2id) ?></td>
		<th colspan="2">Reward 2 Chance</th>
		<td><?php echo number_format((int)$monster->Drop2per * $server->dropRates / 100)."%" ?></td>
	</tr>
	<tr>
		<th colspan="2">Reward 3 ID</th>
		<td><?php echo $this->linkToItem($monster->Drop3id, $monster->Drop3id) ?></td>
		<th colspan="2">Reward 3 Chance</th>
		<td><?php echo number_format((int)$monster->Drop3per * $server->dropRates / 100)."%" ?></td>
	</tr>
	<tr>
		<th colspan="2">Reward 4 ID</th>
		<td><?php echo $this->linkToItem($monster->Drop4id, $monster->Drop4id) ?></td>
		<th colspan="2">Reward 4 Chance</th>
		<td><?php echo number_format((int)$monster->Drop4per * $server->dropRates / 100)."%" ?></td>
	</tr>
	<tr>
		<th colspan="2">Reward 5 ID</th>
		<td><?php echo $this->linkToItem($monster->Drop5id, $monster->Drop5id) ?></td>
		<th colspan="2">Reward 5 Chance</th>
		<td><?php echo number_format((int)$monster->Drop5per * $server->dropRates / 100)."%" ?></td>
	</tr>
	<tr>
		<th colspan="2">Reward 6 ID</th>
		<td><?php echo $this->linkToItem($monster->Drop6id, $monster->Drop6id) ?></td>
		<th colspan="2">Reward 6 Chance</th>
		<td><?php echo number_format((int)$monster->Drop6per * $server->dropRates / 100)."%" ?></td>
	</tr>
	<tr>
		<th colspan="2">Reward 7 ID</th>
		<td><?php echo $this->linkToItem($monster->Drop7id, $monster->Drop7id) ?></td>
		<th colspan="2">Reward 7 Chance</th>
		<td><?php echo number_format((int)$monster->Drop7per * $server->dropRates / 100)."%" ?></td>
	</tr>
	<tr>
		<th colspan="2">Reward 8 ID</th>
		<td><?php echo $this->linkToItem($monster->Drop8id, $monster->Drop8id) ?></td>
		<th colspan="2">Reward 8 Chance</th>
		<td><?php echo number_format((int)$monster->Drop8per * $server->dropRates / 100)."%" ?></td>
	</tr>
	<tr>
		<th colspan="2">Reward 9 ID</th>
		<td><?php echo $this->linkToItem($monster->Drop9id, $monster->Drop9id) ?></td>
		<th colspan="2">Reward 9 Chance</th>
		<td><?php echo number_format((int)$monster->Drop9per * $server->dropRates / 100)."%" ?></td>
	</tr>
	<tr>
		<th colspan="2">Card ID</th>
		<td><?php echo $this->linkToItem($monster->DropCardid, $monster->DropCardid) ?></td>
		<th colspan="2">Card Chance</th>
		<td><?php echo number_format((int)$monster->DropCardper * $server->cardDropRates / 100)."%" ?></td>
	</tr>
</table>
<?php else: ?>
<p>No such monster was found. <a href="javascript:history.go(-1)">Go back</a>.</p>
<?php endif ?>