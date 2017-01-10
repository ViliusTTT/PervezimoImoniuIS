<?php

/**
 * Papildomų paslaugų redagavimo klasė
 *
 * @author ISK
 */

class companies {
	
	public function __construct() {
		
	}
	
	/**
	 * Paslaugų sąrašo išrinkimas
	 * @param type $limit
	 * @param type $offset
	 * @return type
	 */
	public function getCompaniesList($limit = null, $offset = null) {
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
		}
		if(isset($offset)) {
			$limitOffsetString .= " OFFSET {$offset}";
		}
		
		$query = "  SELECT `pervezimu_imone`.`pavadinimas`,
							`pervezimu_imone`.`apyvarta`,
							`pervezimu_imone`.`padaliniu_skaicius`,
							`pervezimu_imone`.`pelnas`,
							`pervezimu_imone`.`el_pastas`,
							`pervezimu_imone`.`telefonas`,
							 `pervezimu_imone`.`adresas`,
							 `pervezimu_imone`.`id`,
							 `pervezimu_imone`.`imones_patikimumas`,
							 `veiklos_pobudziai`.`name` AS `veikla`,
							 `veiklos_sritys`.`name` AS `sritis`
					FROM `pervezimu_imone`
					LEFT JOIN `veiklos_pobudziai`
						ON `pervezimu_imone`.`veiklos_pobudis`=`veiklos_pobudziai`.`id`
					LEFT JOIN `veiklos_sritys`
						ON `pervezimu_imone`.`veiklos_sritis`=`veiklos_sritys`.`id`
					ORDER BY `id` asc LIMIT {$limit} OFFSET {$offset}"; 
							
							$data = mysql::select($query);
		
