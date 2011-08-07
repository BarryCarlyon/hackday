<?php

set_include_path(
	get_include_path() . PATH_SEPARATOR .
	dirname(__FILE__) . '/../includes/'
);
define('INCLUDES', dirname(__FILE__) . '/../includes/');
define('TEMPLATES', dirname(__FILE__) . '/../templates/');

include('spotify.php');
include('twitteroauth/twitteroauth.php');

//$pages = array('home', 'login', 'logout');
$default = 'home';

$tried = '';
$page = $_GET['page'] ? $_GET['page'] : 'home';

// adjust
if (substr($page, -1, 1) == '/') {
	$page = substr($page, 0, -1);
}

// 404 catches with matches
/*
if (!in_array($page, $pages)) {
	$tried = $page;
	$page = 404;
}
*/
if (!is_file(TEMPLATES . $page . '.php')) {
	$tried = $page;
	$page = 404;
}

// load lib
include('common.php');
include('config.php');
include('login.php');
include('game.php');

include('database.php');
$log = new log();

// instantiate
$config = new config();

$db = new db($config->database);

$login = new login();
if (@$_GET['restart']) {
	$login->restart();
	header('Location: /');
	exit;
}
print_r($_SESSION);

if ($page != 'json') {
	include(TEMPLATES . 'common/header.php');
}
include(TEMPLATES . $page . '.php');
if ($page != 'json') {
	include(TEMPLATES . 'common/footer.php');
}
