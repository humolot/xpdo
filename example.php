<?php
require_once("libs/xPdo.php");


// database config
$config = [
	'host'      => 'localhost',
	'driver'    => 'mysql', //mysql, pgsql, sqlite, oracle
	'database'  => 'mikrox',
	'username'  => 'root',
	'password'  => 'hacklock',
	'charset'   => 'utf8',
	'collation' => 'utf8_general_ci',
	'prefix'    => ''
];

// xPdo
$db = new xPdo($config);

// Select 
$records = $db->table('a')->getAll();
foreach ((array) $records as $item) {
	echo  $item->title  . '<br>';
}
//print_r($records);
echo '<hr>';

$rt = $db->table('a')->count('id','title');
print_r($rt);










