<?php
namespace app\view;
use app\model as model;

class pageActSettings extends model\pageTemplate{

	private $db;

	public function __construct($db){

		$this->db = $db;

	}

	public function getBody(){

		if(isset($_SESSION['actType']) && (isset($_SESSION['username']))){

			// User update section

			$this->updateInfo();

			if($_SESSION['actType'] == 'customer'){

				//customer settings

				$this->payAdd();

			}else{

				//employee settings

				$this->bookAdd();

			}

		}else{

			echo 'Error: You are not supposed to be on this page';			


		}

	}

	public function updateInfo(){

		$pass = '';
		$rePass = '';
		$email = '';
		$address = '';
		$phone = '';

		/**
		*
		*	Form for user to update their info
		*
		**/

		// check if needed variables are set
		if(isset($_REQUEST['pass']) || isset($_REQUEST['rePass']) || isset($_REQUEST['email']) || isset($_REQUEST['address']) || isset($_REQUEST['phone'])){



		}else{

			// Regular form asking for stuff
			$this->infoDump();

		}

	}
	
	public function payAdd(){

		/**
		*
		*	Button leading the user to add a new credit card
		*
		**/

		echo '
		<div class="rightForm">
			<h3>Payment Options</h3>
			<form method="get">
				<button type="submit" name="page" value="pagePay">Add Payment Options</button>
			</form>
		</div>';

	}

	public function bookAdd(){

		/**
		*
		*	Form to allow admins to create new books
		*
		**/
		if(isset($_REQUEST['bName']) && isset($_REQUEST['bAuth']) && isset($_REQUEST['bDesc']) && isset($_REQUEST['bPrice']) && isset($_FILES['bFile']) && isset($_REQUEST['bGenre'])){
			
			$fName = $_FILES['bFile']['name'];
			$fTemp = $_FILES['bFile']['tmp_name'];
			$fSize = $_FILES['bFile']['size'];
			$fType = $_FILES['bFile']['type'];

			//This creates the new book
			$this->newBook($_REQUEST['bName'],$_REQUEST['bAuth'],$_REQUEST['bDesc'],$_REQUEST['bPrice'],$_FILES['bFile'],$_REQUEST['bGenre']);

		}else{
			echo '<h3>Error Out</h3>';
		}

		try{
			//This is so people don't have to add new stuff whilly nilly manually
			$rowCheck = 0;
			$stmt = $this->db->prepare('select * from genre');
			if($stmt->execute()){
				while($data = $stmt->fetch()){
					$rowCheck = $rowCheck + 1;
				}
			}

			if(!($rowCheck > 0)){
				try{

					$this->db->beginTransaction();

					$stmt = $this->db->prepare('insert into genre(genre_name) values("Fiction");');
					$stmt->execute();
					$stmt = $this->db->prepare('insert into genre(genre_name) values("Sci-Fi");');
					$stmt->execute();
					$stmt = $this->db->prepare('insert into genre(genre_name) values("Drama");');
					$stmt->execute();
					$stmt = $this->db->prepare('insert into genre(genre_name) values("NonFiction");');
					$stmt->execute();
					$stmt = $this->db->prepare('insert into genre(genre_name) values("Mystery");');
					$stmt->execute();

					$this->db->commit();

				}catch(Exception $e){

					$this->db->rollBack();

				}

			}
		}catch(Exception $e){

		}

		echo '
		<div class="rightForm">
			<h3>Add Book</h3>
			<form method="post" enctype="multipart/form-data">
				<label>Book Name</label>
				<input type="text" name="bName" required></br>
				<label>Author</label>
				<input type="text" name="bAuth" required></br>
				<label>Item Description</label><br>
				<textarea name="bDesc" cols=40 rows=6 required></textarea></br>
				<label>Item Price</label>
				<input type="text" name="bPrice" required></br>
				<label>Book Image(jpg)</label>
				<input type="file" name="bFile" required></br>
				<select name="bGenre">';
		try{
			$stmt = $this->db->prepare('select * from genre;');
			if($stmt->execute()){
				while($data = $stmt->fetch()){
					$rowCheck + 1;
					echo '<option value="' . $data[0] . '"">' .  $data[1] . '</option>';
				}
			}
		}catch(Exception $e){
			echo 'database error';
		}
		echo   '</select></br>
				<button type="submit" name="page" value="pageActSettings">Add Book</button>
			</form>
		</div>
		';

	}

	public function infoDump(){

		/**
		*
		*	Creates form to update user info
		*
		**/

		echo '
				<div class="logBody">
					<h2>Update User Info</h2>
					<form method="get">
						<label>Email</label>
						<input type="text" name="email" required><br>
						<label>Address</label>
						<input type="text" name="address" required></br>
						<label>Phone Number</label>
						<input type="text" name="number" required></br>
						<label>Password</label>
						<input type="text" name="password" required></br>
						<label>Retype Password</label>
						<input type="text" name="rePass" required></br>
						<label>Account Type</label>
						<button type="submit" name="page" value="pageActSettings">Register</button>
					</form>
				</div>
				';


	}

	public function newBook($name,$auth,$desc,$price,$image){

		$fName = $image['name'];
		$fTemp = $image['tmp_name'];
		$fName = $image['name'];
		$fSize = $image['size'];
		$fType = $image['type'];

		try{
			$fContent = file_get_contents($fTemp);
			if($fType != 'jpeg'){
				//force an error
				throw new Exception("Images Must Be JPG or JPEG format");
			}else{
				// run new book object
				$this->db->beginTransaction();
				$stmt = $this->db->prepare('
					insert into inventory values(
						:gen,
						:auth,
						:det,
						:price,
						:name,
						:img,
						:date);
				');
				$this->db->commit();
			}
			//echo '<img src="data:image/jpeg;base64,'.base64_encode( $fContent ).'"/>';
		}catch(Exception $e){
			$this->db->rollBack();
			echo 'Try Again';
		}

	}

} 
?>