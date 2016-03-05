<?php
namespace app\view;
use app\model as model;

class pageActSettings extends model\pageTemplate{

	private $db;

	public function __construct($db){

		$this->db = $db;

	}

	public function getBody(){

		if(isset($_SESSION['actType']) && (isset($_SESSION['username']))){



		}else{

			echo 'Error: You are not supposed to be on this page';			

		}

	}
	
} 

?>