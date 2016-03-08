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
		$bGenre = null;
		$bPrice = null;
		$bDesc = null;
		if(isset($_REQUEST['aCart']) && isset($_SESSION['username']) && isset($_SESSION['actType']) && $_SESSION['actType'] == 'customer'){
			
			try{
				//create the order
				$item;
				$account;
				$card = null;
				$date = date('Y-m-d');
				$order = null;
				$ordStat; // this is needed to make sure we have carted items

				$stmt = $this->db->prepare('select account_ID from account where account_username = :user');
				$stmt->bindParam(':user', $_SESSION['username']);
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						$account = $data[0];
					}
				}
				$stmt = $this->db->prepare('select item_ID from inventory where item_name = :item');
				$stmt->bindParam(':item', $_REQUEST['aCart']);
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						$item = $data[0];
					}
				}
				$stmt = $this->db->prepare('select card_ID from customer_payment where account_ID = :act and name_on_card = "void"');
				$stmt->bindParam(':act', $account);
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						$card = $data[0];
					}
				}
				$stmt = $this->db->prepare('select order_status_ID from order_status where order_status = "cart"');
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						$ordStat = $data[0];
					}
				}
				$this->db->beginTransaction();
				if($card == null){	//Create a null card in case there isn't already one
					$stmt = $this->db->prepare('
						insert into customer_payment(account_ID, name_on_card, billing_address, card_number, expDate, phNum) 
						values(:act,"void","void","void","void",0000)');
					$stmt->bindParam(':act',$account);
					$stmt->execute();
				}
				$this->db->commit();
				$stmt = $this->db->prepare('select card_ID from customer_payment where account_ID = :act and name_on_card = "void"');
				$stmt->bindParam(':act', $_REQUEST['username']);
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						echo 'yo';
						$card = $data[0];
					}
				}
				echo $card;
				$this->db->beginTransaction();
				$stmt = $this->db->prepare('select order_ID from `order` where order.card_ID = :card'); //set order to the null card to add items
				$stmt->bindParam(':card', $card);
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						$order = $data[0];
					}
				}
				echo $order;
				if($order == null){
					$stmt = $this->db->prepare('
						insert into `order`(account_ID, order_status_ID, card_ID, order_date, order_total)
						values(
							:act,
							:ord,
							:card,
							:date,
							0)
						');
					$stmt->bindParam(':act',$account);
					$stmt->bindParam(':ord',$ordStat);
					$stmt->bindParam(':card',$card);
					$stmt->bindParam(':date',$date);
					$stmt->execute();
					$stmt = $this->db->prepare('select order_ID from `order` where order.card_ID = :card'); //set order to the null card to add items
					$stmt->bindParam(':card', $card);
					if($stmt->execute()){
						while($data = $stmt->fetch()){
							$order = $data[0];
						}
					}
				}
				$stmt = $this->db->prepare('insert into order_item_detail(order_ID,item_ID,order_item_quantity)
					values(:ord,
						:item,
						1)');
				$stmt->bindParam(':ord',$order);
				$stmt->bindParam(':item',$item);
				$stmt->execute();
				$this->db->commit();
				echo 'Book Successfully in Cart';
			}catch(Exception $e){
				$this->db->rollBack();
				echo 'There was a problem.';
			}

		}else if(isset($_SESSION['actType']) && $_SESSION['actType'] == 'employee'){

			echo 'Employees cannot buy stuff';
			if(isset($_REQUEST['delete']) && isset($_REQUEST['aCart'])){
				try{
					$itemID;
					$this->db->beginTransaction();
					$stmt = $this->db->prepare('select item_ID from inventory where item_name = :item');
					$stmt->bindParam(':item',$_REQUEST['aCart']);
					if($stmt->execute()){
						while($data = $stmt->fetch()){
							$itemID = $data[0];
						}
					}
					$stmt = $this->db->prepare('delete from cart_item where item_ID = :itemID');
					$stmt->bindParam(':itemID',$itemID);
					$stmt->execute();
					$stmt = $this->db->prepare('delete from inventory where item_name = :item');
					$stmt->bindParam(':item',$_REQUEST['aCart']);
					$stmt->execute();
					$this->db->commit();
					echo 'This item does not exist anymore.';
				}catch(Exception $e){
					$this->db->rollBack();
				}
			}
		}

		if(isset($_REQUEST['itemName'])){
			try{
				$stmt = $this->db->prepare('
					select * from inventory where item_name = :name
				');
				$stmt->bindParam(':name',$_REQUEST['itemName']);
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						$bImage = $data[6];
						$bName = $data[5];
						$bAuth = $data[2];
						$bGenre = $data[1];
						$bPrice = $data[4];
						$bDesc = $data[3];
					}
				}
			}catch(Exception $e){

			}
			if(!isset($_REQUEST['delete'])){
				$this->buildItemPage($bImage,$bName,$bAuth,$bGenre,$bPrice,$bDesc);
			}

		}else{
			echo '404: Page Not Found';
		}
	}

	public function buildItemPage($img,$name,$auth,$genre,$price,$desc){
		$gName = null;
		try{
			$stmt = $this->db->prepare('
				select genre_name from genre where genre_ID = :gen
			');
			$stmt->bindParam(':gen', $genre);
			if($stmt->execute()){
				while($data = $stmt->fetch()){
					$gName = $data[0];
				}
			}
		}catch(Exception $e){

		}
		echo '
		<div class="itemBody">
			<div class="itemTop">
				<div class="itemPic">
					<img src="data:image/jpeg;base64,'.base64_encode( $img ).'" height=200px width=200px/>
				</div>
				<div class="itemInfo">
					<h2>'.$name.'</h2><br>
					<h3>By: '.$auth.'</h3>
					<h5>'.$price.'</h5><br>
					<p>'.$gName.'</p><br>
				</div>
			</div>
			<div class="itemBottom">
				<div class="itemDesc">
					<h2>Item Details</h2><br>
					<pre>'.$desc.'</pre>
				</div>
			</div>';
			if(isset($_SESSION['actType']) && $_SESSION['actType'] == 'customer'){
			echo '<form method="post">
					<input type="hidden" name="aCart" value="'.$name.'">
					<button type="submit" name="page" value="pageItem">Buy Item</button>
				</form>';
			}else if($_SESSION['actType'] == 'employee'){
				echo '<form method="post">
					<input type="hidden" name="aCart" value="'.$name.'">
					<input type="hidden" name="delete" value="true">
					<button type="submit" name="page" value="pageItem">Delete Item</button>
				</form>';
			}
		echo'</div>';

	}

} 

?>