<?php

//require '../settings.php';
require 'controller.php';


$view=new EasyDbView();
$view->start();

//echo "{success:true}";

class EasyDbView {
	private $controller;
	private $uid=1234; //FIXME: testing
	
	function __construct(){
		$this->controller=new EasyDbController($this->uid);
	}

	function start(){
	
		// if no data set show nothing
		if (!isset($_POST['grid_action'])) {
			echo "{success:false}";
			return;
		}
		$action=$_POST['grid_action'];
		
		//echo "action is $action <br>";
		
		// TODO: evaluate security risks
		//$action=addslashes($action);
		
		if ($action=="get_table_data"){
			//echo "get_table_data in action";
			$this->getTableData();
		}
		else if ($action=="set_table_data"){
			$this->set_table_config();
		}
		
		
		else if ($action=="get_table_list"){
			$this->getTableList();
		}
		else if ($action=="get_table_fields"){
			$this->getTableFields();
		
		}
		else if ($action=="set_table_fields"){
		
		}
		else {
			// if not a valid action, just go away
		
		}
	}
	
	function getTableFields(){
		if (!isset($_POST['table_id']) || empty($_POST['table_id'])){
			echo "{success:false}";		
			return;
		}
	
		$table_id=$_POST['table_id'];
	
		$result=$this->controller->getTableFields($table_id);
		$this->displayResults($result);
		
		return true;
	}
	
	function getTableData(){
		$table_id=$_POST['table_id'];
	
		$result=$this->controller->getTableData($table_id);
		$this->displayResults($result);
		
		return true;
	}
	
	
	
	function getTableList(){
		$result=$this->controller->getTableList();
		//print_r($result);
		$this->displayResults($result);
		
		return true;
	}
	
	private function displayResults($data){
		if ($data<1){
			echo "{success:false}";		
			return;
		}
		$result_json;
		$result_json['success']=true;
		
		$result_json['data']=$data;
		
		echo json_encode($result_json);
		
		return true;
	
	}
	
	
	
	function set_table_config(){
	
		$grid_data_json=$_POST['grid_data'];
		$columns_json=$_POST['columns'];
		
		//TODO: prevent injections
		
		$grid_data=json_decode($grid_data_json,true);
		$columns=json_decode($columns_json,true);
		
		// if no column data go away
		if (empty($columns) || !isset($columns) ){
			echo "{success:false}";		
			return;
		}
		
		$result=$this->controller->saveTableData($grid_data,$columns,$this->uid);

		if (intval($result)==1 || $result==true)
			echo "{success:true}";
		else 
			echo "{success:false}";		
			
	
	}
	
}
