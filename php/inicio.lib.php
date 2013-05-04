<?
function getAccounts(){global $DB; global $Accounts;
	$Accounts = pg_fetch_all($DB->query("select alias from accounts where username='".$_ENV['uname']."'"));

	return $Accounts;
}
function printSaldos(){global $DB, $Accounts;
	$tot = 0;
	foreach($Accounts as $a){
		$d = pg_fetch_result($DB->query("select cuando from ".$_ENV['uname']." where cuenta='".$a['alias']."' order by cuando desc limit 1;"),0,0);
		$s = pg_fetch_result($DB->query("select sum(cuanto) from ".$_ENV['uname']." where cuenta='".$a['alias']."';"),0,0);
		
	?>
		<tr><td class="tdh"><?=$a['alias']?></td><td><?=$d?></td><td class="num"><?=$s?></td></tr>

<?}}
?>