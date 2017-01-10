<?php

/**
 * Vilkikų redagavimo klasė
 *
 * @author 
 */

class money {

	public function __construct() {
		
	}
	
	/**
	 * Vilkiko išrinkimas
	 * @param type $id
	 * @return type
	 */
	public function getMoney($id) {
		$query = "  SELECT *
					FROM `dienpinigiai`
					WHERE `id`='{$id}'";
		$data = mysql::select($query);
		
		return $data[0];
	}
	
	/**
	 * Vilkiko atnaujinimas
	 * @param type $data
	 */
	public function updateMoney($data) {
		$query = "  UPDATE `dienpinigiai`
					SET    `suma`='{$data['suma']}',
						   `data`='{$data['data']}',
						   `islaidu_pobudis`='{$data['islaidu_pobudis']}',
						   `fk_IMONES_PADALINYSpadalinio_numeris`='{$data['fk_IMONES_PADALINYSpadalinio_numeris']}'
					WHERE `id`='{$data['id']}'";
		mysql::query($query);
	}

	/**
	 * Vilkiko įrašymas
	 * @param type $data
	 */
	public function insertMoney($data) {
		$query = "  INSERT INTO `dienpinigiai` 
								(
									`suma`,
									`data`,
									`islaidu_pobudis`,
									`id`,
									`fk_IMONES_PADALINYSpadalinio_numeris`
								) 
								VALUES
								(
									'{$data['suma']}',
									'{$data['data']}',
									'{$data['islaidu_pobudis']}',
									'{$data['id']}',
									'{$data['fk_IMONES_PADALINYSpadalinio_numeris']}'
								)";
		mysql::query($query);
	}
	
	/**
	 * Vilkikų sąrašo išrinkimas
	 * @param type $limit
	 * @param type $offset
	 * @return type
	 */
	public function getMoneyList($limit = null, $offset = null) {
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
		}
		if(isset($offset)) {
			$limitOffsetString .= " OFFSET {$offset}";
		}
		
		$query = "  SELECT 
					`dienpinigiai`.`suma`,
					`dienpinigiai`.`data`,
					`dienpinigiai`.`id`,
					`islaidu_pobudziai`.`name` AS `pobudis`,
					`imones_padalinys`.`adresas` AS `padalinys`				
					FROM `dienpinigiai`
						LEFT JOIN `imones_padalinys`
							ON `dienpinigiai`.`fk_IMONES_PADALINYSpadalinio_numeris`=`imones_padalinys`.`id`
						LEFT JOIN `islaidu_pobudziai`
							ON `dienpinigiai`.`islaidu_pobudis`=`islaidu_pobudziai`.`id`
						ORDER BY `id` asc
					 LIMIT {$limit} OFFSET {$offset}";
						
		$data = mysql::select($query);
		
		return $data;
	}
	
	/**
	 * Vilkikų kiekio radimas
	 * @return type
	 */
	 public function getMoneyListCount(){
		$query = "  SELECT COUNT(`id`) AS `kiekis`
					FROM `dienpinigiai`";
						
								$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
	
	/**
	 * Automobilio šalinimas
	 * @param type $id
	 */
	public function deleteMoney($id) {
		$query = "  DELETE FROM `dienpinigiai`
					WHERE `id`='{$id}'";
		mysql::query($query);
	}
	
	 
	 
	/**
	 * Didžiausios automobilio id reikšmės radimas
	 * @return type
	 */
	public function getMaxIdOfMoney() {
		$query = "  SELECT MAX(`id`) AS `latestId`
					FROM `dienpinigiai`";
		$data = mysql::select($query);
		
		return $data[0]['latestId'];
	}
	
	
	
			/**
	 * Modelių sąrašo išrinkimas
	 * @return type
	 */
	public function getIslaidos() {
		$query = "  SELECT *
					FROM `islaidu_pobudziai`";
		$data = mysql::select($query);
		
		return $data;
	}
	public function getPadaliniai() {
		$query = "  SELECT *
					FROM `imones_padalinys`";
		$data = mysql::select($query);
		
		return $data;
	}
}