<?php
define('WP_DEBUG', false);



require_once('apply_filter.php' );

require_once('class-phpass.php' );

if($dbRDBMS == 'pgsql')
{
	require_once('wp-db-pgsql.php' );
	$adeQ = new pgdb( $dbUser, $dbPassword, $dbName, $dbHost, $port);
}elseif ($dbRDBMS == 'mysql'){
	require_once('wp-db.php' );
	$adeQ = new wpdb( $dbUser, $dbPassword, $dbName, $dbHost);
}else{
	require_once('wp-db.php' );
	$adeQ = new wpdb( $dbUser, $dbPassword, $dbName, $dbHost);
}

$hasher = new PasswordHash(8, true);


?>