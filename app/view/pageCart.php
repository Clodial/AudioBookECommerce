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

		/**
		*
		*	1) Display Cart Items (show the total price, and show the items in a page)
		*	2) Allow user to select payment option or update cart (ie remove specific items from cart)
		*	3) When a user chooses to purchase items, clear out a cart and add that info to a new order with item order details of each item for users to look up in an order-lookup
		*	4) Clear out payment options if there is nothing to purchase
		*
		**/
		
	}

}

?>