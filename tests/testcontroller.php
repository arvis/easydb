<?php

require '../settings.php';
require BASE_DIR.'/controller.php';
require '../../simplephptest/basictest.php';


class TestEasyDbController{
	private $controller; 
	private $uid=1;
	
	function __construct() {
		$this->controller= new EasyDbController(1);
		
		// how to set model domains from controller
		$this->controller->setDomains("easydb_tables_test","easydb_fields_test","easydb_data_test","easydb_users_test");

	}
	
	
	function testSetTable(){

		$table_data=array();
		$table_data['grid_name']="grid test";
		
		$table_id=1;
		// setTable($table_data,&$table_id){
		$result=$this->controller->setTable($table_data,$table_id);
		return $result==1;
	}
	
	function testSetTableFields(){
		
		$field=array();
		$field_arr=array();
		
		$field['id']=1;
		$field['field_name']="test_1";
		$field['field_type']="text";
		// $result=$this->model=editTableField($table_id,$this->uid,$field['id'],$field['field_name'],$field['field_type']);
		array_push($field_arr,$field);

		$field['id']=2;
		$field['field_name']="test_2";
		$field['field_type']="text";
		
		array_push($field_arr,$field);
		$table_id=1;
		
		$result=$this->controller->setTableFields($table_id,$field_arr);
		return $result==1;
	}
	
	
	
	function testSetTableData(){
		$json_data='{"4e2b228b73f07":"testva","id":"1234"}';
		$data_arr=array();
		$result=$this->controller->setTableData($json_data,$data_arr);
		
		//print_r($data_arr);
		
		return $data_arr['data'][0]['id']=='1234';
	}
	
	function testSetTableData_insert(){
		$json_data='{"4e2b228b73f07":"testva"}';
		$data_arr=array();
		$result=$this->controller->setTableData($json_data,$data_arr);
		
		return strval($data_arr['data'][0]['uid'])=='1';
	}

	
	function testSetTableData_multiple_values(){
		$json_data='[{"4e2b228b73f07":"testva","id":"1234"},{"4e2b228b73f07":"testva","id":"1"}]';
		$data_arr=array();
		$result=$this->controller->setTableData($json_data,$data_arr);
		
		return strval($data_arr['data'][1]['id'])=='1';
	}
	
	
	
	function testGetTableFields(){
		$table_id=1;
		$result=$this->controller->getTableFields($table_id);
		//print_r($result);
		return $result['1']['table_id']==1;
	
	}
	
	
	function testGetTableData(){
		$table_id=1;
		$result=$this->controller->getTableData($table_id);
		//print_r($result);
		
		//echo $result['1']['table_id'];
		
		return $result['1']['table_id']==1;
	
	
	}
	
	
}


$tst1=new TestEasyDbController();
$testing=new BasicTest();
$testing->addClass($tst1);
$testing->runTests();
	
	