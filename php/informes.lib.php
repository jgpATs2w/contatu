<?php
include_once 'utils.lib.php';
function get_sum($f){global $t, $m, $y,$cc, $uname; 
	$m0=($m=="00")?'01':$m;
	$m1=($m=="00")?'12':$m;
	$q = "select sum(cuanto) from $uname where que like '%$f%' and cuando between date '$y-$m0-01' and (date '$y-$m1-01'+interval '1 month' - interval '1 hour') and cuenta like '$cc'"; //echo $q."<br>";
	$r = $t->query($q);
	
	if($r == false) die("no se pudo hacer la consulta");
	
	return pg_fetch_result($r, 0, 0);
}
function get_data($f){global $t, $m, $y,$cc, $uname;
	$m0=($m=="00")?'01':$m;
	$m1=($m=="00")?'12':$m;
	$q = "select id,cuando,que,descriptor,cuanto,cuenta from $uname where que='$f' and cuando between date '$y-$m0-01' and (date '$y-$m1-01'+interval '1 month' - interval '1 hour')  and cuenta like '$cc' order by cuando asc"; //echo $q."<br>";
	$r = $t->query($q);
	
	if($r == false) die("no se pudo hacer la consulta");
	
	return $r;
}
function get_dates(){global $t, $m, $x, $y,$cc, $uname;
	if($x == 'y'){
		$q = "select min(cuando),max(cuando) from $uname where cuando between date '$y-$m-01' and (date '$y-$m-01'+interval '1 month' - interval '1 hour')  and cuenta like '$cc'"; //echo $q;
		$r = $t->query($q);
		$R = pg_fetch_row($r);
		
		return $R[0]." -> ".$R[1];
	}else{
		return "$m / 2011";
	}
	
}
function get_concept_types(){global $t; global $m, $y,$cc, $uname;
	$m0=($m=="00")?'01':$m;
	$m1=($m=="00")?'12':$m;
	$q = "select que from $uname where cuando between date '$y-$m0-01' and (date '$y-$m1-01'+interval '1 month' - interval '1 hour') and cuenta like '$cc' group by que order by que"; //echo $q;
	$r = $t->query($q);
	
	if($r == false) die("get_concept_types#fallo");

	$R = array();
	while ($l = pg_fetch_array($r)){
		array_push($R,strtolower($l[0]));
	}
	
	return $R;
}
function get_cashflow(){global $t, $uname;
	$r = $t->query("select cuanto from $uname where que = 'tesoreria' order by cuando ASC limit 1");
	$R = pg_fetch_row($r); $f=$R[0]; $r->close();
	
	$r = $t->query("select cuanto from $uname where que = 'tesoreria' order by cuando DESC limit 1");
	$R = pg_fetch_row($r); $l=$R[0]; $r->close();
	
	return ($l-$f);
}

function check_option($v){global $m, $type, $y, $cc;
	if($v == "m_".$m || $v == "type_".$type || $v=="y_".$y || $v=="cc_".$cc) return "selected";
}

