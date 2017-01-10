  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<?php

	include 'libraries/trucks.class.php';
	$trucksObj = new trucks();
	
	include 'libraries/degaline.class.php';
	$degalineObj = new degaline();
	$formErrors = null;
	$fields = array();
	
	// nustatome privalomus laukus
	$required = array('pavaru_dezes','ekonomiskumo_kategorija','degalu_tipai','modelis','valstybinis_nr', 'pagaminimo_data','rida','mase_be_krovinio','mase_su_kroviniu','verte','kebulo_ilgis','miegamu_vietu_skaicius','bako_talpa','asiu_skaicius','bukle','spalva','aukstis','Degalai','variklio_ekonomiskumo_kategorija','pavaru_deze','fk_saskaita','fk_DARBUOTOJASasmens_kodas','fk_MODELISid_MODELIS');
	// maksimalūs leidžiami laukų ilgiai
	$maxLengths = array (
		'valstybinis_nr' => 6,
		'rida' => 7,
		'mase_be_krovinio' => 8,
		'mase_su_kroviniu' => 8,
		'verte' => 6,
		'kebulo_ilgis'=> 3,
		'miegamu_vietu_skaicius'=> 2,
		'bako_talpa' => 4,
		'asiu_skaicius' => 3,
		'bukle' => 10,
		'spalva' => 15,
		'aukstis' => 2
		
	);
	
	
	// paspaustas išsaugojimo mygtukas
	if(!empty($_POST['submit'])) {
		// nustatome laukų validatorių tipus
		$validations = array (
			'valstybinis_nr' => 'anything',
			'pagaminimo_data' => 'date',
			'rida' => 'positivenumber',
			'mase_be_krovinio' => 'positivenumber',
			'mase_su_kroviniu' => 'positivenumber',
			'spalva' => 'alfanum',
			'fk_MODELISid_MODELIS' => 'anything',
			'fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS' => 'anything',
			'fk_saskaita' => 'positivenumber',
			'fk_DARBUOTOJASasmens_kodas' => 'positivenumber',
			'degalai' =>'alfanum',
			'variklio_ekonomiskumo_kategorija' => 'anything',
			'pavaru_deze'=>'alfanum',
			'verte' => 'positivenumber',
			'kebulo_ilgis' => 'positivenumber',
			'miegamu_vietu_skaicius' => 'positivenumber',
			'bako_talpa' => 'positivenumber',
			'asiu_skaicius' => 'positivenumber',
			'bukle' => 'alfanum',
			'aukstis' => 'anything'
			);
		// sukuriame validatoriaus objektą
		include 'utils/validator.class.php';
		$validator = new validator($validations, $required, $maxLengths);

		if($validator->validate($_POST)) {
			// suformuojame laukų reikšmių masyvą SQL užklausai
			$data = $validator->preparePostFieldsForSQL();
			if(isset($data['id'])) {
				// atnaujiname duomenis
				$trucksObj->updateTruck($data);
				
				$degalineObj->insertDegalines($data);
			} else {
				// randame didžiausią markės id duomenų bazėje
				$latestId = $trucksObj->getMaxIdOfTruck();
				
				
				$data['id'] = $latestId+1;
				$trucksObj->insertTruck($data);
				$degalineObj->insertDegalines($data);
			}
			
			// nukreipiame į markių puslapį
			header("Location: index.php?module={$module}");
			die();
		} else {
			// gauname klaidų pranešimą
			$formErrors = $validator->getErrorHTML();
			// gauname įvestus laukus
			$fields = $_POST;
			
			if(isset($_POST['pavadinimai']) && sizeof($_POST['pavadinimai']) > 0) {
				$i = 0;
				foreach($_POST['pavadinimai'] as $key => $val) {
					$fields['degaliniu_list'][$i]['pavadinimas'] = $val;
					$fields['degaliniu_list'][$i]['kuro_kiekis'] = $_POST['kiekiai'][$key];
					$fields['degaliniu_list'][$i]['id'] = $_POST['id_degalines'][$key];
					$fields['degaliniu_list'][$i]['neaktyvus'] = $_POST['neaktyvus'][$key];
					$i++;
					echo "'{$val}'";
				}
			}
		}
	} else {
		// tikriname, ar nurodytas elemento id. Jeigu taip, išrenkame elemento duomenis ir jais užpildome formos laukus.
		if(!empty($id)) {
			$fields = $trucksObj->getTruck($id);
			$tmp = $degalineObj->getDegalineListByID($id);
			
			if(sizeof($tmp) > 0) {
				foreach($tmp as $key => $val) {
//					$val['neaktyvus'] = 1;
					$fields['degaliniu_list'][] = $val;
				}
				$fields['editing'] = 1;
			}
			
		}
		
		
	}
