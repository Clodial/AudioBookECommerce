<?php
namespace app\view;
use app\model as model;

class pageCart extends model\pageTemplate{

	/**
	*
	*	Page that shows all of the items in a user's current cart
	*
	**/

	private $db;

	public function __construct($db){

		$this->db = $db;

	}
	public function getBody(){
	
		if(isset($_SESSION['username']) && isset($_SESSION['actType']) && $_SESSION['actType'] == 'customer'){
			if(isset($_REQUEST['card']) && isset($_REQUEST['tPrice']) && isset($_REQUEST['order'])){
				try{
					$time = date('Y-m-d');
					$orderStatus;
					$stmt = $this->db->prepare('select order_status_ID from order_status where order_status = "in-progress"');
					if($stmt->execute()){
						while($data = $stmt->fetch()){
							$orderStatus = $data[0];
						}
					}
					echo $_REQUEST['order'];
					echo $_REQUEST['card'];
					$this->db->beginTransaction();
					$stmt = $this->db->prepare('update `order` set card_ID = :card, order_status_ID = :oStat, order_date = :date, order_total = :price where order_ID = :order');
					$stmt->bindParam(':card', $_REQUEST['card']);
					$stmt->bindParam(':oStat', $orderStatus);
					$stmt->bindParam(':date', $time);
					$stmt->bindParam(':price', $_REQUEST['tPrice']);
					$stmt->bindParam(':order', $_REQUEST['order']);
					$stmt->execute();
					$this->db->commit();
					echo '<h4>Purchase Successful!</h4>';
				}catch(Exception $e){
					$this->db->rollBack();
				}
			}
			if(isset($_REQUEST['cItem']) && isset($_REQUEST['delItem'])){
				try{
					$this->db->beginTransaction();
					$stmt = $this->db->prepare('delete from order_item_detail where order_detail_ID = :ord');
					$stmt->bindParam(':ord',$_REQUEST['cItem']);
					$stmt->execute();
					$this->db->commit();
					echo '<h3>Item Removed from Cart</h3>';
				}catch(Exception $e){
					$this->db->rollBack();
				}
			}

			$rowCount = 0;
			$acName;
			$item;
			$ord;
			$ordCart;
			$tPrice = 0;
			echo '<div class="leftBody">';
			try{
				$stmt = $this->db->prepare('select account_ID from account where account_username = :user');
				$stmt->bindParam(':user', $_SESSION['username']);
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						$acName = $data[0];
					}
				}
				$stmt = $this->db->prepare('select order_status_ID from order_status where order_status = "cart"');
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						$ordCart = $data[0];
					}
				}
				//Get the user's cart
				$stmt = $this->db->prepare('select order_ID from `order` where account_ID = :user and order_status_ID = :ordCart');
				$stmt->bindParam(':user', $acName);
				$stmt->bindParam(':ordCart', $ordCart);
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						$ord = $data[0];
					}
				}
				//get item id
				$stmt = $this->db->prepare('select item_ID from order_item_detail where order_ID = :ord');
				$stmt->bindParam(':ord',$ord);
				if($stmt->execute())
				{
					while($data = $stmt->fetch()){
						$item = $data[0];
					}
				}
				$stmt = $this->db->prepare('select order_ID from `order` where account_ID = :user and order_status_ID = :ordCart');
				$stmt->bindParam(':user', $acName);
				$stmt->bindParam(':ordCart', $ordCart);
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						$ord = $data[0];
					}
				}
				$stmt = $this->db->prepare('select inventory.item_price, inventory.item_img, inventory.item_name, order_item_detail.order_detail_ID 
					from order_item_detail, inventory 
					where order_item_detail.order_ID = :ord
					and order_item_detail.item_ID = inventory.item_ID');
				echo $ord;
				//$stmt->bindParam(':item', $item);
				$stmt->bindParam(':ord', $ord);
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						echo '<div class="smallItemBody">';
						echo '	<img src="data:image/jpeg;base64,'.base64_encode( $data[1] ).'" height=50px width=50px/>
								<div class="itemName">
									<h5>'.$data[2].'</h5>
									<p>$'.$data[0].'</p>
								</div>';
						echo '	<form method="post">
									<input type="hidden" name="cItem" value="'.$data[3].'">
									<input type="hidden" name="delItem" value="true">
									<button type="submit" name="page" value="pageCart">Remove From Cart</button>';
						echo ' 	</form>';
						echo '</div>';
					}
				}

			}catch(Exception $e){

			}
			echo '</div>';
			echo '<div class="rightBody">';
			echo '	<form method="get">';
			try{

				//get price
				$stmt = $this->db->prepare('select inventory.item_price 
					from order_item_detail, inventory 
					where order_item_detail.order_ID = :ord
					and order_item_detail.item_ID = inventory.item_ID');
				//$stmt->bindParam(':item', $item);
				$stmt->bindParam(':ord', $ord);
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						$tPrice = $tPrice + $data[0];
					}
				}
				echo '	<h5>Total Price: $'.$tPrice.'</h5>';
				echo '	<input type="hidden" name="tPrice" value="'. $tPrice .'">';
				echo '	<input type="hidden" name="order" value="'. $ord .'">';
				echo '	<label>Select Payment Options</label>Cash</br>';
				$stmt = $this->db->prepare('select * from customer_payment where account_ID = :act and name_on_card != "void"');
				$stmt->bindParam(':act', $acName);
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						$rowCount += 1;
						echo '<input type="radio" name="card" value="'.$data[0].'" required>'.$data[3].'</br>';
					}
				}
				
			}catch(Exception $e){

			}
			if($rowCount == 0){
				echo '	<h3>You do not have a card</h3>';
				echo '	<button class="" type="submit" name="page" value="pagePayment">Add Payment Option</button>';
			}else{
				echo '	<input type="hidden" name="order" value="'. $ord .'">';
				echo '	<button class="" type="submit" name="page" value="pageCart">Purchase Items</button>';
			}
			echo '	</form>';
			echo '</div>';
		}else if(isset($_SESSION['actType']) && $_SESSION['actType'] == 'employee'){
			echo '<h3>Employees cannot purchase items</h3>';
		}
	}

}
?>