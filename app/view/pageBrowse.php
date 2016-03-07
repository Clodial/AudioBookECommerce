<?php
namespace app\view;
use app\model as model;

class pageBrowse extends model\pageTemplate
{
	//page that shows book data based on

	private $db;

	public function __construct($db){

		$this->db = $db;

	}
	public function getBody(){

		try{

			$rowcount = 0;
			$stmt = $this->db->prepare('select * from inventory where item_ID <= 3');

			if($stmt->execute()){

				while($data = $stmt->fetch()){
					echo ''.$data[5].'-  Book ID:'.$data[0].' Author:'.$data[2].' Price: $'.$data[4].'<br>';
					$rowcount++;
				}
			}

		}catch(Exception $e){
			echo'errrrrrrrrrrrror';
		}

	}

} 

?>