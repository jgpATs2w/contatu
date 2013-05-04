<?
if(array_key_exists("contatuauth", $_COOKIE)){
	die("ya existe");
}
if(setcookie("contatuauth"))
	echo "OK";
else
	echo "UOPS";
?>