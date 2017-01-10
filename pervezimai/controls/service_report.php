  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<?php

	include 'libraries/companies.class.php';
	$companiesObj = new companies();
	
	
	
	$formErrors = null;
	$fields = array();
	$formSubmitted = false;
		
	$data = array();
	if(!empty($_POST['submit'])) {
		$formSubmitted = true;

		// nustatome laukų validatorių tipus
		$validations = array (
			'rNuo' => 'positivenumber',
			'rIki' => 'positivenumber');
		
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
			<li class="title">Įmonių ataskaita</li>
			<li>Sudarymo data: <span><?php echo date("Y-m-d"); ?></span></li>
			<li>Įmonių patikimumo reitingas:
				<span>
					<?php
						if(!empty($data['rNuo'])) {
							if(!empty($data['rIki'])) {
								echo "nuo {$data['rNuo']} iki {$data['rIki']}";
							} else {
								echo "nuo {$data['rNuo']}";
							}
						} else {
							if(!empty($data['rIki'])) {
								echo "iki {$data['rIki']}";
							} else {
								echo "nenurodyta";
							}
						}
					?>
				</span>
				<a href="report.php?id=2" title="Nauja ataskaita" class="newReport">nauja ataskaita</a>
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
						<p><label class="field" for="rNuo">Įmonės patikimumo reitingas nuo</label><input type="text" id="rNuo" name="rNuo" class="textbox-100 " value="<?php echo isset($fields['rNuo']) ? $fields['rNuo'] : ''; ?>" /></p>
						<p><label class="field" for="rIki">Įmonės patikimumo reitingas nuo</label><input type="text" id="rIki" name="rIki" class="textbox-100 " value="<?php echo isset($fields['rIki']) ? $fields['rIki'] : ''; ?>" /></p>
					</fieldset>
					<p><input type="submit" class="submit" name="submit" value="Sudaryti ataskaitą"></p>
				</form>
			</div>
			<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<?php } else {
			
				// išrenkame ataskaitos duomenis
				$companiesData = $companiesObj->getCompaniess($data['rNuo'], $data['rIki']);
				
				$count = $companiesObj->countCompanies($data['rNuo'], $data['rIki']);
				if(sizeof($companiesData) > 0) { ?>
		
					<table class="reportTable">
						<tr>
							<th>Padalinio adresas</th>
							<th>Padalinio pavadinimas</th>
							<th>Padalinio pelnas</th>
							<th>Darbuotojų skaičius</th>
						</tr>

						<?php

							// suformuojame lentelę
							for($i = 0; $i < sizeof($companiesData); $i++) {
								$branchData = $companiesObj->getPadaliniai($data['rNuo'], $data['rIki'],$companiesData[$i]['id']);
									if(sizeof($branchData)>0){
								if($i == 0 || $companiesData[$i]['id'] != $companiesData[$i-1]['id']) {
									echo
										"<tr class='rowSeparator'><td colspan='5'></td></tr>"
										. "<tr>"
											. "<td class='groupSeparator' colspan='5'>{$companiesData[$i]['pavadinimas']}</td>"
										. "</tr>";
								}
								$sum=$companiesObj->getSum($companiesData[$i]['id'],$data['rNuo'], $data['rIki']);
								$sum2=$companiesObj->getSum2($companiesData[$i]['id'],$data['rNuo'], $data['rIki']);
								$old=$companiesObj->getOldest($companiesData[$i]['id']);
								
								for($j = 0; $j < sizeof($branchData); $j++) {
								echo
									"<tr>"
										. "<td>{$branchData[$j]['adresas']}</td>"	
										. "<td>{$branchData[$j]['miestas']}</td>"	
										. "<td>{$branchData[$j]['apyvarta']}&euro;</td>"	
										. "<td>{$branchData[$j]['darbuotoju_skaicius']}</td>"
									. "</tr>";
								}
								echo 
										"<tr class='aggregate'>"
											. "<td colspan='1'></td>"
											."<td class='label'>Sumos:</td>"
											. "<td class='border'>{$sum}&euro; </td>"
											
											. "<td class='border'>{$sum2}</td>"
										. "</tr>";
										echo 
										"<tr class='aggregate'>"
											. "<td colspan='1'></td>"
											."<td class='label'>Vyriausias uzsakymas222:</td>";
											if($old >0){
												echo
											 "<td class='border'>{$old}</td>";
											}
										else{
											echo
											 "<td class='border'>No info on db</td>";
										}
										echo
										"</tr>";
							
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