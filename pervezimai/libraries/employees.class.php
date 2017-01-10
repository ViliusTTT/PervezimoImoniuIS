<?php

/**
 * Darbuotojų redagavimo klasė
 *
 * @author ISK
 */

class employees {
	
	public function __construct() {
		
	}
	
	/**
	 * Darbuotojo išrinkimas
	 * @param type $id
	 * @return type
	 */
	public function getEmployee($id) {
		$query = "  SELECT *
					FROM `darbuotojas2`
					WHERE `id`='{$id}'";
		$data = mysql::select($query);
		
		return $data[0];
	}
	
		public function getPayList() {
		$query = "  SELECT *
					FROM `uzmokestis`
					LEFT JOIN `darbuotojas2`
							ON `uzmokestis`.`fk_darbuotojas2asmens_kodas`=`darbuotojas2`.`id`";
		$data = mysql::select($query);
		
		return $data;
	}
		/**
	 * Paslaugos kainų šalinimas
	 * @param type $serviceId
	 * @param type $clause
	 */
	public function deletePay($serviceId, $clause = "") {
		$query = "  DELETE FROM `uzmokestis`
					WHERE `fk_darbuotojas2asmens_kodas`='{$serviceId}'" . $clause;
		mysql::query($query);
	}

	public function insertPay($data) {
		foreach($data['suma'] as $key=>$val) {
			{
				$query = "  INSERT INTO `uzmokestis`
										(
											`fk_darbuotojas2asmens_kodas`,
											`uz_kuri_menesi`,
											`suma`
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
	 * Darbuotojų sąrašo išrinkimas
	 * @param type $limit
	 * @param type $offset
	 * @return type
	 */
	public function getEmployeesList($limit = null, $offset = null) {
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
		}
		if(isset($offset)) {
			$limitOffsetString .= " OFFSET {$offset}";
		}
		
		$query = "  SELECT 
					`darbuotojas2`.`vardas`,
					`darbuotojas2`.`pavarde`,
					`darbuotojas2`.`amzius`,
					`darbuotojas2`.`darbo_patirtis`,
					`darbuotojas2`.`lytis`,
					`darbuotojas2`.`tautybe`,
					`darbuotojas2`.`id`,
					`dienpinigiai`.`suma` AS `dienp`,
					`imones_padalinys`.`adresas` AS `padalinys`,
					`vilkiku_garazas`.`adresas` AS `garazas`
					FROM `darbuotojas2`
						LEFT JOIN `dienpinigiai`
							ON `darbuotojas2`.`fk_DIENPINIGIAIid_DIENPINIGIAI`=`dienpinigiai`.`id`
						LEFT JOIN `imones_padalinys`
							ON `darbuotojas2`.`fk_IMONES_PADALINYSpadalinio_numeris`=`imones_padalinys`.`id`
						LEFT JOIN `vilkiku_garazas`
							ON `darbuotojas2`.`fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS`=`vilkiku_garazas`.`id`
							ORDER BY `id` asc LIMIT {$limit} OFFSET {$offset}";
		$data = mysql::select($query);
		
		return $data;
	}
	
	/**
	 * Darbuotojų kiekio radimas
	 * @return type
	 */
	public function getEmployeesListCount() {
		$query = "  SELECT COUNT(`id`) as `kiekis`
					FROM `darbuotojas2`";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
	
	/**
	 * Darbuotojo šalinimas
	 * @param type $id
	 */
	public function deleteEmployee($id) {
		$query = "  DELETE FROM `darbuotojas2`
					WHERE `id`='{$id}'";
		mysql::query($query);
	}
	
	/**
	 * Darbuotojo atnaujinimas
	 * @param type $data
	 */
	public function updateEmployee($data) {
		$query = "  UPDATE `darbuotojas2`
					SET    `vardas`='{$data['vardas']}',
						   `pavarde`='{$data['pavarde']}',
						   `amzius`='{$data['amzius']}',
						   `darbo_patirtis`='{$data['darbo_patirtis']}',
						   `lytis`='{$data['lytis']}',
						   `tautybe`='{$data['tautybe']}',
						   `fk_IMONES_PADALINYSpadalinio_numeris`='{$data['fk_IMONES_PADALINYSpadalinio_numeris']}',
						   `fk_DIENPINIGIAIid_DIENPINIGIAI`={$data['fk_DIENPINIGIAIid_DIENPINIGIAI']}',
						   `fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS`={$data['fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS']}
					WHERE `id`='{$data['id']}'";
		mysql::query($query);
	}
	
	/**
	 * Darbuotojo įrašymas
	 * @param type $data
	 */
	public function insertEmployee($data) {
		$query = "  INSERT INTO `darbuotojas2`
								(
									`vardas`,
									`pavarde`,
									`amzius`,
									`darbo_patirtis`,
									`lytis`,
									`tautybe`,
									`id`,
									 `fk_IMONES_PADALINYSpadalinio_numeris`,
									 `fk_DIENPINIGIAIid_DIENPINIGIAI`,
									 `fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS`
								) 
								VALUES
								(
									'{$data['vardas']}',
									'{$data['pavarde']}',
									'{$data['amzius']}',
									'{$data['darbo_patirtis']}',
									'{$data['lytis']}',
									'{$data['tautybe']}',
									'{$data['id']}',
									'{$data['fk_IMONES_PADALINYSpadalinio_numeris']}',
									'{$data['fk_DIENPINIGIAIid_DIENPINIGIAI']}',
									'{$data['fk_VILKIKU_GARAZASid_VILKIKU_GARAZAS']}'
								)";
		mysql::query($query);
	}
	
	/**
	 * Sutarčių, į kurias įtrauktas darbuotojas2, kiekio radimas
	 * @param type $id
	 * @return type
	 */
	public function getCountEmployee($id) {
		$query = "  SELECT COUNT(`vilkikas`.`id`) AS `kiekis`
					FROM `darbuotojas2`
						INNER JOIN `vilkikas`
							ON `vilkikas`.`fk_darbuotojas2asmens_kodas`=`darbuotojas2`.`id`
					WHERE `darbuotojas2`.`id`='{$id}'";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
	/**
	 * Didžiausios
	 * @return type
	 */
	public function getMaxIdOfEmployee() {
		$query = "  SELECT MAX(`id`) AS `latestId`
					FROM `darbuotojas2`";
		$data = mysql::select($query);
		
		return $data[0]['latestId'];
	}
	public function getIsmoketi($orderId) {
		$query = "  SELECT *
					FROM `ismoketi_uzmokesciai`
					WHERE `fk_darbuotojas2`='{$orderId}'";
		$data = mysql::select($query);
		
		return $data;
	}
	
	/**
	 * Užsakytų papildomų paslaugų atnaujinimas
	 * @param type $data
	 */
	public function updateOrderedServices($data) {
		$this->deleteIsmoketi($data['id']);
		
		foreach($data['paslaugos'] as $key=>$val) {
			$tmp = explode(":", $val);
			$serviceId = $tmp[0];
			$price = $tmp[1];
			$date_from = $tmp[2];
			$query = "  INSERT INTO `ismoketi_uzmokesciai`
									(
										`fk_sutartis`,
										`fk_kaina_galioja_nuo`,
										`fk_paslauga`,
										`kiekis`,
										`kaina`
									)
									VALUES
									(
										'{$data['nr']}',
										'{$date_from}',
										'{$serviceId}',
										'{$data['kiekiai'][$key]}',
										'{$price}'
									)";
				mysql::query($query);
		}
	}
	
	/**
	 * Sutarties būsenų sąrašo išrinkimas
	 * @return type
	 */
	public function deleteIsmoketi($contractId) {
		$query = "  DELETE FROM `ismoketi_uzmokesciai``
					WHERE `fk_darbuotojas2`='{$contractId}'";
		mysql::query($query);
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
	 * Padalinių sąrašo išrinkimas
	 * @return type
	 */
	public function getPadalinys() {
		$query = "  SELECT *
					FROM `imones_padalinys`";
		$data = mysql::select($query);
		
		return $data;
	}
	/**
	 * Dienpinigių sąrašo išrinkimas
	 * @return type
	 */
	public function getDienpinigiai() {
		$query = "  SELECT *
					FROM `dienpinigiai`";
		$data = mysql::select($query);
		
		return $data;
	}
	
}