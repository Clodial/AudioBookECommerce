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

				if((isset($_REQUEST['noc']) && isset($_REQUEST['billad']) && isset($_REQUEST['ccnum']) && isset($_REQUEST['rePass']) && isset($_REQUEST['expdate']) && isset($_REQUEST['number'])) && $_REQUEST['password'] == $_REQUEST['rePass'])
				{
					/**
					*
					*	database additions
					*
					**/

					$id = $_SESSION['account_id'];
					$noc = $_REQUEST['noc'];
					$billad = $_REQUEST['billad'];
					$ccnum = $_REQUEST['ccnum'];
					$expdate = $_REQUEST['expdate'];
					$phNum = $_REQUEST['number'];

					try
					{
						$this->db->beginTransaction();

						$stmt = $this->db->prepare('
								select * from payment where account_id = :id;
								');

						$stmt->bindParam(':id', $id);
						$stmt->execute();
						$stmt = $this->db->prepare('
									insert into customer_payment(account_ID , name_on_card, billing_address, card_number, expDate, phNum) 
									values(
										:id, 
										:nameCard, 
										:billad, 
										:cardNum, 
										:expDate, 
										:phNum);');
								$stmt->bindParam(':id', $id);
								$stmt->bindParam(':nameCard', $noc);
								$stmt->bindParam(':billad', $billad);
								$stmt->bindParam(':cardNum', $ccnum);
								$stmt->bindParam(':expDate', $expDate);
								$stmt->bindParam(':phNum', $phNum);
								$stmt->execute(); // like a git add
								echo '<h3>Payment Addition Successful</h3>';
								
								$this->db->commit(); // like a git commit

								echo '<form method="get">';
								echo ' 	<button type="submit" name="page" value="pageLogin">Sign In</button>';
								echo '</form>';
					}
					catch(Exception $e)
					{
						$this->db->rollBack(); 	// in case of any errors, this gets called and anything before the db->commit doesn't happen
						echo '<h3>User Information is incorrect</h3>';
						$this->newPayment();
					}
				}
				else
				{
					$this->newPayment();
				}
		}

		public function newPayment()
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
							<input type="text" name="number" required></br>
							</select></br>
							<button type="submit" name="page" value="pageRegister">Register Card</button>
						</form>
					</div>
					';

		}

	}
}
?>