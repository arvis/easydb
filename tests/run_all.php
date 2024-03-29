<?php

require '../settings.php';

//require BASE_DIR.'/simpledb.php';
require BASE_DIR.'/model.php';

require '../../simplephptest/basictest.php';


class TestSimpleDb{
	private $sdb; 
	function __construct() {
		$this->sdb= new SimpleDb();
	}
	
	//TODO start and end functions that create and delete test domain
	
	function test_simple_insert(){
		$user_data=array();
		$user_data['uid']=1;
		$user_data['table_id']= 1;
		$user_data['name']= 'janis';
		$user_data['cost']= 10.25;
		
		$response=$this->sdb->editItem('1_1',$user_data,"easydb_data_test");
		//$user_data=$this->sdb->edit_item('1_1');
				
		return $response==1;
	}

	function test_simple_edit(){
		$user_data=array();
		$user_data['uid']=1;
		$user_data['id']=1;
		$user_data['table_id']= 1;
		$user_data['name']= 'janis un peteris';
		$user_data['cost']= 10.25;
		
		$response=$this->sdb->editItem(1,$user_data,"easydb_data_test");
		//$user_data=$this->sdb->edit_item('1_1');
	
		return $response==1;
	}
	
	function test_simple_select(){
		$user_data=$this->sdb->selectItem('1',"easydb_data_test");
		return $user_data['uid']=='1';
	}
	
	function test_simple_delete(){
		//TODO: not yet implemented
		return false;
	}

}

$tst1=new TestSimpleDb();
$testing=new BasicTest();
$testing->addClass($tst1);
$testing->runTests();

class TestBasicModel{
	private $model; 
	function __construct() {
		//$this->model= new EasyDbModel("easydb_tables_test","easydb_fields_test","easydb_data_test","easydb_users_test");
		$this->model= new EasyDbModel();
		$this->model->setDomains("easydb_tables_test","easydb_fields_test","easydb_data_test","easydb_users_test");
	}

	
//FIXME: really test if changes are there, for now testing is done only by judging from return values mostly
	
//FIXME: disabled until we will properly set up testing enviroment, works for now
/*	
	function testCreateTable(){
		$result=$this->model->createTable("my table",1);
		return $result==1;
	}
*/	
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
		$row_data['id']=1;
		$row_data['value']="test edited ".uniqid();
		$row_data['table_id']=1;
		$result=$this->model->editRow(1,$row_data);

		return $result==1;
	}
	
	
	function testGetTableList(){
		$result=$this->model->getTableList(1);
		return $result['1']['table_id']=='1';
	}
	
	function testGetTableFields(){
		$result=$this->model->getTableFields(1);
		
		//TODO: better test results
		return $result['1']['table_id']=='1';
	}
	
	
	function testGetTableColumnData(){
		// get all columns for this table
		$result=$this->model->getTableFields(1);
		//print_r($result);
		
		return $result['1']['table_id']=='1';
	}

	
	function testDeleteRow(){
		return -1;
	}
	
	
}

$tst2=new TestBasicModel();
$testing2=new BasicTest();
$testing2->addClass($tst2);
$testing2->runTests();

$tst3=new TestBasicModel("sqlite");
$testing3=new BasicTest();
$testing3->addClass($tst3);
$testing3->runTests();



