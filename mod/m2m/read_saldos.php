<?

include 'fun.lib.php'; include 'psql.class.php'; $DB = $_ENV['DB'] = new DB(DB_DATABASE_CONTABLE); 
$u = $_ENV['uname'] = $_GET['uname'];

print_r(json_encode(getSaldos($u)));
