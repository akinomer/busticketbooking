<?php
if(!defined('INC')) exit('Bu dosyaya direkt erisim engellenmiştir!');

//error_reporting(0);
require_once('DB.php');

$db = DB::init([
	'db_driver'		=> 'mysql',
	'db_host'		=> 'localhost',
	'db_user'		=> 'root',
	'db_pass'		=> 'mysql',
	'db_name'		=> 'flight',
	'db_charset'	=> 'utf8',
	'db_collation'	=> 'utf8_turkish_ci',
	'strict' => false,
	'db_prefix'	 	=> ''

]);

define("CAN_REGISTER", "any");
define("DEFAULT_ROLE", "member");

define("SECURE", FALSE);
?>