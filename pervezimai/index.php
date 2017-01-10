  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<?php
	// nuskaitome konfigūracijų failą
	include 'config.php';

	// iškviečiame prisijungimo prie duomenų bazės klasę
	include 'utils/mysql.class.php';
	
	// nustatome pasirinktą modulį
	$module = '';
	if(isset($_GET['module'])) {
		$module = mysql::escape($_GET['module']);
	}
	
	// jeigu pasirinktas elementas (sutartis, automobilis ir kt.), nustatome elemento id
	$id = '';
	if(isset($_GET['id'])) {
		$id = mysql::escape($_GET['id']);
	}
	
	// nustatome, ar kuriamas naujas elementas
	$action = '';
	if(isset($_GET['action'])) {
		$action = mysql::escape($_GET['action']);
	}
	
	// jeigu šalinamas elementas, nustatome šalinamo elemento id
	$removeId = 0;
	if(!empty($_GET['remove'])) {
		// paruošiame $_GET masyvo id reikšmę SQL užklausai
		$removeId = mysql::escape($_GET['remove']);
	}
		
	// nustatome elementų sąrašo puslapio numerį
	$pageId = 1;
	if(!empty($_GET['page'])) {
		$pageId = mysql::escape($_GET['page']);
	}
	
	// nustatome, kiek įrašų rodysime elementų sąraše
	define('NUMBER_OF_ROWS_IN_PAGE', 14);
?>
<body style="background-color:black;">

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="robots" content="noindex">
		<title>Krovinių pervežimo įmonių Informacinė Sistema </title>
		
		<link rel="stylesheet" type="text/css" href="scripts/datetimepicker/jquery.datetimepicker.css" media="screen" />
		
		<link rel="stylesheet" type="text/css" href="style/main.css" media="screen" />
		  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<script type="text/javascript" src="scripts/jquery-1.12.0.min.js"></script>
		<script type="text/javascript" src="scripts/datetimepicker/jquery.datetimepicker.full.min.js"></script>
		<script type="text/javascript" src="scripts/main.js"></script>
	</head>

	<body>
		<div id="body">
			<div id="header">
				<h3 id="slogan"><a href="index.php">Krovinių pervežimo įmonių IS</a>	</h3>
			</div>
			<td>
                    <center><img src="truck.png" /></center>
                </td>
			<div id="content">
				<div id="topMenu">
					<ul class="float-left">
					
						<li><a href="index.php?module=service" title="Pasl."<?php if($module == 'service') { echo 'class="active"'; } ?>>Paslaug</a></li>
						<li><a href="index.php?module=customer" title="Užsakov."<?php if($module == 'customer') { echo 'class="active"'; } ?>>Užsako</a></li>
						<li><a href="index.php?module=employee" title="Darb."<?php if($module == 'employee') { echo 'class="active"'; } ?>>Darb</a></li>
						<li><a href="index.php?module=truck" title="Vilkik."<?php if($module == 'truck') { echo 'class="active"'; } ?>>Vilkik</a></li>
						<li><a href="index.php?module=brand" title="Markės"<?php if($module == 'brand') { echo 'class="active"'; } ?>>Mark</a></li>
						<li><a href="index.php?module=model" title="Model."<?php if($module == 'model') { echo 'class="active"'; } ?>>Model</a></li>
						<li><a href="index.php?module=branches" title="Padal."<?php if($module == 'branches') { echo 'class="active"'; } ?>>Padal.</a></li>
						<li><a href="index.php?module=companies" title="Įmonės"<?php if($module == 'companies') { echo 'class="active"'; } ?>>Įmo.</a></li>
							<li><a href="index.php?module=garages" title="garaž"<?php if($module == 'garages') { echo 'class="active"'; } ?>>Gar.</a></li>
							<li><a href="index.php?module=servize" title="servize"<?php if($module == 'servizes') { echo 'class="active"'; } ?>>Serv</a></li>
								<li><a href="index.php?module=money" title="money"<?php if($module == 'money') { echo 'class="active"'; } ?>>Dienping.</a></li>
					</ul>
					<br>
					<br>
				
			
				</div>
				<div id="contentMain">
					<?php
						if(!empty($module)) {
							if(empty($id) && empty($action)) {
								include "controls/{$module}_list.php";
							} else {
								include "controls/{$module}_edit.php";
							}
						}
					?>
					<div class="float-clear"></div>
				</div>
			</div>
			 
			<div id="footer">
<p> Vilius Turenko 2016</p>			</div>
		</div>
	</body>
</html>
