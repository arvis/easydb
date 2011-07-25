<?php
require 'simpledb.php';


class EasyDbModel {

	private $sdb;
	private $fields_domain="easydb_fields";
	private $data_domain="easydb_data";
	private $tables_domain="easydb_tables";
	private $users_domain="easydb_users";
	
	private $user_data;
	
	
	function __construct($tables_domain_in="", $fields_domain_in="",$data_domain_in="",$users_domain_in="") {
		$this->sdb= new SimpleDb($data_domain_in);
		
		if (!empty($tables_domain_in)) $this->tables_domain=$tables_domain_in;
		if (!empty($fields_domain_in)) $this->fields_domain=$fields_domain_in;
		if (!empty($data_domain_in)) $this->data_domain=$data_domain_in;
		if (!empty($users_domain_in)) $this->users_domain=$users_domain_in;
	}

	function setDomains($tables_domain_in="", $fields_domain_in="",$data_domain_in="",$users_domain_in=""){
		if (!empty($tables_domain_in)) $this->tables_domain=$tables_domain_in;
		if (!empty($fields_domain_in)) $this->fields_domain=$fields_domain_in;
		if (!empty($data_domain_in)) $this->data_domain=$data_domain_in;
		if (!empty($users_domain_in)) $this->users_domain=$users_domain_in;
	}
	
	
	/**
	gets users data from database
	@param uid- user id
	*/
	function getUserData($uid){
		$this->user_data=$this->sdb->selectItem($uid,$this->users_domain);
		return 1;
	}
	
	/**
	gets users data from database
	@param uid- user id
	@param user_data- array of user data
	*/
	function setUserData($uid,$user_data){
		$response=$this->sdb->editItem($uid,$user_data,$this->users_domain);
		return $response;
	}
	
	/**
	generates table id and creates it
	*/
	function createTable($table_name,$uid){
	
		//TODO: get maximum id to assign to 
		// Table id is in following format uid_uniqid 
		$table_id=$uid."_".uniqid();
		$result=$this->editTable($table_id,$table_name,$uid);
		
		return $result;
	}

	/**
	edits  table or creates it if id does not exist
	@return 1 if success
	*/
	function editTable(&$table_id,$uid,$table_name){
		try {
		
			error_log("starting  editTable");
			if (empty($table_id)) $table_id=uniqid();
			
		
			$user_data=array();
			$user_data['uid']=$uid;
			$user_data['id']=$table_id;
			$user_data['table_name']= $table_name;
			
			$response=$this->sdb->editItem($table_id,$user_data,$this->tables_domain);
			//$user_data=$this->sdb->edit_item('1_1');
			error_log("response from simpledb is ".$response);
			
					
			return $response;
		} catch (Exception $e) {
			$err_msg='editTable exception: '.$e->getMessage();
			error_log($err_msg);
			return -1;
		}
		
	}
	
	
	function createTableField($table_id,$uid,$field_name,$field_type){
		$field_id=uniqid();
		$result=$this->editTableField($table_id,$uid,$field_id,$field_name,$field_type);
		return $result;
	}
	
	
	/**
	edits or inserts (if not exists) field configuration data for specified table
	*/
	//function editTableField($table_id,$uid,$field_id,$field_name,$field_header,$field_type){
	function editTableField($table_id,$user_data){
		//$user_data=array();
		
		if (empty($field_id))
			$field_id=uniqid();
		else
			$field_id=$user_data['id'];
		
		$user_data['id']=$field_id;
		$user_data['table_id']=$table_id;
		
		$result=$this->sdb->editItem($field_id,$user_data,$this->fields_domain);
		
		return $result;
	}
	
	function dropTable($table_id){
	
		return -1;
	}
	
	function getTableCount($uid){
		$sql="SELECT count(*) FROM `".$this->tables_domain."` where uid='$uid'";
		$result=$this->sdb->customSelect($sql);
		return $result;
	}
	
	function getTableList($uid){
		$sql="SELECT * FROM `".$this->tables_domain."` where uid='$uid'";
		$result=$this->sdb->customSelect($sql);
		return $result;
	}
	
	function getTableFields($table_id){
		//SELECT id,field_caption,field_type FROM `easydb_fields_test` where table_id='1'
		$sql="SELECT * FROM `".$this->fields_domain."` where table_id='$table_id'";
		$result=$this->sdb->customSelect($sql);
		return $result;
	}
	
	//function selectAllFromTable($var_name, $table_id){
	function selectAllFromTable($table_id,$uid){
	
		//TODO: prevent SQL injections
		// escape characters
		$table_id=$this->custom_escape_string($table_id);
		
		//$sql="SELECT * FROM `".$this->data_domain."` where $var_name='$table_id'";
		$sql="SELECT * FROM `".$this->data_domain."` where table_id='$table_id'";
		$result=$this->sdb->customSelect($sql);
		return $result;
	}

	function getRecord($row_id){
		$result=$this->sdb->selectItem($row_id,$this->data_domain );
		return $result;
	}
	
	function insertRow($uid, $row_data){
		$row_id=uniqid();
		$result=$this->editRow($uid,$row_id, $row_data);
		return $result;
	}
	
	function editRow($uid,$row_id,$row_data){
		$row_data['id']=$row_id;
		$row_data['uid']=$uid;
		$result=$this->sdb->editItem($row_id,$row_data,$this->data_domain);
		return $result;
	}
	
	function deleteRow($row_id){
		//FIXME: need to be implemented
		return -1;
	}

	public function custom_escape_string($data) {
        if ( !isset($data) or empty($data) ) return '';
        if ( is_numeric($data) ) return $data;

        $non_displayables = array(
            '/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
            '/%1[0-9a-f]/',             // url encoded 16-31
            '/[\x00-\x08]/',            // 00-08
            '/\x0b/',                   // 11
            '/\x0c/',                   // 12
            '/[\x0e-\x1f]/'             // 14-31
        );
        foreach ( $non_displayables as $regex )
            $data = preg_replace( $regex, '', $data );
        $data = str_replace("'", "''", $data );
        return $data;
    }	
	
	

}
