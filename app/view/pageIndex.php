<?php
namespace app\view;
use app\model as model;

class pageIndex extends model\pageTemplate{
	
	private $db;

	public function __construct($db){

		$this->db = $db;

	}

	public function getBody(){
		
		/**
		*
		* @brief -> creates the index page functionality for the website
		*
		**/

		$this->displayFaves();

		$this->displayNew();

	}
	public function displayFaves(){

		/**
		*
		*	displays favorite books based on number in order_item_detail
		*
		**/

	}
	public function displayNew(){

		/**
		*
		*	displays favorite books based on what's the most recent
		*
		**/
		echo '<div class="indexDisplay">';
		echo '	<h3>New Additions</h3>';
		try{

			$stmt = $this->db->prepare('select item_name, item_img, item_price from inventory order by date_added desc limit 4');
			if($stmt->execute()){
				while($data = $stmt->fetch()){
					echo $data[0];
					echo $data[2];
				}
			}

		}catch(Exception $e){

		}
		echo '</div>';
	}
}

?>