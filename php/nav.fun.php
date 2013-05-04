<? include_once '../mod/upa/check.lib.php';
ob_start();
function html_start($t){
	firstCheck();
	?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>CONTATU :: <?=$t?></title>
		<meta charset="ISO-8859-1" />
		
		<? extra_head($t);?>
		<script type="text/javascript" src="../js/nav.fun.js"></script>
		<script type="text/javascript" src="../js/ajax.js"></script>
		<link rel="stylesheet" href="../css/main.css" type="text/css" media="all">
		
		
	</head>
	<body onload="load('<?=$t?>')" <? extra_body($t);?>>
		<div id="menu"></div>
		<div id="logout"></div>
		<div id="alert" class="hidden">zzz...</div>
		<? ob_flush();?>
		<div id="body1">
	<?
	ob_flush();
}
function extra_head($t){
	$fun = "header_".$t; if(function_exists($fun)) $fun();
}
function extra_body($t){
	$fun = "body_".$t; if(function_exists($fun)) $fun();
}
function header_inicio(){?>
	<link rel="stylesheet" href="../css/inicio.css" type="text/css" media="all">
	<link rel="stylesheet" href="../css/suggest.css" type="text/css" media="all">
	<script type="text/javascript" src="../js/load_ajax.js"></script>
	<script type="text/javascript" src="../js/contable.js"></script>
	<script type="text/javascript" src="../js/suggest.js"></script>
	<script type="text/javascript" src="../js/inicio.js"></script>
<?}
function header_movimientos(){?>
	<link rel="stylesheet" href="../css/movimientos.css" type="text/css" media="all">
	<script type="text/javascript" src="../js/movimientos.js"></script>
	<script type="text/javascript" src="../js/load_ajax.js"></script>
	<script type="text/javascript" src="../js/ediTable.js"></script>
<? ob_flush();
}
function header_informes(){?>
	<script type="text/javascript" src="../js/analisis.js"></script>
	<script type="text/javascript" src="../js/load_ajax.js"></script>
	<script type="text/javascript">var table = 'apisquillos';</script>
	<script type="text/javascript" src="../js/ediTable.js"></script>
	<script type="text/javascript" src="../js/magic_table_sortable.js"></script>
	<script type="text/javascript" src="../js/plot.js"></script>
	
	<link rel="stylesheet" href="../css/informes.css" type="text/css" media="all">
<?}
function header_configurar(){?>
	<script type="text/javascript" src="../js/magic_table.js"></script>
	<script type="text/javascript" src="../js/configurar.js"></script>
	
	<link rel="stylesheet" href="../css/configurar.css" type="text/css" media="all">
	
<?}
function html_end($t){?>
		</div>
		<!--<footer>eleuteron AT gmail.com. Copy free. Actualizado <?=date('m / Y',filectime($t.'.php'))?></footer> -->
		</body>
</html>
	<?
}
function printAccountOptions(){global $Accounts;
	foreach($Accounts as $a){?>
		<option value="<?=$a['alias']?>"><?=$a['alias']?></option>
	<?}
}
function sessionLoad(){
	session_start();
	global $uname; $uname = $_SESSION['uname'];
	global $Accounts; $Accounts = $_SESSION['Accounts'];
}
?>
