<?php

/**
 * Automobilių markių redagavimo klasė
 *
 * @author ISK
 */

class brands {

	public function __construct() {
		
	}
	
	/**
	 * Markės išrinkimas
	 * @param type $id
	 * @return type
	 */
	public function getBrand($id) {
		$query = "  SELECT *
					FROM `marke`
					WHERE `id`='{$id}'";
		$data = mysql::select($query);
		
		return $data[0];
	}
	
	/**
	 * Markių sąrašo išrinkimas
	 * @param type $limit
	 * @param type $offset
	 * @return type
	 */
	public function getBrandList($limit = null, $offset = null) {
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
		}
		if(isset($offset)) {
			$limitOffsetString .= " OFFSET {$offset}";
		}
		
		$query = "  SELECT *
					FROM `marke`" . $limitOffsetString;
		$data = mysql::select($query);
		
		return $data;
	}

	/**
	 * Markių kiekio radimas
	 * @return type
	 */
	public function getBrandListCount() {
		$query = "  SELECT COUNT(`id`) as `kiekis`
					FROM `marke`";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
	
	/**
	 * Markės įrašymas
	 * @param type $data
	 */
	public function insertBrand($data) {
		$query = "  INSERT INTO `marke`
								(
								`id`,
								`pavadinimas`,
								`salis`
								)
								VALUES
								(
									'{$data['id']}',
									'{$data['pavadinimas']}',
									'{$data['salis']}'										
								)";
		mysql::query($query);
	}
	
	/**
	 * Markės atnaujinimas
	 * @param type $data
	 */
	public function updateBrand($data) {
				$query = "  UPDATE `marke`
					SET    `pavadinimas`='{$data['pavadinimas']}',
						   `salis`='{$data['salis']}'
					WHERE `id`='{$data['id']}'";
		mysql::query($query);
	}
	
	
	/**
	 * Markės šalinimas
	 * @param type $id
	 */
	public function deleteBrand($id) {
		$query = "  DELETE FROM `marke`
					WHERE `id`='{$id}'";
		mysql::query($query);
	}
	
	/**
	 * Markės modelių kiekio radimas
	 * @param type $id
	 * @return type
	 */
	public function getModelCountOfBrand($id) {
		$query = "  SELECT COUNT(`modelis`.`id`) AS `kiekis`
					FROM `marke`
						INNER JOIN `modelis`
							ON `marke`.`id`=`modelis`.`fk_MARKEid_MARKE`
					WHERE `marke`.`id`='{$id}'";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
	
	
	/**
	 * Didžiausiausios markės id reikšmės radimas
	 * @return type
	 */
	public function getMaxIdOfBrand() {
		$query = "  SELECT MAX(`id`) AS `latestId`
					FROM `marke`";
		$data = mysql::select($query);
		
		return $data[0]['latestId'];
	}
	
}