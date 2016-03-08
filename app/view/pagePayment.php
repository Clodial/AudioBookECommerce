<?php
namespace app\view;
use app\model as model;

class pagePayment extends model\pageTemplate
{

	private $db;

	public function __construct($db)
	{

		$this->db = $db;

	}

	public function getBody()
	{
		/**
		*
		* @brief -> creates the Add Payment Method functionality for the page
		*
		**/
		if(!isset($_SESSION['username']))
		{

			echo '<h2>Please Sign In To Add A New Payment</h2>';
			echo '<form method="get">';
			echo '	<button class="" type="submit" name="page" value="pageLogin">Sign In</button>';
			echo '</form>';
		}
		else
		{

			if((isset($_REQUEST['noc']) && isset($_REQUEST['billad']) && isset($_REQUEST['ccnum']) && isset($_REQUEST['expdate']) && isset($_REQUEST['phNum'])))
			{
				/**
				*
				*	database additions
				*
				**/

				$id;
				$noc = $_REQUEST['noc'];
				$billad = $_REQUEST['billad'];
				$ccnum = $_REQUEST['ccnum'];
				$expdate = $_REQUEST['expdate'];
				$phNum = $_REQUEST['phNum'];

				try
				{
					$this->db->beginTransaction();

					$stmt = $this->db->prepare('
							select account_id from account where account_username = :name;
							');

					$stmt->bindParam(':name', $_SESSION['username']);
					if($stmt->execute())
					{
						while($data = $stmt->fetch()){
								$id = $data[0];
						}
					}
					$stmt = $this->db->prepare('
								insert into customer_payment(account_ID , name_on_card, billing_address, card_number, expDate, phNum) 
								values(
									:id, 
									:noc, 
									:billad, 
									:ccnum, 
									:expDate, 
									:phNum);');
							$stmt->bindParam(':id', $id);
							$stmt->bindParam(':noc', $noc);
							$stmt->bindParam(':billad', $billad);
							$stmt->bindParam(':ccnum', $ccnum);
							$stmt->bindParam(':expDate', $expDate);
							$stmt->bindParam(':phNum', $phNum);
							$stmt->execute(); // like a git add
							echo '<h3>Payment Addition Successful</h3>';
							
							$this->db->commit(); // like a git commit

							echo '<form method="get">';
							echo ' 	<button type="submit" name="page" value="pageCart">To Checkout</button>';
							echo '</form>';
				}
				catch(Exception $e)
				{
					$this->db->rollBack(); 	// in case of any errors, this gets called and anything before the db->commit doesn't happen
					echo '<h3>User Information is incorrect</h3>';
					$this->newPayment();
				}
			}else{
				$this->newPayment();
			}
		}
	}

	 function newPayment()
	{

		echo '
				<div id="paymentBody">
					<h2>Add A New Payment</h2>
					<form method="get">
						<label>Name on Card</label>
						<input type="text" name="noc" required><br>
						<label>Billing Address</label>
						<input type="text" name="billad" required></br>
						<label>Credit Card Number</label>
						<input type="text" name="ccnum" required></br>
						<label>Expiration Date</label>
						<input type="text" name="expdate" required></br>
						<label>Phone Number</label>
						<input type="text" name="phNum" required></br>
						</select></br>
						<button type="submit" name="page" value="pagePayment">Register Card</button>
					</form>
				</div>
				';

	}
}
?>