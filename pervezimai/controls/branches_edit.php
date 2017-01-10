  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<?php
	
	include 'libraries/branches.class.php';
	$branchesObj = new branches();

	
	$formErrors = null;
	$fields = array();
	
	// nustatome privalomus laukus
	$required = array('miestas','apyvarta','darbuotoju_skaicius','adresas','isikurimo_data','fk_PERVEZIMU_IMONEimones_kodas');
	
	// maksimalūs leidžiami laukų ilgiai
	$maxLengths = array (
		'miestas' => 30,
		'apyvarta' => 10,
		'darbuotoju_skaicius' => 5,
		'adresas' => 25,
		
		
	);
	
	// paspaustas išsaugojimo mygtukas
	if(!empty($_POST['submit'])) {
		// nustatome laukų validatorių tipus
		$validations = array (
			'miestas' => 'alfanum',
			'apyvarta' => 'anything',
			'darbuotoju_skaicius' => 'anything',
			'adresas' => 'anything',
			'isikurimo_data' => 'date',
			'id' => 'anything',
			'fk_PERVEZIMU_IMONEimones_kodas' => 'anything');
		
		// sukuriame validatoriaus objektą
		include 'utils/validator.class.php';
		$validator = new validator($validations, $required, $maxLengths);
		
		// laukai įvesti be klaidų
		if($validator->validate($_POST)) {
			// suformuojame laukų reikšmių masyvą SQL užklausai
			$data = $validator->preparePostFieldsForSQL();
			if(isset($data['id'])) {
				// atnaujiname duomenis
				$branchesObj->updateBranch($data);
							
						
			} else {
				// randame didžiausią paslaugos numeri duomenų bazėje
				$latestId = $branchesObj->getMaxIdOfBranches();
				
				// įrašome naują įrašą
				$data['id'] = $latestId + 1;
				$branchesObj->insertBranch($data);

				
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
			$fields = $branchesObj->getBranch($id);
			
				}
			}
		
	
?>
<ul id="pagePath">
	<li><a href="index.php">Pradžia</a></li>
	<li><a href="index.php?module=<?php echo $module; ?>">Įmonės padaliniai</a></li>
	<li><?php if(!empty($id)) echo "Padalinio redagavimas"; else echo "Naujas padalinys"; ?></li>
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
			<legend>Padalinio informacija</legend>
			<p>
				<label class="field" for="name">Miestas<?php echo in_array('miestas', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="name" name="miestas" class="textbox-150" value="<?php echo isset($fields['miestas']) ? $fields['miestas'] : ''; ?>">
				<?php if(key_exists('miestas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['miestas']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="name">Apyvarta<?php echo in_array('apyvarta', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="name" name="apyvarta" class="textbox-150" value="<?php echo isset($fields['apyvarta']) ? $fields['apyvarta'] : ''; ?>">
				<?php if(key_exists('apyvarta', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['apyvarta']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="name">Darbuotojų skaičius<?php echo in_array('darbuotoju_skaicius', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="name" name="darbuotoju_skaicius" class="textbox-150" value="<?php echo isset($fields['darbuotoju_skaicius']) ? $fields['darbuotoju_skaicius'] : ''; ?>">
				<?php if(key_exists('darbuotoju_skaicius', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['miestas']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="name">Adresas<?php echo in_array('adresas', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="name" name="adresas" class="textbox-150" value="<?php echo isset($fields['adresas']) ? $fields['adresas'] : ''; ?>">
				<?php if(key_exists('adresas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['adresas']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="name">Įsikūrimo data<?php echo in_array('isikurimo_data', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="name" name="isikurimo_data" class="date textbox-70" value="<?php echo isset($fields['isikurimo_data']) ? $fields['isikurimo_data'] : ''; ?>">
				<?php if(key_exists('isikurimo_data', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['isikurimo_data']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="brand1">Priklauso įmonei<?php echo in_array('fk_PERVEZIMU_IMONEimones_kodas', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="brand1" name="fk_PERVEZIMU_IMONEimones_kodas">
					<option value="-1">Pasirinkite įmonę</option>
					<?php
						// išrenkame visas markes
						$brands = $branchesObj->getCompanies();
						foreach($brands as $key => $val) {
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