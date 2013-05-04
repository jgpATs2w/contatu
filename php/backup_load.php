<?
include 'psql.class.php'; $DB = new DB();

$R = pg_fetch_all($DB->query("select * from usertest"));
echo '<b>datos de la DB:</b><br>';

$n;
if($json = file_get_contents('../tmp/20130305_usertest.json')){
	$R = json_decode($json, true);
	$n = substr_count($json, 'id');
}

echo '<br><b>datos del fichero:</b><br>';
print_r($R);

$DB->query('delete from usertest;');
$i = 1;
ob_start();
foreach ($R as $id => $Row) { printf("%2.1f",($i / $n * 100)); echo '%<br>';$i++; ob_flush();
	$DB->query('insert into usertest (que, descriptor, cuando, cuanto, cuenta) VALUES '.
									"('".$Row['que']."','".$Row['descriptor']."','".$Row['cuando']."','".$Row['cuanto']."','".$Row['cuenta']."')");
}
ob_flush();
ob_clean();

?>