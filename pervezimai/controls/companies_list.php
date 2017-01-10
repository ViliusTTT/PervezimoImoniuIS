<?php
	

	include 'libraries/companies.class.php';
	$servicesObj = new companies();
	
	include 'utils/paging.class.php';
	$paging = new paging(NUMBER_OF_ROWS_IN_PAGE);

	if(!empty($removeId)) {
		$count = $servicesObj->getBranchesCount($removeId);
		
	if($count == 0) {
				
			
			$servicesObj->deleteCompany($removeId);
	}
	else{
				$removeErrorParameter = '&remove_error=1';
		

		// nukreipiame į modelių puslapį
		header("Location: index.php?module=companies{$removeErrorParameter}");
		die();
	}
	}
?>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<ul id="pagePath">
	<li><a href="index.php">Pradžia</a></li>
	<li>Pervežimų įmonės</li>
</ul>
<div id="actions">
	<a href="report.php?id=2" target="_blank">Įmonių ataskaita</a>
	<a href='index.php?module=<?php echo $module; ?>&action=new'>Nauja įmonė</a>
</div>
<div class="float-clear"></div>

<?php if(isset($_GET['remove_error'])) { ?>
	<div class="errorBox">
		Įmonė nepašalinta, nes turi padalinių
	</div>
<?php } ?>

<table>
	<tr>
	<th>Imones kodas</th>
		<th>Pavadinimas</th>
		<th>Apyvarta</th>
		<th>Padalinių skaičius</th>
		<th>Pelnas</th>
		<th>Adresas</th>
		<th>Imones patikimumas</th>
		<th>Veiklos pobūdis</th>
		<th>Veiklos sritis</th>
		<th></th>
	</tr>
	<?php
		// suskaičiuojame bendrą įrašų kiekį
		$elementCount = $servicesObj->getCompaniesListCount();

		// suformuojame sąrašo puslapius
		$paging->process($elementCount, $pageId);

		// išrenkame nurodyto puslapio paslaugas
		$data = $servicesObj->getCompaniesList($paging->size, $paging->first);

		// suformuojame lentelę
		foreach($data as $key => $val) {
			echo
				"<tr>"
					. "<td>{$val['id']}</td>"
					. "<td>{$val['pavadinimas']}</td>"
					. "<td>{$val['apyvarta']}</td>"
					. "<td>{$val['padaliniu_skaicius']}</td>"
					. "<td>{$val['pelnas']}</td>"
					. "<td>{$val['adresas']}</td>"
					. "<td>{$val['imones_patikimumas']}</td>"
					. "<td>{$val['veikla']}</td>"
					. "<td>{$val['sritis']}</td>"
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