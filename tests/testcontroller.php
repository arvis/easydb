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
		$table_data['table_name']="grid test";
		
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
}


$tst1=new TestEasyDbController();
$testing=new BasicTest();
$testing->addClass($tst1);
$testing->runTests();
	
	