<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Viewing Monster</h2>
<?php if ($monster): ?>
<h3>#<?php echo htmlspecialchars($monster->monster_id) ?>: “<?php echo htmlspecialchars($monster->kName) ?>” / “<?php echo htmlspecialchars($monster->iName) ?>”
<?php if ($monster->MEXP): ?>: MvP<?php endif ?></h3>
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
	
	<?php
	$rewards       = array();
	$rewards[]     = $monster->MVP1id;
	$rewards[]     = $monster->MVP2id;
	$rewards[]     = $monster->MVP3id;
	$rewards[]     = $monster->Drop1id;
	$rewards[]     = $monster->Drop2id;
	$rewards[]     = $monster->Drop3id;
	$rewards[]     = $monster->Drop4id;
	$rewards[]     = $monster->Drop5id;
	$rewards[]     = $monster->Drop6id;
	$rewards[]     = $monster->Drop7id;
	$rewards[]     = $monster->Drop8id;
	$rewards[]     = $monster->Drop9id;
	$rewards[]     = $monster->DropCardid;

	$rewardsPer    = array();
	$rewardsPer[]  = $monster->MVP1per * $server->mvpDropRates / 100;
	$rewardsPer[]  = $monster->MVP2per * $server->mvpDropRates / 100;
	$rewardsPer[]  = $monster->MVP3per * $server->mvpDropRates / 100;
	$rewardsPer[]  = $monster->Drop1per * $server->dropRates / 100;
	$rewardsPer[]  = $monster->Drop2per * $server->dropRates / 100;
	$rewardsPer[]  = $monster->Drop3per * $server->dropRates / 100;
	$rewardsPer[]  = $monster->Drop4per * $server->dropRates / 100;
	$rewardsPer[]  = $monster->Drop5per * $server->dropRates / 100;
	$rewardsPer[]  = $monster->Drop6per * $server->dropRates / 100;
	$rewardsPer[]  = $monster->Drop7per * $server->dropRates / 100;
	$rewardsPer[]  = $monster->Drop8per * $server->dropRates / 100;
	$rewardsPer[]  = $monster->Drop9per * $server->dropRates / 100;
	$rewardsPer[]  = $monster->DropCardper * $server->cardDropRates / 100.000;
	
	$rewardsList   = array();
	$rewardsList[] = "MvP Reward 1";
	$rewardsList[] = "MvP Reward 2";
	$rewardsList[] = "MvP Reward 3";
	$rewardsList[] = "Drop 1";
	$rewardsList[] = "Drop 2";
	$rewardsList[] = "Drop 3";
	$rewardsList[] = "Drop 4";
	$rewardsList[] = "Drop 5";
	$rewardsList[] = "Drop 6";
	$rewardsList[] = "Drop 7";
	$rewardsList[] = "Drop 8";
	$rewardsList[] = "Drop 9";
	$rewardsList[] = "Card";
	
	if ($monster->MEXP): ?>
	<tr>
		<th>MvP EXP Reward</th>
		<td colspan="3"><?php echo number_format((int)$monster->MEXP * $server->mvpExpRates) ?></td>
		<th>Reward Chance</th>
		<td><?php echo number_format((int)$monster->ExpPer/100)."%" ?></td>
	</tr>
	<?php endif ?>
	<?php for ($reward = 0; $reward <= 12; $reward++) {
	if ($rewards[$reward]) {
	echo "
	<tr>
		<th>$rewardsList[$reward] ID</th>
		<td colspan=\"3\">".$this->linkToItem($rewards[$reward], $rewards[$reward])."</td>
		<th>$rewardsList[$reward] Chance</th>";
		if ($rewardsPer[$reward] > 100)
			$rewardsPer[$reward] = 100;
	echo "
		<td>".$rewardsPer[$reward]."%</td>
	</tr>
	"; } } ?>
</table>
<?php else: ?>
<p>No such monster was found. <a href="javascript:history.go(-1)">Go back</a>.</p>
<?php endif ?>