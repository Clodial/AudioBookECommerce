<?php
namespace app\view;
use app\model as model;

class pageBrowse extends model\pageTemplate{
	
	//page that shows book data based on

	private $db;

	public function __construct($db){

		$this->db = $db;

	}

	public function getBody()
		{
			try{
				$rowcount;
				$stmt = $this->db->prepare('select * from inventory where item_ID <= 3');
				if($stmt->execute())
				{
					while($data = $stmt->fetch())
					{
						$rowcount = $rowcount+1;
						echo 'Book1:'.$data[0].'';
					}
				}
			}catch(Exception $e)
			{
				echo'errrrrrrrrrrrror';
			}
		}

} 

?>