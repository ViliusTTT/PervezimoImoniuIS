<?php
	

	include 'libraries/branches.class.php';
	$branchesObj = new branches();
	
	include 'utils/paging.class.php';
	$paging = new paging(NUMBER_OF_ROWS_IN_PAGE);

	if(!empty($removeId)) {
			
	$count = $branchesObj->getGarageCount($removeId);
		
	if($count == 0) {
			// pašaliname modelį
			$branchesObj->deleteBranch($removeId);
		} else {
			$removeErrorParameter = '&remove_error=1';
		
		
		// nukreipiame į modelių puslapį
		header("Location: index.php?module=branches{$removeErrorParameter}");
		die();
	}
	}
?>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<ul id="pagePath">
	<li><a href="index.php">Pradžia</a></li>
	<li>Įmonių padaliniai</li>
</ul>
<div id="actions">

	<a href='index.php?module=<?php echo $module; ?>&action=new'>Naujas padalinys</a>
</div>
<div class="float-clear"></div>

<?php if(isset($_GET['remove_error'])) { ?>
	<div class="errorBox">
		Padalinys nebuvo pašalintas, nes turi priskirtų garažų.
	</div>
<?php } ?>

<table>
	<tr>
		<th>Padalinio numeris</th>
		<th>Miestas</th>
		<th>Apyvarta</th>
		<th>Darbuotojų skaičius</th>
		<th>Adresas</th>
		<th>Įsikūrimo data</th>
		<th>Priklauso Imonei</th>
		<th></th>
	</tr>
	<?php
		// suskaičiuojame bendrą įrašų kiekį
		$elementCount = $branchesObj->getBranchesListCount();

		// suformuojame sąrašo puslapius
		$paging->process($elementCount, $pageId);

		// išrenkame nurodyto puslapio paslaugas
		$data = $branchesObj->getBranchesList($paging->size, $paging->first);

		// suformuojame lentelę
		foreach($data as $key => $val) {
			echo
				"<tr>"
					. "<td>{$val['id']}</td>"
					. "<td>{$val['miestas']}</td>"
					. "<td>{$val['apyvarta']}</td>"
					. "<td>{$val['darbuotoju_skaicius']}</td>"
					. "<td>{$val['adresas']}</td>"
					. "<td>{$val['isikurimo_data']}</td>"
					. "<td>{$val['Imone']}</td>"
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