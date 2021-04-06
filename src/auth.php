<?php
$name=$_POST['name'];
$pwd=$_POST['pass'];

$m=new MongoClient();
$db=$m->sumit_data;
$collec=$db->Newdata;
$result=$collec->find();
foreach ($result as $res) {
	if ($res['Name']==$name && $res['Password']==$pwd) {
		echo "CONGO U R IN !";
		break;
	}

echo "User authentication Denied!";
}
?>