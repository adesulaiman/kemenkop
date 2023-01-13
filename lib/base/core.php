<?php

define('ABSPATH', dirname(__FILE__) . '/');

define( 'PG4WP_DEBUG', false);
// If you just want to log queries that generate errors, leave PG4WP_DEBUG to "false"
// and set this to true
define( 'PG4WP_LOG_ERRORS', false);

// If you want to allow insecure configuration (from the author point of view) to work with PG4WP,
// change this to true
define( 'PG4WP_INSECURE', false);


// Load the driver defined in 'db.php'
require_once( 'driver_'.DB_DRIVER.'.php');

// This loads up the wpdb class applying appropriate changes to it
$replaces = array(
	'define( '	=> '// define( ',
	'class wpdb'	=> 'class wpdb2',
	'new wpdb'	=> 'new wpdb2',
	'mysql_'	=> 'wpsql_',
	'<?php'		=> '',
	'?>'		=> '',
);


eval( str_replace( array_keys($replaces), array_values($replaces), file_get_contents(ABSPATH . 'wp-db.php')));

$adeQ = new wpdb2( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
?>