<?
$file = realpath('../tmp/'.$_GET['file']);
header('Content-type: text/plain');
header('Content-disposition: attachment; filename="'.basename($file).'"');
readfile($file);
?>