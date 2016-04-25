<?php
class bookApi{

	//create connection
	private $apiDb = NULL;

	public function __construct(){
		//initialize $db object, error out otherwise
		$apiName = 'mdm39';
		$apiPass = 'seminole9';
		$apiUser = 'mdm39';
		$apiHost = 'sql2.njit.edu';
		$mainEmail = 'maravillamatthew@gmail.com';

		try{
			$apiDb = new PDO("mysql:host=$apiHost;dbname=$apiName", $apiUser, $apiPass);
			$apiDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}

	public function useMethod(){

		echo 'yo yo yo yo yo bro';

	}

	public function getOrderData(){
		//Get the necessary account data and address data of a user
		printf($apiDb);
	}

}

?>