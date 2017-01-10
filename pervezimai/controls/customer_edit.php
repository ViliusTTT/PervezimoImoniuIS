  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<?php
	
	include 'libraries/customers.class.php';
	$customersObj = new customers();

	$formErrors = null;
	$fields = array();
	
	// nustatome privalomus formos laukus
	$required = array('adresas', 'pavadinimas', 'el_pastas','telefonas','id');
	
	// maksimalūs leidžiami laukų ilgiai
	$maxLengths = array (
		'adresas' => 40,
		'pavadinimas' => 30,
		'el_pastas' => 30,
		'telefonas' => 10,
                'id' => 5
	);
	
	// vartotojas paspaudė išsaugojimo mygtuką
	if(!empty($_POST['submit'])) {
		include 'utils/validator.class.php';
		
		// nustatome laukų validatorių tipus
		$validations = array (
			'adresas' => 'anything',
			'pavadinimas' => 'anything',
			'el_pastas' => 'anything',
			'telefonas' => 'positivenumber',
			'id' => 'int'
					);
		
		// sukuriame laukų validatoriaus objektą
		$validator = new validator($validations, $required, $maxLengths);

		// laukai įvesti be klaidų
		if($validator->validate($_POST)) {
			// suformuojame laukų reikšmių masyvą SQL užklausai
			$data = $validator->preparePostFieldsForSQL();
			if(isset($data['id'])) {
				// redaguojame klientą
				$customersObj->updateCustomer($data);
			} else {
				// įrašome naują klientą
				$latestId = $customersObj->getMaxIdOfCustomers();
				$data['id'] = $latestId+1;
				
				
				$customersObj->insertCustomer($data);
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
			$fields = $customersObj->getCustomer($id);
			
		}
	}
?>
<ul id="pagePath">
	<li><a href="index.php">Pradžia</a></li>
	<li><a href="index.php?module=<?php echo $module; ?>">Užsakovai</a></li>
	<li><?php if(!empty($id)) echo "Užsakovo redagavimas"; else echo "Naujas užsakovas"; ?></li>
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
			<legend>Užsakovo informacija</legend>
				
			<p>
				<label class="field" for="adresas">Adresas<?php echo in_array('adresas', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="adresas" name="adresas" class="textbox-150" value="<?php echo isset($fields['adresas']) ? $fields['adresas'] : ''; ?>" />
				<?php if(key_exists('adresas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['adresas']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="pavadinimas">Pavadinimas<?php echo in_array('pavadinimas', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="pavadinimas" name="pavadinimas" class="textbox-150" value="<?php echo isset($fields['pavadinimas']) ? $fields['pavadinimas'] : ''; ?>" />
				<?php if(key_exists('pavadinimas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['pavadinimas']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="el_pastas">El paštas<?php echo in_array('el_pastas', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="el_pastas" name="el_pastas" class="textbox-150" value="<?php echo isset($fields['el_pastas']) ? $fields['el_pastas'] : ''; ?>" />
			</p>
			<p>
				<label class="field" for="telefonas">Telefonas<?php echo in_array('telefonas', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="telefonas" name="telefonas" class="textbox-150" value="<?php echo isset($fields['telefonas']) ? $fields['telefonas'] : ''; ?>" />
			</p>
			
		</fieldset>
		<p class="required-note">* pažymėtus laukus užpildyti privaloma</p>
		<p>
			<input type="submit" class="submit" name="submit" value="Išsaugoti">
		</p>
	</form>
</div>