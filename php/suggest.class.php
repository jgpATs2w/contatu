<?php
require_once('config.php');
include_once('psql.class.php');
class Suggest{
	private $DB;
	
	function __construct(){
		$this->DB = $dbconn = new DB() or die('No se ha podido conectar: ' . pg_last_error());
			
	}

	function __destruct(){}
	
	public function getSuggestions($keyword){global $u;
		$patterns = array('/\s+/', '/"+/', '/%+/');
		$replace = array('');
		$keyword = preg_replace($patterns, $replace, $keyword);
		
		if($keyword != '')
			$query = "SELECT que FROM  $u WHERE que LIKE '$keyword%' group by que"; 
		
		else
			$query = 'SELECT que FROM $u WHERE que="" group by que';
		
		$result = pg_query($query);
		
		$output = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
		$output .= '<response>';
		
		//if($result->num_rows)
		while ($row = pg_fetch_array($result))
			$output .= '<name>' . $row['que'] . '</name>';

		$output .= '</response>';

		return $output;
	}

}
?>
