<?
include 'psql.class.php'; session_start(); $uname = $_SESSION['uname'];
$cc=$_GET['cc']; $from = $_GET['from']; $to = $_GET['to'];
$common_query = "select que from $uname where cuenta like '$cc'";

$DB = new DB();
$q = $common_query." AND cuando between date '$from' and date '$to' group by que order by que asc";
$r = $DB->query($q); 

$C = pg_fetch_all($r);
if($C == null) die('false;');

$M;
foreach ($C as $c){
	$q = "select que,cuando,cuanto,descriptor,cuenta from $uname where cuenta like '$cc' AND que='".$c['que']."' AND cuando between date '$from' and date '$to'";

	$r = $DB->query($q);
	if(pg_num_rows($r)>0)
		$M[$c['que']] = pg_fetch_all($r);
	else 
		$M[$c['que']] = false;
}
		
echo "true;".json_encode($M);
	
?>
