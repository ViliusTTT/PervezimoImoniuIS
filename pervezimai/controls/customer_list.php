<?php
	
	// sukuriame klientų klasės objektą
	include 'libraries/customers.class.php';
	$customersObj = new customers();

	// sukuriame puslapiavimo klasės objektą
	include 'utils/paging.class.php';
	$paging = new paging(NUMBER_OF_ROWS_IN_PAGE);

	if(!empty($removeId)) {
		// patikriname, ar klientas neturi sudarytų sutarčių
		
		
		
		
			// šaliname klientą
			$customersObj->deleteCustomer($removeId);
		
		

		// nukreipiame į klientų puslapį
		header("Location: index.php?module={$module}{$removeErrorParameter}");
		die();
	}
?>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<ul id="pagePath">
	<li><a href="index.php">Pradžia</a></li>
	<li>Užsakovai</li>
</ul>
<div id="actions">
	<a href='index.php?module=<?php echo $module; ?>&action=new'>Naujas užsakovas</a>
</div>
<div class="float-clear"></div>

<?php if(isset($_GET['remove_error'])) { ?>
	<div class="errorBox">
		Klientas nebuvo pašalintas, nes turi užsakymą (-ų).
	</div>
<?php } ?>

<table>
	<tr>
			<th>Užsakovo kodas</th>
		<th>Adresas</th>
		<th>Pavadinimas</th>
		<th>El paštas</th>
		<th>Telefonas</th>
	
	</tr>
	<?php
		// suskaičiuojame bendrą įrašų kiekį
		$elementCount = $customersObj->getCustomersListCount();

		// suformuojame sąrašo puslapius
		$paging->process($elementCount, $pageId);

		// išrenkame nurodyto puslapio klientus
		$data = $customersObj->getCustomersList($paging->size, $paging->first);

		// suformuojame lentelę
		foreach($data as $key => $val) {
			echo
				"<tr>"
					. "<td>{$val['id']}</td>"
					. "<td>{$val['adresas']}</td>"
					. "<td>{$val['pavadinimas']}</td>"
					. "<td>{$val['el_pastas']}</td>"
					. "<td>{$val['telefonas']}</td>"
				
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