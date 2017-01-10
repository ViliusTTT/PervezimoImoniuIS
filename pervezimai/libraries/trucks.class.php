<?php

/**
 * Vilkikų redagavimo klasė
 *
 * @author 
 */

class trucks {

	public function __construct() {
		
	}
	
	/**
	 * Vilkiko išrinkimas
	 * @param type $id
	 * @return type
	 */
	public function getTruck($id) {
		$query = "  SELECT *
					FROM `vilkikas`
					WHERE `id`='{$id}'";
		$data = mysql::select($query);
		
		return $data[0];
	}
	
	/**
	 * Vilkiko atnaujinimas
	 * @param type $data
	 */
	public function updateTruck($data) {
		$query = "  UPDATE `vilkikas`
					SET    `valstybinis_nr`='{$data['valstybinis_nr']}',
						   `pagaminimo_data`='{$data['pagaminimo_data']}',
						   `rida`='{$data['rida']}',
						   `mase_be_krovinio`='{$data['mase_be_krovinio']}',
						   `mase_su_kroviniu`='{$data['mase_su_kroviniu']}',
						   `verte`='{$data['verte']}',
						   `kebulo_ilgis`='{$data['kebulo_ilgis']}',
						   `miegamu_vietu_skaicius`='{$data['miegamu_vietu_skaicius']}',
						   `bako_talpa`='{$data['bako_talpa']}',
						   `asiu_skaicius`='{$data['asiu_skaicius']}',
						   `bukle`='{$data['bukle']}',
						   `spalva`='{$data['spalva']}',
						   `aukstis`='{$data['aukstis']}',
						   `degalai`='{$data['degalai']}',
						   `variklio_ekonomiskumo_kategorija`='{$data['variklio_ekonomiskumo_kategorija']}',
						   `pavaru_deze`='{$data['pavaru_deze']}',
						   `fk_MODELISid_MODELIS`='{$data['fk_MODELISid_MODELIS']}'
					WHERE `id`='{$data['id']}'";
		mysql::query($query);
	}

	/**
	 * Vilkiko įrašymas
	 * @param type $data
	 */
	public function insertTruck($data) {
		$query = "  INSERT INTO `vilkikas` 
								(
									`valstybinis_nr`,
									`pagaminimo_data`,
									`rida`,
									`mase_be_krovinio`,
									`mase_su_kroviniu`,
									`verte`,
									`kebulo_ilgis`,
									`miegamu_vietu_skaicius`,
									`bako_talpa`,
									`asiu_skaicius`,
									`bukle`,
									`spalva`,
									`id`,
									`aukstis`,
									`degalai`,
									`variklio_ekonomiskumo_kategorija`,
									`pavaru_deze`,
									`fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS`,
									`fk_saskaita`,
									`fk_asmens_kodas`,
									`fk_MODELISid_MODELIS`
								) 
								VALUES
								(
									'{$data['valstybinis_nr']}',
									'{$data['pagaminimo_data']}',
									'{$data['rida']}',
									'{$data['mase_be_krovinio']}',
									'{$data['mase_su_kroviniu']}',
									'{$data['verte']}',
									'{$data['kebulo_ilgis']}',
									'{$data['miegamu_vietu_skaicius']}',
									'{$data['bako_talpa']}',
									'{$data['asiu_skaicius']}',
									'{$data['bukle']}',
									'{$data['spalva']}',
									'{$data['id']}',
									'{$data['aukstis']}',
									'{$data['degalai']}',
									'{$data['variklio_ekonomiskumo_kategorija']}',
									'{$data['pavaru_deze']}',
									'{$data['fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS']}',
									'{$data['fk_saskaita']}',
									'{$data['fk_asmens_kodas']}',
									'{$data['fk_MODELISid_MODELIS']}'
								)";
		mysql::query($query);
	}
	
