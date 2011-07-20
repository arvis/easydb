<?php

require 'model.php';


class EasyDbController {
	private $model; 
	function __construct() {
		// 	function __construct($tables_domain_in="", $fields_domain_in="",$data_domain_in="",$users_domain_in="") {
		$this->model= new EasyDbModel();
	}

	function get_table_fields($table_id){
	
	}

	function set_table_fields($table_id,$field_arr){

	}
	
	function get_table_data($table_id){
	
	}
	
	function set_table_data($table_id,$data_arr){
	
	}
	
	
}