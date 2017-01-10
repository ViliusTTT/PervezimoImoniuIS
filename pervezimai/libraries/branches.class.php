<?php

/**
 * Papildomų paslaugų redagavimo klasė
 *
 * @author ISK
 */

class branches {
	
	public function __construct() {
		
	}
	
	/**
	 * Paslaugų sąrašo išrinkimas
	 * @param type $limit
	 * @param type $offset
	 * @return type
	 */
	public function getBranchesList($limit = null, $offset = null) {
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
		}
		if(isset($offset)) {
			$limitOffsetString .= " OFFSET {$offset}";
		}
		
		$query = "  SELECT `imones_padalinys`.`miestas`,
							`imones_padalinys`.`apyvarta`,
							`imones_padalinys`.`darbuotoju_skaicius`,
							`imones_padalinys`.`adresas`,
							`imones_padalinys`.`isikurimo_data`,
							`imones_padalinys`.`id`,
							 `pervezimu_imone`.`pavadinimas` AS `Imone`
					FROM `imones_padalinys`
					LEFT JOIN `pervezimu_imone`
						ON `imones_padalinys`.`fk_PERVEZIMU_IMONEimones_kodas`=`pervezimu_imone`.`id`
					ORDER BY `id` asc LIMIT {$limit} OFFSET {$offset};";
							$data = mysql::select($query);
		
		return $data;
	}
	
	/**
	 * Paslaugų kiekio radimas
	 * @return type
	 */
	public function getBranchesListCount() {
		$query = "  SELECT COUNT(`id`) as `kiekis`
					FROM `imones_padalinys`";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
	
	/**
	 * imones_padalinys kainų sąrašo radimas
	 * @param type $serviceId
	 * @return type
	 */
	public function getCompanies() {
		$query = "  SELECT *
					FROM `pervezimu_imone`
					";
		$data = mysql::select($query);
		
		return $data;
	}
	/**
	 * imones_padalinys kainų sąrašo radimas
	 * @param type $serviceId
	 * @return type
	 */

	
	
	/**
	 * imones_padalinys išrinkimas
	 * @param type $id
	 * @return type
	 */
	public function getBranch($id) {
		$query = "  SELECT *
					FROM `imones_padalinys`
					WHERE `id`='{$id}'";
		$data = mysql::select($query);
		
		return $data[0];
	}
	
	/**
	 * imones_padalinys įrašymas
	 * @param type $data
	 */
	public function insertBranch($data) {
		$query = "  INSERT INTO `imones_padalinys`
								(
									`id`,
									`miestas`,
									`apyvarta`,
									`darbuotoju_skaicius`,
									`adresas`,
									`isikurimo_data`,
									`fk_PERVEZIMU_IMONEimones_kodas`
								)
								VALUES
								(
									'{$data['id']}',
									'{$data['miestas']}',
									'{$data['apyvarta']}',
									'{$data['darbuotoju_skaicius']}',
									'{$data['adresas']}',
									'{$data['isikurimo_data']}',
									'{$data['fk_PERVEZIMU_IMONEimones_kodas']}'
								)";
		mysql::query($query);
	}
	
	/**
	 * imones_padalinys atnaujinimas
	 * @param type $data
	 */
	public function updateBranch($data) {
		$query = "  UPDATE `imones_padalinys`
					SET    `miestas`='{$data['miestas']}',
						   `apyvarta`='{$data['apyvarta']}',
						   `darbuotoju_skaicius`='{$data['darbuotoju_skaicius']}',
						   `adresas`='{$data['adresas']}',
						   `isikurimo_data`='{$data['isikurimo_data']}',
						   `fk_PERVEZIMU_IMONEimones_kodas`='{$data['fk_PERVEZIMU_IMONEimones_kodas']}'
					WHERE `id`='{$data['id']}'";
		mysql::query($query);
	}
	
	/**
	 * imones_padalinys šalinimas
	 * @param type $id
	 */
	public function deleteBranch($id) {
		$query = "  DELETE FROM `imones_padalinys`
					WHERE `id`='{$id}'";
		mysql::query($query);
	}
	


	/**
	 * Didžiausios imones_padalinys id reikšmės radimas
	 * @return type
	 */
	public function getMaxIdOfBranches() {
		$query = "  SELECT MAX(`id`) AS `latestId`
					FROM `imones_padalinys`";
		$data = mysql::select($query);
		
		return $data[0]['latestId'];
	}
	/**
	 * imones_padalinys kainų sąrašo radimas
	 * @param type $serviceId
	 * @return type
	 */
	public function getServices() {
		$query = "  SELECT *
					FROM `pervezimu_imone`
					";
		$data = mysql::select($query);
		
		return $data;
	}
	public function getGarageCount($id) {
		$query = "  SELECT COUNT(`vilkiku_garazas`.`id_VILKIKU_GARAZAS`) AS `kiekis`
					FROM `imones_padalinys`
						INNER JOIN `vilkiku_garazas`
							ON `vilkiku_garazas`.`	fk_IMONES_PADALINYSpadalinio_numeris`=`imones_padalinys`.`id`
					WHERE `imones_padalinys`.`id`='{$id}'";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
	 
	
}