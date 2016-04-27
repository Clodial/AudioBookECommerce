<?php
namespace app\view;
use app\model as model;

class pageBrowse extends model\pageTemplate
{
	//page that shows book data based on

	private $db;

	public function __construct($db){

		$this->db = $db;

	}
	public function getBody(){

		try{
			$this->genreSelector();
			if(isset($_REQUEST['genre'])){
				$stmt = $this->db->prepare('select * from inventory where genre_ID = :gen');
				$stmt->bindParam(':gen', $_REQUEST['genre']);
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						echo '
						<div class="smItemBody col-md-12">
							<div class="miniImg col-md-4"><form method="get"><input type="hidden" name="itemName" value="' . $data[5] . '"><input class="imgButton" type="image" name="page" value="pageItem" alt="submit" src="data:image/jpeg;base64,'.base64_encode( $data[6] ).'" height=100px width=100px/></form></div>
							<div class="itemInfo col-md-5">
								<form method="get">
									<input type="hidden" name="itemName" value="' . $data[5] . '">
									<button type="submit" name="page" value="pageItem">'. $data[5] .'</button>
								</form></br>
								<h6>By: ' . $data[2] .'</h6></br>
								<p>Price: ' . $data[4] . '</h6></br>
							</div>
						</div> 
						';
					}
				}
			}else{
				$stmt = $this->db->prepare('select * from inventory');
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						echo '
						<div class="smItemBody col-md-12">
							<div class="miniImg col-md-4"><form method="get"><input type="hidden" name="itemName" value="' . $data[5] . '"><input class="imgButton" type="image" name="page" value="pageItem" alt="submit" src="data:image/jpeg;base64,'.base64_encode( $data[6] ).'" height=100px width=100px/></form></div>
							<div class="itemInfo">
								<form method="get">
									<input type="hidden" name="itemName" value="' . $data[5] . '">
									<button type="submit" name="page" value="pageItem">'. $data[5] .'</button>
								</form></br>
								<h6>By: ' . $data[2] .'</h6></br>
								<p>Price: ' . $data[4] . '</h6></br>
							</div>
						</div> 
						';
					}
				}
			}
		}catch(Exception $e){
			echo'errrrrrrrrrrrror';
		}
	}
	public function genreSelector(){

		echo '
		<div class="formBody top-piece">
			<form method="get">
				<select name="genre">';
		try{
			$stmt = $this->db->prepare('select * from genre;');
			if($stmt->execute()){
				while($data = $stmt->fetch()){
					echo '<option value="'.$data[0].'">'.$data[1]. '</option>';
				}
			}
		}catch(Exception $e){

		}
		echo '	</select>
				<button type="submit" name="page" value="pageBrowse">Search Genre</button>
			</form></br>

			<h2>Browse AudioBook Selection</h2>
		</div>';

	}

} 

?>