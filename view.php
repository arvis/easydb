<?php

//require '../settings.php';
require 'controller.php';


//view=new EasyDbView();
//view.start();

class EasyDbView {
	private $controller;
	
	function __construct(){
		$controller=new EasyDbController();
	}

	public function start(){
		$action=$_POST['grid_action'];
		
		//echo "action is $action <br>";
		
		// TODO: evaluate security risks
		//$action=addslashes($action);
		
		if ($action=="get_table_data"){
			echo "get_table_data in action";
			//return;
		}
		else if ($action=="set_table_data"){
		
		}
		
		
		else if ($action=="get_table_fields"){
		
		}
		else if ($action=="set_table_fields"){
		
		}
		else {
			// if not a valid action, just go away
		
		}
		
	}
	
	
}
