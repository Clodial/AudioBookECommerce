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

			// User update section

			$this->updateInfo();

			if($_SESSION['actType'] == 'customer'){

				//customer settings

				$this->payAdd();

			}else{

				//employee settings

				$this->bookAdd();

			}

		}else{

			echo 'Error: You are not supposed to be on this page';			


		}

	}

	public function updateInfo(){

		/**
		*
		*
		*
		**/

	}
	
	public function payAdd(){

		/**
		*
		*
		*
		**/

	}

	public function bookAdd(){

		/**
		*
		*
		*
		**/

	}

} 

?>