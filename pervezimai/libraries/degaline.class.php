<?php

/**

 */

class degaline {
	
	public function __construct() {
		
	}
	
	
	public function getDegalineListByID($id) {
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
		}
		if(isset($offset)) {
			$limitOffsetString .= " OFFSET {$offset}";
		}
		
		$query = "  SELECT *
					FROM `degaline`
					WHERE `fk_vilkikas`='{$id}'
					" . $limitOffsetString;
		$data = mysql::select($query);
		
		return $data;
	}
	
	
	public function insertDegalines($data) {
		foreach($data['id_degalines'] as $key=>$val) {
				$query = "  INSERT INTO `degaline`
										(
											`pavadinimas`,
											`kuro_kiekis`,
											`id`,
											`fk_vilkikas`
										)
										VALUES
										(	
											'{$data['pavadinimai'][$key]}',
											'{$data['kiekiai'][$key]}',
											'{$data['id_degalines'][$key]}',
											'{$data['id']}'
										)";
				mysql::query($query);
		}
		
	}	
	
	public function deleteDegalines($data) {
		foreach($data['id_degalines'] as $key=>$val) {
			if($data['neaktyvus'] == array() || $data['neaktyvus'][$key] == 0) {
				$query = "  DELETE FROM `degaline`
					WHERE `id`='{$data['id_degalines'][$key]}'";
				mysql::query($query);
			//	echo "{$data['id_SĄSKAITOS'][$key]} ";
			}
		}
	}
	
	public function deleteSas($id) {
		$query = "  DELETE FROM `degaline`
			WHERE `id`='{$id}'";
		mysql::query($query);
	}
	
		public function deleteDegalinePrices($id) {
		$query = "  DELETE FROM `degaline`
					WHERE `fk_vilkikas`='{$id}'";
		mysql::query($query);
	}
	
	
}