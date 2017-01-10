  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<?php
	
	include 'libraries/employees.class.php';
	$employeesObj = new employees();
	include 'libraries/uzmokestis.class.php';
	$uzmokestisObj=new uzmokestis();
	$formErrors = null;
	$fields = array();
	
	// nustatome privalomus formos laukus
	$required = array('vardas', 'pavarde','amzius','darbo_patirtis','lytis','tautybe');
	
	// maksimalūs leidžiami laukų ilgiai
	$maxLengths = array (
		'vardas' => 20,
		'pavarde' => 20,
		'amzius' => 2,
		'darbo_patirtis' => 2,
		'lytis' => 8,
		'tautybe' => 20,
		
	);
	
	// vartotojas paspaudė išsaugojimo mygtuką
	if(!empty($_POST['submit'])) {
		
		include 'utils/validator.class.php';
		
		// nustatome laukų validatorių tipus
		$validations = array (
			'vardas' => 'anything',
			'pavarde' => 'alfanum',
			'amzius' => 'positivenumber',
			'darbo_patirtis' => 'positivenumber',
			'lytis' => 'alfanum',
			'tautybe' => 'alfanum',
			'suma'=> 'positivenumber',
			'uz_kuri_menesi'=> 'date',
			'fk_IMONES_PADALINYSpadalinio_numeris' => 'positivenumber',
			'fk_DIENPINIGIAIid_DIENPINIGIAI' => 'positivenumber',
			'fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS' => 'positivenumber');
		
		// sukuriame laukų validatoriaus objektą
		$validator = new validator($validations, $required, $maxLengths);

		// laukai įvesti be klaidų
		if($validator->validate($_POST)) {
			// suformuojame laukų reikšmių masyvą SQL užklausai
			$data = $validator->preparePostFieldsForSQL();

			if(isset($data['id'])) {
				// redaguojame klientą
				$employeesObj->updateEmployee($data);
			
				$uzmokestisObj->insertUzmokestis($data);
				
				
				
			} 
else {
				
				
				$latestId = $employeesObj->getMaxIdOfEmployee();
				$data['id'] = $latestId+1;
				$employeesObj->insertEmployee($data);
				$uzmokestisObj->insertUzmokestis($data);
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
			if(isset($_POST['sumos']) && sizeof($_POST['sumos']) > 0) {
				$i = 0;
				foreach($_POST['sumos'] as $key => $val) {
					$fields['uzmokesciu_list'][$i]['suma'] = $val;
					$fields['uzmokesciu_list'][$i]['uz_kuri_menesi'] = $_POST['datos'][$key];
					$fields['uzmokesciu_list'][$i]['id'] = $_POST['id_uzsak'][$key];
					$fields['uzmokesciu_list'][$i]['neaktyvus'] = $_POST['neaktyvus'][$key];
					$i++;
					echo "'{$val}'";
				}
			}
			
		}
	} else {
		// tikriname, ar nurodytas elemento id. Jeigu taip, išrenkame elemento duomenis ir jais užpildome formos laukus.
		if(!empty($id)) {
			// išrenkame klientą
			$fields = $employeesObj->getEmployee($id);
				$tmp = $uzmokestisObj->getUzmokestisListByID($id);
			
			if(sizeof($tmp) > 0) {
				foreach($tmp as $key => $val) {
//					$val['neaktyvus'] = 1;
					$fields['uzmokesciu_list'][] = $val;	
				}
			
			$fields['editing'] = 1;
		}
	}
	}
?>
<ul id="pagePath">
	<li><a href="index.php">Pradžia</a></li>
	<li><a href="index.php?module=<?php echo $module; ?>">Darbuotojai</a></li>
	<li><?php if(!empty($id)) echo "Darbuotojo redagavimas"; else echo "Naujas darbuotojas"; ?></li>
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
			<legend>Darbuotojo informacija</legend>
			
			<p>
				<label class="field" for="vardas">Vardas<?php echo in_array('vardas', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="vardas" name="vardas" class="textbox-150" value="<?php echo isset($fields['vardas']) ? $fields['vardas'] : ''; ?>" />
				<?php if(key_exists('vardas', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['vardas']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="pavarde">Pavardė<?php echo in_array('pavarde', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="pavarde" name="pavarde" class="textbox-150" value="<?php echo isset($fields['pavarde']) ? $fields['pavarde'] : ''; ?>" />
				<?php if(key_exists('pavarde', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['pavarde']} simb.)</span>"; ?>
			</p>
				
				<p>
				<label class="field" for="amzius">Amžius<?php echo in_array('amzius', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="amzius" name="amzius" class="textbox-150" value="<?php echo isset($fields['amzius']) ? $fields['amzius'] : ''; ?>" />
				<?php if(key_exists('amzius', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['amzius']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="darbo_patirtis">Darbo patirtis<?php echo in_array('darbo_patirtis', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="darbo_patirtis" name="darbo_patirtis" class="textbox-150" value="<?php echo isset($fields['darbo_patirtis']) ? $fields['darbo_patirtis'] : ''; ?>" />
				<?php if(key_exists('darbo_patirtis', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['darbo_patirtis']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="lytis">Lytis<?php echo in_array('lytis', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="lytis" name="lytis" class="textbox-150" value="<?php echo isset($fields['lytis']) ? $fields['lytis'] : ''; ?>" />
				<?php if(key_exists('lytis', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['lytis']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="tautybe">Tautybė<?php echo in_array('tautybe', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="tautybe" name="tautybe" class="textbox-150" value="<?php echo isset($fields['tautybe']) ? $fields['tautybe'] : ''; ?>" />
				<?php if(key_exists('tautybe', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['tautybe']} simb.)</span>"; ?>
			</p>
			<p>																		
				<label class="field" for="brand4">Vilkikų garažas<?php echo in_array('fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="brand4" name="fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS">
					<option value="-1">Pasirinkite vilkikų garažą</option>
					<?php
						
						$brands = $employeesObj->getGarage();
						foreach($brands as $key => $val) {
							$selected = "";
							if(isset($fields['fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS']) && $fields['fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS'] == $val['id']) {
								$selected = " selected='selected'";
							}
							echo "<option{$selected} value='{$val['id']}'>{$val['adresas']}</option>";
						}
					

					?>
						
				</select>
			</p>
			<p>																		
				<label class="field" for="brand4">Padalinio numeris<?php echo in_array('fk_IMONES_PADALINYSpadalinio_numeris', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="brand4" name="fk_IMONES_PADALINYSpadalinio_numeris">
					<option value="-1">Pasirinkite padalinį</option>
					<?php
						
						$brands = $employeesObj->getPadalinys();
						foreach($brands as $key => $val) {
							$selected = "";
							if(isset($fields['fk_IMONES_PADALINYSpadalinio_numeris']) && $fields['fk_IMONES_PADALINYSpadalinio_numeris'] == $val['id']) {
								$selected = " selected='selected'";
							}
							echo "<option{$selected} value='{$val['id']}'>{$val['adresas']}</option>";
						}
				

					?>
						
				</select>
			</p>
			<p>																		
				<label class="field" for="brand4">Dienpinigiai<?php echo in_array('fk_DIENPINIGIAIid_DIENPINIGIAI', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="brand4" name="fk_DIENPINIGIAIid_DIENPINIGIAI">
					<option value="-1">Pasirinkite dienpinigių sumą</option>
					<?php
						
						$brands = $employeesObj->getDienpinigiai();
						foreach($brands as $key => $val) {
							$selected = "";
							if(isset($fields['fk_DIENPINIGIAIid_DIENPINIGIAI']) && $fields['fk_DIENPINIGIAIid_DIENPINIGIAI'] == $val['id']) {
								$selected = " selected='selected'";
							}
							echo "<option{$selected} value='{$val['id']}'>{$val['suma']}</option>";
						}
				

					?>
						
				</select>
			</p>
			
		</fieldset>
		<p class="required-note">* pažymėtus laukus užpildyti privaloma</p>
		<fieldset>
		<legend>Užmokesčiai</legend>
			<div class="childRowContainer">
			<p>
                <div class="labelLeft class="field" for="uz_kuri_menesi">uz_kuri_menesi<?php echo in_array('uz_kuri_menesi', $required) ? '<span> *</span>' : ''; ?></div>
			<div class="labelRight class="field" for="suma">____Suma<?php echo in_array('suma', $required) ? '<span> *</span>' : ''; ?></div>
				<div class="labelRight class="field" for="id">Uzmokestis ID<?php echo in_array('id', $required) ? '<span> *</span>' : ''; ?></div>
			</p>
				<div class="float-clear"></div>
			<p>
			</p>
				
				<?php
					if(empty($fields['uzmokesciu_list']) || sizeof($fields['uzmokesciu_list']) == 0) {
				?>
					<div class="childRow hidden">
						<input type="text" name="datos[]" value="" class="date textbox-70" disabled="disabled" />
						<input type="text" name="sumos[]" value="" class="textbox-70" disabled="disabled" />
						<input type="text" name="id_uzsak[]" value="" class="textbox-70" disabled="disabled" />
						<input type="hidden" class="isDisabledForEditing" name="neaktyvus[]" value="0" />
						<a href="#" title="" class="removeChild">šalinti</a>
					</div>
					<div class="float-clear"></div>
					
								<?php
					} else {
						foreach($fields['uzmokesciu_list'] as $key => $val) {
						?>
						<div class="childRow">
							<input type="text" name="datos[]" value="<?php echo $val['uz_kuri_menesi']; ?>" class="date textbox-70"  />
							<input type="text" name="sumos[]" value="<?php echo $val['suma']; ?>" class="textbox-70" />
							<input type="text" name="id_uzsak[]" value="<?php echo $val['id']; ?>" class="textbox-70<?php if(isset($val['neaktyvus']) && $val['neaktyvus'] == 1) echo ' disabledInput'; ?>" />
							<input type="hidden" class="isDisabledForEditing" name="neaktyvus[]" value="<?php if(isset($val['neaktyvus']) && $val['neaktyvus'] == 1) echo "1"; else echo "0"; ?>" />
							<?php if(key_exists('id', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['id']} simb.)</span>"; ?>
							<a href="#" title="" class="removeChild<?php if(isset($val['neaktyvus']) && $val['neaktyvus'] == 1) echo " hidden"; ?> <?php $uzmokestisObj->deleteSas($val['id']) ?> ">šalinti</a>
							
							
							
							</div>
							<div class="float-clear"></div>
						<?php	
						}
					}
					?>					
							</select>
			</p>
			</div>
			<p id="newItemButtonContainer">
				<a href="#" title="" class="addChild">Pridėti</a>
			</p>
		</fieldset>
		
		<p>
			<input type="submit" class="submit" name="submit" value="Išsaugoti">
		</p>
	</form>
</div>