<?
include 'psql.class.php'; include 'cli.fun.php';
$DB = new DB();
$n = prompt('numero');
$name = prompt('nombre');
$t = prompt('tipo', 'AC');

	$n = $DB->query_parse("select count(num) from cuentas where num = '$n'");
	if($n == 0)
		$DB->query("insert into cuentas (num, nombre, tipo) values ('$n','$name','$t')");
	else
		die("la cuenta $n ya existe\n");

?>