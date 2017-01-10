<?php

	// sukuriame automobilių klasės objektą
	include 'libraries/servize.class.php';
	$sObj = new servizes();
	
	// sukuriame puslapiavimo klasės objektą
	include 'utils/paging.class.php';
	$paging = new paging(NUMBER_OF_ROWS_IN_PAGE);

	if(!empty($removeId)) {
			$sObj->deleteServize($removeId);
		
	
			
		
		
		// nukreipiame į modelių puslapį
		header("Location: index.php?module=servize{$removeErrorParameter}");
		die();
	}
	
?>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<ul id="pagePath">
	<li><a href="index.php">Pradžia</a></li>
	<li>Servisai</li>
</ul>
<div id="actions">
	<a href='index.php?module=<?php echo $module; ?>&action=new'>Naujas servisas</a>
</div>
<div class="float-clear"></div>
<?php if(isset($_GET['remove_error'])) { ?>
	<div class="errorBox">
		Servisas nebuvo pašalintas, nes turi priskirtų vilkikų.
	</div>
<?php } ?>

<table>
	<tr>
		<th>Serviso kodas</th>
		<th>Miestas</th>
		<th>Darbuotojų skaičius</th>
		<th>Adresas</th>
		<th>Telefonas</th>
		<th>Imones pavadinimas</th>
		<th>Paslauga</th>
			<th></th>
		
	</tr>
	<?php
		// suskaičiuojame bendrą įrašų kiekį
		$elementCount = $sObj->getServizeListCount();

		// suformuojame sąrašo puslapius
		$paging->process($elementCount, $pageId);

		// išrenkame nurodyto puslapio automobilius
		$data = $sObj->getServizeList($paging->size, $paging->first);

		// suformuojame lentelę
		foreach($data as $key => $val) {
			echo
				"<tr>"
					. "<td>{$val['id']}</td>"
					. "<td>{$val['miestas']}</td>"
					. "<td>{$val['darbuotoju_skaicius']}</td>"
					. "<td>{$val['adresas']}</td>"
					. "<td>{$val['telefonas']}</td>"
					. "<td>{$val['imones_pavadinimas']}</td>"
					. "<td>{$val['paslauga']}</td>"
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