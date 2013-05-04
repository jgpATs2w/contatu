<?
function cuentas_show(){
$r = pg_query("select * from cuentas order by num");

echo "Numero     Nombre                                             Tipo\n";
echo "------   + ------                                           + ----\n";
while ($l = pg_fetch_assoc($r)){
	printf("%-10s %-50s %s\n", $l['num'], $l['nombre'],$l['tipo']);
}
}
?>