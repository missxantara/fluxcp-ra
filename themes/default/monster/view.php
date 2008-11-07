<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2>Viewing Monster</h2>
<?php if ($monster[0]): ?>
<h3>#<?php echo htmlspecialchars($monster[0]->monster_id) ?>: “<?php echo htmlspecialchars($monster[0]->kName) ?>” / “<?php echo htmlspecialchars($monster[0]->iName) ?>”
<?php if ($monster[0]->MEXP): ?>: MvP<?php endif ?></h3>
<table class="vertical-table">
	<tr>
		<th>Monster ID</th>
		<td><?php echo htmlspecialchars($monster[0]->monster_id) ?></td>
		<th>kRO Name</th>
		<td><?php echo htmlspecialchars($monster[0]->kName) ?></td>
		<th>iRO Name</th>
		<td><?php echo htmlspecialchars($monster[0]->iName) ?></td>
	</tr>
	<tr>
		<th>Sprite</th>
		<td><?php echo htmlspecialchars($monster[0]->Sprite) ?></td>
		<th>HP</th>
		<td><?php echo number_format((int)$monster[0]->HP) ?></td>
		<th>SP</th>
		<td><?php echo number_format((int)$monster[0]->SP) ?></td>
	</tr>
	<tr>
		<th>Level</th>
		<td><?php echo number_format((int)$monster[0]->LV) ?></td>
		<th>ATK1</th>
		<td><?php echo number_format((int)$monster[0]->ATK1) ?></td>
		<th>ATK2</th>
		<td><?php echo number_format((int)$monster[0]->ATK2) ?></td>
	</tr>
	<tr>
		<th>Range1</th>
		<td><?php echo number_format((int)$monster[0]->Range1) ?></td>
		<th>Range2</th>
		<td><?php echo number_format((int)$monster[0]->Range2) ?></td>
		<th>Range3</th>
		<td><?php echo number_format((int)$monster[0]->Range3) ?></td>
	</tr>
	<tr>
		<th>STR</th>
		<td><?php echo number_format((int)$monster[0]->STR) ?></td>
		<th>AGI</th>
		<td><?php echo number_format((int)$monster[0]->AGI) ?></td>
		<th>VIT</th>
		<td><?php echo number_format((int)$monster[0]->VIT) ?></td>
	</tr>
	<tr>
		<th>INT</th>
		<td><?php echo number_format((int)$monster[0]->INT) ?></td>
		<th>DEX</th>
		<td><?php echo number_format((int)$monster[0]->DEX) ?></td>
		<th>LUK</th>
		<td><?php echo number_format((int)$monster[0]->LUK) ?></td>
	</tr>
	<tr>
		<th>DEF</th>
		<td><?php echo number_format((int)$monster[0]->DEF) ?></td>
		<th>MDEF</th>
		<td><?php echo number_format((int)$monster[0]->MDEF) ?></td>
		<th>Scale</th>
		<td><?php echo number_format((int)$monster[0]->Scale) ?></td>
	</tr>
	<tr>
		<th>Base EXP</th>
		<td><?php echo number_format((int)$monster[0]->EXP * $server->baseExpRates) ?></td>
		<th>Job EXP</th>
		<td><?php echo number_format((int)$monster[0]->JEXP * $server->jobExpRates) ?></td>
		<th>Race</th>
		<td>
			<?php if ($race=Flux::monsterRaceName($monster[0]->Race)): ?>
				<?php echo htmlspecialchars($race) ?>
			<?php else: ?>
				<span class="not-applicable">Unknown</span>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<th>Element</th>
		<td><?php echo Flux::elementName($monster[0]->DefEle) ?> (<?php echo (int)$monster[0]->EleLv ?>)</td>
		<th>Mode</th>
		<td><?php echo number_format((int)$monster[0]->Mode) ?></td>
		<th>Speed</th>
		<td><?php echo number_format((int)$monster[0]->Speed) ?></td>
	</tr>
	<tr>
		<th>aDelay</th>
		<td><?php echo number_format((int)$monster[0]->aDelay) ?></td>
		<th>aMotion</th>
		<td><?php echo number_format((int)$monster[0]->aMotion) ?></td>
		<th>dMotion</th>
		<td><?php echo number_format((int)$monster[0]->dMotion) ?></td>
	</tr>
	
	<?php
	$rewards       = array();
	$rewardsPer    = array();
	$rewardsList   = array();
	$rewardsName   = array();
	
	for ($loop = 1; $loop <= 3; $loop++) {
		$id            = "MVP".$loop."id";
		$Per           = "MVP".$loop."per";
		$Name          = "MVP".$loop."name";
		$rewards[]     = $monster[0]->$id;
		$rewardsPer[]  = $monster[0]->$Per * $server->mvpDropRates / 100;
		$rewardsList[] = "MvP Reward ".$loop;
		$rewardsName[] = $monster[$loop]->$Name;
	}
	
		for ($loop = 1; $loop <= 9; $loop++) {
		$id            = "Drop".$loop."id";
		$Per           = "Drop".$loop."per";
		$Name          = "Drop".$loop."name";
		$rewards[]     = $monster[0]->$id;
		$rewardsPer[]  = $monster[0]->$Per * $server->dropRates / 100;
		$rewardsList[] = "Drop".$loop;
		$rewardsName[] = $monster[$loop+3]->$Name;
	}
	
	$rewards[]     = $monster[0]->DropCardid;
	$rewardsPer[]  = $monster[0]->DropCardper * $server->cardDropRates / 100.000;
	$rewardsList[] = "Card";
	$rewardsName[] = $monster[13]->DropCardname;
	
	if ($monster[0]->MEXP): ?>
	<tr>
		<th>MvP EXP Reward</th>
		<td colspan="3"><?php echo number_format((int)$monster[0]->MEXP * $server->mvpExpRates) ?></td>
		<th>Reward Chance</th>
		<td><?php echo number_format((int)$monster[0]->ExpPer/100)."%" ?></td>
	</tr>
	<?php endif ?>
	<?php for ($reward = 0; $reward <= 12; $reward++) {
	if ($rewards[$reward]) {
	echo "
	<tr>
		<th>$rewardsList[$reward]</th>
		<td colspan=\"3\">".$this->linkToItem($rewards[$reward], $rewardsName[$reward]." (#".$rewards[$reward].")")."</td>
		<th>$rewardsList[$reward] Chance</th>";
		if ($rewardsPer[$reward] > 100)
			$rewardsPer[$reward] = 100;
	echo "
		<td>".$rewardsPer[$reward]."%</td>
	</tr>
	";
	} }
	if (!is_readable($mobDB)) {
		echo "<td colspan=\"17\" align=\"center\">Mob Skill DB could not be read.</td>";
	} else if (filesize($mobDB) == 0) {
		echo "<td colspan=\"6\" align=\"center\">The Mob Skill DB needs to be reloaded by an Admin.</td>";
	} else {
		echo "
		</table>
		<br />
		<h3>Monster Skills</h3>
		<table class=\"vertical-table\">
		<tr>
		<th>Info</th>
		<th>State</th>
		<th>Skill ID</th>
		<th>Skill Level</th>
		<th>Rate</th>
		<th>Cast Time</th>
		<th>Delay</th>
		<th>Cancelable</th>
		<th>Target</th>
		<th>Condition</th>
		<th>Value</th>
		<th>Val1</th>
		<th>Val2</th>
		<th>Val3</th>
		<th>Val4</th>
		<th>Val5</th>
		<th>Emotion</th>
		</tr>";
		if (count($skills) == 0)
			echo "<td colspan=\"17\" align=\"center\">This monster has no skills.</td>";
		foreach ($skills as $skill) {
			$skill = explode(',', $skill);
			$info = explode('@', $skill[1],2);
			$skill[1] = $info[1];
			echo "
			<tr>";
			$skill[5] = ($skill[5]/100.00)."%";
			$skill[6] = ($skill[6]/100.00)." sec";
			$skill[7] = ($skill[7]/100.00)." sec";
			for ($a = 1; $a <= 17; $a++) {
				if (trim($skill[$a]) != "")
					echo "<td>$skill[$a]</td>";
				else
					echo "<td><span class=\"not-applicable\">None</span></td>";
			}
			echo "
			</tr>";
		}
	}
	echo "
	</tr>";
	?>
</table>
<?php else: ?>
<p>No such monster was found. <a href="javascript:history.go(-1)">Go back</a>.</p>
<?php endif ?>