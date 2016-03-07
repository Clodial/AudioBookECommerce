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

		try{
			$rowCheck = 0;
			$stmt = $this->db->prepare('
				select * from order_status;
			');
			if($stmt->execute()){
				while($stmt->fetch()){
					echo 'yo';
					$rowCheck = $rowCheck + 1;
				}
			}
			if(!($rowCheck > 0)){
				try{
					echo 'yo';
					$this->db->beginTransaction();
					$stmt = $this->db->prepare('insert into order_status(order_status) values("in-progress");');
					$stmt->execute();
					$stmt = $this->db->prepare('insert into order_status(order_status) values("complete");');
					$stmt->execute();
					$stmt = $this->db->prepare('insert into order_status(order_status) values("canned");');
					$stmt->execute();
					$stmt = $this->db->prepare('insert into order_status(order_status) values("cart");');
					$stmt->execute();
					$this->db->commit();
				}catch(Exception $e){
					$this->db->rollBack();
				}
			}
		}catch(Exception $e){

		}

		if(isset($_REQUEST['order'])){

			try{
				$user = '';
				$card = '';
				$stat = '';
				$price = '';
				$date = '';
				$stmt = $this->db->prepare('
					select 
					account.account_username, payment.card_number, order_status.order_status, order.order_total, order.order_date  
					from order, account, payment, order_status 
					where order.order_ID = :order
						and order.account_ID = account.account_ID
						and order.card_ID = payment.card_ID
						and order.order_status_ID = order_status.order_status_ID;
				');
				$stmt->bindParam(':order', $_REQUEST['order']);
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						$user = $data[0];
						$card = $data[1];
						$stat = $data[2];
						$price = $data[3];
						$date = $data[4];
					}
				}
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
						where order_item_detail.order_ID = :ord;
				');
				$stmt->bindParam(':ord', $_REQUEST['order']);
				if($stmt->execute()){
					while($data = $stmt->fetch()){
					echo '<div class="orderItem">
						<img src="data:image/jpeg;base64,'.base64_encode( $data[2] ).'"/>
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
							$this->db->beginTransaction();
							$stmt = $this->db->prepare('
								update order 
								set order_status_ID = :ordStat
								where order_ID = :ord;
							');
							$stmt->bindParam(':ordStat',$_REQUEST['ordStat']);
							$stmt->bindParam(':ord',$_REQUEST['order']);
							$stmt->execute();
							$this->db->commit();
						}catch(Exception $e){
							$this->db->rollBack();
							echo 'Order Status Error';
						}
					}

					echo '
					<div class="formBody">
						<form method="post">
							<label>Update Order Status</label>
							<select name="ordStat">';
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

			}catch(Exception $e){

			}

		}else{



		}

		$this->orderForm();

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
		</div>
		';

	}

}

?>