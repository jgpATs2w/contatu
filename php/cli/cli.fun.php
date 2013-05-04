<?

function apunte($c, $i, $sc, $cp){
	global $a, $d, $DB;
	$DB->query("insert into diario (asiento, fecha, concepto, importe, cuenta, contrapartida) values ($a, '$d','$c', $i, '$sc', '$cp')");
}
function prompt_operacion(){global $o;
	echo "operacion($o)(?) "; fscanf(STDIN, "%s\n", $io);
	if($io == "?"){
			show_predefinidos();
			prompt_operacion();
	}else if(strlen($io) > 0) $o = $io;
}
function show_predefinidos(){
	echo 
		"1. Compra o gasto\n".
		"2. Gasto con IRPF\n".
		"3. Venta\n".
		"4. Nomina\n";
}
function prompt($s,$def='', $help_fun=''){
	echo "$s ($def)";
		echo (strlen($help_fun)>0)? "(?):": ":"; 
	$in = trim(fgets(STDIN));
	
	if($in == '?'){ $help_fun(); $in = prompt($s, $def, $help_fun);}
	
	return (strlen($in) > 0)? $in : $def;
}
function asiento_show($a){
	echo "Mostrando asiento $a\n";
	$r = pg_query("select * from diario where asiento = ".$a);

	echo "fecha      concepto\timporte\tsubcuenta\tcontrapartida\n";
	echo "-----      --------\t-------\t---------\t-------------\n";
	while ($l = pg_fetch_assoc($r)){
		echo $l['fecha']." ".$l['concepto']."\t".$l['importe']."\t".$l['cuenta']."     \t".$l['contrapartida']."\n";
	}
	
	global $DB;
	$s = $DB->query_parse("select sum(importe) from diario where asiento = '$a'");
	echo "Descuadre: $s\n";
}
?>