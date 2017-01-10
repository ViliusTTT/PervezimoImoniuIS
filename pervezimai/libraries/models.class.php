<?php

/**
 * Automobilių modelių redagavimo klasė
 *
 * @author ISK
 */

class models {
	
	public function __construct() {
		
	}
	
	/**
	 * Modelio išrinkimas
	 * @param type $id
	 * @return type
	 */
	public function getModel($id) {
		$query = "  SELECT *
					FROM `modelis`
					WHERE `id`='{$id}'";
		$data = mysql::select($query);
		
		return $data[0];
	}
	
	/**
	 * Modelių sąrašo išrinkimas
	 * @param type $limit
	 * @param type $offset
	 * @return type
	 */
	public function getModelList($limit = null, $offset = null) {
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
		}
		if(isset($offset)) {
			$limitOffsetString .= " OFFSET {$offset}";
		}
		
		$query = "  SELECT `modelis`.`id`,
						   `modelis`.`pavadinimas`,
						    `marke`.`pavadinimas` AS `marke`
					FROM `modelis`
						LEFT JOIN `marke`
							ON `modelis`.`fk_MARKEid_MARKE`=`marke`.`id` LIMIT {$limit} OFFSET {$offset}";
		$data = mysql::select($query);
		
		return $data;
	}

	/**
	 * Modelių kiekio radimas
	 * @return type
	 */
	public function getModelListCount() {
		$query = "  SELECT COUNT(`modelis`.`id`) as `kiekis`
					FROM `modelis`
						LEFT JOIN `marke`
							ON `modelis`.`fk_MARKEid_MARKE`=`marke`.`id`";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
	
	/**
	 * Modelių išrinkimas pagal markę
	 * @param type $brandId
	 * @return type
	 */
	public function getModelListByBrand($brandId) {
		$query = "  SELECT *
					FROM `modelis`
					WHERE `fk_MARKEid_MARKE`='{$brandId}'";
		$data = mysql::select($query);
		
		return $data;
	}
	
	/**
	 * Modelio atnaujinimas
	 * @param type $data
	 */
	public function updateModel($data) {
		$query = "  UPDATE `modelis`
					SET    `pavadinimas`='{$data['pavadinimas']}',
						   `fk_MARKEid_MARKE`='{$data['fk_MARKEid_MARKE']}'
					WHERE `id`='{$data['id']}'";
		mysql::query($query);
	}
	
	/**
	 * Modelio įrašymas
	 * @param type $data
	 */
	public function insertModel($data) {
		$query = "  INSERT INTO `modelis`
								(
									`id`,
									`pavadinimas`,
									`fk_MARKEid_MARKE`
								)
								VALUES
								(
									'{$data['id']}',
									'{$data['pavadinimas']}',
									'{$data['fk_MARKEid_MARKE']}'
								)";
		mysql::query($query);
	}
	
	/**
	 * Modelio šalinimas
	 * @param type $id
	 */
	public function deleteModel($id) {
		$query = "  DELETE FROM `modelis`
					WHERE `id`='{$id}'";
		mysql::query($query);
	}
	
	/**
	 * Nurodyto modelio automobilių kiekio radimas
	 * @param type $id
	 * @return type
	 */
	public function getCarCountOfModel($id) {
		$query = "  SELECT COUNT(`vilkikas`.`VIN`) AS `kiekis`
					FROM `modelis`
						INNER JOIN `vilkikas`
							ON `modelis`.`id`=`vilkikas``.`fk_MODELISid_MODELIS`
					WHERE `modelis`.`id`='{$id}'";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
	
	/**
	 * Didžiausios modelio id reikšmės radimas
	 * @return type
	 */
	public function getMaxIdOfModel() {
		$query = "  SELECT MAX(`id`) AS `latestId`
					FROM `modelis`";
		$data = mysql::select($query);
		
		return $data[0]['latestId'];
	}
	
}