function get_cash(){ global $t, $uname;
	$r = $t->query("select cuando,cuanto from $uname where que = 'tesoreria' order by cuando ASC limit 1");
	$R = pg_fetch_assoc($r);
	
	return $R;
}
function get_saldo($date){global $cc,$t, $uname;
	$r = $t->query("select sum(cuanto) from $uname where cuando < date '$date' and cuenta like '$cc';");
	
	return pg_fetch_result($r, 0, 0);
}
function get_saldo_final($date){global $cc,$t, $uname;
	$r = $t->query("select sum(cuanto) from $uname where cuando < (date '$date'+interval '1 month' - interval '1 hour') and cuenta like '$cc';");
	
	return pg_fetch_result($r, 0, 0);
}
function tbody_diario(){global $m, $y, $cc, $t, $uname;
	?><thead><tr><th>fecha</th><th colspan='2'>concepto / descripcion</th><th>Cuenta</th><th>Importe</th><th>Saldo</th><th></th></tr></thead>
		<tbody>
	<?
	$m0=($m=="00")?'01':$m;
	$m1=($m=="00")?'12':$m;
	$q = "select * from $uname where cuando between date '$y-$m0-01' and (date '$y-$m1-01'+interval '1 month' - interval '1 hour')  and cuenta like '$cc' order by cuando asc"; //echo $q."<br>";
	$r = $t->query($q);
	$saldo = get_saldo("$y-$m0-01");
	while ($R = pg_fetch_assoc($r)){
		$idh = "td_".$R["id"];
		$saldo += $R['cuanto'];?>
		<tr class='report1'><td id='<?=$idh?>_cuando' onclick="edit('<?=$idh?>_cuando')" class="cuando"><?=$R["cuando"]?></td><td id='<?=$idh?>_que' onclick="edit('<?=$idh?>_que')" class="que"><?=$R["que"]?></td><td id='<?=$idh?>_descriptor' onclick="edit('<?=$idh?>_descriptor')" class="descriptor"><?=$R["descriptor"]?></td><td id='<?=$idh?>_cuenta' onclick="edit('<?=$idh?>_cuenta')" class="cuenta"><?=$R['cuenta']?></td><td id='<?=$idh?>_cuanto' onclick="edit('<?=$idh?>_cuanto')" class="cuanto"><?=$R['cuanto']?></td><td id='<?=$idh?>_saldo' onclick="edit('<?=$idh?>_saldo')" class="saldo"><?=$saldo?></td><td><a href="javascript:supr('<?=$R["id"]?>')">del</a></td></tr>
	<?}
}
function tbody_mayor(){global $m, $y, $cc, $t,$details;
	if($details=="true"){?>
		<thead><tr><th>fecha</th><th colspan='2'>concepto / descripcion</th><th>Cuenta</th><th>Importe</th><th>Saldo</th><th></th></tr></thead>
	<?}else{?>
		<thead><tr><th></th><th colspan='4'>concepto</th><th>Saldo</th><th></th></tr></thead>
	<?}
	$date0 = ($m=="00")?"$y-01-01":"$y-$m-01";
	$date1 = ($m=="00")?"$y-12-31":"$y-$m-01";
	$saldo0 = get_saldo($date0);
	$saldo1 = get_saldo_final($date1);?>
	<tbody>
		<tr class="report2"><td></td><td colspan='4'>saldo inicial</td><td><?=$saldo0?></td></tr>
	<?
	
	$C = get_concept_types();
 	foreach($C as $c){
 		if($details=="true"){
 			$r = get_data($c);
 			while ($l = pg_fetch_assoc($r)){
				$idh = "td_".$l["id"];?>
					<tr class='report1' title='<?=$l["id"]?>'><td id='<?=$idh?>_cuando' onclick="edit('<?=$idh?>_cuando')" class="cuando"><?=$l["cuando"]?></td><td id='<?=$idh?>_que' onclick="edit('<?=$idh?>_que')" class="que"><?=$l["que"]?></td><td id='<?=$idh?>_descriptor' onclick="edit('<?=$idh?>_descriptor')" class="descriptor"><?=$l["descriptor"]?></td><td id='<?=$idh?>_cuenta' onclick="edit('<?=$idh?>_cuenta')" class="cuenta"><?=$l['cuenta']?></td><td id='<?=$idh?>_cuanto' onclick="edit('<?=$idh?>_cuanto')" class="cuanto"><?=$l['cuanto']?></td><td></td><td><a href="javascript:supr('<?=$l["id"]?>')">del</a></td></tr>
			<?}
 		}
		$s = sprintf("%3.2f", get_sum($c)); 
		?><tr class='report2'><td></td><td colspan='2'><?=$c?></td><td colspan="2"></td><td><?=$s?></td></tr><?
 	}
	
	?>
		<tr class="report2"><td></td><td colspan='4'>saldo final</td><td><?=$saldo1?></td></tr>
		<tr class="report2"><td></td><td colspan='4'>Resultado periodo</td><td><?=($saldo1-$saldo0)?></td></tr>
	<?
	
}
function tbody_promedios(){global $t, $m, $y,$cc, $uname; 
	$B=array();
	?>
	<thead><tr><th>Concepto</th><?for($i=1;$i<13;$i++){?><th><?printf('%02s',$i)?></th><?}?><th>Promedio</th><th>Total</th></tr></thead>
	<tr class="promtr1" onclick="toogle_div('gastos');" style="cursor:default">
			<td>Gastos</td>
			<?$j=0;$st=0;for($i=1;$i<13;$i++){
				$m=sprintf('%02s',$i);
				$o=$t->query2result("select sum(cuanto) from $uname where que like 'gastos%' and cuando between date '$y-$m-01' and (date '$y-$m-01'+interval '1 month' - interval '1 hour')  and cuenta like '$cc';");
				$st += $o;$j=($o!="")?$j+1:$j;
				?>
			<td><?printf("%6.0f",$o)?></td>
			<?}?>
			<td><?printf("%5.0f",($st/$j))?></td>
			<td><?printf("%5.0f",$st)?></td>
	</tr>

		<?
		$r = $t->query("select que from $uname where que like 'gastos%' and cuando between date '$y-01-01' and (date '$y-01-01'+interval '1 year' - interval '1 minute')  and cuenta like '$cc' group by que order by que asc"); 
		$C = pg_fetch_all($r);
		$s="";
		foreach($C as $c){
			$k = explode(" ", $c['que']); if(strpos($s, $k[1])) continue; else $s.= $k[1]; 
		?>
			<tr class="promtr2">
				<td ><?=$k[1]?></td>
				<?$j=0;$st=0;for($i=1;$i<13;$i++){
					$m=sprintf('%02s',$i);
					$o=$t->query2result("select sum(cuanto) from $uname where que like 'gastos ".$k[1]."%' and cuando between date '$y-$m-01' and (date '$y-$m-01'+interval '1 month' - interval '1 hour')  and cuenta like '$cc';");
					$st += $o;$j=($o!="")?$j+1:$j;
					$B[i]=$o;?>
				<td><?printf("%6.0f",$o)?></td>
			<?}?>
			<td><?printf("%5.0f",($st/$j))?></td>
			<td><?printf("%5.0f",$st)?></td>
			</tr>
			<?}?>

	<tr class="promtr1" onclick="toogle_div('ingresos');" style="cursor:default">
			<td>Ingresos</td>
			<?$j=0;$st=0;for($i=1;$i<13;$i++){
				$m=sprintf('%02s',$i);
				$o=$t->query2result("select sum(cuanto) from $uname where que like 'ingresos%' and cuando between date '$y-$m-01' and (date '$y-$m-01'+interval '1 month' - interval '1 hour')  and cuenta like '$cc';");
				$st += $o;$j=($o!="")?$j+1:$j;
				$B[i]+=$o;?>
			<td><?printf("%6.0f",$o)?></td>
			<?}?>
			<td><?printf("%5.0f",($st/$j))?></td>
			<td><?printf("%5.0f",$st)?></td>
	</tr>

		<?
		$r = $t->query("select que from $uname where que like 'ingresos%' and cuando between date '$y-01-01' and (date '$y-01-01'+interval '1 year' - interval '1 minute')  and cuenta like '$cc' group by que"); 
		$C = pg_fetch_all($r);
		foreach($C as $c){
			$k = explode(" ", $c['que']);
		?>
			<tr class="promtr2">
				<td ><?=$k[1]?></td>
				<?$j=0;$st=0;for($i=1;$i<13;$i++){
					$m=sprintf('%02s',$i);
					$o=$t->query2result("select sum(cuanto) from $uname where que like 'ingresos ".$k[1]."%' and cuando between date '$y-$m-01' and (date '$y-$m-01'+interval '1 month' - interval '1 hour')  and cuenta like '$cc';");
					$st += $o;$j=($o!="")?$j+1:$j;
					$B[i]=$o;?>
				<td><?printf("%6.0f",$o)?></td>
			<?}?>
			<td><?printf("%5.0f",($st/$j))?></td>
			<td><?printf("%5.0f",$st)?></td>
			</tr>
			<?}?>
	<?
}
?>