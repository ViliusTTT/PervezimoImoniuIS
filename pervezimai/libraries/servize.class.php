<?php

/**
 * Vilkikų redagavimo klasė
 *
 * @author 
 */

class servizes {

	public function __construct() {
		
	}
	
	/**
	 * Vilkiko išrinkimas
	 * @param type $id
	 * @return type
	 */
	public function getServize($id) {
		$query = "  SELECT *
					FROM `servisas`
					WHERE `id`='{$id}'";
		$data = mysql::select($query);
		
		return $data[0];
	}
	
	/**
	 * Vilkiko atnaujinimas
	 * @param type $data
	 */
	public function updateServize($data) {
		$query = "  UPDATE `servisas`
					SET    `miestas`='{$data['miestas']}',
						   `darbuotoju_skaicius`='{$data['darbuotoju_skaicius']}',
						   `adresas`='{$data['adresas']}',
						   `telefonas`='{$data['telefonas']}',
						   `imones_pavadinimas`='{$data['imones_pavadinimas']}',
						   `paslauga`='{$data['paslauga']}'
					WHERE `servisas`.`id`='{$data['id']}'";
		mysql::query($query);
	}

	/**
	 * Vilkiko įrašymas
	 * @param type $data
	 */
	public function insertServize($data) {
		$query = "  INSERT INTO `servisas` 
								(
									`miestas`,
									`darbuotoju_skaicius`,
									`adresas`,
									`telefonas`,
									`imones_pavadinimas`,
									`id`,
									`paslauga`
									
								) 
								VALUES
								(
									'{$data['miestas']}',
									'{$data['darbuotoju_skaicius']}',
									'{$data['adresas']}',
									'{$data['telefonas']}',
									'{$data['imones_pavadinimas']}',
									'{$data['id']}',
									'{$data['paslauga']}'
								)";
		mysql::query($query);
	}
	
	/**
	 * Vilkikų sąrašo išrinkimas
	 * @param type $limit
	 * @param type $offset
	 * @return type
	 */
	public function getServizeList($limit = null, $offset = null) {
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
		}
		if(isset($offset)) {
			$limitOffsetString .= " OFFSET {$offset}";
		}
		
		$query = "  SELECT 
					`servisas`.`miestas`,
					`servisas`.`darbuotoju_skaicius`,
					`servisas`.`adresas`,
					`servisas`.`telefonas`,
					`servisas`.`imones_pavadinimas`,
					`servisas`.`id`,
					`aptarnavimo_pobudis`.`name` AS `paslauga`
										
					FROM `servisas`
						LEFT JOIN `aptarnavimo_pobudis`
							ON `servisas`.`paslauga`=`aptarnavimo_pobudis`.`id`
					 LIMIT {$limit} OFFSET {$offset}";
						
		$data = mysql::select($query);
		
		return $data;
	}
	
	/**
	 * Vilkikų kiekio radimas
	 * @return type
	 */
	 public function getServizeListCount(){
		$query = "  SELECT COUNT(`servisas`.`id`) AS `kiekis`
					FROM `servisas`
						LLEFT JOIN `aptarnavimo_pobudis`
							ON `servisas`.`paslauga`=`aptarnavimo_pobudis`.`id`";
								$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
	
	/**
	 * Automobilio šalinimas
	 * @param type $id
	 */
	public function deleteServize($id) {
		$query = "  DELETE FROM `servisas`
					WHERE `id`='{$id}'";
		mysql::query($query);
	}
	
	 
	 
	/**
	 * Didžiausios automobilio id reikšmės radimas
	 * @return type
	 */
	public function getMaxIdOfServize() {
		$query = "  SELECT MAX(`id`) AS `latestId`
					FROM `servisas`";
		$data = mysql::select($query);
		
		return $data[0]['latestId'];
	}
	
	
	
			/**
	 * Modelių sąrašo išrinkimas
	 * @return type
	 */
	public function getPobudziai() {
		$query = "  SELECT *
					FROM `aptarnavimo_pobudis`";
		$data = mysql::select($query);
		
		return $data;
	}
		public function getTruckCount($id) {
		$query = "  SELECT COUNT(`vilkikas`.`id`) AS `kiekis`
					FROM `vilkiku_garazas`
						INNER JOIN `vilkikas`
							ON `vilkiku_garazas`.`id`=`vilkikas`.`fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS`
					WHERE `vilkiku_garazas``.`id`='{$id}'";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
}