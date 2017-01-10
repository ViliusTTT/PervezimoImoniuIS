  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<?php
	

	include 'libraries/services.class.php';
	$servicesObj = new services();
	
	$formErrors = null;
	$fields = array();
	
	// nustatome privalomus laukus
	$required = array('atliktos_paslaugos', 'data', 'kaina','fk_PERVEZIMU_IMONEimones_kodas','fk_SERVISASserviso_kodas');
	
	// maksimalūs leidžiami laukų ilgiai
	$maxLengths = array (
		'kaina' => 10,
		
	);
	include 'utils/validator.class.php';
	// paspaustas išsaugojimo mygtukas
	if(!empty($_POST['submit'])) {
		// nustatome laukų validatorių tipus
		$validations = array (
			'kaina' => 'anything',
			'data' => 'date',
			'atliktos_paslaugos' => 'anything',
			'fk_PERVEZIMU_IMONEimones_kodas' => 'anything',
			'fk_SERVISASserviso_kodas' => 'anything');
		
		// sukuriame laukų validatoriaus objektą
		$validator = new validator($validations, $required, $maxLengths);

		// laukai įvesti be klaidų
		if($validator->validate($_POST)) {
			// suformuojame laukų reikšmių masyvą SQL užklausai
			$data = $validator->preparePostFieldsForSQL();

			if(isset($data['id'])) {
				// redaguojame klientą
				$servicesObj->updateService($data);
			} else {
				// įrašome naują klientą
				$latestId = $servicesObj->getMaxIdOfService();
				$data['id'] = $latestId +1 ;
				
				$servicesObj->insertService($data);
			}

			// nukreipiame vartotoją į klientų puslapį
			header("Location: index.php?module={$module}");
			die();
		}
		else {
			// gauname klaidų pranešimą
			$formErrors = $validator->getErrorHTML();
			// laukų reikšmių kintamajam priskiriame įvestų laukų reikšmes
			$fields = $_POST;
		}
	} else {
		// tikriname, ar nurodytas elemento id. Jeigu taip, išrenkame elemento duomenis ir jais užpildome formos laukus.
		if(!empty($id)) {
			// išrenkame klientą
			$fields = $servicesObj->getService($id);
			
		}
	}
?>
<ul id="pagePath">
	<li><a href="index.php">Pradžia</a></li>
	<li><a href="index.php?module=<?php echo $module; ?>">Servisų saskaitos</a></li>
	<li><?php if(!empty($id)) echo "Sąskaitos redagavimas"; else echo "Nauja sąskaita"; ?></li>
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
			<legend>Sąskaitos informacija</legend>
				<p>
				<label class="field" for="brand">Atliktos paslaugos<?php echo in_array('atliktos_paslaugos', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="brand2" name="atliktos_paslaugos">
					<option value="-1">Pasirinkite paslaugą</option>
					<?php
						
						$brands3 = $servicesObj->getPaslaugos();
						foreach($brands3 as $key => $val) {
							$selected = "";
							if(isset($fields['atliktos_paslaugos']) && $fields['atliktos_paslaugos'] == $val['id_aptarnavimo_pobudis']) {
								$selected = " selected='selected'";
							}
							echo "<option{$selected} value='{$val['id_aptarnavimo_pobudis']}'>{$val['name']}</option>";
						}
						

					?>
						
				</select>
			</p>
			
			
			
			<p>
				<label class="field" for="data">Data<?php echo in_array('data', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="data" name="data" class="textbox-70 date" value="<?php echo isset($fields['data']) ? $fields['data'] : ''; ?>">
				<?php if(key_exists('data', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['data']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="kaina">Kaina<?php echo in_array('kaina', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="kaina" name="kaina" class="textbox-150 " value="<?php echo isset($fields['kaina']) ? $fields['kaina'] : ''; ?>">
				<?php if(key_exists('kaina', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['kaina']} simb.)</span>"; ?>
			</p>
		
		
		</fieldset>
		
		<fieldset>
			<legend>Serviso informacija</legend>
				<p>
				<label class="field" for="brand">Servisas pateikęs sąskaitą<?php echo in_array('fk_SERVISASserviso_kodas', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="brand2" name="fk_SERVISASserviso_kodas">
					<option value="-1">Pasirinkite servisą</option>
					<?php
						
						$brands3 = $servicesObj->getServisai();
						foreach($brands3 as $key => $val) {
							$selected = "";
							if(isset($fields['fk_SERVISASserviso_kodas']) && $fields['fk_SERVISASserviso_kodas'] == $val['id']) {
								$selected = " selected='selected'";
							}
							echo "<option{$selected} value='{$val['id']}'>{$val['imones_pavadinimas']}{$val['adresas']}</option>";
						}
						

					?>
						
				</select>
			</p>
		</fieldset>
		<fieldset>
			<legend>Įmonės informacija</legend>
				<p>
				<label class="field" for="brand">Įmonė, kuriai pateikiama sąskaita<?php echo in_array('fk_PERVEZIMU_IMONEimones_kodas', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="brand2" name="fk_PERVEZIMU_IMONEimones_kodas">
					<option value="-1">Pasirinkite įmonę</option>
					<?php
						
						$brands3 = $servicesObj->getImones();
						foreach($brands3 as $key => $val) {
							$selected = "";
							if(isset($fields['fk_PERVEZIMU_IMONEimones_kodas']) && $fields['fk_PERVEZIMU_IMONEimones_kodas'] == $val['id']) {
								$selected = " selected='selected'";
							}
							echo "<option{$selected} value='{$val['id']}'>{$val['pavadinimas']} {$val['adresas']}</option>";
						}
						

					?>
						
				</select>
			</p>
		</fieldset>
		
		<p class="required-note">* pažymėtus laukus užpildyti privaloma</p>
		<p>
			<input type="submit" class="submit" name="submit" value="Išsaugoti">
		</p>
		<?php if(isset($fields['id'])) { ?>
			<input type="hidden" name="id" value="<?php echo $fields['id']; ?>" />
		<?php } ?>
	</form>
</div>