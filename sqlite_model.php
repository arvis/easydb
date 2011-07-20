<?php 

// create new database using the OOP approach

$db=new SQLiteDatabase("db.sqlite");

// create table 'USERS' and insert sample data

$db->query("BEGIN;

        CREATE TABLE users (id INTEGER(4) UNSIGNED PRIMARY KEY,
name CHAR(255), email CHAR(255));

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