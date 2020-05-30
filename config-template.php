<?php
// Specify the private database login info

// Use your own server code to detemrine if running local or not
$server = $_SERVER['REMOTE_ADDR'];
$pos = strrpos($server, "::1");

if (($server == '10.0.0.168') or ($pos > -1))
{
	// Local host
	$config['hostname'] = "localhost";
	$config['database'] = "mylocaldatabase";
	$config['username'] = "daboss";
	$config['password'] = "secretpassword";
}
else
{
	// Remote
	$config['hostname'] = "localhost:3236";
	$config['database'] = "myremotedatabase";
	$config['username'] = "daboss";
	$config['password'] = "mypassw0rd";
}
?>