	/**
	 * Vilkikų sąrašo išrinkimas
	 * @param type $limit
	 * @param type $offset
	 * @return type
	 */
	public function getTruckList($limit = null, $offset = null) {
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
		}
		if(isset($offset)) {
			$limitOffsetString .= " OFFSET {$offset}";
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
							ON `vilkikas`.`variklio_ekonomiskumo_kategorija`=`ekonomiskumo_kategorijos`.`id`
						LEFT JOIN `modelis`
							ON `vilkikas`.`fk_MODELISid_MODELIS`=`modelis`.`id`
						LEFT JOIN `pavaru_dezes`
							ON `vilkikas`.`pavaru_deze`=`pavaru_dezes`.`id`
						LEFT JOIN `marke`
							ON `modelis`.`fk_MARKEid_MARKE`=`marke`.`id` 
					ORDER BY `id` asc LIMIT {$limit} OFFSET {$offset}";
						
		$data = mysql::select($query);
		
		return $data;
	}
	
	/**
	 * Vilkikų kiekio radimas
	 * @return type
	 */
	 public function getTruckListCount(){
		$query = "  SELECT COUNT(`vilkikas`.`id`) AS `kiekis`
					FROM `vilkikas`
						LEFT JOIN `ekonomiskumo_kategorijos`
							ON `vilkikas`.`variklio_ekonomiskumo_kategorija`=`ekonomiskumo_kategorijos`.`id`
						LEFT JOIN `modelis`
							ON `vilkikas`.`fk_MODELISid_MODELIS`=`modelis`.`id`
						LEFT JOIN `pavaru_dezes`
							ON `vilkikas`.`pavaru_deze`=`pavaru_dezes`.`id`
						LEFT JOIN `marke`
							ON `modelis`.`fk_MARKEid_MARKE`=`marke`.`id`" ;
								$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
	
	/**
	 * Automobilio šalinimas
	 * @param type $id
	 */
	public function deleteTruck($id) {
		$query = "  DELETE FROM `vilkikas`
					WHERE `id`='{$id}'";
		mysql::query($query);
	}
	
		 /** Sutačių, į kurias įtrauktas automobilis, kiekio radimas
	 * @param type $id
	 * @return type
	*/
	public function getDriverCountOfTruck($id) {
		$query = "  SELECT COUNT(`darbuotojas2`.`id`) AS `kiekis`
					FROM `vilkikas`
						INNER JOIN ``
							ON ``.`id`=`vilkikas`.`fk_asmens_kodas`
					WHERE `vilkikas`.`id`='{$id}'";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
	 
	 
	/**
	 * Didžiausios automobilio id reikšmės radimas
	 * @return type
	 */
	public function getMaxIdOfTruck() {
		$query = "  SELECT MAX(`id`) AS `latestId`
					FROM `vilkikas`";
		$data = mysql::select($query);
		
		return $data[0]['latestId'];
	}
	
	/**
	 * Pavarų dėžių sąrašo išrinkimas
	 * @return type
	 */
	public function getGearboxList() {
		$query = "  SELECT *
					FROM `pavaru_dezes`";
		$data = mysql::select($query);
		
		return $data;
	}
	
	/**
	 * Degalų tipo sąrašo išrinkimas
	 * @return type
	 */
	public function getFuelTypeList() {
		$query = "  SELECT *
					FROM `degalu_tipai`";
		$data = mysql::select($query);
		
		return $data;
	}

	/**
	 * variklio ekonomiskumo išrinkimas
	 * @return type
	 */
	public function getEngineTypeList() {
		$query = "  SELECT *
					FROM `ekonomiskumo_kategorijos`";
		$data = mysql::select($query);
		
		return $data;
	}

	/**
	 * Garažų sąrašo išrinkimas
	 * @return type
	 */
	public function getGarage() {
		$query = "  SELECT *
					FROM `vilkiku_garazas`";
		$data = mysql::select($query);
		
		return $data;
	}

	/**
	 * Servisų sąrašo išrinkimas
	 * @return type
	 */
	public function getService() {
		$query = "  SELECT *
					FROM `saskaita`";
		$data = mysql::select($query);
		
		return $data;
	}
		/**
	 * Darbuotojų sąrašo išrinkimas
	 * @return type
	 */
	public function getDriver() {
		$query = "  SELECT *
					FROM `darbuotojas2`";
		$data = mysql::select($query);
		
		return $data;
	}
			/**
	 * Modelių sąrašo išrinkimas
	 * @return type
	 */
	public function getModel() {
		$query = "  SELECT *
					FROM `modelis`";
		$data = mysql::select($query);
		
		return $data;
	}
}