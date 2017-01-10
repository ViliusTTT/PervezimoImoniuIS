  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<?php
include 'libraries/services.class.php';
	$servicesObj = new services();
	
	
	
	$formErrors = null;
	$fields = array();
	$formSubmitted = false;
		
	$data = array();
	if(!empty($_POST['submit'])) {
		$formSubmitted = true;

		// nustatome laukų validatorių tipus
		$validations = array (
			'dataNuo' => 'date',
			'dataIki' => 'date');
		
		// sukuriame validatoriaus objektą
		include 'utils/validator.class.php';
		$validator = new validator($validations);
		

		if($validator->validate($_POST)) {
			// suformuojame laukų reikšmių masyvą SQL užklausai
			$data = $validator->preparePostFieldsForSQL();
		} else {
			// gauname klaidų pranešimą
			$formErrors = $validator->getErrorHTML();
			// gauname įvestus laukus
			$fields = $_POST;
		}
	}
	
if($formSubmitted == true && ($formErrors == null)) { ?>
	<div id="header">
		<ul id="reportInfo">
			<li class="title">Sąskaitų ataskaita</li>
			<li>Sudarymo data: <span><?php echo date("Y-m-d"); ?></span></li>
			<li>Sutarčių sudarymo laikotarpis:
				<span>
					<?php
						if(!empty($data['dataNuo'])) {
							if(!empty($data['dataIki'])) {
								echo "nuo {$data['dataNuo']} iki {$data['dataIki']}";
							} else {
								echo "nuo {$data['dataNuo']}";
							}
						} else {
							if(!empty($data['dataIki'])) {
								echo "iki {$data['dataIki']}";
							} else {
								echo "nenurodyta";
							}
						}
					?>
				</span>
				<a href="report.php?id=1" title="Nauja ataskaita" class="newReport">nauja ataskaita</a>
			</li>
		</ul>
	</div>
<?php } ?>
<div id="content">
	<div id="contentMain">
		<?php if($formSubmitted == false || $formErrors != null) { ?>
			<div id="formContainer">
				<?php if($formErrors != null) { ?>
					<div class="errorBox">
						Neįvesti arba neteisingai įvesti šie laukai:
						<?php 
							echo $formErrors;
						?>
					</div>
				<?php } ?>
				<form action="" method="post">
					<fieldset>
						<legend>Įveskite ataskaitos kriterijus</legend>
						<p><label class="field" for="dataNuo">Užsakymai sudaryti nuo</label><input type="text" id="dataNuo" name="dataNuo" class="textbox-100 date" value="<?php echo isset($fields['dataNuo']) ? $fields['dataNuo'] : ''; ?>" /></p>
						<p><label class="field" for="dataIki">Užsakymai sudaryti iki</label><input type="text" id="dataIki" name="dataIki" class="textbox-100 date" value="<?php echo isset($fields['dataIki']) ? $fields['dataIki'] : ''; ?>" /></p>
					</fieldset>
					<p><input type="submit" class="submit" name="submit" value="Sudaryti ataskaitą"></p>
				</form>
			</div>
		<?php } else {
			
				// išrenkame ataskaitos duomenis
				$contractData = $servicesObj->getImones();
				
				$count = $servicesObj->countServices($data['dataNuo'], $data['dataIki']);
				if($count > 0) { ?>
		
					<table class="reportTable">
						<tr>
							<th>Aptarnavo servisas</th>
							<th>Paslauga</th>
							<th>Data</th>
							<th>Sąskaitos id</th>
							<th>Kaina</th>
						</tr>

						<?php

							// suformuojame lentelę
							for($i = 0; $i < sizeof($contractData); $i++) {
								$TotalserviceData=$servicesObj->getCompanyCountOfSaskaita($contractData[$i]['id'],$data['dataNuo'], $data['dataIki']);
								if($TotalserviceData>0){
								if($i == 0 || $contractData[$i]['id'] != $contractData[$i-1]['id']) {
									echo
										"<tr class='rowSeparator'><td colspan='5'></td></tr>"
										. "<tr>"
											. "<td class='groupSeparator' colspan='5'>{$contractData[$i]['pavadinimas']}</td>"
										. "</tr>";
								}
								
								$TotalserviceData2=$servicesObj->getCompanyCountOfSumas($contractData[$i]['id'],$data['dataNuo'], $data['dataIki']);
								$serviceData = $servicesObj->getServices($data['dataNuo'], $data['dataIki'],$contractData[$i]['id']);
								for($j = 0; $j < sizeof($serviceData); $j++) {
								echo
									"<tr>"
										. "<td>{$serviceData[$j]['servisas']}</td>"	
										. "<td>{$serviceData[$j]['pobudis']}</td>"
										. "<td>{$serviceData[$j]['data']}</td>"
										. "<td>{$serviceData[$j]['id']}</td>"
										. "<td>{$serviceData[$j]['kaina']}&euro;</td>"
									. "</tr>";
								}
								echo 
										"<tr class='aggregate'>"
											. "<td colspan='2'></td>"
											."<td class='label'>Suma:</td>"
											. "<td class='border'>{$TotalserviceData} </td>"
											. "<td class='border'>{$TotalserviceData2}&euro;</td>"
										. "</tr>";
							
							}
							}
						?>
						
						<tr class="rowSeparator">
							<td colspan="5"></td>
						</tr>
						
				
					</table>
			<?php   } else { ?>
						<div class="warningBox">
							Nurodytu laikotarpiu įmonės nebuvo užsisakiusios paslaugų
						</div>
					<?php
					}
			} ?>
	</div>
</div>