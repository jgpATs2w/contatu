<?
function getAccounts($u){
	$Accounts = pg_fetch_all($_ENV['DB']->query("select alias from accounts where username='$u'"));

	return $Accounts;
}
function getSaldos($u){ $DB = $_ENV['DB'];
	$Accounts = getAccounts($u);
	
	foreach($Accounts as $i => $a){
			$n = pg_fetch_result($DB->query("select count(cuanto) from $u where cuenta='".$a['alias']."' limit 1"),0,0);
			if($n>0){
				$d = pg_fetch_result($DB->query("select cuando from $u where cuenta='".$a['alias']."' order by cuando desc limit 1;"),0,0);
				$s = pg_fetch_result($DB->query("select sum(cuanto) from $u where cuenta='".$a['alias']."';"),0,0);
			
				$R[$i] = array('alias' => $a['alias'], 'date' => $d, 'saldo' => $s);
			}else
				$R[$i] = array('alias' => $a['alias'], 'date' => '', 'saldo' => 'no hay datos');
	}
	return $R;
}
?>