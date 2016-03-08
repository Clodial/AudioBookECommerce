<?php
namespace app\view;
use app\model as model;

class pageLibrary extends model\pageTemplate{
	
	private $db;

	public function __construct($db){

		$this->db = $db;

	}

	public function getBody(){

		if(isset($_SESSION['username']) && isset($_SESSION['actType']) && $_SESSION['actType'] == 'customer'){

			$orderID;
			$stmt = $this->db->prepare('
				select order_status_ID from order_status where order_status = "complete"');
			if($stmt->execute()){
				while($data = $stmt->fetch()){
					$orderID = $data[0];
				}
			}
			$stmt = $this->db->prepare('
				select inventory.item_name, inventory.item_img from inventory, order_item_detail, `order`, account
				where account.account_ID = `order`.account_ID
					and order_item_detail.order_ID = `order`.order_ID
					and order_status_ID = :ordStat');
			$stmt->bindParam(':ordStat', $ordID);
			if($stmt->execute()){
				while($data = $stmt->fetch()){
					echo '<img src="data:image/jpeg;base64,'.base64_encode( $data[1] ).'" height=50px width=50px/>';
					echo '<h3>'.$data[0].'</h3>';
				}
			}	

		}else{
			echo '<p>You must be an active user to look at your library.</p>';
			echo '<form method="get">';
			echo '	<button class="" type="submit" name="page" value="pageIndex">Go Home</button>';
			echo '</form>';
		}

	}


}
?>