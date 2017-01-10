<?php

/**
 * Vilkikų redagavimo klasė
 *
 * @author 
 */

class garages {

	public function __construct() {
		
	}
	
	/**
	 * Vilkiko išrinkimas
	 * @param type $id
	 * @return type
	 */
	public function getGarage($id) {
		$query = "  SELECT *
					FROM `vilkiku_garazas`
					WHERE `id`='{$id}'";
		$data = mysql::select($query);
		
		return $data[0];
	}
	
	/**
	 * Vilkiko atnaujinimas
	 * @param type $data
	 */
	public function updateGarage($data) {
		$query = "  UPDATE `vilkiku_garazas`
					SET    `vilkiku_skaicius`='{$data['vilkiku_skaicius']}',
						   `miestas`='{$data['miestas']}',
						   `adresas`='{$data['adresas']}',
						   `telefonas`='{$data['telefonas']}',
						   `fk_IMONES_PADALINYSpadalinio_numeris`='{$data['fk_IMONES_PADALINYSpadalinio_numeris']}'
					WHERE `id`='{$data['id']}'";
		mysql::query($query);
	}
	
	/**
	 * Vilkiko įrašymas
	 * @param type $data
	 */
	public function insertGarage($data) {
		$query = "  INSERT INTO `vilkiku_garazas` 
								(
									`vilkiku_skaicius`,
									`miestas`,
									`adresas`,
									`telefonas`,
									`id`,
									`fk_IMONES_PADALINYSpadalinio_numeris`
								) 
								VALUES
								(
									'{$data['vilkiku_skaicius']}',
									'{$data['miestas']}',
									'{$data['adresas']}',
									'{$data['telefonas']}',
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
	public function getGarageList($limit = null, $offset = null) {
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
		}
		if(isset($offset)) {
			$limitOffsetString .= " OFFSET {$offset}";
		}
		
		$query = "  SELECT 
					`vilkiku_garazas`.`vilkiku_skaicius`,
					`vilkiku_garazas`.`miestas`,
					`vilkiku_garazas`.`adresas`,
					`vilkiku_garazas`.`telefonas`,
					`vilkiku_garazas`.`id`,
					`imones_padalinys`.`adresas` AS `padalinys`
										
					FROM `vilkiku_garazas`
						LEFT JOIN `imones_padalinys`
							ON `vilkiku_garazas`.`fk_IMONES_PADALINYSpadalinio_numeris`=`imones_padalinys`.`id`
						
					 LIMIT {$limit} OFFSET {$offset}";
						
		$data = mysql::select($query);
		
		return $data;
	}
	
	/**
	 * Vilkikų kiekio radimas
	 * @return type
	 */
	 public function getGarageListCount(){
		$query = "  SELECT COUNT(`vilkiku_garazas`.`id`) AS `kiekis`
					FROM `vilkiku_garazas`
						LEFT JOIN `imones_padalinys`
							ON `vilkiku_garazas`.`fk_IMONES_PADALINYSpadalinio_numeris`=`imones_padalinys`.`id`";
								$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
	
	/**
	 * Automobilio šalinimas
	 * @param type $id
	 */
	public function deleteGarage($id) {
		$query = "  DELETE FROM `vilkiku_garazas`
					WHERE `id`='{$id}'";
		mysql::query($query);
	}
	
	 
	 
	/**
	 * Didžiausios automobilio id reikšmės radimas
	 * @return type
	 */
	public function getMaxIdOfgarage() {
		$query = "  SELECT MAX(`id`) AS `latestId`
					FROM `vilkiku_garazas`";
		$data = mysql::select($query);
		
		return $data[0]['latestId'];
	}
		public function getTrucks($verteFrom, $verteTo, $id) {
			$whereClauseString = "";
		if(!empty($verteFrom)) {
			$whereClauseString .= " WHERE `vilkikas`.`verte`>='{$verteFrom}'";
			if(!empty($verteTo)) {
				$whereClauseString .= " AND `vilkikas`.`verte`<='{$verteTo}'";
			}
		} else {
			if(!empty($verteTo)) {
				$whereClauseString .= " WHERE `vilkikas`.`verte`<='{$verteTo}'";
			}
		}
		
		$query = "  SELECT 
					`vilkikas`.`valstybinis_nr`,
					`vilkikas`.`pagaminimo_data`,
					`vilkikas`.`rida`,
					`vilkikas`.`mase_be_krovinio`,
					`vilkikas`.`mase_su_kroviniu`,
					`vilkikas`.`verte`,
					`vilkikas`.`kebulo_ilgis`,
					`vilkikas`.`miegamu_vietu_skaicius`,
					`vilkikas`.`bako_talpa`,
					`vilkikas`.`asiu_skaicius`,
					`vilkikas`.`bukle`,
					`vilkikas`.`spalva`,
					`vilkikas`.`id`,
					`vilkikas`.`aukstis`,
					`modelis`.`pavadinimas` AS `model`,
					`marke`.`pavadinimas` AS `mark`,
					`pavaru_dezes`.`name` AS `pavar`,
					`ekonomiskumo_kategorijos`.`name` AS `kat`
					
					FROM `vilkikas`
						LEFT JOIN `ekonomiskumo_kategorijos`
							ON `vilkikas`.`variklio_ekonomiskumo_kategorija`=`ekonomiskumo_kategorijos`.`id_ekonomiskumo_kategorijos`
						LEFT JOIN `modelis`
							ON `vilkikas`.`fk_MODELISid_MODELIS`=`modelis`.`id`
						LEFT JOIN `pavaru_dezes`
							ON `vilkikas`.`pavaru_deze`=`pavaru_dezes`.`id_pavaru_dezes`
						LEFT JOIN `marke`
							ON `modelis`.`fk_MARKEid_MARKE`=`marke`.`id`

					{$whereClauseString} AND `vilkikas`.`fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS`={$id}
					ORDER BY `vilkikas`.`verte` ASC";// GROUP BY `mark`
					
					
		$data = mysql::select($query);
		
		return $data;
	}
	public function getMaxVerteDriver($verteFrom, $verteTo,$id) {
			$whereClauseString = "";
		if(!empty($verteFrom)) {
			$whereClauseString .= " WHERE `vilkikas`.`verte`>='{$verteFrom}'";
			if(!empty($verteTo)) {
				$whereClauseString .= " AND `vilkikas`.`verte`<='{$verteTo}'";
			}
		} else {
			if(!empty($verteTo)) {
				$whereClauseString .= " WHERE `vilkikas`.`verte`<='{$verteTo}'";
			}
		}
		$query = " SELECT MAX(`darbuotojas2`.`amzius`) AS `amzius`
				    FROM `darbuotojas2`
					LEFT JOIN `vilkikas`
					ON `vilkikas`.`fk_darbuotojas2asmens_kodas` = `darbuotojas2`.`id`
					LEFT JOIN `vilkiku_garazas` 
					ON `darbuotojas2`.`fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS`=`vilkiku_garazas`.`id`
					{$whereClauseString}";
		$data = mysql::select($query);
		
		return $data[0]['amzius'];
	}
	
	
			/**
	 * Modelių sąrašo išrinkimas
	 * @return type
	 */
	public function getBranch() {
		$query = "  SELECT *
					FROM `imones_padalinys`";
		$data = mysql::select($query);
		
		return $data;
	}
		public function getVerteCount2($verteFrom, $verteTo) {
			$whereClauseString = "";
		if(!empty($verteFrom)) {
			$whereClauseString .= " WHERE `vilkikas`.`verte`>='{$verteFrom}'";
			if(!empty($verteTo)) {
				$whereClauseString .= " AND `vilkikas`.`verte`<='{$verteTo}'";
			}
		} else {
			if(!empty($verteTo)) {
				$whereClauseString .= " WHERE `vilkikas`.`verte`<='{$verteTo}'";
			}
		}
		$query = "  SELECT COUNT(`vilkikas`.`id`) AS `kiekis`
				FROM `vilkiku_garazas` 
				INNER JOIN `vilkikas` 
				ON `vilkiku_garazas`.`id`=`vilkikas`.`fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS` 
				{$whereClauseString}";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
	public function getMaxVerte2($verteFrom, $verteTo,$id) {
			$whereClauseString = "";
		if(!empty($verteFrom)) {
			$whereClauseString .= " WHERE `vilkikas`.`verte`>='{$verteFrom}'";
			if(!empty($verteTo)) {
				$whereClauseString .= " AND `vilkikas`.`verte`<='{$verteTo}'";
			}
		} else {
			if(!empty($verteTo)) {
				$whereClauseString .= " WHERE `vilkikas`.`verte`<='{$verteTo}'";
			}
		}
		$query = "  SELECT MAX(`vilkikas`.`verte`) AS `max`
				FROM `vilkiku_garazas` 
				INNER JOIN `vilkikas` 
				ON `vilkiku_garazas`.`id`=`vilkikas`.`fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS` 
				{$whereClauseString} AND `vilkikas`.`fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS`={$id}";
		$data = mysql::select($query);
		
		return $data[0]['max'];
	}
	public function getMinVerte2($verteFrom, $verteTo,$id) {
			$whereClauseString = "";
		if(!empty($verteFrom)) {
			$whereClauseString .= " WHERE `vilkikas`.`verte`>='{$verteFrom}'";
			if(!empty($verteTo)) {
				$whereClauseString .= " AND `vilkikas`.`verte`<='{$verteTo}'";
			}
		} else {
			if(!empty($verteTo)) {
				$whereClauseString .= " WHERE `vilkikas`.`verte`<='{$verteTo}'";
			}
		}
		$query = "  SELECT MIN(`vilkikas`.`verte`) AS `min`
				FROM `vilkiku_garazas` 
				INNER JOIN `vilkikas` 
				ON `vilkiku_garazas`.`id`=`vilkikas`.`fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS` 
				{$whereClauseString} AND `vilkikas`.`fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS`={$id}";
		$data = mysql::select($query);
		
		return $data[0]['min'];
	}
	public function getAvrgVerte2($verteFrom, $verteTo,$id) {
			$whereClauseString = "";
		if(!empty($verteFrom)) {
			$whereClauseString .= " WHERE `vilkikas`.`verte`>='{$verteFrom}'";
			if(!empty($verteTo)) {
				$whereClauseString .= " AND `vilkikas`.`verte`<='{$verteTo}'";
			}
		} else {
			if(!empty($verteTo)) {
				$whereClauseString .= " WHERE `vilkikas`.`verte`<='{$verteTo}'";
			}
		}
		$query = "  SELECT AVG(`vilkikas`.`verte`) AS `avg`
				FROM `vilkiku_garazas` 
				INNER JOIN `vilkikas` 
				ON `vilkiku_garazas`.`id`=`vilkikas`.`fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS` 
				{$whereClauseString} AND `vilkikas`.`fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS`={$id}";
		$data = mysql::select($query);
		
		return number_format((float)$data[0]['avg'], 2, '.', '');
	}
	public function getGarazai() {
		$query = "  SELECT *
					FROM `vilkiku_garazas`";
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