<?php

/**
 * Papildomų paslaugų redagavimo klasė
 *
 * @author ISK
 */

class services {
	
	public function __construct() {
		
	}
	
	/**
	 * Paslaugų sąrašo išrinkimas
	 * @param type $limit
	 * @param type $offset
	 * @return type
	 */
	public function getServicesList($limit = null, $offset = null) {
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
		}
		if(isset($offset)) {
			$limitOffsetString .= " OFFSET {$offset}";
		}

		$query = "  SELECT `saskaita`.`data`,
							`saskaita`.`kaina`,
							`saskaita`.`id`,
							`aptarnavimo_pobudis`.`name` AS `pobudis`,
							`servisas`.`imones_pavadinimas` AS `servisas`,
							`pervezimu_imone`.`pavadinimas` AS `imone`
					FROM `saskaita`		
						LEFT JOIN `aptarnavimo_pobudis` 
							 ON `saskaita`.`atliktos_paslaugos`=`aptarnavimo_pobudis`.`id`  
						LEFT JOIN `pervezimu_imone` 
							 ON `saskaita`.`fk_PERVEZIMU_IMONEimones_kodas`=`pervezimu_imone`.`id`
						LEFT JOIN `servisas` 
							 ON `saskaita`.`fk_SERVISASserviso_kodas`=`servisas`.`id` LIMIT {$limit} OFFSET {$offset}";  
		
					
		$data = mysql::select($query);
		
