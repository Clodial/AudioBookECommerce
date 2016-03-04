<?php
namespace app\model;

abstract class pageTemplate{

	/****
	*
	*	@brief Main template of every page, including all of the most used functions
	*
	****/

	public function get(){
		$this->getHeader();
		$this->getBody();
		$this->getFooter();
	}
	public function post(){
		$this->getHeader();
		$this->getBody();
		$this->getFooter();
	}

	public function getHeader(){
		/**
		*
		*	@brief Creates header of page
		*
		**/

		echo '<div class="navbar" id="topNav">';
		echo '<form method="get">';
		echo 'This is the logo';
		echo '  <ul class="nav navbar-nav nav-left">';
		echo '  	<li><button class="" type="submit" name="page" value="pageIndex">Home</button></li>';
		echo '  	<li><button class="" type="submit" name="page" value="pageLibrary">Library</button</li>';
		echo '  	<li><button class="" type="submit" name="page" value="pageBrowse">Browse</button></li>';
		echo '  </ul>';
		echo '</form>';

		if(isset($_SESSION['username'])){

			/**
			*
			*	creates the navigation bar based if there is a user logged in
			*
			**/

			$this->userBar();

		}else{

			$this->logBar();

		}
		echo '</div>';

	}
	public function getBody(){
		/**
		*
		*	@brief Creates body of page
		*
		**/

	}
	public function getFooter(){
		/**
		*
		*	@brief Creates footer of page
		*
		**/

	}

	public function userBar(){

		/**
		*
		*	@brief the navigation bar for logged in users
		*
		**/

		//There will be a function call determining how much is in the cart to then be put after the cart number

		echo '<form method="get">';
		echo '	<ul class="nav navbar-nav nav-right">';
		echo '  	<li><button class="" type="submit" name="page" value="pageLogout">Logout</button</li>';
		echo '  	<li><button class="" type="submit" name="page" value="pageCart">Cart</button></li>';
		echo '  </ul>';
		echo '</form>';

	}
	public function logBar(){

		echo '<form method="get">';
		echo '	<ul class="nav navbar-nav nav-right">';
		echo '  	<li><button class="" type="submit" name="page" value="pageRegister">Register</button</li>';
		echo '  	<li><button class="" type="submit" name="page" value="pageLogin">Sign In</button></li>';
		echo '  </ul>';
		echo '</form>';

	}

}

?>