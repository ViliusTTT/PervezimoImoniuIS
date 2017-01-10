<?php
	
	// sukuriame darbuotojų klasės objektą
	include 'libraries/employees.class.php';
	$employeesObj = new employees();

	// sukuriame puslapiavimo klasės objektą
	include 'utils/paging.class.php';
	$paging = new paging(NUMBER_OF_ROWS_IN_PAGE);

	if(!empty($removeId)) {
		// patikriname, ar uzsakymas222 neturi sudarytų sutarčių
		$count = $employeesObj->getCountEmployee($removeId);
		
		$removeErrorParameter = '';
		if($count == 0) {
			// šaliname darbuotoją
			$employeesObj->deleteEmployee($removeId);
			$employeesObj->deletePay($removeId);
		} else {
			// nepašalinome, nes uzsakymas222 sudaręs bent vieną sutartį, rodome klaidos pranešimą
			$removeErrorParameter = '&remove_error=1';
		}

		// nukreipiame į darbuotojų puslapį
		header("Location: index.php?module={$module}{$removeErrorParameter}");
		die();
	}
?>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<ul id="pagePath">
	<li><a href="index.php">Pradžia</a></li>
	<li>Darbuotojai</li>
</ul>
<div id="actions">
	<a href='index.php?module=<?php echo $module; ?>&action=new'>Naujas darbuotojas</a>
</div>
<div class="float-clear"></div>

<?php if(isset($_GET['remove_error'])) { ?>
	<div class="errorBox">
		Klientas nebuvo pašalintas, nes turi vilkiką.
	</div>
<?php } ?>

<table>
	<tr>
	<th>id</th>
	
		<th>Vardas</th>
		<th>Pavardė</th>
		<th>Amžius</th>
		<th>Darbo patirtis</th>
		<th>Lytis</th>
		<th>Tautybė</th>
		<th>Dienpinigiai</th>
		<th>Padalinys</th>
		<th>Garažas</th>
		<th></th>
	</tr>
	<?php
		// suskaičiuojame bendrą įrašų kiekį
		$elementCount = $employeesObj->getEmployeesListCount();

		// suformuojame sąrašo puslapius
		$paging->process($elementCount, $pageId);

		// išrenkame nurodyto puslapio darbuotojus
		$data = $employeesObj->getEmployeesList($paging->size, $paging->first);

		// suformuojame lentelę
		foreach($data as $key => $val) {
			echo
				"<tr>"
				. "<td>{$val['id']}</td>"
				
					. "<td>{$val['vardas']}</td>"
					. "<td>{$val['pavarde']}</td>"
					. "<td>{$val['amzius']}</td>"
					. "<td>{$val['darbo_patirtis']}</td>"
					. "<td>{$val['lytis']}</td>"
					. "<td>{$val['tautybe']}</td>"
					. "<td>{$val['dienp']}</td>"
					. "<td>{$val['padalinys']}</td>"
					. "<td>{$val['garazas']}</td>"
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