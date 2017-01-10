<?php

	// sukuriame automobilių klasės objektą
	include 'libraries/garages.class.php';
	$garagesObj = new garages();
	
	// sukuriame puslapiavimo klasės objektą
	include 'utils/paging.class.php';
	$paging = new paging(NUMBER_OF_ROWS_IN_PAGE);

	if(!empty($removeId)) {
		
	$count = $garagesObj->getTruckCount($removeId);	
		if($count == 0) {
			$garagesObj->deleteGarage($removeId);
		}
		else{
			$removeErrorParameter = '&remove_error=1';
		
		
		// nukreipiame į modelių puslapį
		header("Location: index.php?module=garages{$removeErrorParameter}");
		die();
	}
	}
?>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<ul id="pagePath">
	<li><a href="index.php">Pradžia</a></li>
	<li>Garažai</li>
</ul>
<div id="actions">
	<a href="report.php?id=3" target="_blank">Garažų ataskaita</a>
	<a href='index.php?module=<?php echo $module; ?>&action=new'>Naujas garažas</a>
</div>
<div class="float-clear"></div>
<?php if(isset($_GET['remove_error'])) { ?>
	<div class="errorBox">
		Garažas nebuvo pašalintas, nes turi priskirtų vilkikų.
	</div>
<?php } ?>

<table>
	<tr>
		<th>id</th>
		<th>Vilkikų vietų skaičius</th>
		<th>Miestas</th>
		<th>Adresas</th>
		<th>Telefonas</th>
		<th>Padalinys</th>
			<th></th>
		
	</tr>
	<?php
		// suskaičiuojame bendrą įrašų kiekį
		$elementCount = $garagesObj->getGarageListCount();

		// suformuojame sąrašo puslapius
		$paging->process($elementCount, $pageId);

		// išrenkame nurodyto puslapio automobilius
		$data = $garagesObj->getGarageList($paging->size, $paging->first);

		// suformuojame lentelę
		foreach($data as $key => $val) {
			echo
				"<tr>"
				. "<td>{$val['id']}</td>"
					. "<td>{$val['vilkiku_skaicius']} </td>"
					. "<td>{$val['miestas']}</td>"
					. "<td>{$val['adresas']}</td>"
					. "<td>{$val['telefonas']}</td>"
					. "<td>{$val['padalinys']}</td>"
					
				
					. "<td>"
						. "<a href='#' onclick='showConfirmDialog(\"{$module}\", \"{$val['id']}\"); return false;' title=''>šalinti</a>&nbsp;"
				. "<a href='index.php?module={$module}&id={$val['id']}' title=''>redaguoti</a>"
					. "</td>"
				. "</tr>";
		}
	?>
</table>

<?php
// įtraukiame puslapių šabloną
	include 'controls/paging.php';
?>