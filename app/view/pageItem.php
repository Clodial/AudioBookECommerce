<?php
namespace app\view;
use app\model as model;

class pageItem extends model\pageTemplate{
	
	//contains the data leading to each individual item page

	private $db;

	public function __construct($db){

		$this->db = $db;

	}

	public function getBody(){

		//show item data
		$bImage;
		$bName;
		$bAuth;
		$bGenre;
		$bPrice;
		$bDesc;
		if(isset($_REQUEST['aCart']) && isset($_SESSION['username']) && isset($_SESSION['actType']) && $_SESSION['actType'] == 'customer'){

			try{
				$item;
				$account;
				$stmt = $this->db->prepare('select account_ID from account where account_username = :user');
				$stmt->bindParam(':user', $_SESSION['username']);
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						$account = $data[0];
					}
				}
				$stmt = $this->db->prepare('select item_ID from inventory where item_name = :item');
				$stmt->bindParam(':item', $_REQUEST['aCart']);
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						$item = $data[0];
					}
				}
				$this->db->beginTransaction();
				$stmt = $this->db->prepare('
					insert into cart_item(item_ID, account_ID) values(:item, :act);
				');
				$this->db->commit();
				echo 'Book Successfully in Cart'
			}catch(Exception $e){
				$this->db->rollBack();
				echo 'There was a problem.';
			}

		}
		if(isset($_REQUEST['itemName'])){
			try{
				$stmt = $this->db->prepare('
					select * from inventory where item_name = :name
				');
				$stmt->bindParam(':name',$bName);
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						$bImage = $data[6];
						$bName = $data[5];
						$bAuth = $data[3];
						$bGenre = $data[1];
						$bPrice = $data[4];
						$bDesc = $data[3];
					}
				}
			}catch(Exception $e){

			}

			$this->buildItemPage($bImage,$bName,$bAuth,$bGenre,$bPrice,$bDesc);

		}else{
			echo '404: Page Not Found';
		}
	}

	public function buildItemPage($img,$name,$auth,$genre,$price,$desc){
		$gName;
		try{
			$stmt = $this->db->prepare('
				select genre_name from genre where genre_ID = :gen
			');
			$stmt->bindParam(':gen', $genre);
			if($stmt->execute()){
				while($data = $stmt->fetch()){
					$gName = $data[0];
				}
			}
		}catch(Exception $e){

		}

		echo '
		<div class="itemBody">
			<div class="itemTop">
				<div class="itemPic">
					<img src="data:image/jpeg;base64,'.base64_encode( $img ).'"/>
				</div>
				<div class="itemInfo">
					<h2>'.$name.'</h2><br>
					<h3>By: '.$auth.'</h3>
					<h5>'.$price.'</h5><br>
					<p>'.$gName.'</p><br>
				</div>
			</div>
			<div class="itemBottom">
				<div class="itemDesc">
					<h2>Item Details</h2><br>
					<pre>'.$desc.'</pre>
				</div>
			</div>
			<form method="post">
				<input type="hidden" name="aCart" value="'.$name.'">
				<button type="submit" name="page" value="pageItem">Buy Item</button>
			</form>
		</div>'

	}

} 

?>