<?php 
require_once('config.php');

class DB{
	public $conn;
	
	function __construct(){
		$this->conn = pg_pconnect("host=".DB_HOST." dbname=".DB_DATABASE." user=".DB_USER." password=".DB_PASSWORD)
    	or die('psql.class.php#No se ha podido conectar: ' . pg_last_error());
		
		pg_set_client_encoding($this->conn, 'LATIN1');
	}
	
	function query($q){
		if(DEBUG) echo "psql.class.php#query: ".$q;
		
		$r = pg_query($q);
		
		if($r){ return $r; }else{
			echo pg_last_error();
			return false;
		}
		
	}
	function query2result($q){
		$r = $this->query($q);
		return pg_fetch_result($r,0,0);
	}

}

?>