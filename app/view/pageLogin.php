<?php
namespace app\view;
use app\model as model;

class pageLogin extends model\pageTemplate{
	
	private $db;

	public function __construct($db){

		$this->db = $db;

	}

	public function getBody(){

		if(isset($_SESSION['username'])){
			echo '<form method="get">';
			echo '	<button class="" type="submit" name="page" value="pageIndex">Go Home</button>';
			echo '</form>';
		}else{

			/**
			*
			*	@brief main functionality for registration
			*
			**/

			if(!isset($_REQUEST['username'])){
				echo '
				<div id="registBody">
					<h2>Sign In</h2>
					<form method="get">
						<label>Username</label>
						<input type="text" name="username" required></br>
						<label>Password</label>
						<input type="text" name="password" required></br>
						<button type="submit" name="page" value="pageLogin">Sign In</button>
					</form>
					<form method="get">
						<button type="submit" name="page" value="pageRegister">Make New Account</button>
					</form>
				</div>
				';
			}else{

				if(isset($_REQUEST['username']) && isset($_REQUEST['password'])){
					try{

						/**
						* database stuff
						**/

						$rowCount = 0;
	
						$stmt = $this->db->prepare('select account_username, account_type_ID from account 
													where account_username="' . $_REQUEST['username'] . '" 
													and account_password="' . $_REQUEST['password'] . '"');
						
						if($stmt->execute()){
							while($data = $stmt->fetch()){
								$rowCount = $rowCount + 1;
								$_SESSION['username'] = $data[0];
								if($data[1] == 1){				
									$_SESSION['actType'] = "employee";
								}else{
									$_SESSION['actType'] = "customer";
								}
								echo $_SESSION['username'];
							}
						}
						if($rowCount > 0){
							echo '<h4>Login Successful!';
							echo '<form method="get">
									<button type="submit" name="page" value="pageActSettings">Check your Account</button>
								  </form>';
						}else{
							echo '<h4>Login Unsuccessful</h4>';
							echo '<div id="registBody">
									<h2>Sign In</h2>
									<form method="get">
										<label>Username</label>
										<input type="text" name="username" required></br>
										<label>Password</label>
										<input type="text" name="password" required></br>
										<button type="submit" name="page" value="pageLogin">Sign In</button>
									</form>
									<form method="get">
										<button type="submit" name="page" value="pageRegister">Make New Account</button>
									</form>
								</div>';
						}


					}catch(Exception $e){

						echo 'yo';

					}
				}

			}
		}

	}

}

?>