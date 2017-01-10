  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<?php
	
	include 'libraries/contracts.class.php';
	include 'libraries/customers.class.php';
		include 'libraries/companies.class.php';
	$contractsObj = new contracts();



	
	$formErrors = null;
	$fields = array();
	
	// nustatome privalomus laukus
	$required = array('krovinio_tipas', 'uzsakymo_verte', 'uzsakymo_data', 'uzsakymo_terminas', 'uzsakymo_statusas', 'fk_PERVEZIMU_IMONEimones_kodas', 'fk_UZSAKOVASuzsakovo_kodas');
	$maxLengths = array (
		'krovinio_tipas' => 20,
		'uzsakymo_verte' => 8
		
	);
	// vartotojas paspaudė išsaugojimo mygtuką
	if(!empty($_POST['submit'])) {
		include 'utils/validator.class.php';
		
		// nustatome laukų validatorių tipus
		$validations = array (
			'krovinio_tipas' => 'anything',
			'uzsakymo_verte' => 'positivenumber',
			'uzsakymo_data' => 'date',
			'uzsakymo_terminas' => 'date',
			'id' => 'anything',
			'uzsakymo_statusas' => 'anything',
			'fk_PERVEZIMU_IMONEimones_kodas' => 'anything',
			'fk_UZSAKOVASuzsakovo_kodas' => 'anything');
		
		// sukuriame laukų validatoriaus objektą
		$validator = new validator($validations, $required);
		
		// laukai įvesti be klaidų
		if($validator->validate($_POST)) {
			// suformuojame laukų reikšmių masyvą SQL užklausai
			$data = $validator->preparePostFieldsForSQL();

			if(isset($data['id'])) {
				// atnaujiname sutartį
				$contractsObj->updateContract($data);
			} else {
				
				$latestId = $contractsObj->getMaxIdOfContract();
				
				
				$data['id'] =$latestId + 1 ;
					$contractsObj->insertContract($data);
					
					
				
			}
			
			// nukreipiame vartotoją į sutarčių puslapį
			if($formErrors == null) {
				header("Location: index.php?module={$module}");
				die();
			}
		} else {
			// gauname klaidų pranešimą
			$formErrors = $validator->getErrorHTML();
			
			// laukų reikšmių kintamajam priskiriame įvestų laukų reikšmes
			$fields = $_POST;
			
			
		}
	} else {
		// tikriname, ar adreso eilutėje nenurodytas elemento id. Jeigu taip, išrenkame elemento duomenis ir jais užpildome formos laukus.
		if(!empty($id)) {
			$fields = $contractsObj->getContract($id);
			
		}
	}
	
?>
<ul id="pagePath">
	<li><a href="index.php">Pradžia</a></li>
	<li><a href="index.php?module=<?php echo $module; ?>">Užsakymai</a></li>
	<li><?php if(!empty($id)) echo "Užsakymo redagavimas"; else echo "Naujas užsakymas"; ?></li>
</ul>
<div class="float-clear"></div>
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
			<legend>Užsakymo informacija</legend>
			<p>
				<label class="field" for="krovinio_tipas">Krovinio tipas<?php echo in_array('krovinio_tipas', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="krovinio_tipas" name="krovinio_tipas" class="textbox-150" value="<?php echo isset($fields['krovinio_tipas']) ? $fields['krovinio_tipas'] : ''; ?>">
			</p>
			<p>
				<label class="field" for="uzsakymo_terminas">Užsakymo terminas<?php echo in_array('uzsakymo_terminas', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="uzsakymo_terminas" name="uzsakymo_data" class="date textbox-70" value="<?php echo isset($fields['uzsakymo_terminas']) ? $fields['uzsakymo_terminas'] : ''; ?>">
			</p>
			<p>
				<label class="field" for="uzsakymo_statusas">Užsakymo statusas<?php echo in_array('fk_klientas', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="uzsakymo_statusas" name="uzsakymo_statusas">
					<option value="">---------------</option>
					<?php
						
						$data = $contractsObj->getStatus();
						foreach($data as $key => $val) {
							$selected = "";
							if(isset($fields['uzsakymo_statusas']) && $fields['uzsakymo_statusas'] == $val['id_uzsakymo_statusai']) {
								$selected = " selected='selected'";
							}
							echo "<option{$selected} value='{$val['id_uzsakymo_statusai']}'> {$val['name']}</option>";
						}
					?>
				</select>
			</p>
			
		
			
		</fieldset>

		<fieldset>
			<legend>Užsakovo informacija</legend>
			<p>
				<label class="field" for="brand1">Užsakovas<?php echo in_array('fk_UZSAKOVASuzsakovo_kodas', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="brand1" name="fk_UZSAKOVASuzsakovo_kodas">
					<option value="-1">Pasirinkite užsakovą</option>
					<?php
						
					$brands = $contractsObj->getUzsakovai();
						foreach($brands as $key => $val) {
							$selected = "";
							if(isset($fields['fk_UZSAKOVASuzsakovo_kodas']) && $fields['fk_UZSAKOVASuzsakovo_kodas'] == $val['uzsakovo_kodas']) {
								$selected = " selected='selected'";
							}
							
							echo "<option{$selected} value='{$val['uzsakovo_kodas']}'>{$val['pavadinimas']}- {$val['uzsakovo_kodas']}}</option>";
						}
						
					?>
						
				</select>
			</p>
					<p>
				<label class="field" for="uzsakymo_data">Užsakymo data<?php echo in_array('uzsakymo_data', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="uzsakymo_data" name="uzsakymo_data" class="date textbox-70" value="<?php echo isset($fields['uzsakymo_data']) ? $fields['uzsakymo_data'] : ''; ?>">
			</p>
			
				
					<p>
				<label class="field" for="uzsakymo_verte">Vertė<?php echo in_array('uzsakymo_verte', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="uzsakymo_verte" name="uzsakymo_verte" class="textbox-150" value="<?php echo isset($fields['uzsakymo_verte']) ? $fields['uzsakymo_verte'] : ''; ?>">
			</p>
			
		</fieldset>

		<fieldset>
			<legend>Įmonės informacija</legend>
			<p>
				<label class="field" for="brand1">Vykdo užsakymą<?php echo in_array('fk_PERVEZIMU_IMONEimones_kodas', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="brand1" name="fk_PERVEZIMU_IMONEimones_kodas">
					<option value="-1">Pasirinkite įmonę</option>
					<?php
						// išrenkame visas markes
						$brands = $contractsObj->getCompanies();
						foreach($brands as $key => $val) {
							$selected = "";
							if(isset($fields['fk_PERVEZIMU_IMONEimones_kodas']) && $fields['fk_PERVEZIMU_IMONEimones_kodas'] == $val['id']) {
								$selected = " selected='selected'";
							}
							echo "<option{$selected} value='{$val['id']}'>{$val['pavadinimas']}</option>";
						}
						

					?>
						
				</select>
			</p>
		</fieldset>
		<p>
			<input type="submit" class="submit" name="submit" value="Išsaugoti">
		</p>
		
	</form>
</div>