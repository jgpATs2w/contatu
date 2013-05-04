<?
session_start(); 
if(array_key_exists('uname', $_SESSION)){
	$u = $_SESSION['uname']; $Accounts = $_SESSION['Accounts'];
}else if($_GET)
	if(array_key_exists('uname', $_GET)){
		include 'inicio.lib.php'; include 'psql.class.php'; $DB = new DB(); 
		$u = $_ENV['uname'] = $_GET['uname'];
		$Accounts = getAccounts();
	}
if($Accounts == null) die('ir a login.');
include_once 'psql.class.php';  global $DB; if(!$DB) $DB = new DB(); 
foreach($Accounts as $i => $a){
		$n = pg_fetch_result($DB->query("select count(cuanto) from $u where cuenta='".$a['alias']."' limit 1"),0,0);
		if($n>0){
			$d = pg_fetch_result($DB->query("select cuando from $u where cuenta='".$a['alias']."' order by cuando desc limit 1;"),0,0);
			$s = pg_fetch_result($DB->query("select sum(cuanto) from $u where cuenta='".$a['alias']."';"),0,0);
		
			$R[$i] = array('alias' => $a['alias'], 'date' => $d, 'saldo' => $s);
		}else
			$R[$i] = array('alias' => $a['alias'], 'date' => '', 'saldo' => 'no hay datos');
}
print_r(json_encode($R));
