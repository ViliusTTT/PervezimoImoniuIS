  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<?php

	include 'libraries/garages.class.php';
	$garagesObj = new garages();
	
	$formErrors = null;
	$fields = array();
	
	// nustatome privalomus laukus
	$required = array('vilkiku_skaicius','miestas','adresas','telefonas','fk_IMONES_PADALINYSpadalinio_numeris');
	// maksimalūs leidžiami laukų ilgiai
	$maxLengths = array (
		'vilkiku_skaicius' => 3,
		'miestas' => 15,
		'adresas' => 25,
		'telefonas' => 8,
		'fk_IMONES_PADALINYSpadalinio_numeris' => 6
	);
	
	
	// paspaustas išsaugojimo mygtukas
	if(!empty($_POST['submit'])) {
		// nustatome laukų validatorių tipus
		$validations = array (
			'vilkiku_skaicius' => 'positivenumber',
			'miestas' => 'alfanum',
			'adresas' => 'anything',
			'telefonas' => 'int',
			'fk_IMONES_PADALINYSpadalinio_numeris' => 'int',
			'id' => 'int'
			);
		// sukuriame validatoriaus objektą
		include 'utils/validator.class.php';
		$validator = new validator($validations, $required, $maxLengths);

		if($validator->validate($_POST)) {
			// suformuojame laukų reikšmių masyvą SQL užklausai
			$data = $validator->preparePostFieldsForSQL();
			if(isset($data['id'])) {
				// atnaujiname duomenis
				$garagesObj->updateGarage($data);
			} else {
				// randame didžiausią markės id duomenų bazėje
				$latestId = $garagesObj->getMaxIdOfGarage();
				
				
				$data['id'] = $latestId+1;
				$garagesObj->insertGarage($data);
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
			$fields = $garagesObj->getGarage($id);
		}
	}
?>
<ul id="pagePath">
	<li><a href="index.php">Pradžia</a></li>
	<li><a href="index.php?module=<?php echo $module; ?>">Garažai </a></li>
	<li><?php if(!empty($id)) echo "Garažo redagavimas"; else echo "Naujas garažas"; ?></li>
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
			<legend>Garažo informacija</legend>
			
			<p>
				<label class="field" for="name">Vilkikų vietų skaičius<?php echo in_array('valstybinis_nr', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="name" name="vilkiku_skaicius" class="textbox-150" value="<?php echo isset($fields['vilkiku_skaicius']) ? $fields['vilkiku_skaicius'] : ''; ?>">
				<?php if(key_exists('vilkiku_skaicius', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['vilkiku_skaicius']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="miestas">Miestas<?php echo in_array('miestas', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="miestas" name="miestas" class="textbox-150" value="<?php echo isset($fields['miestas']) ? $fields['miestas'] : ''; ?>">
			</p>
			<p>
				<label class="field" for="name">Adresas<?php echo in_array('adresas', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="adresas" name="adresas" class="textbox-150" value="<?php echo isset($fields['adresas']) ? $fields['adresas'] : ''; ?>">
				<?php if(key_exists('adresas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['adresas']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="name">Telefonas<?php echo in_array('telefonas', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="name" name="telefonas" class="textbox-150" value="<?php echo isset($fields['telefonas']) ? $fields['telefonas'] : ''; ?>">
				<?php if(key_exists('telefonas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['telefonas']} simb.)</span>"; ?>
			</p>
			
			<p>
				<label class="field" for="brand1">Padalinys<?php echo in_array('fk_IMONES_PADALINYSpadalinio_numeris', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="brand1" name="fk_IMONES_PADALINYSpadalinio_numeris">
					<option value="-1">Pasirinkite padalinį</option>
					<?php
						// išrenkame visas markes
						$brands = $garagesObj->getBranch();
						foreach($brands as $key => $val) {
							$selected = "";
							if(isset($fields['fk_IMONES_PADALINYSpadalinio_numeris']) && $fields['fk_IMONES_PADALINYSpadalinio_numeris'] == $val['id']) {
								$selected = " selected='selected'";
							}
							echo "<option{$selected} value='{$val['id']}'>{$val['adresas']} {$val['miestas']}</option>";
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