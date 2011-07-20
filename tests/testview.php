<?php

require '../settings.php';
require BASE_DIR.'/view.php';
require '../../simplephptest/basictest.php';


class TestEasyDbView{
	private $view; 
	function __construct() {
		$this->view= new EasyDbView();
	}

	function testStart(){
		$_POST['grid_action']="get_table_data";
		$result=$this->view->start();
		return true;
	}
}


$tst1=new TestEasyDbView();
$testing=new BasicTest();
$testing->addClass($tst1);
$testing->runTests();
	
	