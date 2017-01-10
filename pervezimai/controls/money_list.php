<?php

	// sukuriame automobilių klasės objektą
	include 'libraries/money.class.php';
	$garagesObj = new money();
	
	// sukuriame puslapiavimo klasės objektą
	include 'utils/paging.class.php';
	$paging = new paging(NUMBER_OF_ROWS_IN_PAGE);

	if(!empty($removeId)) {
			$garagesObj->deleteMoney($removeId);
		
		
		
		
		// nukreipiame į modelių puslapį
		header("Location: index.php?module=money{$removeErrorParameter}");
		die();
	
	}
?>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<ul id="pagePath">
	<li><a href="index.php">Pradžia</a></li>
	<li>Dienpinigai</li>
</ul>
<div id="actions">
	<a href='index.php?module=<?php echo $module; ?>&action=new'>Naujas pavedimas</a>
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
		<th>Suma</th>
		<th>Data</th>
		<th>Išlaidų pobūdis</th>
		<th>Padalinys</th>
			<th></th>
		
	</tr>
	<?php
		// suskaičiuojame bendrą įrašų kiekį
		$elementCount = $garagesObj->getMoneyListCount();

		// suformuojame sąrašo puslapius
		$paging->process($elementCount, $pageId);

		// išrenkame nurodyto puslapio automobilius
		$data = $garagesObj->getMoneyList($paging->size, $paging->first);

		// suformuojame lentelę
		foreach($data as $key => $val) {
			echo
				"<tr>"
				. "<td>{$val['id']}</td>"
					. "<td>{$val['suma']} </td>"
					. "<td>{$val['data']}</td>"
					. "<td>{$val['pobudis']}</td>"
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