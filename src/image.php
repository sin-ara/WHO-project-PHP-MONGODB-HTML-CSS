<!doctype html>
<html>
<head>
<meta charset="utf-8">	
</head>
<body>
<?php
/*
<id>/[<size>]/[<crop>]/[<name>][?v]
original.jpg/500x300/0,0,250,250/new.png
>	Grabs original jpg image (from data uri stored in MongoDB)
>	Resizes to 500x300
>	Crops to 250x250 starting at coords 0,0
>	Returns a png image with a new name
---
class Image {
	public $_id;  // file name
	public $data; // data url
	public $size; // file size
	public $time; // modification
	public $type; // mime
}
*/
class Item {
	public $_id;
	
	function __construct(array $fields = array()) {
		foreach ($fields as $field => $value) {
			$this->$field = stripslashes($value);
		}
		if (empty($this->_id)) {
			$this->_id = (string) new ItemID;
		}
	}
}
class ItemID {
	function __tostring() {
		$template = '%04X%04X-%04X-%04X-%04X-%04X%04X%04X';
		return sprintf(
			$template, 
			mt_rand(0, 65535), 
			mt_rand(0, 65535), 
			mt_rand(0, 65535), 
			mt_rand(16384, 20479), 
			mt_rand(32768, 49151), 
			mt_rand(0, 65535), 
			mt_rand(0, 65535), 
			mt_rand(0, 65535)
		);
	}
}
$mong = new Mongo;
$db = $mong->test;
function parseQuery( $template )
{
	$values = array_slice( func_get_args(), 1 );
	$query = vsprintf( $template, $values );
	$query = json_decode( $query, true );
	$query = var_export( $query, true );
	
	return eval("return $query;");
}
if ($_FILES) {
	
	$fileName = $_FILES['afile']['name'];
	$fileType = $_FILES['afile']['type'];
	$fileContent = file_get_contents($_FILES['afile']['tmp_name']);
	$dataUrl = 'data:' . $fileType . ';base64,' . base64_encode($fileContent);
	
	$item = new Item(array(
		'name' => $fileName,
		'type' => $fileType,
		'data' => $dataUrl
	));
	
	$db->images->insert($item);
	
	echo "<a href=?name=$fileName&type=$fileType>$fileName</a>";
}
if ($_GET) {
	$fileName = $_GET['name'];
	$fileType = $_GET['type'];
	$query = '{"name":"%s", "type":"%s"}';
		
	header("Content-type: $fileType");
	$item = $db->images->findOne( parseQuery($query, $fileName, $fileType) );
	
	echo "<img src=\"{$item['data']}\">";
}
?>

<form method=post>
	<p><label>Title: <input name=title size=100></label></p>
	<p><label>Summary:<br><textarea name=summary cols=100 rows=2></textarea></label></p>
	<p><label>Path: <input name=path size=100></label></p>
	<p><input type=submit value="Yep"></p>
</form>

<hr>

<form method=post enctype="multipart/form-data">
	<p><label>Image: <input type=file name="afile" accept="image/*"></p>
	<p><input type=submit value="Upload"></p>
</form>

</body>
</html>