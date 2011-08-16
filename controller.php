<?php

require 'model.php';


class EasyDbController {

	private $model; 
	private $uid;
	
	
	function __construct($uid) {
		// 	function __construct($tables_domain_in="", $fields_domain_in="",$data_domain_in="",$users_domain_in="") {
		// $this->model= new EasyDbModel();
		$this->model= new EasyDbModel();
		$this->uid=$uid;
		
	}

	
	public function setUid($uid){
		$this->uid=$uid;
	}
	
	
	function saveTableConfig($table_data,$table_fields, $uid){
	
		//$table_id=0;
		$table_id=$table_data['id'];
/*
		//FIXME: testing
		echo "table input fields";
		print_r($table_data);
		print_r($table_fields);
		die();
*/		
		
		$result=$this->setTable($table_data,$this->uid);
		
		error_log("saving table data.");
		if (!$result){
			error_log("cannot save table config data.");
			return -1;
		}
		
		$result=$this->setTableFields($table_id,$table_fields);
		
		if (!$result){
			error_log("cannot save table fields config data. Some data might be lost");
			return -1;
		}
		
		return $result;
	
	}
	
	/**
	creates or updates information about table and returns new table id, if not existed
	*/
	
	public function setTable($table_data,&$table_id){
	
		$result=$this->model->editTable($table_id,$this->uid,$table_data['grid_name']);
		return $result;
	}
	
	/**
	gets all fields assiciated with table_id and their config info and returns it to server
	*/
	function getTableFields($table_id){
		$result=$this->model->getTableFields($table_id,$this->uid);	
		return $result;
	}
	
	function getTableData($table_id){
		$result=$this->model->selectAllFromTable($table_id,$this->uid);	
		
		// convert named array to array to array
		$result_arr=array_values($result);
		return $result_arr;
	}
	
	function setTableData($grid_data,$table_id, &$result_arr){
		
		// converting from JSON
		
		// if brace is not a first char, add to start and finish to simulate array
		//$brace_pos = strpos($grid_data, "[");
		$brace_pos = substr(trim($grid_data), 0, 1);

		//echo "brace is - $brace_pos <br>" ;
		
		if ($brace_pos != "[") {
			$grid_data="[".$grid_data."]";
		}
		
		$data_arr=json_decode($grid_data,true);
		
		//looping through data and setting 
		$success_arr=array();
		$result_arr=array();
		$result;
		
		foreach ($data_arr as &$field) {
			if (!isset($field['uid'])) $field['uid']=$this->uid;
			if (!isset($field['id'])) 
				$result=$this->model->insertRow($field,$this->uid,$table_id);
			else
				$result=$this->model->editRow($field['id'],$field,$table_id);
				
			array_push($success_arr,$result);
			$field['success']=$result;
		}
		
		$result_arr['data']=$data_arr;
		
		// searches if data update results were successfull or not
		if (in_array(0, $success_arr)) {
			$result_arr['success']=false;
			$result_arr['message']="Some data failed to save.";
			return 0;
		}
		else{
			$result_arr['success']=true;
			$result_arr['message']="Data save successfully.";
		} 
		
		
		return 1;

	
	}
	

	function setTableFields($table_id,$field_arr){
		
		foreach ($field_arr as $field) {
			//if (empty($field['id'])) $field['id']=
		
			//	function editTableField($table_id,$uid,$field_id,$field_name,$field_type){
			if ($field['field_type']=="auto") $field['field_type']="text";
			$field['uid']=$this->uid;

			$result=$this->model->editTableField($table_id,$field /*,$field['id'],$field['data_index'],$field['header'], $field['field_type']*/);
			//error_log("setTableFields result is ".$result);
			
		}
		return $result;
	}
	
	function getTableList(){
		$result=$this->model->getTableList($this->uid);	
		return $result;
		
	}
	
	
	function setDomains($tables_domain_in, $fields_domain_in,$data_domain_in,$users_domain_in){
		// TODO: investigate, maybe it's better to use something like reflection API
		$this->model->setDomains($tables_domain_in, $fields_domain_in,$data_domain_in,$users_domain_in);
	}
	
}