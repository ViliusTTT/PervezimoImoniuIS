  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<?php

	include 'libraries/garages.class.php';
	$garagesObj = new garages();
	
	$formErrors = null;
	$fields = array();
	$formSubmitted = false;
		
	$data = array();
	if(!empty($_POST['submit'])) {
		$formSubmitted = true;

		// nustatome laukų validatorių tipus
		$validations = array (
			'verteNuo' => 'positivenumber',
			'verteIki' => 'positivenumber');
		
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
			<li class="title">Garažų ataskaita</li>
			<li>Sudarymo data: <span><?php echo date("Y-m-d"); ?></span></li>
			<li>Vilkikų kaina kinta:
				<span>
					<?php
						if(!empty($data['verteNuo'])) {
							if(!empty($data['verteIki'])) {
								echo "nuo {$data['verteNuo']} iki {$data['verteIki']}";
							} else {
								echo "nuo {$data['verteNuo']}";
							}
						} else {
							if(!empty($data['verteIki'])) {
								echo "iki {$data['verteIki']}";
							} else {
								echo "nenurodyta";
							}
						}
					?>
				</span>
				<a href="report.php?id=3" title="Nauja ataskaita" class="newReport">nauja ataskaita</a>
			</li>
		</ul>
	</div>
<?php } ?>
<div id="content">
	<div id="contentMain">
		<?php
			if($formSubmitted == false || $formErrors != null) { ?>
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
							<p><label class="field" for="verteNuo">Vilkikai,kurių vertė nuo</label><input type="text" id="verteNuo" name="verteNuo" class="textbox-100 " value="<?php echo isset($fields['verteNuo']) ? $fields['verteNuo'] : ''; ?>" /></p>
							<p><label class="field" for="verteIki">Vilkikai,kurių vertė iki</label><input type="text" id="verteIki" name="verteIki" class="textbox-100 " value="<?php echo isset($fields['verteIki']) ? $fields['verteIki'] : ''; ?>" /></p>
						</fieldset>
						<p><input type="submit" class="submit" name="submit" value="Sudaryti ataskaitą"></p>
					</form>
				</div>
	<?php	} else {
					// išrenkame ataskaitos duomenis
				$garageData = $garagesObj->getGarazai();
				$truckCount= $garagesObj->getVerteCount2($data['verteNuo'], $data['verteIki']);
			
				if($truckCount > 0) { ?>
				
					<table class="reportTable">
						<tr>
							<th>Vilkiko markė</th>
							<th>Vilkiko modelis</th>
							<th>Vilkiko verte</th>
							
						</tr>

						<?php
							
							// suformuojame lentelę
							for($i = 0; $i < sizeof($garageData); $i++) {
									$maxVerte=$garagesObj->getMaxVerte2($data['verteNuo'], $data['verteIki'],$garageData[$i]['id']);
									$minVerte=$garagesObj->getMinVerte2($data['verteNuo'], $data['verteIki'],$garageData[$i]['id']);
									$avgVerte=$garagesObj->getAvrgVerte2($data['verteNuo'], $data['verteIki'],$garageData[$i]['id']);
									$truckData = $garagesObj->getTrucks($data['verteNuo'], $data['verteIki'],$garageData[$i]['id']);
									
									
							if(sizeof($truckData)>0){
								if($i == 0 || $garageData[$i]['id'] != $garageData[$i-1]['id']) {
									echo
										"<tr class='rowSeparator'><td colspan='5'></td></tr>"
										. "<tr>"
											. "<td class='groupSeparator' colspan='5'>{$garageData[$i]['adresas']}</td>"
										. "</tr>";
								}
								
							
						
								
								for($j = 0; $j < sizeof($truckData); $j++) {
								echo
									"<tr>"
										
										. "<td>{$truckData[$j]['mark']}</td>"	
										. "<td>{$truckData[$j]['model']}</td>"	
										. "<td>{$truckData[$j]['verte']}&euro;</td>"
										
									. "</tr>";
								}
								echo 
										"<tr class='aggregate'>"
											. "<td colspan='1'></td>"
											."<td class='label'>Didžiausia vertė:</td>"
											. "<td class='border'>{$maxVerte}&euro; </td>"
											
										. "</tr>";
										echo 
										"<tr class='aggregate'>"
											. "<td colspan='1'></td>"
											."<td class='label'>Mažiausia vertė:</td>"
											. "<td class='border'>{$minVerte}&euro; </td>"
											
										. "</tr>";
										echo 
										"<tr class='aggregate'>"
											. "<td colspan='1'></td>"
											."<td class='label'>Vidutinė vertė:</td>"
											. "<td class='border'>{$avgVerte}&euro; </td>"
											
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
							Nurodytos vertės vilkikų nėra
						</div>
					<?php
					}
			} ?>
	</div>
</div>