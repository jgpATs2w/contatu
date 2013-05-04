<?php
include 'psql.class.php';
$t = new DB();

$u=$_GET['uname']; $p=$_GET['poema'];

if(check($u,$p)) echo 'OK';
else echo "ERROR";
	
function check($u,$p){
	$q = "select count(*) from main where uname='$u' AND poema='$p'";
	
	return query_count($t, $q)>0;
}
function query_count($t,$q){
	$r = pg_query($q);
	
	if($r == false) die("no se pudo hacer la consulta");
	
	$l = pg_fetch_row($r);
	
	return $l[0];
}

?>