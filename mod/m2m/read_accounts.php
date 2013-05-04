<?
include 'psql.class.php'; include 'fun.lib.php';

$_ENV['DB'] = new DB(DB_DATABASE_CONTABLE);

print_r(json_encode(getAccounts($_GET['uname'])));
?>