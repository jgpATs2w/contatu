<?
session_start(); $uname = $_SESSION['uname'];
include 'psql.class.php';  global $DB; $DB = new DB(); 

$fname = date("Ymd").'_'.$uname.".psql";


$R = pg_fetch_all($DB->query("select * from $uname"));


$fid = fopen("../tmp/".$fname, 'w');
fwrite($fid, json_encode($R));

fclose($fid);

echo $fname;
?>
