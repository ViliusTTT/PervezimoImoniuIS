<?php

/**
 * Sutarčių redagavimo klasė
 *
 * @author ISK
 */

class contracts {

	public function __construct() {
		
	}
	
	/**
	 * Sutarčių sąrašo išrinkimas
	 * @param type $limit
	 * @param type $offset
	 * @return type
	 */
	public function getContractList($limit, $offset) {
		$query = "  SELECT 
						`uzsakymas2`.`krovinio_tipas`,
						`uzsakymas2`.`uzsakymo_verte`,
						`uzsakymas2`.`uzsakymo_data`,
						`uzsakymas2`.`uzsakymo_terminas` AS `terminas`,
					    `uzsakymas2`.`id`,
						`uzsakymo_statusai`.`name` AS `uzsakymo_status`,
						`pervezimu_imone`.`pavadinimas` AS `imones_pavadinimas`,
						`uzsakovas`.`pavadinimas` AS `Uzsakovas`
					FROM `uzsakymas2`
						LEFT JOIN `pervezimu_imone`
							ON `uzsakymas2`.`fk_PERVEZIMU_IMONEimones_kodas`=`pervezimu_imone`.`id`
						LEFT JOIN `uzsakymo_statusai`
							ON `uzsakymas2`.`uzsakymo_statusas`=`uzsakymo_statusai`.`id_uzsakymo_statusai`
						LEFT JOIN `uzsakovas`
							ON `uzsakymas2`.`fk_UZSAKOVASuzsakovo_kodas`=`uzsakovas`.`id`
						ORDER BY `id` asc LIMIT {$limit} OFFSET {$offset}";
		$data = mysql::select($query);
		
		return $data;
	}
	
	/**
	 * Sutarčių kiekio radimas
	 * @return type
	 */
	public function getContractListCount() {
		$query = "  SELECT COUNT(`id`) AS `kiekis`
					FROM `uzsakymas2`
						LEFT JOIN `pervezimu_imone`
							ON `uzsakymas2`.`fk_PERVEZIMU_IMONEimones_kodas`=`pervezimu_imone`.`id`
						LEFT JOIN `uzsakovas`
							ON `uzsakymas2`.`fk_UZSAKOVASuzsakovo_kodas`=`uzsakovas`.`id`";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
	
	/**
	 * Sutarties išrinkimas
	 * @param type $id
	 * @return type
	 */
	public function getContract($id) {
		$query = "  SELECT *
					FROM `uzsakymas2`
					WHERE `id`='{$id}'";
		$data = mysql::select($query);
		
		return $data[0];
	}

	
	/**
	 * Sutarties atnaujinimas
	 * @param type $data
	 */
	public function updateContract($data) {
		$query = "  UPDATE `uzsakymas2`
					SET    `uzsakymo_data`='{$data['uzsakymo_data']}',
							`krovinio_tipas`='{$data['krovinio_tipas']}',
						   `uzsakymo_verte`='{$data['uzsakymo_verte']}',
						   `uzsakymo_terminas`='{$data['uzsakymo_terminas']}',
						   `uzsakymo_statusas`='{$data['uzsakymo_statusas']}',
						   `fk_PERVEZIMU_IMONEimones_kodas`='{$data['fk_PERVEZIMU_IMONEimones_kodas']}',
						   `fk_UZSAKOVASuzsakovo_kodas`='{$data['fk_UZSAKOVASuzsakovo_kodas']}'
					WHERE `id`='{$data['id']}'";
		mysql::query($query);
	}
	
	/**
	 * Sutarties įrašymas
	 * @param type $data
	 */
	public function insertContract($data) {
		$query = "  INSERT INTO `uzsakymas2`
								(
									'krovinio_tipas',
									`id`,
									`uzsakymo_verte`,
									`uzsakymo_data`,
									`uzsakymo_terminas`,
									`uzsakymo_statusas`,
									`fk_PERVEZIMU_IMONEimones_kodas`,
									`fk_UZSAKOVASuzsakovo_kodas`,
								)
								VALUES
								(
									'{$data['krovinio_tipas']}',
									'{$data['id']}',
									'{$data['uzsakymo_verte']}',
									'{$data['uzsakymo_data']}',
									'{$data['uzsakymo_terminas']}',
									'{$data['uzsakymo_statusas']}',
									'{$data['fk_PERVEZIMU_IMONEimones_kodas']}',
									'{$data['fk_UZSAKOVASuzsakovo_kodas']}'
									
								)";
		mysql::query($query);
	}
	
	/**
	 * Sutarties šalinimas
	 * @param type $id
	 */
	public function deleteContract($id) {
		$query = "  DELETE FROM `uzsakymas2`
					WHERE `id`='{$id}'";
		mysql::query($query);
	}
	
	public function getMaxIdOfContract() {
		$query = "  SELECT MAX(`id`) AS `latestId`
					FROM `uzsakymas2`";
		$data = mysql::select($query);
		
		return $data[0]['latestId'];
	}
	public function getStatus() {
		$query = "  SELECT *
					FROM `uzsakymo_statusai`";
		$data = mysql::select($query);
		
		return $data;
	}
	/**
	 * imones_padalinys kainų sąrašo radimas
	 * @param type $serviceId
	 * @return type
	 */
	public function getCompanies($serviceId) {
		$query = "  SELECT *
					FROM `pervezimu_imone`
					";
		$data = mysql::select($query);
		
		return $data;
	}
		public function getUzsakovai($serviceId) {
		$query = "  SELECT *
					FROM `uzsakovas`
					";
		$data = mysql::select($query);
		
		return $data;
	}
}