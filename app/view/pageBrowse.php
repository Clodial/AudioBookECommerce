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

			if(isset($_REQUEST['genre'])){
				$stmt = $this->db->prepare('select * from inventory where item_genre = :gen');
			}else{
				$stmt = $this->db->prepare('select * from inventory');
				if($stmt->execute()){
					while($data = $stmt->fetch()){
						echo '
						<div class="smItemBody">
							<div class="miniImg"><img src="data:image/jpeg;base64,'.base64_encode( $data[6] ).'" height=100px width=100px/></div>
							<div class="itemInfo">
								<form method="get">
									<input type="hidden" name="itemName" value="' . $data[5] . '">
									<button type="submit" name="page" value="pageItem">'. $data[5] .'</button>
								</form>
								<h6>By: ' . $data[2] .'</h6>
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

} 

?>