<?php
include_once 'config.php';
function log_debug($m){
	if(DEBUG==1) log_info($m);
}
function log_info($s){
	echo "<script>console.info('$s')</script>";
	ob_flush();
}
?>