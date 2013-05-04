<?php 
require_once('config.php');

class DB{
	public $conn, $debug=false;
	
	function __construct(){
		$this->conn = pg_pconnect("host=".DB_HOST." dbname=".DB_DATABASE." user=".DB_USER." password=".DB_PASSWORD)
    	or die('psql.class.php#No se ha podido conectar: ' . pg_last_error());
	}
	
	function query($q){
		if($this->debug) echo "psql.class.php#query: ".$q;
		
		$r = pg_query($q);
		
		if($r){ return $r; }else{
			echo pg_last_error();
			return false;
		}
		
	}
	
	function query_parse($q){
		$r = $this->query($q);
		$R = pg_fetch_array($r);
		return $R[0];
	}
}

?>