<?php

/*** define the site path ***/
$site_path = realpath(dirname(__FILE__));
define ('__SITE_PATH', $site_path);

/*** Load and define constants from .env file ***/
$dotenv = array();
$dotenv_filename = __SITE_PATH .'/../.env';
foreach(preg_split('/\r\n|\n/', file_get_contents($dotenv_filename)) as $line) {
	$arr = explode('#', trim($line), 2);
	$arr = explode('=', $arr[0], 2);
	if(count($arr) != 2) continue;
	$dotenv[$arr[0]] = $arr[1];
}
foreach(array('DB_HOST', 'DB_USER', 'DB_PASS', 'DB_NAME', 'DB_SSL_CA','API_TOKEN') as $k) {
	if(isset($dotenv[$k])) {
		define($k, $dotenv[$k]);
	}
}

/*** include the init.php file ***/
include 'includes/init.php';

/*** load the router ***/
$registry->router = new router($registry);

/*** set the controller path ***/
$registry->router->setPath (__SITE_PATH . '/controller');

/*** load up the template ***/
$registry->template = new template($registry);

/*** load the controller ***/
$registry->router->loader();
