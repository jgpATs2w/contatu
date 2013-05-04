<?php
echo $_GET['q'];
include_once 'psql.class.php';
$db = new DB();
$q = str_replace("\\", "", urldecode($_GET['q']));
$r = $db->query($q);

//echo "update.php#mysql info: ".mysqli_info($db->m);

if($r)
	echo "OK";
else 
	echo "error";

?>