  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<?php

	include 'libraries/money.class.php';
	$garagesObj = new money();
	
	$formErrors = null;
	$fields = array();
	
	// nustatome privalomus laukus
	$required = array('suma','data','islaidu_pobudis');
	// maksimalūs leidžiami laukų ilgiai
	$maxLengths = array (
		'suma' => 8,
		'data' => 15,
		'islaidu_pobudis' => 25,
		'fk_IMONES_PADALINYSpadalinio_numeris' => 6
	);
	
	
	// paspaustas išsaugojimo mygtukas
	if(!empty($_POST['submit'])) {
		// nustatome laukų validatorių tipus
		$validations = array (
			'suma' => 'anything',
			'data' => 'date',
			'islaidu_pobudis' => 'anything',
			'fk_IMONES_PADALINYSpadalinio_numeris' => 'int'
			);
		// sukuriame validatoriaus objektą
		include 'utils/validator.class.php';
		$validator = new validator($validations, $required, $maxLengths);

		if($validator->validate($_POST)) {
			// suformuojame laukų reikšmių masyvą SQL užklausai
			$data = $validator->preparePostFieldsForSQL();
			if(isset($data['id'])) {
				// atnaujiname duomenis
				$garagesObj->updateMoney($data);
			} else {
				// randame didžiausią markės id duomenų bazėje
				$latestId = $garagesObj->getMaxIdOfMoney();
				
				
				$data['id'] = $latestId+1;
				$garagesObj->insertMoney($data);
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
			$fields = $garagesObj->getMoney($id);
		}
	}
?>
<ul id="pagePath">
	<li><a href="index.php">Pradžia</a></li>
	<li><a href="index.php?module=<?php echo $module; ?>">Dienpinigiai </a></li>
	<li><?php if(!empty($id)) echo "Dienpinigių redagavimas"; else echo "Naujas pavedimas"; ?></li>
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
			<legend>Dienpinigių informacija</legend>
			
			<p>
				<label class="field" for="name">Suma<?php echo in_array('suma', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="name" name="suma" class="textbox-150" value="<?php echo isset($fields['suma']) ? $fields['suma'] : ''; ?>">
				<?php if(key_exists('suma', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['suma']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="data">Data<?php echo in_array('data', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="data" name="data" class="date textbox-70" value="<?php echo isset($fields['data']) ? $fields['data'] : ''; ?>">
			</p>
			<p>
				<label class="field" for="brand1">Padalinys<?php echo in_array('fk_IMONES_PADALINYSpadalinio_numeris', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="brand1" name="fk_IMONES_PADALINYSpadalinio_numeris">
					<option value="-1">Pasirinkite padalinį</option>
					<?php
						// išrenkame visas markes
						$brands = $garagesObj->getPadaliniai();
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
			<p>
				<label class="field" for="brand1">Išlaidų pobūdis<?php echo in_array('islaidu_pobudis', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="brand1" name="islaidu_pobudis">
					<option value="-1">Pasirinkite pobūdį</option>
					<?php
						// išrenkame visas markes
						$brands = $garagesObj->getIslaidos();
						foreach($brands as $key => $val) {
							$selected = "";
							if(isset($fields['islaidu_pobudis']) && $fields['islaidu_pobudis'] == $val['id']) {
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