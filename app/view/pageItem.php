<?php
namespace app\view;
use app\model as model;

class pageItem extends model\pageTemplate{
	
	//contains the data leading to each individual item page

	private $db;

	public function __construct($db){

		$this->db = $db;

	}

	public function getBody(){

		//show item data
		$bImage;
		$bName;
		$bAuth;
		$bGenre;
		$bPrice;
		$bDesc;
		if(isset($_REQUEST['itemName'])){
			try{
				$stmt = $this->db->prepare('
					select * from inventory where item_name = :name
				');
				$stmt->bindParam(':name',$bName);
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						$bImage = $data[6];
						$bName = $data[5];
						$bAuth = $data[3];
						$bGenre = $data[1];
						$bPrice = $data[4];
						$bDesc = $data[3];
					}
				}
			}catch(Exception $e){
				
			}

			$this->buildItemPage($bImage,$bName,$bAuth,$bGenre,$bPrice,$bDesc);

		}else{
			
		}
	}

	public function buildItemPage($img,$name,$auth,$genre,$price,$desc){

		

	}

} 

?>