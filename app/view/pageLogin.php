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
						<input type="password" name="password" required></br>
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
	
						$stmt = $this->db->prepare('select account.account_username, account_type.account_type from account, account_type 
													where account.account_username=:user 
													and account.account_password=:pass
													and account_type.account_type_ID = account.account_type_ID');
						
						$stmt->bindParam(':user', $_REQUEST['username']);
						$stmt->bindParam(':pass', $_REQUEST['password']);
						if($stmt->execute()){
							while($data = $stmt->fetch()){
								$rowCount = $rowCount + 1;
								$_SESSION['username'] = $data[0];
								$_SESSION['actType'] = $data[1];
							}
						}
						if($rowCount > 0){
							echo '<h4>Login Successful!';
							echo 'Welcome, ' . $_SESSION['username'] . '!';
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