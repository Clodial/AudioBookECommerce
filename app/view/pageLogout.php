<?php
namespace app\view;
use app\model as model;

class pageLogout extends model\pageTemplate{
	
	private $db;

	public function __construct($db){

		$this->db = $db;

	}

	public function getBody(){

		if(isset($_SESSION['username'])){
			session_destroy();
			echo '<p>Logout Successful.</p>';
		}
		echo '<form method="get">';
		echo '	<button class="" type="submit" name="page" value="pageIndex">Go Home</button>';
		echo '</form>';

	}


}
?>