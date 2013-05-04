<?php
include 'psql.class.php';
require_once 'check.lib.php';
$t = new DB();

if($_GET['action'] == "chk_u"){
	if (query_count($t, "select count(*) from main where uname='".$_GET['user']."'")>0) echo "OK;OK";
	else echo "ERROR;usuario no valido";
	
}
if($_GET['action'] == "chk_p"){
	$u=$_GET['user']; $p=$_GET['pass'];
	if(query_count($t, "select count(*) from main where uname='$u' AND poema='$p'")>0){ loadSession(); echo "OK;OK";}
	else die("ERROR;no valido");
	
}
function query_count($t,$q){
	$r = pg_query($q);
	
	if($r == false) die("no se pudo hacer la consulta");
	
	$l = pg_fetch_row($r);
	
	return $l[0];
}

?>