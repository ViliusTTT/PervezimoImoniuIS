  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<?php

	include 'libraries/servize.class.php';
	$garagesObj = new servizes();
	
	$formErrors = null;
	$fields = array();
	
	// nustatome privalomus laukus
	$required = array('miestas','darbuotoju_skaicius','adresas','telefonas','imones_pavadinimas','paslauga');
	// maksimalūs leidžiami laukų ilgiai
	$maxLengths = array (
		'miestas' => 23,
		'darbuotoju_skaicius' => 25,
		'adresas' => 25,
		'telefonas' => 8,
		'imones_pavadinimas' => 28
	);
	
	
	// paspaustas išsaugojimo mygtukas
	if(!empty($_POST['submit'])) {
		// nustatome laukų validatorių tipus
		$validations = array (
			'miestas' => 'alfanum',
			'darbuotoju_skaicius' => 'int',
			'adresas' => 'anything',
			'telefonas' => 'anything',
			'imones_pavadinimas' => 'anything',
			'id' => 'anything',
			'paslauga' => 'anything'
			);
		// sukuriame validatoriaus objektą
		include 'utils/validator.class.php';
		$validator = new validator($validations, $required, $maxLengths);

		if($validator->validate($_POST)) {
			// suformuojame laukų reikšmių masyvą SQL užklausai
			$data = $validator->preparePostFieldsForSQL();
			if(isset($data['id']))
				$garagesObj->updateServize($data);
			 else {
				// randame didžiausią markės id duomenų bazėje
				$latestId = $garagesObj->getMaxIdOfServize();
				
				
				$data['id'] = $latestId+1;
				$garagesObj->insertServize($data);
			}
			
			// nukreipiame į markių puslapį
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
			$fields = $garagesObj->getServize($id);
		}
	}
?>
<ul id="pagePath">
	<li><a href="index.php">Pradžia</a></li>
	<li><a href="index.php?module=<?php echo $module; ?>">Servisai </a></li>
	<li><?php if(!empty($id)) echo "Serviso redagavimas"; else echo "Naujas servisas"; ?></li>
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
			<legend>Serviso informacija</legend>
			
			
			<p>
				<label class="field" for="miestas">Miestas<?php echo in_array('miestas', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="miestas" name="miestas" class="textbox-150" value="<?php echo isset($fields['miestas']) ? $fields['miestas'] : ''; ?>">
			</p>
			<p>
				<label class="field" for="adresas">Adresas<?php echo in_array('miestas', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="name" name="adresas" class="textbox-150" value="<?php echo isset($fields['adresas']) ? $fields['adresas'] : ''; ?>">
				<?php if(key_exists('adresas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['adresas']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="name">Darbuotojų skaičius<?php echo in_array('adresas', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="darbuotoju_skaicius" name="darbuotoju_skaicius" class="textbox-150" value="<?php echo isset($fields['darbuotoju_skaicius']) ? $fields['darbuotoju_skaicius'] : ''; ?>">
				<?php if(key_exists('darbuotoju_skaicius', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['darbuotoju_skaicius']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="name">Telefonas<?php echo in_array('telefonas', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="name" name="telefonas" class="textbox-150" value="<?php echo isset($fields['telefonas']) ? $fields['telefonas'] : ''; ?>">
				<?php if(key_exists('telefonas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['telefonas']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="name">Imonės pavadinimas<?php echo in_array('imones_pavadinimas', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="name" name="imones_pavadinimas" class="textbox-150" value="<?php echo isset($fields['imones_pavadinimas']) ? $fields['imones_pavadinimas'] : ''; ?>">
				<?php if(key_exists('imones_pavadinimas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['imones_pavadinimas']} simb.)</span>"; ?>
			</p>
			
			<p>
				<label class="field" for="brand1">Paslauga<?php echo in_array('paslauga', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="brand1" name="paslauga">
					<option value="-1">Pasirinkite paslaugą</option>
					<?php
						// išrenkame visas markes
						$brands = $garagesObj->getPobudziai();
						foreach($brands as $key => $val) {
							$selected = "";
							if(isset($fields['paslauga']) && $fields['paslauga'] == $val['id']) {
								$selected = " selected='selected'";
							}
							echo "<option{$selected} value='{$val['id']}'>{$val['name']} </option>";
						}
						
					?>
						
				</select>
			</p>
					
			
		</fieldset>
		<p class="required-note">Visus laukus užpildyti privaloma</p>
		<p>
			<input type="submit" class="submit" name="submit" value="Išsaugoti">
		</p>
		<?php if(isset($fields['id'])) { ?>
			<input type="hidden" name="id" value="<?php echo $fields['id']; ?>" />
		<?php } ?>
	</form>
</div>