  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<?php

	include 'libraries/companies.class.php';
	$compObj = new companies();

	$formErrors = null;
	$fields = array();
	
	// nustatome privalomus laukus
	$required = array('pavadinimas', 'apyvarta','padaliniu_skaicius','pelnas','el_pastas','telefonas','adresas','imones_patikimumas');

	// maksimalūs leidžiami laukų ilgiai
	$maxLengths = array (
		'pavadinimas' => 35,
		'apyvarta' => 10,
		'padaliniu_skaicius' => 5,
		'pelnas' => 7,
		'el_pastas' => 35,
		'telefonas' => 9,
		'adresas' => 35,
		'imones_patikimumas' => 5
	);
	
	// paspaustas išsaugojimo mygtukas
	if(!empty($_POST['submit'])) {
		// nustatome laukų validatorių tipus
		$validations = array (
			'pavadinimas' => 'anything',
			'apyvarta' => 'positivenumber',
			'padaliniu_skaicius' => 'positivenumber',
			'pelnas' => 'anything',
			'el_pastas' => 'anything',
			'telefonas' => 'positivenumber',
			'adresas' => 'anything',
			'imones_patikimumas' => 'positivenumber'
			);
		
		// sukuriame validatoriaus objektą
		include 'utils/validator.class.php';
		$validator = new validator($validations, $required, $maxLengths);
		
		// laukai įvesti be klaidų
		if($validator->validate($_POST)) {
			// suformuojame laukų reikšmių masyvą SQL užklausai
			$data = $validator->preparePostFieldsForSQL();
			if(isset($data['id'])) {
				// atnaujiname duomenis
				$compObj->updateCompany($data);
			} else {
				// randame didžiausią modelio id duomenų bazėje
				$latestId = $compObj->getMaxIdOfCompany();

				// įrašome naują įrašą
				$data['id'] = $latestId + 1;
				$compObj->insertCompany($data);
			}
			
			// nukreipiame į modelių puslapį
			header("Location: index.php?module={$module}");
			die();
		} else {
			// gauname klaidų pranešimą
			$formErrors = $validator->getErrorHTML();
			// gauname įvestus laukus
			$fields = $_POST;
		}
	} else {
		// tikriname, ar nurodytas elemento id. Jeigu taip, išrenkame elemento duomenis ir jais užpildome formos laukus.
		if(!empty($id)) {
			$fields = $compObj->getCompany($id);
		}
	}
?>
<ul id="pagePath">
	<li><a href="index.php">Pradžia</a></li>
	<li><a href="index.php?module=<?php echo $module; ?>">Pervežimų įmonės
	</a></li>
	<li><?php if(!empty($id)) echo "Modelio redagavimas"; else echo "Naujas modelis"; ?></li>
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
			<legend>Įmonės informacija</legend>
			<p>
			<label class="field" for="name">Pavadinimas<?php echo in_array('pavadinimas', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="name" name="pavadinimas" class="textbox-150" value="<?php echo isset($fields['pavadinimas']) ? $fields['pavadinimas'] : ''; ?>">
				<?php if(key_exists('pavadinimas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['pavadinimas']} simb.)</span>"; ?>
			</p>
			<p>
			<label class="field" for="name">Apyvarta<?php echo in_array('apyvarta', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="name" name="apyvarta" class="textbox-150" value="<?php echo isset($fields['apyvarta']) ? $fields['apyvarta'] : ''; ?>">
				<?php if(key_exists('apyvarta', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['apyvarta']} simb.)</span>"; ?>
			</p>
			<p>
			<label class="field" for="name">Padalinių skaičius<?php echo in_array('padaliniu_skaicius', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="name" name="padaliniu_skaicius" class="textbox-150" value="<?php echo isset($fields['padaliniu_skaicius']) ? $fields['padaliniu_skaicius'] : ''; ?>">
				<?php if(key_exists('padaliniu_skaicius', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['padaliniu_skaicius']} simb.)</span>"; ?>
			</p>
			<p>
			<label class="field" for="name">Pelnas<?php echo in_array('pelnas', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="name" name="pelnas" class="textbox-150" value="<?php echo isset($fields['pelnas']) ? $fields['pelnas'] : ''; ?>">
				<?php if(key_exists('pelnas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['pelnas']} simb.)</span>"; ?>
			</p>
			<p>
			<label class="field" for="name">El paštas<?php echo in_array('el_pastas', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="name" name="el_pastas" class="textbox-150" value="<?php echo isset($fields['el_pastas']) ? $fields['el_pastas'] : ''; ?>">
				<?php if(key_exists('el_pastas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['el_pastas']} simb.)</span>"; ?>
			</p>
			<p>
			<label class="field" for="name">Telefonas<?php echo in_array('telefonas', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="name" name="telefonas" class="textbox-150" value="<?php echo isset($fields['telefonas']) ? $fields['telefonas'] : ''; ?>">
				<?php if(key_exists('telefonas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['telefonas']} simb.)</span>"; ?>
			</p>
			<p>
			<label class="field" for="name">Adresas<?php echo in_array('adresas', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="name" name="adresas" class="textbox-150" value="<?php echo isset($fields['adresas']) ? $fields['adresas'] : ''; ?>">
				<?php if(key_exists('adresas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['adresas']} simb.)</span>"; ?>
			</p>
			<p>
			<label class="field" for="name">Įmonės patikimumas<?php echo in_array('imones_patikimumas', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="name" name="imones_patikimumas" class="textbox-150" value="<?php echo isset($fields['imones_patikimumas']) ? $fields['imones_patikimumas'] : ''; ?>">
				<?php if(key_exists('imones_patikimumas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['imones_patikimumas']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="brand">Veiklos pobūdis<?php echo in_array('veiklos_pobudis', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="brand" name="veiklos_pobudis">
					<option value="-1">Pasirinkite veiklos pobūdį</option>
					<?php
						// išrenkame visas markes
						$brands = $compObj->getVeiklosPobudziai();
						foreach($brands as $key => $val) {
							$selected = "";
							if(isset($fields['veiklos_pobudis']) && $fields['veiklos_pobudis'] == $val['id']) {
								$selected = " selected='selected'";
							}
							echo "<option{$selected} value='{$val['id']}'>{$val['name']}</option>";
						}
						

					?>
						
				</select>
			</p>
			<label class="field" for="brand2">Veiklos sritis<?php echo in_array('veiklos_sritis', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="brand2" name="veiklos_sritis">
					<option value="-1">Pasirinkite veiklos sritį</option>
					<?php
						
						$brands = $compObj->getVeiklosSritys();
						foreach($brands as $key => $val) {
							$selected = "";
							if(isset($fields['veiklos_sritis']) && $fields['veiklos_sritis'] == $val['id']) {
								$selected = " selected='selected'";
							}
							echo "<option{$selected} value='{$val['id']}'>{$val['name']}</option>";
						}
						?>
						
				</select>
			<p>
				
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