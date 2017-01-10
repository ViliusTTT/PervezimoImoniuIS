<?php
	
	include 'libraries/contracts.class.php';
	$contractsObj = new contracts();

	include 'libraries/services.class.php';
	$servicesObj = new services();
	
	include 'utils/paging.class.php';
	$paging = new paging(NUMBER_OF_ROWS_IN_PAGE);

	if(!empty($removeId)) {
		
			
			
			// pašaliname paslaugą
			$servicesObj->deleteService($removeId);
		

		// nukreipiame į modelių puslapį
		header("Location: index.php?module={$module}{$removeErrorParameter}");
		die();
	}
?>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<ul id="pagePath">
	<li><a href="index.php">Pradžia</a></li>
	<li>Servisų sąskaitos</li>
</ul>
<div id="actions">
	<a href="report.php?id=1" target="_blank">Sąskaitų ataskaita</a>
	<a href='index.php?module=<?php echo $module; ?>&action=new'>Nauja sąskaita</a>
</div>
<div class="float-clear"></div>

<?php if(isset($_GET['remove_error'])) { ?>
	<div class="errorBox">
		Servisas nebuvo pašalinta.
	</div>
<?php } ?>

<table>
	<tr>
		<th>Sąskaitos id.</th>	
		<th>Atliktos paslaugos</th>
		<th>Data</th>
		<th>Kaina</th>
		<th>Mokanti įmonė</th>
		<th>Servisas</th>
		<th></th>
	</tr>
	<?php
		// suskaičiuojame bendrą įrašų kiekį
		$elementCount = $servicesObj->getServicesListCount();

		// suformuojame sąrašo puslapius
		$paging->process($elementCount, $pageId);

		// išrenkame nurodyto puslapio paslaugas
		$data = $servicesObj->getServicesList($paging->size, $paging->first);

		// suformuojame lentelę
		foreach($data as $key => $val) {
			echo
				"<tr>"
				. "<td>{$val['id']}</td>"
					. "<td>{$val['pobudis']}</td>"
					. "<td>{$val['data']}</td>"
					. "<td>{$val['kaina']}</td>"
					. "<td>{$val['imone']}</td>"
					. "<td>{$val['servisas']}</td>"
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