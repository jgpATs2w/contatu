<?php

include_once 'nav.fun.php'; sessionLoad();
include 'informes.lib.php';
include_once 'psql.class.php';

if(array_key_exists('month', $_GET)){
	$y = $_GET['year'];
	$m = $_GET['month'];
	$type = $_GET['type'];$details = $_GET['details'];
	$cc = $_GET['cc'];
} else {
	$y=date("Y");
	$m= date("m", time());
	$type= 'diario'; $details = 'false';
	$cc = '%';
}
$t = new DB();
$C = get_concept_types();

html_start('informes');
?>

<select id="month" onchange="analisis_load();" >
	<?php 
		$M = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
		for ($i=1;$i<=12;$i++){
			$s = vsprintf("%02d",$i);?>
			<option value="<?=$s?>" <?=check_option("m_".$s)?>><?=$M[$i-1]?></option>
		<?}?>
			<option value="00" <?=check_option("m_00")?>>Todos</option>
		<?
	?>
</select>
		<select id="year" onchange="analisis_load();">
	<?php 
		for ($i=2010;$i<=2015;$i++){?>
			<option value="<?=$i?>" <?=check_option("y_".$i)?>><?=$i?></option>
		<?}
	?>
</select>
<select id="type" onchange="analisis_load();">
	<option value="diario" <?=check_option('type_diario')?>>diario</option>
	<option value="mayor" <?=check_option('type_mayor')?>>mayor</option>
	<option value="promedios" <?=check_option('type_promedios')?>>promedios</option>
</select>

	<input id="details" type="checkbox" <?=($details=="true")?"checked":""?> onchange="analisis_load();"/>Detalles

<select id="cc" onchange="analisis_load();">
	<option value="%" <?=check_option('cc_%')?>>Todas</option>
	<? printAccountOptions(); ?>
</select>
<button id="button_plot" onclick="openPlotWindow();">Mostrar grafico</button>
<table id="at">
		 <?php 
		 $fun = "tbody_".$type; $fun();
		 ?>
	 	</tbody>
</table>
<? html_end('informes'); ?>
