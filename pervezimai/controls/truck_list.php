<?php

	// sukuriame automobilių klasės objektą
	include 'libraries/trucks.class.php';
	$trucksObj = new trucks();
	
	// sukuriame puslapiavimo klasės objektą
	include 'utils/paging.class.php';
	$paging = new paging(NUMBER_OF_ROWS_IN_PAGE);

	if(!empty($removeId)) {
		$count = $trucksObj->getDriverCountOfTruck($removeId);
		
	if($count == 0) {
			// pašaliname modelį
			$trucksObj->deleteTruck($removeId);
		} else {
			$removeErrorParameter = '&remove_error=1';
		
		
		// nukreipiame į modelių puslapį
		header("Location: index.php?module=truck{$removeErrorParameter}");
		die();
	}
	}
?>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<ul id="pagePath">
	<li><a href="index.php">Pradžia</a></li>
	<li>Vilkikai</li>
</ul>
<div id="actions">
	<a href='index.php?module=<?php echo $module; ?>&action=new'>Naujas vilkikas</a>
</div>
<div class="float-clear"></div>
<?php if(isset($_GET['remove_error'])) { ?>
	<div class="errorBox">
		Vilkikas nebuvo pašalinta, nes yra priskirtas vairuotojui(-is).
	</div>
<?php } ?>

<table>
	<tr>
		<th>id</th>
		<th>Markė</th>
		<th>Modelis</th>
		<th>Valst. nr.</th>
		<th>Pagaminimo data</th>
		<th>Rida</th>
		<th>Vertė</th>
		<th>Pavarų dėžė</th>
		<th>Ekonomiškumas</th>
			<th></th>
		
	</tr>
	<?php
		// suskaičiuojame bendrą įrašų kiekį
		$elementCount = $trucksObj->getTruckListCount();

		// suformuojame sąrašo puslapius
		$paging->process($elementCount, $pageId);

		// išrenkame nurodyto puslapio automobilius
		$data = $trucksObj->getTruckList($paging->size, $paging->first);

		// suformuojame lentelę
		foreach($data as $key => $val) {
			echo
				"<tr>"
				. "<td>{$val['id']}</td>"
					. "<td>{$val['mark']} </td>"
					. "<td>{$val['model']}</td>"
					. "<td>{$val['valstybinis_nr']}</td>"
					. "<td>{$val['pagaminimo_data']}</td>"
					. "<td>{$val['rida']}</td>"
					. "<td>{$val['verte']}</td>"
					. "<td>{$val['pavar']}</td>"
					. "<td>{$val['kat']}</td>"
				
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