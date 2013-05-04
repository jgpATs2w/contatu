<?
include 'psql.class.php'; session_start(); $uname = $_SESSION['uname'];
$y = $_GET['y']; $cc=$_GET['cc'];
$common_query = "select que from $uname where cuenta like '$cc'";

$DB = new DB();
$q = $common_query." AND cuando between date '$y-1-01' and (date '$y-01-01'+interval '1 year' - interval '1 minute') group by que order by que asc";
$r = $DB->query($q);

$C = pg_fetch_all($r);
if($C == null) die('false;');

$M = null;
foreach ($C as $c){
	for($m=1;$m<=12;$m++){
		$q = "select sum(cuanto) from $uname where cuenta like '$cc' AND que='".$c['que']."' AND cuando between date '$y-$m-01' and (date '$y-$m-01'+interval '1 month' - interval '1 hour')";
		//echo $q;
		$r = $DB->query($q);
		$mstr = ($m<10)?'0'.$m:$m;
		if(pg_num_rows($r)>0)
			$M[$c['que']]["$y-$mstr-15"] = (float)pg_fetch_result($r,0,0);
		else 
			$M[$c['que']]["$y-$mstr-15"] = 0;
	}
}
		
echo "true;".json_encode($M);
	
?>
