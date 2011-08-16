<?php

require '../settings.php';

//require BASE_DIR.'/simpledb.php';
require BASE_DIR.'/model.php';

require '../../simplephptest/basictest.php';

//TODO: later enable in model.php
//require BASE_DIR.'/pdo_model.php';


class TestPDOModel{
	private $sdb; 
	function __construct() {
		$this->sdb= new PDOModel();
		//CREATE TABLE easydb_tables (id INTEGER PRIMARY KEY ASC, uid CHAR(255), table_header CHAR(255), table_id CHAR(255));
	}
	
	//TODO start and end functions that create and delete test domain
	
	function test_simple_insert(){
		$user_data=array();
		$user_data['uid']=1;
		$user_data['table_id']= 1;
		$user_data['table_header']= 'name'.uniqid();
		
		$response=$this->sdb->insertItem($user_data,"easydb_tables");
		//$user_data=$this->sdb->edit_item('1_1');
				
		return $response==1;
	}

	function test_simple_edit(){
		$user_data=array();
		$user_data['id']=1;
		$user_data['uid']=1;
		$user_data['table_id']= 1;
		$user_data['table_header']= 'name edit '.uniqid();
		
		$response=$this->sdb->editItem(1,$user_data,"easydb_tables");
		//$user_data=$this->sdb->edit_item('1_1');
	
		return $response==1;
	}
	
	function test_simple_select(){
		$user_data=$this->sdb->selectItem('1',"easydb_tables");
		return $user_data['uid']=='1';
	}
	
	function test_simple_delete(){
		//TODO: not yet implemented
		return false;
	}
}

$tst1=new TestPDOModel();
$testing=new BasicTest();
$testing->addClass($tst1);
$testing->runTests();

/*
class TestBasicModelSqlite{
	private $model; 
	function __construct() {
		// 	function __construct($tables_domain_in="", $fields_domain_in="",$data_domain_in="",$users_domain_in="") {
		$this->model= new EasyDbModel("easydb_tables_test","easydb_fields_test","easydb_data_test","easydb_users_test");
	}

	
//FIXME: really test if changes are there, for now testing is done only by judging from return values mostly
	
	function testEditTable(){
		$table_name="my test table ".uniqid();
		$table_id=1;
		$result=$this->model->editTable($table_id,1,$table_name);
		return $result==1;
	}
	
	
	function testeditTableField_create(){
		//($table_id,$uid,$field_id,$field_name,$field_type)
		$field_id=false;
		
		$user_data['uid']=1;
		$user_data['table_id']= 1;
		$user_data['name']= "test_field";
		$user_data['field_header']= "test field";
		$user_data['field_type']= "text";
		
		
		//$result=$this->model->editTableField($field_id,1,1,"my test","text");
		$result=$this->model->editTableField($field_id,$user_data);
		return $result==1;
	}
	
	function testEditTableField_edit(){
		//($table_id,$uid,$field_id,$field_name,$field_type)

		$field_id=1;
		$user_data['uid']=1;
		$user_data['id']=1;
		$user_data['table_id']= 1;
		$user_data['data_index']= "test_field";
		$user_data['header']= "test field edited";
		$user_data['field_type']= "text";

		$result=$this->model->editTableField($field_id,$user_data);
		return $result==1;
	}
	
	function testSelectAllFromTable(){
		//$result=$this->model->selectAllFromTable("table_id",1);
		$result=$this->model->selectAllFromTable(1,1);
		
		return $result['1']['id']==1;
	}

	function testDeleteTable(){
		$result=$this->model->dropTable(1);
		return $result==1;
	}

	
	function testInsertRow(){
		$row_data=array();
		$row_data['value']="test1".uniqid();
		$row_data['table_id']=1;
		
		$result=$this->model->insertRow(1,$row_data);
		
		return $result==1;
	}
	
	function testEditRow(){
		$row_data=array();
		
		$row_data['value']="test edited ".uniqid();
		$result=$this->model->editRow(1,1,$row_data);
		$row_data['table_id']=1;

		return $result==1;
	}
	
	
	function testGetTableList(){
		$result=$this->model->getTableList(1);
		return $result['1']['table_id']=='1';
	}
	
	
	function testGetTableColumnData(){
		// get all columns for this table
		$result=$this->model->getTableFields(1);
		return $result['1']['table_id']=='1';
	}

	
	function testDeleteRow(){
		return -1;
	}
	
	
}

$tst3=new TestBasicModelSqlite();
$testing3=new BasicTest();
$testing3->addClass($tst3);
$testing3->runTests();
*/

