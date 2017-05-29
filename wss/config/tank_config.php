<?php
error_reporting(0);
$hostname_tankdb = "localhost";    //database host 
$database_tankdb = "tankdb";       //database name
$username_tankdb = "root";         //mysql user name
$password_tankdb = "";             //mysql password
$tankdb = mysql_connect($hostname_tankdb, $username_tankdb, $password_tankdb) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_query("set names 'utf8'");

require "function.class.php";

$language = "cn";
$advsearch = get_item( 'advsearch' );
$outofdate = get_item( 'outofdate' ) ;
?>
<?php require "multilingual/language_$language".".php"; ?>