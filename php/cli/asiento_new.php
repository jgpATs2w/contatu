<?
include_once 'psql.class.php'; include_once 'cli.fun.php';include_once 'predef.fun.php';
$DB = new DB();

$r = pg_query("select max(asiento) from diario");
$a = pg_fetch_result($r,0,0) + 1; echo "Nuevo asiento $a\n";
$d = prompt('fecha', date("Y-m-d"));
	
$o = prompt('predefinido', '1', 'show_predefinidos');

$fun = "predef_".$o;
$fun($a,$d);

asiento_show($a);
?>