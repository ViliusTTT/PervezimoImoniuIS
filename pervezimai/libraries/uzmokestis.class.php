<?php

/**

 * @author 
 */

class uzmokestis {
	
	public function __construct() {
		
	}
	
	
	public function getUzmokestisListByID($id) {
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
		}
		if(isset($offset)) {
			$limitOffsetString .= " OFFSET {$offset}";
		}
		
		$query = "  SELECT *
					FROM `uzmokestis`
					WHERE `uzmokestis`.`fk_darbuotojas2asmens_kodas`='{$id}'
					" . $limitOffsetString;
		$data = mysql::select($query);
		
		return $data;
	}
	
	
	public function insertUzmokestis($data) {
		foreach($data['id_uzsak'] as $key=>$val) {
				$query = "  INSERT INTO `uzmokestis`
										(
											`id`,
											`uz_kuri_menesi`,
											`suma`,
											`fk_darbuotojas2asmens_kodas`
										)
										VALUES
										(	
											'{$data['id_uzsak'][$key]}',
											'{$data['datos'][$key]}',
											'{$data['sumos'][$key]}',
											'{$data['id']}'
										)";
				mysql::query($query);
		}
		
	}	
	
	public function deleteUzmokescius($data) {
		foreach($data['id_uzsak'] as $key=>$val) {
			if($data['neaktyvus'] == array() || $data['neaktyvus'][$key] == 0) {
				$query = "DELETE FROM `uzmokestis`
					WHERE `id`='{$data['id_uzsak'][$key]}'";
				mysql::query($query);
			//	echo "{$data['id_SĄSKAITOS'][$key]} ";
			}
		}
	}
	
	public function deleteSas($id) {
		$query = "  DELETE FROM `uzmokestis`
			WHERE `id`='{$id}'";
		mysql::query($query);
	}
	
		public function deleteDalyvisPrices($id) {
		$query = "  DELETE FROM `uzmokestis`
					WHERE `	fk_darbuotojas2asmens_kodas`='{$id}'";
		mysql::query($query);
	}
	
	
}