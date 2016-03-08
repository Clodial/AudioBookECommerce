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
	/*
	public function payment()
	{
		if(isset($_REQUEST['aCart']) && isset($_SESSION['username'])
			&& isset($_SESSION['actType']) && $_SESSION['actType'] == 'customer') /** Make sure the account is someone who can purchase **/
		/*{
			echo $_REQUEST['aCart'];
			
			try
			{
				$stmt = $this->db->prepare('delete * from cart_item where account_ID = :c_id');
				
				$stmt->bindParam(':c_id',$_REQUEST['c_acc_id']);
				
				if($stmt->execute())
				{
					while($data = $stmt->fetch())
					{
						echo 'Purchase complete';
					}
				}
			}
			catch(Exception $e)
			{
				echo 'Purchase failed';
			}
		}
		
	}*/
	public function getBody(){
		if(isset($_SESSION['username']) && isset($_SESSION['actType']) && $_SESSION['actType'] == 'customer'){
			try{
				$acName;
				$item;
				$tPrice = 0;
				echo '<form method="get">';
				$stmt = $this->db->prepare('select account_ID from account where account_username = :user');
				$stmt->bindParam(':user', $_SESSION['username']);
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						$acName = $data[0];
					}
				}
				//get item id
				$stmt = $this->db->prepare('select item_ID from cart_item where account_ID = :c_id');
				$stmt->bindParam(':c_id',$acName);
				if($stmt->execute())
				{
					while($data = $stmt->fetch()){
						$item = $data[0];
					}
				}
				//get price
				$stmt = $this->db->prepare('select inventory.item_price from cart_item, inventory where cart_item.item_ID = :item and inventory.item_ID = :item');
				$stmt->bindParam(':item', $item);
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						$tPrice = $tPrice + $data[0];
						echo ' <';
					}
				}
				echo '	<input type="hidden" name="tPrice" value="'. $tPrice .'">';
				$stmt = $this->db->prepare('select * from customer_payment where account_ID = :act');
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						echo '<input type="radio" name="card" value="'.$data[4].'">'.$data[3].'</br>';
					}
				}

				echo '</form>';
			}catch(Exception $e){

			}
		}
	}
	public function getFace(){

		/**
		*
		*	1) Display Cart Items (show the total price, and show the items in a page)
		*	2) Allow user to select payment option or update cart (ie remove specific items from cart)
		*	3) When a user chooses to purchase items, clear out a cart and add that info to a new order with 
		*		item order details of each item for users to look up in an order-lookup
		*	4) Clear out payment options if there is nothing to purchase
		*
		**/
		
		$pc_item = null;
		$pc_cost = 0;
		$pc_img = null;
		$pc_name = null;
		$pc_auth = null;
		$pc_genre = null;
		$pc_desc = null;
		$pc_price = null;
		
		$pc_cust_id = null;
		$pc_ccnum = null;
		
		$pc_itemID = null;
		
		
		if(isset($_SESSION['username']) && isset($_SESSION['actType']) && $_SESSION['actType'] == 'customer') /** Make sure the account is someone who can purchase **/
		{
			
			try
			{
				$acName;
				$stmt = $this->db->prepare('select account_ID from account where account_username = :user');
				$stmt->bindParam(':user', $_SESSION['username']);
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						$acName = $data[0];
					}
				}
				$stmt = $this->db->prepare('select item_ID from cart_item where account_ID = :c_id');
				
				$stmt->bindParam(':c_id',$acName);
				
				if($stmt->execute())
				{
					while($data = $stmt->fetch())
					{
						$pc_itemID = $data[0]; /** Wherever Item ID is in the table **/
						try
						{
							$stmt = $this->db->prepare("select * from inventory where item_id = :item");
							$stmt->bindParam(':item',$pc_itemID);
							if($stmt->execute())
							{
								while($data = $stmt->fetch())
								{
									$pc_img = $data[6];
									$pc_name = $data[5];
									$pc_auth = $data[2];
									$pc_genre = $data[1];
									$pc_desc = $data[3];
									$pc_price = $data[4];
									$pc_cost += $pc_price;
									
									echo
									'
									<div class = "Cart">
										<div class = "Cart Items">
											<h2>'.$pc_name.'</h2><br>
											<h3>By: '.$pc_auth.'</h3>
											<h5>'.$pc_price.'</h5><br>
											<p>'.$pc_name.'</p><br>
										</div>
									</div>
									';
								}
							}
						}
						catch(Exception $e)
						{
							
						}
						
					}
				}
				try
				{
					$stmt = $this->db->prepare('select card_ID from customer_payment where account_ID = :acc_id');
					$stmt->bindParam(':acc_id',$acName);
					if($stmt->execute())
					{
						while($data = $stmt->fetch())
						{
							$pc_ccnum = data[4];
							echo
							'	
								<input type=radio name="pay_option" value='.$pc_ccnum.'> '.$pc_ccnum.' <br>
							';
						}
					}
				}
				catch(Exception $e)
				{
					
				}
			}
			catch(Exception $e)
			{
				$this->db->rollBack();
				echo 'Transaction Failed: Cannot Access Cart';
			}
		}
	}

}
?>