<?php 
//http://www.switchonthecode.com/tutorials/php-tutorial-creating-and-modifying-sqlite-databases

class SqliteModel {



	private $dbName="easy.db";
	private $db;
	
	function __construct($dbname_in){
		if (isset($dbname_in))
			$this->dbName=$dbname_in;
	
		$this->db=new SQLiteDatabase($this->$dbName);
	}
	
	private function createDatabase(){
	
	}
	
	public function insertRow(){
	
	}
	
	public function updateRow(){
	
	}
	public function deleteRow(){
	
	}
	public function createTable($table_name, $table_cols){
	
        //CREATE TABLE users (id INTEGER(4) UNSIGNED PRIMARY KEY,name CHAR(255), email CHAR(255));
		$query = "CREATE TABLE $table_name 
			(id INTEGER(6) UNSIGNED PRIMARY KEY ";
        
		foreach ($table_cols as $col) {
		//foreach ($arr as $value) {
			echo "Key: $key; Value: $value<br />\n";
			$query .=", ".$col['field_name']." ".$col['field_type'] ;
		}
		$query .=");"; 
		 
		if(!$database->queryExec($query, $error))
		{
		  die($error);
		}
		
		return true;
	}
	
	public function selectData($table_name,$rows,$criteria,&$return_dataset){
		$query = "SELECT * FROM Movies";
		if($result = $database->query($query, SQLITE_BOTH, $error))
		{
		  while($row = $result->fetch())
		  {
			print("Title: {$row['Title']} <br />" .
				  "Director: {$row['Director']} <br />".
				  "Year: {$row['Year']} <br /><br />");
		  }
		}
		else
		{
		  die($error);
		}
	}
	
	
	
	


}

/*

// create new database using the OOP approach

$db=new SQLiteDatabase("db.sqlite");

// create table 'USERS' and insert sample data

$db->query("BEGIN;

        CREATE TABLE users (id INTEGER(4) UNSIGNED PRIMARY KEY,name CHAR(255), email CHAR(255));

        INSERT INTO users (id,name,email) VALUES
(NULL,'User1','user1@domain.com');

        INSERT INTO users (id,name,email) VALUES
(NULL,'User2','user2@domain.com');

        INSERT INTO users (id,name,email) VALUES
(NULL,'User3','user3@domain.com');

        COMMIT;");

// fetch rows from the 'USERS' database table

$result=$db->query("SELECT * FROM users");

// loop over rows of database table

while($result->valid()) {

    // fetch current row

    $row=$result->current();

    print_r($row);

    // move pointer to next row

    $result->next();

}

// fetch rows from the 'USERS' database table

$result=$db->query("SELECT * FROM users");

// loop over rows of database table

while($row=$result->fetch(SQLITE_ASSOC)){

    // fetch current row

    echo $row['id'].' '.$row['name'].' '.$row['email'].'<br />';

}

//displays the following:

1 User1 user1@domain.com

$rows=$result->fetchAll();

foreach($rows as $row){

   echo 'Id: '.$row['id'].'  Name: '.$row['name'].' Email: '.$row
['email'].'<br />';

}

while($row=$result->fetchObject()){

    // fetch current row

    echo $row->id.' '.$row->name.' '.$row->email.'<br />';

}

*/