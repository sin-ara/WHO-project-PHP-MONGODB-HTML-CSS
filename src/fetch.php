<?php



       $c = new MongoClient();
				$db=$c->sumit_data;
        
        

	$data  = "<table style='border:1px solid black;";
	$data .= "border-collapse:collapse' border='1px'>";
	$data .= "<thead>";
	$data .= "<tr>";
	$data .= "<th>Name</th>";
	$data .= "<th>Gender</th>";
	$data .= "<th>Mobile_Number</th>";
	$data .= "<th>Email</th>";
	$data .= "</tr>";
	$data .= "</thead>";
	$data .= "<tbody>";

	try{
		$db = $c->sumit_data;
		$collection = $db->Newdata;
		$cursor = $collection->find();
		foreach($cursor as $document){
			$data .= "<tr>";
			$data .= "<td>" . $document["Name"] . "</td>";
			$data .= "<td>" . $document["Gender"]."</td>";
			$data .= "<td>" . $document["Mobile_Number"]."</td>";
			$data .= "<td>" . $document["Email"]."</td>";
			$data .= "</tr>";
		}
		$data .= "</tbody>";
		$data .= "</table>";
		echo $data;
	
	}catch(MongoException $mongoException){
		print $mongoException;
		exit;
	}
	
