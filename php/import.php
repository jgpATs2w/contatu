
<?php
include_once 'utils.lib.php'; include_once 'config.php';
include_once 'psql.class.php'; $DB = new DB();

$destination_path = "/tmp/"; 
$cc = $_GET['cc'];
$result = 0;$nr=0;
 
$target_path = $destination_path . basename($_FILES['myfile']['name']);

if(@move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path) ) {
	$HEADER = 6; $hp = 0; $r = 0;
	$DICTIO = get_dictionary();
	if(($h = fopen($target_path, 'r')) != FALSE){
		while(($D = fgetcsv($h)) != FALSE){
			$hp++; if($hp<$HEADER) continue;
			
			if(strlen($D[0] == 0)) continue;
			$F = split('/', $D[0]); $f = $F[2]."-".$F[1]."-".$F[0]; 
			$que = strtolower((array_key_exists($D[2], $DICTIO))?$DICTIO[$D[2]] : $D[2]); 
			$c = floatval(preg_replace('#^([-]*[0-9\.,\' ]+?)((\.|,){1}([0-9-]{1,2}))*$#e', "str_replace(array('.', ',', \"'\", ' '), '', '\\1') . '.\\4'", $D[3]));
			$d=implode(";", array_slice($D, 8));
			$q ="insert into apisquillos (cuando, que, descriptor, cuanto, cuenta) values ('$f','$que','$d','".$c."','".$_GET['cc']."')";
			
			if($DB->query($q))
				$nr++;
			else 
				log_info(pg_last_error());
		}
	}
	
	$result =1;
}



sleep(1);
function get_dictionary(){
	return array(
				"CARGO POR COBRO DE SERVICIOS" => "gastos cuentas",
				"TRANSFERENCIA A SU FAVOR OTRAS ENTIDADES" => "pendientes",
				"TRANSFERENCIA A SU FAVOR" => "pendientes",
				"TRASPASO A SU FAVOR" => "pendientes",
				"RECIBO IMPUESTOS O CONTRIBUCION" => "gastos impuestos",
				"RECIBO DE TELEFONO" => "gastos telefono",
				"SEGUROS SOCIALES" => "gastos seguros sociales",
				"RECIBOS VARIOS" => "pendientes",
				"SU ORDEN DE TRASPASO" => "traspasos",
				"SU ORDEN DE TRANSFERENCIA" => "traspasos",
				"INGRESO EN EFECTIVO" => "pendientes",
				"ABONO DE PENSIONES" => "ingresos ayudas",
				"PAGO EN EFECTIVO" => "traspasos pendientes",
				"RECIBO DE AGUA" => "gastos suministros",
				"RECIBO DE LUZ" => "gastos suministros",
				"TRANSFERENCIAS A OTRAS ENTIDADES" => "pendientes",
				"CARGO MONEDA EXTRANJERA" => "pendientes",
				"PAGO AYUNTAMIENTO DE MADRID" => "gastos multas",
				"REINTEGRO CAJERO AUTOMATICO" => "traspasos pendientes",
				"ABONO DE INTERESES" => "gastos cuentas"
				);
}
?>
<script language="javascript" type="text/javascript">
console.info("tried to insert file <?php printf("%s into cc %s",basename($target_path),$cc); ?>");
var m = "inserted: <?=$nr?> lines";
console.info(m);
window.parent.document.getElementById('result').innerHTML = m;
</script>
