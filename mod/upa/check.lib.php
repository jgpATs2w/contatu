<?
function firstCheck(){
	$s = "no esta autorizad@ para ver el contenido";
	session_start();
	if(!array_key_exists("id", $_SESSION)) die($s);
	if(!(strcmp($_SESSION['id'],md5("wellcome to tijuana")) == 0)) die($s);
	session_write_close();
	
}
function loadSession(){
	session_start();
	$_SESSION['id'] = md5("wellcome to tijuana");
	$_SESSION['uname'] = $_GET['user'];
	$_ENV['uname'] = $_GET['user'];
	session_write_close();
}
function getUser(){
	session_start();
	return $_SESSION['user'];
}
?>