?>
<ul id="pagePath">
	<li><a href="index.php">Pradžia</a></li>
	<li><a href="index.php?module=<?php echo $module; ?>">Vilkikai </a></li>
	<li><?php if(!empty($id)) echo "Vilkiko redagavimas"; else echo "Naujas vilkikas"; ?></li>
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
			<legend>Vilkiko informacija</legend>
			<p>
				<label class="field" for="brand6">Vilkiko modelis<?php echo in_array('fk_MODELISid_MODELIS', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="brand6" name="fk_MODELISid_MODELIS">
					<option value="-1">Pasirinkite vilkiko modelį</option>
					<?php
						
						$brands = $trucksObj->getModel();
						foreach($brands as $key => $val) {
							$selected = "";
							if(isset($fields['fk_MODELISid_MODELIS']) && $fields['fk_MODELISid_MODELIS'] == $val['id']) {
								$selected = " selected='selected'";
							}
							echo "<option{$selected} value='{$val['id']}'>{$val['pavadinimas']}</option>";
						}
						

					?>
						
				</select>
			</p>
		
			<p>
				<label class="field" for="name">Valstybinis nr.<?php echo in_array('valstybinis_nr', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="name" name="valstybinis_nr" class="textbox-150" value="<?php echo isset($fields['valstybinis_nr']) ? $fields['valstybinis_nr'] : ''; ?>">
				<?php if(key_exists('valstybinis_nr', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['valstybinis_nr']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="pagaminimo_data">Pagaminimo data<?php echo in_array('pagaminimo_data', $required) ? '<span> *</span>' : ''; ?></label>
				<input type="text" id="pagaminimo_data" name="pagaminimo_data" class="date textbox-70" value="<?php echo isset($fields['pagaminimo_data']) ? $fields['pagaminimo_data'] : ''; ?>">
			</p>
			<p>
				<label class="field" for="name">Rida<?php echo in_array('rida', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="name" name="rida" class="textbox-150" value="<?php echo isset($fields['rida']) ? $fields['rida'] : ''; ?>">
				<?php if(key_exists('rida', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['rida']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="name">Masė be krovinio<?php echo in_array('mase_be_krovinio', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="name" name="mase_be_krovinio" class="textbox-150" value="<?php echo isset($fields['mase_be_krovinio']) ? $fields['mase_be_krovinio'] : ''; ?>">
				<?php if(key_exists('mase_be_krovinio', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['mase_be_krovinio']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="name">Masė su kroviniu<?php echo in_array('mase_su_kroviniu', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="name" name="mase_su_kroviniu" class="textbox-150" value="<?php echo isset($fields['mase_su_kroviniu']) ? $fields['mase_su_kroviniu'] : ''; ?>">
				<?php if(key_exists('mase_su_kroviniu', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['mase_su_kroviniu']} simb.)</span>"; ?>
			</p>
			<p>	
				<label class="field" for="name">Vertė <?php echo in_array('verte', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="name" name="verte" class="textbox-150" value="<?php echo isset($fields['verte']) ? $fields['verte'] : ''; ?>">
				<?php if(key_exists('verte', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['verte']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="name">Kėbulo ilgis<?php echo in_array('kebulo_ilgis', $required) ? '<span> </span>' : ''; ?></label>
				
				<input type="text" id="name" name="kebulo_ilgis" class="textbox-150" value="<?php echo isset($fields['kebulo_ilgis']) ? $fields['kebulo_ilgis'] : ''; ?>">
				<?php if(key_exists('kebulo_ilgis', $maxLengths))echo "<span class='max-len'>(iki {$maxLengths['kebulo_ilgis']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="name">Miegamų vietų skaičius <?php echo in_array('miegamu_vietu_skaicius', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="name" name="miegamu_vietu_skaicius" class="textbox-150" value="<?php echo isset($fields['miegamu_vietu_skaicius']) ? $fields['miegamu_vietu_skaicius'] : ''; ?>">
				<?php if(key_exists('miegamu_vietu_skaicius', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['miegamu_vietu_skaicius']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="name">Bako talpa <?php echo in_array('bako_talpa', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="name" name="bako_talpa" class="textbox-150" value="<?php echo isset($fields['bako_talpa']) ? $fields['bako_talpa'] : ''; ?>">
				<?php if(key_exists('bako_talpa', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['bako_talpa']} simb.)</span>"; ?>
			</p>
				
			<p>
				<label class="field" for="name">Ašių skaičius <?php echo in_array('asiu_skaicius', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="name" name="asiu_skaicius" class="textbox-150" value="<?php echo isset($fields['asiu_skaicius']) ? $fields['asiu_skaicius'] : ''; ?>">
				<?php if(key_exists('asiu_skaicius', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['asiu_skaicius']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="name">Būklė <?php echo in_array('bukle', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="name" name="bukle" class="textbox-150" value="<?php echo isset($fields['bukle']) ? $fields['bukle'] : ''; ?>">
				<?php if(key_exists('bukle', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['bukle']} simb.)</span>"; ?>
			</p>
			<p>
				<label class="field" for="name">Spalva <?php echo in_array('spalva', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="name" name="spalva" class="textbox-150" value="<?php echo isset($fields['spalva']) ? $fields['spalva'] : ''; ?>">
				<?php if(key_exists('spalva', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['spalva']} simb.)</span>"; ?>
			</p>
			
			<p>
				<label class="field" for="name">Aukštis <?php echo in_array('aukstis', $required) ? '<span> *</span>' : ''; ?></label>
				
				<input type="text" id="name" name="aukstis" class="textbox-150" value="<?php echo isset($fields['aukstis']) ? $fields['aukstis'] : ''; ?>">
				<?php if(key_exists('aukstis', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['aukstis']} simb.)</span>"; ?>
			</p>
				<p>
				<label class="field" for="brand1">Degalai<?php echo in_array('degalai', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="brand1" name="degalai">
					<option value="-1">Pasirinkite degalų tipą</option>
					<?php
						// išrenkame visas markes
						$brands = $trucksObj->getFuelTypeList();
						foreach($brands as $key => $val) {
							$selected = "";
							if(isset($fields['degalai']) && $fields['degalai'] == $val['id']) {
								$selected = " selected='selected'";
							}
							echo "<option{$selected} value='{$val['id']}'>{$val['name']}</option>";
						}
						

					?>
						
				</select>
			</p>
			<p>
				<label class="field" for="brand">Variklio eko kategorija<?php echo in_array('variklio_ekonomiskumo_kategorija', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="brand2" name="variklio_ekonomiskumo_kategorija">
					<option value="-1">Pasirinkite ekonomiškumo kategoriją</option>
					<?php
						
						$brands3 = $trucksObj->getEngineTypeList();
						foreach($brands3 as $key => $val) {
							$selected = "";
							if(isset($fields['variklio_ekonomiskumo_kategorija']) && $fields['variklio_ekonomiskumo_kategorija'] == $val['id']) {
								$selected = " selected='selected'";
							}
							echo "<option{$selected} value='{$val['id']}'>{$val['name']}</option>";
						}
						

					?>
						
				</select>
			</p>
			
						<p>
				<label class="field" for="brand3">Pavarų dėžės tipas<?php echo in_array('pavaru_deze', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="brand3" name="pavaru_deze">
					<option value="-1">Pasirinkite pavarų dėžės tipą</option>
					<?php
						
						$brands = $trucksObj->getGearboxList();
						foreach($brands as $key => $val) {
							$selected = "";
							if(isset($fields['pavaru_deze']) && $fields['pavaru_deze'] == $val['id']) {
								$selected = " selected='selected'";
							}
							echo "<option{$selected} value='{$val['id']}'>{$val['name']}</option>";
						}
							?>
						
				</select>
						</p>
				<p>																		
				<label class="field" for="brand4">Vilkikų garažas<?php echo in_array('fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="brand4" name="fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS">
					<option value="-1">Pasirinkite vilkikų garažą</option>
					<?php
						
						$brands = $trucksObj->getGarage();
						foreach($brands as $key => $val) {
							$selected = "";
							if(isset($fields['fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS']) && $fields['fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS'] == $val['id']) {
								$selected = " selected='selected'";
							}
							echo "<option{$selected} value='{$val['id']}'>{$val['adresas']} {$val['telefonas']} </option>";
						}
						

				
			

					?>
						
				</select>
			</p>
			
						<p>
				<label class="field" for="brand5">Paslauga atlikta servise<?php echo in_array('fk_saskaita', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="brand5" name="fk_saskaita">
					<option value="-1">Pasirinkite sąskaitą</option>
					<?php
						
						$brands = $trucksObj->getService();
						foreach($brands as $key => $val) {
							$selected = "";
							if(isset($fields['fk_saskaita']) && $fields['fk_saskaita'] == $val['id']) {
								$selected = " selected='selected'";
							}
							echo "<option{$selected} value='{$val['id']}'>{$val['id']} {$val['data']}</option>";
						}
						

					?>
						
				</select>
			</p>
							<p>
				<label class="field" for="brand6">Vairuotojas<?php echo in_array('fk_DARBUOTOJASasmens_kodas', $required) ? '<span> *</span>' : ''; ?></label>
				<select id="brand6" name="fk_DARBUOTOJASasmens_kodas">
					<option value="-1">Pasirinkite darbuotoją</option>
					<?php
						
						$brands = $trucksObj->getDriver();
						foreach($brands as $key => $val) {
							$selected = "";
							if(isset($fields['fk_DARBUOTOJASasmens_kodas']) && $fields['fk_DARBUOTOJASasmens_kodas'] == $val['id']) {
								$selected = " selected='selected'";
							}
							echo "<option{$selected} value='{$val['id']}'>{$val['pavarde']} {$val['vardas']}</option>";
						}
						

					?>
						
				</select>
			</p>
			
		</fieldset>
		<fieldset>
			<legend>Degalinės</legend>
			<div class="childRowContainer">
			<p>
                <div class="labelLeft class="field" for="pavadinimas">Pavadinimas<?php echo in_array('pavadinimas', $required) ? '<span> *</span>' : ''; ?></div>
				<div class="labelRight class="field" for="kuro_kiekis">Kuro kiekis<?php echo in_array('kuro_kiekis', $required) ? '<span> *</span>' : ''; ?></div>
				
				<div class="labelLeft class="field" for="id">id<?php echo in_array('id', $required) ? '<span> *</span>' : ''; ?></div>
			
			<div class="float-clear"></div>
			<p>
			</p>
				
				<?php
					if(empty($fields['degaliniu_list']) || sizeof($fields['degaliniu_list']) == 0) {
				?>
					<div class="childRow hidden">
						<input type="text" name="pavadinimai[]" value="" class="textbox-70" disabled="disabled" />
						<input type="text" name="kiekiai[]" value="" class="textbox-70" disabled="disabled" />
						<input type="text" name="id_degalines[]" value="" class="textbox-70" disabled="disabled" />
						<input type="hidden" class="isDisabledForEditing" name="neaktyvus[]" value="0" />
						<a href="#" title="" class="removeChild">šalinti</a>
					</div>
					<div class="float-clear"></div>
					
								<?php
					} else {
						foreach($fields['degaliniu_list'] as $key => $val) {
						?>
						<div class="childRow">
							<input type="text" name="pavadinimai[]" value="<?php echo $val['pavadinimas']; ?>" class="textbox-70"  />
							<input type="text" name="kiekiai[]" value="<?php echo $val['kuro_kiekis']; ?>" class="textbox-70" />
							<input type="text" name="id_degalines[]" value="<?php echo $val['id']; ?>" class="textbox-70<?php if(isset($val['neaktyvus']) && $val['neaktyvus'] == 1) echo ' disabledInput'; ?>" />
							<input type="hidden" class="isDisabledForEditing" name="neaktyvus[]" value="<?php if(isset($val['neaktyvus']) && $val['neaktyvus'] == 1) echo "1"; else echo "0"; ?>" />
							<?php if(key_exists('id', $maxLengths)) echo "<span class='max-len'>(iki {$maxLengths['id']} simb.)</span>"; ?>
							<a href="#" title="" class="removeChild<?php if(isset($val['neaktyvus']) && $val['neaktyvus'] == 1) echo " hidden"; ?> <?php $degalineObj->deleteSas($val['id']) ?> ">šalinti</a>
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
		
		<p class="required-note">Visus laukus užpildyti privaloma</p>
		<p>
			<input type="submit" class="submit" name="submit" value="Išsaugoti">
		</p>
		
	</form>
</div>