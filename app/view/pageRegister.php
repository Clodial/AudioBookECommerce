<?php
namespace app\view;
use app\model as model;

class pageRegister extends model\pageTemplate{
	
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

			if((isset($_REQUEST['email']) && isset($_REQUEST['username']) && isset($_REQUEST['password']) && isset($_REQUEST['rePass']) && isset($_REQUEST['address']) && isset($_REQUEST['actType'])) && $_REQUEST['password'] == $_REQUEST['rePass']){
				
				/**
				*
				*	database additions
				*
				**/

				$email = $_REQUEST['email'];
				$user = $_REQUEST['username'];
				$pass = $_REQUEST['password'];
				$rePass = $_REQUEST['rePass'];
				$address = $_REQUEST['address'];
				$actType = $_REQUEST['actType'];
				$number = $_REQUEST['number'];

				if($pass == $rePass){

					$empCheck = 0;
					$custCheck = 0;
					// Checking if the database needs to create things for account types
					try{
						$stmt = $this->db->prepare('select * from account_type');
						if($stmt->execute()){
							while($data = $stmt->fetch()){
								if($data[1] == 'customer'){
									$custCheck = $custCheck + 1;
								}else if($data[1] == 'employee'){
									$empCheck = $empCheck + 1;
								}
							}
						}
					}catch(Exception $e){

						$this->newUser();

					}

					if(!($empCheck > 0)){
						try{
							$this->db->beginTransaction();
							$stmt = $this->db->prepare('insert into account_type values("2", "employee")');
							if($stmt->execute()){
								echo "did it";
							}
							$this->db->commit();
						}catch(Exception $e){
							$this->db->rollBack();
						}
					}
					if(!($custCheck > 0)){
						try{
							$this->db->beginTransaction();
							$stmt = $this->db->prepare('insert into account_type values("1", "customer")');
							if($stmt->execute()){
								echo "did it";
							}
							$this->db->commit();
						}catch(Exception $e){
							$this->db->rollBack();
						}
					}

					// Once that stuff works, then it does a try/catch block to add new item
				
					try{
						$this->db->beginTransaction(); //like git init
						$actCheck = 0;	

						// Check to see if there are any duplicate accounts in the database

						$stmt = $this->db->prepare('
							select * from account where account_username = :user;
							');
						$stmt->bindParam(':user', $user);
						$stmt->execute();
						while($data = $stmt->fetch()){
							$actCheck = $actCheck + 1;
						}
						$stmt = $this->db->prepare('
							select * from account where account_email = :email;
							');
						$stmt->bindParam(':email', $email);
						$stmt->execute();
						while($data = $stmt->fetch()){
							$actCheck = $actCheck + 1;
						}
						if($actCheck == 0){
							$stmt = $this->db->prepare('
								insert into account(account_type_ID, account_email, account_address, account_username, account_password, phone_num)
								values(
									:acType,
									:email,
									:address,
									:user,
									:pass,
									:phone);');
							$stmt->bindParam(':acType', $actType);
							$stmt->bindParam(':email', $email);
							$stmt->bindParam(':address', $address);
							$stmt->bindParam(':user', $user);
							$stmt->bindParam(':pass', $pass);
							$stmt->bindParam(':phone', $number);
							$stmt->execute(); // like a git add
							echo '<h3>Registration Successful</h3>';
						}else{
							echo '<h3>Account Exists</h3>';
						}
						$this->db->commit(); // like a git commit

						echo '<form method="get">';
						echo ' 	<button type="submit" name="page" value="pageLogin">Sign In</button>';
						echo '</form>';
					}catch(Exception $e){
						$this->db->rollBack(); 	// in case of any errors, this gets called and anything before the db->commit doesn't happen
						echo '<h3>User Information is incorrect</h3>';
						$this->newUser();
					}

				}

			}else{

				$this->newUser();

			}
		}

	}

	public function newUser(){

		echo '
				<div id="registBody">
					<h2>New User Sign Up</h2>
					<h5>Welcome!</h5>
					<form method="get">
						<label>Email</label>
						<input type="email" name="email" required><br>
						<label>Username</label>
						<input type="text" name="username" required></br>
						<label>Address</label>
						<select name="address">';
						$addArr = json_decode(bookAPIuse("https://web.njit.edu/~cmn6/IT490/testApi.php", 'getAddress', 0, 0));
						foreach($addArr as $key => $value){
							echo '<option value=' . $value . '>' . $value . '</option>'; 
						}
		echo 			'</select></br>
						<label>Phone Number</label>
						<input type="text" name="number" required></br>
						<label>Password</label>
						<input type="password" name="password" required></br>
						<label>Retype Password</label>
						<input type="password" name="rePass" required></br>
						<label>Account Type</label>
						<select name="actType" required>
							<option value="1">Customer</option>
							<option value="2">Employee</option>
						</select></br>
						<button type="submit" name="page" value="pageRegister">Register</button>
					</form>
					<form method="get">
						<button type="submit" name="page" value="pageLogin">Sign In</button>
					</form>
				</div>
				';

	}

}
?>