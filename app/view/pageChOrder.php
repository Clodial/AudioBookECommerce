<?php
namespace app\view;
use app\model as model;

class pageChOrder extends model\pageTemplate{
	
	private $db;

	public function __construct($db){

		$this->db = $db;

	}

	public function getBody(){
		
		/**
		*
		* @brief -> Creates a form for people to check orders based on numbers
		*
		**/

		if(isset($_REQUEST['order'])){

			$this->displayOrder($_REQUEST['order']);
			
		}

		if(isset($_REQUEST['all'])){

			$orderArray = array();
			try{
				$stmt = $this->db->prepare('
					select order_ID from `order`');
				if($stmt->execute()){
					while($order = $stmt->fetch()){
						$this->displayOrder($order[0]);
					}
				}
			}catch(PDOException $e){

			}
		}

		$this->orderForm();

		if(isset($_SESSION['actType']) && $_SESSION['actType'] == 'employee'){
			try{
				$stmt = $this->db->prepare('
					select `order`.*, order_status.order_status from `order`, order_status 
					where (order_status.order_status = "complete" or order_status.order_status = "in-progress")
					and `order`.order_status_ID = order_status.order_status_ID
				');
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						echo '<div class="col-md-12">';
						echo '	<div class="col-md-4"';
						echo '		<h5>Order Number</h5>
									<p>'.$data[0].'</p>
									<h5>Order Date</h5>
									<p>'.$data[4].'</p>
								</div>
								<div class="col-md-6">
									<h4>Order Price</h4>
									<p>'.$data[5].'</p>
									<h3>Order Status</h3>
									<p>'.$data[6].'</p>
								</div>
								';
						echo '</div>';
					}
				}
			}catch(Exception $e){

			}

		}
		
	}

	public function orderForm(){

		echo '
		<div class="formBody">
		<h3>Find out your order status</h3> 
		<form method="get">
			<label>Order Number</label>
			<input type="text" name="order"><br>
			<button type="submit" name="page" value="pageChOrder">Check Order</button>
		</form>
		<form method="get">
			<input type="hidden" name="all" value="true">
			<button type="submit" name="page" value="pageChOrder">Check All Orders</button>
		</form>
		</div>
		';

	}
	public function displayOrder($orderId){

		try{
			$rowCheck = 0;
			$user = '';
			$card = '';
			$stat = '';
			$price = '';
			$date = '';
			$stmt = $this->db->prepare('
				select 
				account.account_username, customer_payment.card_number, order_status.order_status, `order`.order_total, `order`.order_date  
				from `order`, account, customer_payment, order_status 
				where `order`.order_ID = :order
					and `order`.account_ID = account.account_ID
					and `order`.card_ID = customer_payment.card_ID
					and `order`.order_status_ID = order_status.order_status_ID
					and order_status.order_status != "cart";
			');
			$stmt->bindParam(':order', $orderId);
			if($stmt->execute()){
				while($data = $stmt->fetch()){
					$user = $data[0];
					$card = $data[1];
					$stat = $data[2];
					$price = $data[3];
					$date = $data[4];
					$rowCheck += 1;
				}
			}
			if($rowCheck > 0){
				echo '
				<div class="orderDetail">
					username = ' . $user . '</br>
					card number = ' . $card . '</br>
					order status = ' . $stat . '</br>
					order price = ' . $price . '</br>
					order date = ' . $date . '</br>
				';
				$stmt = $this->db->prepare('
					select 
					order_item_detail.order_item_quantity, 
					inventory.item_name, 
					inventory.item_img
						from order_item_detail, inventory
						where order_item_detail.order_ID = :ord
						and inventory.item_ID = order_item_detail.item_ID
						group by inventory.item_name;
				');
				$stmt->bindParam(':ord', $_REQUEST['order']);
				if($stmt->execute()){
					while($data = $stmt->fetch()){
					echo '<div class="orderItem">
						<img src="data:image/jpeg;base64,'.base64_encode( $data[2] ).'" width=100px height=100px/>
						inventory name = ' . $data[1] . ' </br>
						quantity = ' . $data[0] . ' </br>
					';	
					echo '	</div>';
					}
				}
			
				echo '</div>
				';
				if(isset($_SESSION['actType']) && $_SESSION['actType'] == 'employee'){
					
					// Add the ability to modify orders in the database
					
					if(isset($_REQUEST['ordStat'])){
						try{
							echo $_REQUEST['ordStat'];
							$this->db->beginTransaction();
							$stmt = $this->db->prepare('
								update `order` 
								set order_status_ID = :ordStat
								where order_ID = :ord;
							');
							$stmt->bindParam(':ordStat',$_REQUEST['ordStat']);
							$stmt->bindParam(':ord', $orderId);
							$stmt->execute();
							$this->db->commit();
						}catch(Exception $e){
							$this->db->rollBack();
							echo 'Order Status Error';
						}
					}
					if(isset($_REQUEST['flightNum'])){
						try{
							$this->db->beginTransaction();
							$stmt = $this->db->prepare('
								update `order`
								set flight_num = :num
								where order_ID = :ord');
							$stmt->bindParam(':num', $_REQUEST['flightNum']);
							$stmt->bindParam(':ord', $orderId);
						}catch(Exception $e){
							$this->db->rollBack();
						}
					}

					echo '
					<div class="formBody">
						<form method="post">
							<label>Update Order Status</label>';
					$flights = bookAPIuse("https://web.njit.edu/~cmn6/IT490/testApi.php", 'getAddress', 0, 0);
					echo		'<select name="ordStat">';
					$stmt = $this->db->prepare('
						select * from order_status;');
					if($stmt->execute()){
						while($data = $stmt->fetch()){
							echo '<option value="' . $data[0] . '">' . $data[1] . '</option>';
						}
					}
					echo '	</select>
							<button type="submit" name="page" value="pageChOrder">Update Order
						</form>
					</div>';

				}
			}
		}catch(PDOException $e){

		}


	}

}

?>