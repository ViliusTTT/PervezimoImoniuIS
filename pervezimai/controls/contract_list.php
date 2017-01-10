<?php
	
	// sukuriame sutarčių klasės objektą
	include 'libraries/contracts.class.php';
	$contractsObj = new contracts();

	// sukuriame puslapiavimo klasės objektą
	include 'utils/paging.class.php';
	$paging = new paging(NUMBER_OF_ROWS_IN_PAGE);
	
	if(!empty($removeId)) {
		

		// šaliname sutartį
		$contractsObj->deleteContract($removeId);

		// nukreipiame į sutarčių puslapį
		header("Location: index.php?module={$module}");
		die();
	}
?>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<ul id="pagePath">
	<li><a href="index.php">Pradžia</a></li>
	<li>Užsakymai</li>
</ul>
<div id="actions">
	<a href="report.php?id=2" target="_blank">Užsakymų ataskaita</a>
	<a href='index.php?module=<?php echo $module; ?>&action=new'>Naujas Užsakymas</a>
</div>
<div class="float-clear"></div>

<table>
	<tr>
		<th>Užsakymo nr.</th>
		<th>Krovinio tipas</th>
		<th>Data</th>
		<th>Vertė</th>
		<th>Terminas</th>
		<th>Statusas</th>
		<th>Pervežimo įmonė</th>
		<th>Užsakovas</th>
		<th></th>
	</tr>
	<?php
		// suskaičiuojame bendrą įrašų kiekį
		$elementCount = $contractsObj->getContractListCount();

		// suformuojame sąrašo puslapius
		$paging->process($elementCount, $pageId);

		// išrenkame nurodyto puslapio sutartis
		$data = $contractsObj->getContractList($paging->size, $paging->first);

		// suformuojame lentelę
		foreach($data as $key => $val) {
			echo
				"<tr>"
					. "<td>{$val['id']}</td>"
					. "<td>{$val['krovinio_tipas']}</td>"
					. "<td>{$val['uzsakymo_data']}</td>"
					. "<td>{$val['uzsakymo_verte']}</td>"
					. "<td>{$val['terminas']}</td>"
					. "<td>{$val['uzsakymo_status']}</td>"
					. "<td>{$val['imones_pavadinimas']}</td>"
					. "<td>{$val['Uzsakovas']}</td>"
					. "<td>"
						. "<a href='#' onclick='showConfirmDialog(\"{$module}\", \"{$val['id']}\"); return false;' title=''>šalinti</a>&nbsp;"
						. "<a href='index.php?module={$module}&id={$val['id']}' title=''>redaguoti</a>"
					. "</td>"
				. "</tr>";
		}
	?>
</table>

<?php include 'controls/paging.php'; ?>