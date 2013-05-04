<?php
echo $_GET['q'];
include_once 'psql.class.php';
$db = new DB();
$q = str_replace("\\", "", urldecode($_GET['q']));
$r = $db->query($q);

//echo $q;

if($r)
	echo "OK";
else 
	echo "ERROR";

?>