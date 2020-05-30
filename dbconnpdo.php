<?php
/* 
Private database connection info stored in config.php in 4 variables: 
	$config['hostname']
	$config['database']
	$config['username']
	$config['password']
*/
require_once('config.php');
try
{
    // Use PDO to connect to database
    $conn_str = 'mysql:host=' . $config['hostname'] . ';dbname=' . $config['database'] . ';charset=utf8;';
    $db = new PDO($conn_str, $config['username'], $config['password']);
    // Tells PDO to disable emulated prepared statements and use real prepared statements.
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $ex)
{
    // DB connection failed
    echo ("Unable to connect to database: " . strtoupper($ex->getMessage()));
    die();
}

?>
