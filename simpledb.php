<?php
//TODO: need other way to get htdocs root path 
require $_SERVER['DOCUMENT_ROOT'].'/sdk/sdk.class.php';


class SimpleDb {
	private $sdb;
	private $domain_name="easydb_data";

	function __construct() {
	
		$this->sdb = new AmazonSDB();
		//$domain_name=$domain;
	}

	function setDomainName($in_domain="easydb_data") {
		$this->domain_name=$in_domain;
        }

	public function getDomainName(){
		return $this->$domain_name;
	}

	public function selectItem($item_name,$domain){
		try {
			//if ($domain=="") $domain=$this->domain_name;
		
			$attrs=$this->sdb->getAttributes($domain,$item_name);
			$data_arr=array();
			//echo $attrs->body->GetAttributesResult->asXML();

			foreach($attrs->body->GetAttributesResult->Attribute as $attr){
					// TODO: return data as XML
					$data_arr[(string)$attr->Name[0]]=(string)$attr->Value[0];
			}

			return $data_arr;
		} catch (Exception $e) {
			$err_msg='select_item exception: '.$e->getMessage();
			error_log($err_msg);
			return -1;
		}
	}
	
	public function customSelect($sql){
		try {
			$response = $this->sdb->select($sql);
			
			//if (!$response) return false;
			
			$data_arr=array();
			$itm=$response->body->SelectResult->Item;
			
			// checking if nothing is returned
			if (!isset($itm) || $itm==null) return array();
			
			foreach($itm as $item_data){
				$row_name=(string)$item_data->Name;
				$row_arr=array();
				foreach($item_data->Attribute as $Attribute){
					$item_name=(string)$Attribute->Name;
					$item_value=(string)$Attribute->Value;
					$row_arr[$item_name]=$item_value;
				}
				$data_arr[$row_name]=$row_arr;
			}	
			return $data_arr;
			
		} catch (Exception $e) {
			$err_msg='custom_select exception: '.$e->getMessage();
			error_log($err_msg);
			return -1;
		}
	
	}
	
	public function insertItem($user_data,$domain){
		$row_id=uniqid();
		$user_data['id']=$row_id;
		$result=$this->editItem($row_id,$user_data,$domain);
		
		return $result;
	}
	
	public function editItem($item_name,$user_data,$domain){
		try {
		$attributes=array();
		$response=$this->convertUserData($user_data,$attributes);
		//if ($domain=="") $domain=$this->domain_name;
		
			if ($response==-1){
				return -1;
			}
		
		$response=$this->sdb->put_attributes($domain,$item_name,$attributes,true);
		
		if ($response->isOK() ) {
			return 1;
		}	
		else{
			return -1;
		}	
			
		} catch (Exception $e) {
			$err_msg='edit_item exception: '.$e->getMessage();
			error_log($err_msg);
			return -1;
		}
	}
	
	
	private function convertUserData(&$user_data,&$attrs){
		try {
			foreach( $user_data as $key => $value){
				$attrs[$key]= array("value" => $value);
			}

		return 1;
			
		} catch (Exception $e) {
			$err_msg='edit_item exception: '.$e->getMessage();
			error_log($err_msg);
			return -1;
		}
	
	
	}
	
	public function createDomain($domain_name){
	
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