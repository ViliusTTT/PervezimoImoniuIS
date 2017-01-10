<?php

/**
 * Klientų redagavimo klasė
 *
 * @author ISK
 */

class customers {
	
	public function __construct() {
		
	}
	
	/**
	 * Kliento išrinkimas
	 * @param type $id
	 * @return type
	 */
	public function getCustomer($id) {
		$query = "  SELECT 
					`uzsakovas`.`adresas`,
					`uzsakovas`.`pavadinimas`,
					`uzsakovas`.`el_pastas`,
					`uzsakovas`.`telefonas`,
					`uzsakovas`.`id`
					FROM `uzsakovas`
					WHERE `id`='{$id}'";
		$data = mysql::select($query);
		
		return $data[0];
	}
	
	/**
	 * Klientų sąrašo išrinkimas
	 * @param type $limit
	 * @param type $offset
	 * @return type
	 */
	public function getCustomersList($limit = null, $offset = null) {
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
		}
		if(isset($offset)) {
			$limitOffsetString .= " OFFSET {$offset}";
		}
		
		$query = "  SELECT 
					`uzsakovas`.`adresas`,
					`uzsakovas`.`pavadinimas`,
					`uzsakovas`.`el_pastas`,
					`uzsakovas`.`telefonas`,
					`uzsakovas`.`id`
					FROM `uzsakovas`
					ORDER BY `id` asc
					LIMIT {$limit} OFFSET {$offset}";
		$data = mysql::select($query);
		
		return $data;
	}
	
	/**
	 * Klientų kiekio radimas
	 * @return type
	 */
	public function getCustomersListCount() {
		$query = "  SELECT COUNT(`id`) as `kiekis`
					FROM `uzsakovas`";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
	
	/**
	 * Kliento šalinimas
	 * @param type $id
	 */
	public function deleteCustomer($id) {
		$query = "  DELETE FROM `uzsakovas`
					WHERE `id`='{$id}'";
		mysql::query($query);
	}
	
	/**
	 * Kliento atnaujinimas
	 * @param type $data
	 */
	public function updateCustomer($data) {
		$query = "  UPDATE `uzsakovas`
					SET    `adresas`='{$data['adresas']}',
						   `pavadinimas`='{$data['pavadinimas']}',
						   `el_pastas`='{$data['el_pastas']}',
						   `telefonas`='{$data['telefonas']}'
						   WHERE `uzsakovas`.`id`='{$data['id']}'";
		mysql::query($query);
	}
	
	/**
	 * Kliento įrašymas
	 * @param type $data
	 */
	public function insertCustomer($data) {
		$query = "  INSERT INTO `uzsakovas`
								(
									`id`,
									`pavadinimas`,
									`el_pastas`,
									`telefonas`,
									`adresas`
								) 
								VALUES
								(
									'{$data['id']}',
									'{$data['pavadinimas']}',
									'{$data['el_pastas']}',
									'{$data['telefonas']}',
									'{$data['adresas']}'
								)";
		mysql::query($query);
	}
	
	/**
	 * Sutarčių, į kurias įtrauktas klientas, kiekio radimas
	 * @param type $id
	 * @return type
	 */
	public function getContractCountOfCustomer($id) {
		$query = "  SELECT COUNT(`sutartys`.`nr`) AS `kiekis`
					FROM `klientai`
						INNER JOIN `sutartys`
							ON `klientai`.`asmens_kodas`=`sutartys`.`fk_klientas`
					WHERE `klientai`.`asmens_kodas`='{$id}'";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
		public function getMaxIdOfCustomers() {
		$query = "  SELECT MAX(`id`) AS `latestId`
					FROM `uzsakovas`";
		$data = mysql::select($query);
		
		return $data[0]['latestId'];
	}
}