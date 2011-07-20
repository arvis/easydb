<?php

require '../settings.php';

require BASE_DIR.'/simpledb.php';
require BASE_DIR.'/model.php';

require '../../simplephptest/basictest.php';


class TestSimpleDb{
	private $sdb; 
	function __construct() {
		$this->sdb= new SimpleDb("easydb_data_test");
	}
	
	//TODO start and end functions that create and delete test domain
	
	function test_simple_insert(){
		$user_data=array();
		$user_data['uid']=1;
		$user_data['id']=1;
		$user_data['table_id']= 1;
		$user_data['name']= 'janis';
		$user_data['cost']= 10.25;
		
		$response=$this->sdb->editItem('1_1',$user_data);
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
		
		$response=$this->sdb->editItem(1,$user_data);
		//$user_data=$this->sdb->edit_item('1_1');
	
		return $response==1;
	}
	
	function test_simple_select(){
		$user_data=$this->sdb->selectItem('1');
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
		// 	function __construct($tables_domain_in="", $fields_domain_in="",$data_domain_in="",$users_domain_in="") {
		$this->model= new EasyDbModel("easydb_tables_test","easydb_fields_test","easydb_data_test","easydb_users_test");
	}

	
//FIXME: really test if changes are there, for now testing is done only by judging from return values mostly
	
//FIXME: disabled until we will properly set up testing enviroment, works for now
/*	
	function testCreateTable(){
		$result=$this->model->createTable("my table",1);
		return $result==1;
	}
*/	
	function testEditTableData(){
		$table_name="my test table ".uniqid();
		$result=$this->model->editTable(1,$table_name,1);
		return $result==1;
	}
	
	function testDeleteTable(){
		$result=$this->model->dropTable(1);
		return $result==1;
	}
	
	function testCreateFieldsConfig(){
		//($table_id,$uid,$field_id,$field_name,$field_type)
		$result=$this->model->createTableField(1,1,"my test","text");
		return $result==1;
	}
	function testEditFieldsConfig(){
		//($table_id,$uid,$field_id,$field_name,$field_type)
		$result=$this->model->editTableField(1,1,1,"my test edit","text");
		return $result==1;
	}
	
	function testSelectAllFromTable(){
		$result=$this->model->selectAll(1);
		//print_r($result);
		
		return $result['1']['id']==1;
	}
	
	function testInsertRow(){
		$row_data=array();
		$row_data['name']="test1".uniqid();
		$result=$this->model->insertRow(1,$row_data);
		
		return $result==1;
	}
	
	function testEditRow(){
		$row_data=array();
		
		$row_data['name']="test edited ".uniqid();
		$result=$this->model->editRow(1,1,$row_data);
		return $result==1;
	}
	
	function testGetTableCount(){
		$result=$this->model->getTableCount(1);
		return $result==1;
	}
	
	function testGetTableColumnData(){
		// get all columns for this table
		$result=$this->model->getColumnData(1);
		return $result==1;
	
	}
	
	function testDeleteRow(){
		return -1;
	}
	
	function testCanCreateTable(){
		return -1;
	}
	
	function testCanAddRow(){
		return -1;
	}
	
	
	
}

$tst2=new TestBasicModel();
$testing2=new BasicTest();
$testing2->addClass($tst2);
$testing2->runTests();


