<?php

//require 'simpledb.php';
//FIXME: need to remove after moving to model

require BASE_DIR.'/simpledb.php';
require BASE_DIR.'/pdo_model.php';

class BasicModel{
	private $db;
	private $dbType="simpledb";

	function __construct($dbType,$dbName="",$userName="",$password="",$dbOptions=false) {
		$this->dbType=$dbType;
		
		if ($dbType=="simpledb"){
			$this->setupSimpleDb($dbOptions);
		}
		else if ($dbType=="sqlite"){
			$this->setupSqlLite($dbName,$dbOptions=false);
		}
	
	}
	
	private function setupSimpleDb($dbOptions){
		//TODO: set amazon uid and key if set
		$this->db=new SimpleDb();
	
	}

	private function setupSqlLite($dbName,$dbOptions=false){
		//TODO: check if these options are present
		//echo "setting dbtype is  $dbName <br>";
		
		if (empty($dbName)){
			error_log("cannot connect- no db name ");
			die("cannot connect to database - no dbName");
			return;
		}
	
		$this->db=new PDOModel($dbName);

	}


	function selectItem($item_name,$domain=""){
		$result=$this->db->selectItem($item_name,$domain);
		return $result;
	}
	
	function deleteItem($item_name,$domain=""){
		
	
	}
	
	function insertItem($edit_data,$domain){
	
		$result=$this->db->insertItem($edit_data,$domain);
		return $result;
	}
	
	function editItem($id,$edit_data,$domain){
	
		if ($this->dbType=="simpledb"){
			if (!isset($edit_data['id']) || $edit_data['id']==0){
				$row_id=uniqid();
				$edit_data['id']=$row_id;
			}
		}
		
		$result=$this->db->editItem($id,$edit_data,$domain);
		return $result;
	}


}
