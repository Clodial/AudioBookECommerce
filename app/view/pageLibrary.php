<?php
namespace app\view;
use app\model as model;

class pageLibrary extends model\pageTemplate{
	
	private $db;

	public function __construct($db){

		$this->db = $db;

	}

	public function getBody(){

		if(isset($_SESSION['username'])){

			

		}else{
			echo '<p>You must be an active user to look at your library.</p>';
			echo '<form method="get">';
			echo '	<button class="" type="submit" name="page" value="pageIndex">Go Home</button>';
			echo '</form>';
		}

	}


}
?>