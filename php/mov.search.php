<?php
include_once 'nav.fun.php'; sessionLoad(); global $uname;
include_once 'psql.class.php';
include_once 'utils.lib.php';

$q = "select * from $uname where cuando >= DATE('".$_GET['f']."') AND cuando <= DATE('".$_GET['t']."')";
	if(array_key_exists("tx", $_GET)) $q .= " AND que like '%".$_GET['que']."' AND descriptor like '%".$_GET['tx']."%'";

	
ob_start();
log_info($q);
		
$DB = new DB();
$r = $DB->query($q." order by cuando");

$n = pg_num_rows($r);
if($n<= 0) die("no hay datos que contengan '".$_GET['tx']."'");
else if($n >= 500) die('mas de 1000 datos!! por favor acota la busqueda')

?>

	<p class="comments">Obtenidos <?=$n?> resultados</p>
	<table id="mov">
		<thead><tr><th>fecha</th><th colspan='2'>concepto / descripcion</th><th width="20px">Cuenta</th><th>Importe</th></tr></thead>
		<tbody>
	<?$i=1; while($l = pg_fetch_assoc($r)){ $idh = "td_".$l["id"];?>
		<tr title='<?=$l["id"]?>' class='mov-<?= ($i%2 == 0)? "par": "impar"?>'><td  class='mov-fecha' id='<?=$idh?>_cuando' onclick="edit('<?=$idh?>_cuando')" width="75"><?=$l["cuando"]?></td><td class="mov-que" id='<?=$idh?>_que' onclick="edit('<?=$idh?>_que')"><?=$l["que"]?></td><td class="mov-descriptor" id='<?=$idh?>_descriptor' onclick="edit('<?=$idh?>_descriptor')"><?=$l["descriptor"]?></td><td class="mov-cuenta" id='<?=$idh?>_cuenta' onclick="edit('<?=$idh?>_cuenta')"><?=$l["cuenta"]?></td><td class="mov-cuanto" id='<?=$idh?>_cuanto' onclick="edit('<?=$idh?>_cuanto')"><?=$l["cuanto"]?></td></tr>
	<?$i++;}?>
		<tr class="mov-total"><td colspan='4'>Total</td><td><?
		$q=str_replace("*", "sum(cuanto)", $q); log_info($q);
		$r = pg_fetch_array($DB->query($q));
		printf("<b>%3.0f</b>",$r[0]);?></td></tr>
		</tbody>
	</table>
