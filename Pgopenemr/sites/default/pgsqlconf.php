<?php
//  OpenEMR
//  Postgre Config

$host	= '127.0.0.1';
$port	= '5432';
$login	= 'postgres';
$pass	= 'postgres';
$dbase	= 'postgres';

//Added ability to disable
//utf8 encoding - bm 05-2009
global $disable_utf8_flag;
$disable_utf8_flag = false;

$pgsqlconf = array();
global $pgsqlconf;
$pgsqlconf["host"]= $host;
$pgsqlconf["port"] = $port;
$pgsqlconf["login"] = $login;
$pgsqlconf["pass"] = $pass;
$pgsqlconf["dbase"] = $dbase;
//////////////////////////
//////////////////////////
//////////////////////////
//////DO NOT TOUCH THIS///
$config = 1; /////////////
//////////////////////////
//////////////////////////
//////////////////////////
?>

