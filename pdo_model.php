<?php 
/*
Used tutorials

http://net.tutsplus.com/tutorials/php/why-you-should-be-using-phps-pdo-for-database-access/
*/
/**
PDO support for EasyDb database

*/
class PDOModel {

	private $dbName="easydb_test.db";
	private $db;
	
	// TODO: configure so I can set any database as PDO
	function __construct($dbname_in="",$dbtype="sqlite",$connection_string=""){
		try{
		
			if (empty($dbname_in))
				$this->dbName=$dbname_in;
		
			//$dbh = new PDO('mysql:host=localhost;dbname=test', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
		
			//$this->db=new PDO("sqlite:".$this->dbName);  
			$this->db=new PDO("sqlite:easydb_test.db");  
				
		}  
		catch(PDOException $e) {  
			$err_msg="ERROR: PDOModel construct  ".$e->getMessage();
			error_log($err_msg);   
		}
 		catch(Exception $e) {  
			$err_msg="ERROR: PDOModel construct  ".$e->getMessage();
			error_log($err_msg);   
		}
	}
	
	public function selectItem($item_id,$table_name){
		$where_arr=array('id'=>$item_id);
		
		$result=$this->selectData($where_arr,$table_name);
		
		if (sizeof($result)<1) return 0;
		
		return $result[0];
	}

	public function selectAll($table_name){
		$result=$this->selectData(false,$table_name);
		return $result;
	
	}
	/**
	selects data from db
	@param $where_arr - named array to create where statemend ie $where_arr array('id'=>1)
	
	*/
	
	public function selectData($where_arr,$table_name){
		//$item_name
		$sql="select * from $table_name";
		
		// if where array is not set select all values
		if ($where_arr){
			// TODO: better way to genereate where statements
			$sql_where=" where ";

			$i=1;
			foreach($where_arr as $key=>$value){
				if ($i>1) 
					$sql_where .= " AND $key=?";
				else
					$sql_where .= " $key=?";
					
				$i++;
			}
			$sql.=$sql_where;
		
		}
		//echo "$sql <br>";
		$statement = $this->db->prepare($sql);
		
		if ($statement===false){
			$err_msg="error occured";
			print_r($this->db->errorInfo());
			return -1;
		}
		else{
			//$statement->execute(array(':id' => $item_id));
			$statement->execute(array_values($where_arr));
		}
		
		$result = $statement->fetchAll();
		
		return $result;
	}
	
	
	public function customSelect($sql){
		$sql_values="";

	}
	
	public function insertItem($user_data,$table_name){
	
		$sql_values="";

		foreach($user_data as $key=>$value){
			$sql_values .="?,";
		}
		$sql_values=substr($sql_values,0,-1);
		$sql_fields=implode(array_keys($user_data),",");
	
		$sql="INSERT INTO $table_name ($sql_fields) values ($sql_values);";
		$result=$this->modifyData($sql,array_values($user_data),$results_arr);
		return $result;
	}
	
	public function editItem($item_id,$user_data,$table_name){
		
		$sql_values="";
		
		// if there is id item in update delete it from array
		// if (isset($user_data['id'])) unset($user_data['id']);

		foreach($user_data as $key=>$value){
			// TODO: better make it as named params
			$sql_values .="$key=?,";
			
		}
		$sql_values=substr($sql_values,0,-1);
		$sql="UPDATE $table_name set $sql_values where id=?;";
		$values_arr=array_values($user_data);
		array_push($values_arr,$item_id);
		
		$results_arr=array();
		$result=$this->modifyData($sql,$values_arr,$results_arr);
		
		return $result;
	
	}
	
	
	

	/**
	unified insert update function
	performs operation and returns result
	@param $sql - sql with unnamed Placeholders
	@param	$values_arr - array with values
	@param  $results_arr - OUT returns with result array or error array
	@return success/failure (1/0)
	
	*/
	
	public function modifyData($sql,$values_arr,&$results_arr){
		try {
				
			//echo "$sql <br> ";
			$statement = $this->db->prepare($sql);  
			
			if ($statement===false){
				$err_msg="error occured";
				print_r($this->db->errorInfo());
				return -1;
			}
			else{
				$statement->execute($values_arr);  	
			}
			
			return 1;
		}  
		catch(PDOException $e) {  
			$this->db->rollBack();
			$err_msg="ERROR: PDOModel construct  ".$e->getMessage();
			error_log($err_msg);   
			return -1;
		}

	}
	


}