		return $data;
	}
	public function getSumPriceOfServices($dateFrom, $dateTo) {
		$whereClauseString = "";
		if(!empty($dateFrom)) {
			$whereClauseString .= " WHERE `saskaita`.`data`>='{$dateFrom}'";
			if(!empty($dateTo)) {
				$whereClauseString .= " AND `saskaita`.`data`<='{$dateTo}'";
			}
		} else {
			if(!empty($dateTo)) {
				$whereClauseString .= " WHERE `saskaita`.`data`<='{$dateTo}'";
			}
		}
		
		$query = "  SELECT sum(`saskaita`.`kaina`) AS `suma`
					FROM `saskaita`
					";
					
		$data = mysql::select($query);

		return $data;
	}
	/**
	 * Paslaugų kiekio radimas
	 * @return type
	 */
	public function getServicesListCount() {
		$query = "  SELECT COUNT(`saskaita`.`id`) as `kiekis`
				FROM `saskaita`
				LEFT JOIN `aptarnavimo_pobudis` 
							 ON `saskaita`.`atliktos_paslaugos`=`aptarnavimo_pobudis`.`id_aptarnavimo_pobudis`  
							 LEFT JOIN `pervezimu_imone` 
							 ON `saskaita`.`fk_PERVEZIMU_IMONEimones_kodas`=`pervezimu_imone`.`id`
				    LEFT JOIN `servisas` 
							 ON `saskaita`.`fk_SERVISASserviso_kodas`=`servisas`.`id` ";
					
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
	
	/**
	 * Paslaugos kainų sąrašo radimas
	 * @param type $serviceId
	 * @return type
	 */
	public function getServicePrices($serviceId) {
		$query = "  SELECT *
					FROM `paslaugu_kainos`
					WHERE `fk_paslauga`='{$serviceId}'";
		$data = mysql::select($query);
		
		return $data;
	}
	public function countServices($dateFrom, $dateTo)
	{
		$whereClauseString = "";
		if(!empty($dateFrom)) {
			$whereClauseString .= " WHERE `saskaita`.`data`>='{$dateFrom}'";
			if(!empty($dateTo)) {
				$whereClauseString .= " AND `saskaita`.`data`<='{$dateTo}'";
			}
		} else {
			if(!empty($dateTo)) {
				$whereClauseString .= " WHERE `saskaita`.`data`<='{$dateTo}'";
			}
		}
		$query = "  SELECT COUNT(`saskaita`.`id`) as `kiekis`
				FROM `saskaita`
				LEFT JOIN `aptarnavimo_pobudis` 
							 ON `saskaita`.`atliktos_paslaugos`=`aptarnavimo_pobudis`.`id`  
							 LEFT JOIN `pervezimu_imone` 
							 ON `saskaita`.`fk_PERVEZIMU_IMONEimones_kodas`=`pervezimu_imone`.`id`
				    LEFT JOIN `servisas` 
							 ON `saskaita`.`fk_SERVISASserviso_kodas`=`servisas`.`id` 
					 {$whereClauseString}";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
		
	}
	
	public function countServizesServices($dateFrom, $dateTo)
	{
		$whereClauseString = "";
		if(!empty($dateFrom)) {
			$whereClauseString .= " WHERE `saskaita`.`data`>='{$dateFrom}'";
			if(!empty($dateTo)) {
				$whereClauseString .= " AND `saskaita`.`data`<='{$dateTo}'";
			}
		} else {
			if(!empty($dateTo)) {
				$whereClauseString .= " WHERE `saskaita`.`data`<='{$dateTo}'";
			}
		}
		$query = "  SELECT COUNT(`saskaita`.`id`) as `kiekis`
				FROM `saskaita`
				LEFT JOIN `aptarnavimo_pobudis` 
							 ON `saskaita`.`atliktos_paslaugos`=`aptarnavimo_pobudis`.`id`  
							 LEFT JOIN `pervezimu_imone` 
							 ON `saskaita`.`fk_PERVEZIMU_IMONEimones_kodas`=`pervezimu_imone`.`id`
				    LEFT JOIN `servisas` 
							 ON `saskaita`.`fk_SERVISASserviso_kodas`=`servisas`.`id` 
					 {$whereClauseString}";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
		
	}

	public function getServices($dateFrom, $dateTo,$serviceId) {
		$whereClauseString = "";
		if(!empty($dateFrom)) {
			$whereClauseString .= " WHERE `saskaita`.`data`>='{$dateFrom}'";
			if(!empty($dateTo)) {
				$whereClauseString .= " AND `saskaita`.`data`<='{$dateTo}'";
			}
		} else {
			if(!empty($dateTo)) {
				$whereClauseString .= " WHERE `saskaita`.`data`<='{$dateTo}'";
			}
		}
		
	$query = "  SELECT `saskaita`.`data` ,
				`saskaita`.`kaina`, `saskaita`.`id`,
				`aptarnavimo_pobudis`.`name` AS `pobudis`,
				`servisas`.`imones_pavadinimas` AS `servisas`,
				`pervezimu_imone`.`pavadinimas` AS `imone`
				 FROM `saskaita`
				 LEFT JOIN `aptarnavimo_pobudis` ON `saskaita`.`atliktos_paslaugos`=`aptarnavimo_pobudis`.`id` 
				 LEFT JOIN `pervezimu_imone` ON `saskaita`.`fk_PERVEZIMU_IMONEimones_kodas`=`pervezimu_imone`.`id` 
				 LEFT JOIN `servisas` ON `saskaita`.`fk_SERVISASserviso_kodas`=`servisas`.`id` 
				{$whereClauseString} AND `saskaita`.`fk_PERVEZIMU_IMONEimones_kodas`= '{$serviceId}'
				 Order by `saskaita`.`kaina` asc";//GROUP BY `servisas`.`id`
		$data = mysql::select($query);
		return $data;
	}
	
	/**
	 * Kiek imoniu uzsisake tokia paslauga
	 * @param type $serviceId
	 * @return type
	 */
	public function getCompanyCountOfSaskaita($serviceId,$dateFrom, $dateTo) {
		$whereClauseString = "";
		if(!empty($dateFrom)) {
			$whereClauseString .= " WHERE `saskaita`.`data`>='{$dateFrom}'";
			if(!empty($dateTo)) {
				$whereClauseString .= " AND `saskaita`.`data`<='{$dateTo}'";
			}
		} else {
			if(!empty($dateTo)) {
				$whereClauseString .= " WHERE `saskaita`.`data`<='{$dateTo}'";
			}
		}
		
		$query = "  SELECT COUNT(`saskaita`.`id`) AS `kiekis`
					FROM `saskaita`
						INNER JOIN `pervezimu_imone`
							ON `saskaita`.`fk_PERVEZIMU_IMONEimones_kodas`=`pervezimu_imone`.`id`
						INNER JOIN `servisas`
							ON `saskaita`.`fk_SERVISASserviso_kodas`=`servisas`.`id`
						
					{$whereClauseString} AND `saskaita`.`fk_PERVEZIMU_IMONEimones_kodas`='{$serviceId}'";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
	
	public function getCompanyCountOfSumas($serviceId,$dateFrom, $dateTo) {
		$whereClauseString = "";
		if(!empty($dateFrom)) {
			$whereClauseString .= " WHERE `saskaita`.`data`>='{$dateFrom}'";
			if(!empty($dateTo)) {
				$whereClauseString .= " AND `saskaita`.`data`<='{$dateTo}'";
			}
		} else {
			if(!empty($dateTo)) {
				$whereClauseString .= " WHERE `saskaita`.`data`<='{$dateTo}'";
			}
		}
		
		$query = "  SELECT sum(`saskaita`.`kaina`) AS `kiekis`
					FROM `saskaita`
						INNER JOIN `pervezimu_imone`
							ON `saskaita`.`fk_PERVEZIMU_IMONEimones_kodas`=`pervezimu_imone`.`id`
						INNER JOIN `servisas`
							ON `saskaita`.`fk_SERVISASserviso_kodas`=`servisas`.`id`
					{$whereClauseString} AND `saskaita`.`fk_PERVEZIMU_IMONEimones_kodas`='{$serviceId}'";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
	
	/**
	 * Paslaugos išrinkimas
	 * @param type $id
	 * @return type
	 */
	public function getService($id) {
		$query = "   SELECT `saskaita`.`atliktos_paslaugos`,
							`saskaita`.`data`,
							`saskaita`.`kaina`,
							`saskaita`.`id`,
							`saskaita`.`fk_PERVEZIMU_IMONEimones_kodas`,
							`saskaita`.`fk_SERVISASserviso_kodas`
					FROM `saskaita`
					WHERE `id`='{$id}'";
		$data = mysql::select($query);
		
		return $data[0];
	}
	
	/**
	 * Paslaugos įrašymas
	 * @param type $data
	 */
	public function insertService($data) {
		$query = "  INSERT INTO `saskaita`
								(
									`atliktos_paslaugos`,
									`data`,
									`kaina`,
									`id`,
									`fk_PERVEZIMU_IMONEimones_kodas`,
									`fk_SERVISASserviso_kodas`
								)
								VALUES
								(
									'{$data['atliktos_paslaugos']}',
									'{$data['data']}',
									'{$data['kaina']}',
									'{$data['id']}',
									'{$data['fk_PERVEZIMU_IMONEimones_kodas']}',
									'{$data['fk_SERVISASserviso_kodas']}'
								)";
		mysql::query($query);
	}
	
	/**
	 * Paslaugos atnaujinimas
	 * @param type $data
	 */
	public function updateService($data) {
		$query = "  UPDATE `saskaita`
					SET    `atliktos_paslaugos`='{$data['atliktos_paslaugos']}',
							`data`='{$data['data']}',
						   `kaina`='{$data['kaina']}',
						   `fk_PERVEZIMU_IMONEimones_kodas`='{$data['fk_PERVEZIMU_IMONEimones_kodas']}',
						   `fk_SERVISASserviso_kodas`='{$data['fk_SERVISASserviso_kodas']}'
					WHERE `id`='{$data['id']}'";
		mysql::query($query);
	}
	
	/**
	 * Paslaugos šalinimas
	 * @param type $id
	 */
	public function deleteService($id) {
		$query = "  DELETE FROM `saskaita`
					WHERE `id`='{$id}'";
		mysql::query($query);
	}
	
	/**
	 * Paslaugos kainų įrašymas
	 * @param type $data
	 */
	public function insertServicePrices($data) {
		foreach($data['kainos'] as $key=>$val) {
			if($data['neaktyvus'] == array() || $data['neaktyvus'][$key] == 0) {
				$query = "  INSERT INTO `paslaugu_kainos`
										(
											`fk_paslauga`,
											`galioja_nuo`,
											`kaina`
										)
										VALUES
										(
											'{$data['id']}',
											'{$data['datos'][$key]}',
											'{$val}'
										)";
				mysql::query($query);
			}
		}
	}
	
	/**
	 * Paslaugos kainų šalinimas
	 * @param type $serviceId
	 * @param type $clause
	 */
	public function deleteServicePrices($serviceId, $clause = "") {
		$query = "  DELETE FROM `paslaugu_kainos`
					WHERE `fk_paslauga`='{$serviceId}'" . $clause;
		mysql::query($query);
	}

	/**
	 * Didžiausios paslaugos id reikšmės radimas
	 * @return type
	 */
	public function getMaxIdOfService() {
		$query = "  SELECT MAX(`id`) AS `latestId`
					FROM `saskaita`";
		$data = mysql::select($query);
		
		return $data[0]['latestId'];
	}


	
	
	public function getPaslaugos() {
		$query = "  SELECT *
					FROM `aptarnavimo_pobudis`";
		$data = mysql::select($query);
		
		return $data;
	}
	public function getImones() {
		$query = "  SELECT *
					FROM `pervezimu_imone`";
		$data = mysql::select($query);
		
		return $data;
	}
		public function getServisai() {
		$query = "  SELECT *
					FROM `servisas`";
		$data = mysql::select($query);
		
		return $data;
	}
	}