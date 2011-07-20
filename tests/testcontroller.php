<?php

require '../settings.php';
require BASE_DIR.'/controller.php';
require '../../simplephptest/basictest.php';


class TestEasyDbController{
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


$tst1=new TestEasyDbController();
$testing=new BasicTest();
$testing->addClass($tst1);
$testing->runTests();
	
	