		return $data;
	}
	public function countCompanies($rFrom, $rTo){
		$whereClauseString = "";
		if(!empty($rFrom)) {
			$whereClauseString .= " WHERE `pervezimu_imone`.`imones_patikimumas`>='{$rFrom}'";
			if(!empty($rTo)) {
				$whereClauseString .= " AND `pervezimu_imone`.`imones_patikimumas`<='{$rTo}'";
			}
		} else {
			if(!empty($rTo)) {
				$whereClauseString .= " WHERE `pervezimu_imone`.`imones_patikimumas`<='{$rTo}'";
			}
		}
		
	}
		public function getPadaliniai($rFrom, $rTo, $id) {
		$whereClauseString = "";
		if(!empty($rFrom)) {
			$whereClauseString .= " WHERE `pervezimu_imone`.`imones_patikimumas`>='{$rFrom}'";
			if(!empty($rTo)) {
				$whereClauseString .= " AND `pervezimu_imone`.`imones_patikimumas`<='{$rTo}'";
			}
		} else {
			if(!empty($rTo)) {
				$whereClauseString .= " WHERE `pervezimu_imone`.`imones_patikimumas`<='{$rTo}'";
			}
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

					{$whereClauseString} AND `imones_padalinys`.`fk_PERVEZIMU_IMONEimones_kodas`={$id}
					 ORDER BY `imones_padalinys`.`apyvarta` ASC"; //Group by `imones_padalinys`.`miestas`
					
					
		$data = mysql::select($query);
		
		return $data;
	}
		
		
public function getSum($serviceId,$rFrom, $rTo) {
	$whereClauseString = "";
		if(!empty($rFrom)) {
			$whereClauseString .= " WHERE `pervezimu_imone`.`imones_patikimumas`>='{$rFrom}'";
			if(!empty($rTo)) {
				$whereClauseString .= " AND `pervezimu_imone`.`imones_patikimumas`<='{$rTo}'";
			}
		} else {
			if(!empty($rTo)) {
				$whereClauseString .= " WHERE `pervezimu_imone`.`imones_patikimumas`<='{$rTo}'";
			}
		}
		
		$query = "  SELECT sum(`imones_padalinys`.`apyvarta`) AS `kiekis`
					FROM `imones_padalinys`
						INNER JOIN `pervezimu_imone`
							ON `imones_padalinys`.`fk_PERVEZIMU_IMONEimones_kodas`=`pervezimu_imone`.`id`
					{$whereClauseString} AND `imones_padalinys`.`fk_PERVEZIMU_IMONEimones_kodas`='{$serviceId}'";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
		public function getSum2($serviceId,$rFrom, $rTo) {
	$whereClauseString = "";
		if(!empty($rFrom)) {
			$whereClauseString .= " WHERE `pervezimu_imone`.`imones_patikimumas`>='{$rFrom}'";
			if(!empty($rTo)) {
				$whereClauseString .= " AND `pervezimu_imone`.`imones_patikimumas`<='{$rTo}'";
			}
		} else {
			if(!empty($rTo)) {
				$whereClauseString .= " WHERE `pervezimu_imone`.`imones_patikimumas`<='{$rTo}'";
			}
		}
		
		$query = "  SELECT sum(`imones_padalinys`.`darbuotoju_skaicius`) AS `kiekis`
					FROM `imones_padalinys`
						INNER JOIN `pervezimu_imone`
							ON `imones_padalinys`.`fk_PERVEZIMU_IMONEimones_kodas`=`pervezimu_imone`.`id`
					{$whereClauseString} AND `imones_padalinys`.`fk_PERVEZIMU_IMONEimones_kodas`='{$serviceId}'";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
	public function getOldest($id) {
		
		$query = "  SELECT MAX(`uzsakymas222`.`amzius`) AS `max`
				FROM `imones_padalinys` 
				INNER JOIN `uzsakymas222` 
				ON `uzsakymas222`.`fk_IMONES_PADALINYSpadalinio_numeris`=`imones_padalinys`.`id` 
					INNER JOIN `pervezimu_imone` 
				ON `imones_padalinys`.`fk_PERVEZIMU_IMONEimones_kodas`=`pervezimu_imone`.`id` 
				where `pervezimu_imone`.`id`={$id}";
		$data = mysql::select($query);
		
		return $data[0]['max'];
	}
	
	public function getCompaniess($rFrom, $rTo) {
		$whereClauseString = "";
		if(!empty($rFrom)) {
			$whereClauseString .= " WHERE `pervezimu_imone`.`imones_patikimumas`>='{$rFrom}'";
			if(!empty($rTo)) {
				$whereClauseString .= " AND `pervezimu_imone`.`imones_patikimumas`<='{$rTo}'";
			}
		} else {
			if(!empty($rTo)) {
				$whereClauseString .= " WHERE `pervezimu_imone`.`imones_patikimumas`<='{$rTo}'";
			}
		}
		
	$query = " SELECT `pervezimu_imone`.`pavadinimas`,
							`pervezimu_imone`.`apyvarta`,
							`pervezimu_imone`.`padaliniu_skaicius`,
							`pervezimu_imone`.`pelnas`,
							`pervezimu_imone`.`el_pastas`,
							`pervezimu_imone`.`telefonas`,
							 `pervezimu_imone`.`adresas`,
							 `pervezimu_imone`.`id`,
							 `pervezimu_imone`.`imones_patikimumas`,
							 `veiklos_pobudziai`.`name` AS `veikla`,
							 `veiklos_sritys`.`name` AS `sritis`
					FROM `pervezimu_imone`
					LEFT JOIN `veiklos_pobudziai`
						ON `pervezimu_imone`.`veiklos_pobudis`=`veiklos_pobudziai`.`id`
					LEFT JOIN `veiklos_sritys`
						ON `pervezimu_imone`.`veiklos_sritis`=`veiklos_sritys`.`id`
						{$whereClauseString}
					ORDER BY `id` asc 
								";
		$data = mysql::select($query);
		return $data;
	}
	/**
	 * Paslaugų kiekio radimas
	 * @return type
	 */
	public function getCompaniesListCount() {
		$query = "  SELECT COUNT(`pervezimu_imone`.`id`) as `kiekis`
					FROM `pervezimu_imone`
					LEFT JOIN `veiklos_pobudziai`
							ON `pervezimu_imone`.`veiklos_pobudis`=`veiklos_pobudziai`.`id`
					LEFT JOIN `veiklos_sritys`
							ON `pervezimu_imone`.`veiklos_sritis`=`veiklos_sritys`.`id`";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
	
	/**
	 * pervezimu_imone kainų sąrašo radimas
	 * @param type $serviceId
	 * @return type
	 */
	public function getVeiklosSritys() {
		$query = "  SELECT *
					FROM `veiklos_sritys`
					";
		$data = mysql::select($query);
		
		return $data;
	}
	public function getVeiklosPobudziai() {
		$query = "  SELECT *
					FROM `veiklos_pobudziai`";
					
		$data = mysql::select($query);
		
		return $data;
	}
	/**
	 * Kompaniju atliekanciu pervezimus tam 
	 * @param type $serviceId
	 * @return type
	 */
	public function getContractCountOfService($serviceId) {
		$query = "  SELECT COUNT(`sutartys`.`nr`) AS `kiekis`
					FROM `pervezimu_imone`
						INNER JOIN `paslaugu_kainos`
							ON `pervezimu_imone`.`id`=`paslaugu_kainos`.`fk_paslauga`
						INNER JOIN `uzsakytos_pervezimu_imone`
							ON `paslaugu_kainos`.`fk_paslauga`=`uzsakytos_pervezimu_imone`.`fk_paslauga`
						INNER JOIN `sutartys`
							ON `uzsakytos_pervezimu_imone`.`fk_sutartis`=`sutartys`.`nr`
					WHERE `pervezimu_imone`.`id`='{$serviceId}'";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
	
	/**
	 * pervezimu_imone išrinkimas
	 * @param type $id
	 * @return type
	 */
	public function getCompany($id) {
		$query = "  SELECT *
					FROM `pervezimu_imone`
					WHERE `id`='{$id}'";
		$data = mysql::select($query);
		
		return $data[0];
	}
	
	/**
	 * pervezimu_imone įrašymas
	 * @param type $data
	 */
	public function insertCompany($data) {
		$query = "  INSERT INTO `pervezimu_imone`
								(
									`pavadinimas`,
									`apyvarta`,
									`padaliniu_skaicius`,
									`pelnas`,
									`el_pastas`,
									`telefonas`,
									`adresas`,
									`id`,
									`imones_patikimumas`,
									`veiklos_pobudis`,
									`veiklos_sritis`
								)
								VALUES
								(
									'{$data['pavadinimas']}',
									'{$data['apyvarta']}',
									'{$data['padaliniu_skaicius']}',
									'{$data['pelnas']}',
									'{$data['el_pastas']}',
									'{$data['telefonas']}',
									'{$data['adresas']}',
									'{$data['id']}',
									'{$data['imones_patikimumas']}',
									'{$data['veiklos_pobudis']}',
									'{$data['veiklos_sritis']}'
								)";
		mysql::query($query);
	}
	
	/**
	 * pervezimu_imone atnaujinimas
	 * @param type $data
	 */
	public function updateCompany($data) {
		$query = "  UPDATE `pervezimu_imone`
					SET    `pavadinimas`='{$data['pavadinimas']}',
						   `apyvarta`='{$data['apyvarta']}',
						   `padaliniu_skaicius`='{$data['padaliniu_skaicius']}',
						   `pelnas`='{$data['pelnas']}',
						   `el_pastas`='{$data['el_pastas']}',
						   `telefonas`='{$data['telefonas']}',
							`adresas`='{$data['adresas']}',
							`id`='{$data['id']}',
							`imones_patikimumas`='{$data['imones_patikimumas']}',
							`veiklos_pobudis`='{$data['veiklos_pobudis']}',
							`veiklos_sritis`='{$data['veiklos_sritis']}'
					WHERE `id`='{$data['id']}'";
		mysql::query($query);
	}
	
	/**
	 * pervezimu_imone šalinimas
	 * @param type $id
	 */
	public function deleteCompany($id) {
		$query = "  DELETE FROM `pervezimu_imone`
					WHERE `id`='{$id}'";
		mysql::query($query);
	}



	/**
	 * Didžiausios pervezimu_imone id reikšmės radimas
	 * @return type
	 */
	public function getMaxIdOfCompany() {
		$query = "  SELECT MAX(`id`) AS `latestId`
					FROM `pervezimu_imone`";
		$data = mysql::select($query);
		
		return $data[0]['latestId'];
	}
public function getBranchesCount($id) {
		$query = "  SELECT COUNT(`imones_padalinys`.`padalinio_numeris`) AS `kiekis`
					FROM `pervezimu_imone`
						INNER JOIN `imones_padalinys`
							ON `pervezimu_imone`.`id`=`imones_padalinys`.`fk_PERVEZIMU_IMONEimones_kodas`
					WHERE `pervezimu_imone`.`id`='{$id}'";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}


}