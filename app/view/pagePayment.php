<?php
namespace app\view;
use app\model as model;

class pagePayment extends model\pageTemplate{

	private $db;

	public function __construct($db){

		$this->db = $db;

	}

	public function getBody(){
		
		/**
		*
		* @brief -> creates the Add Payment Method functionality for the page
		*
		**/

		if(isset($_SESSION['username'])){

			$this->newPayment();
		}
		else
		{
			echo '<h2>Please Sign In To Add A New Payment</h2>';
			echo '<form method="get">';
			echo '	<button class="" type="submit" name="page" value="pageLogin">Sign In</button>';
			echo '</form>';
		}
	}

	public function newPayment(){

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

?>