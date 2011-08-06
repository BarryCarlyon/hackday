<?php

set_include_path(
	get_include_path() . PATH_SEPARATOR .
	dirname(__FILE__) . '/../includes/'
);
define('TEMPLATES', dirname(__FILE__) . '/../templates/');

include('spotify.php');
include('twitteroauth/twitteroauth.php');

$pages = array('home', 'login');
$default = 'home';

$tried = '';
$page = $_GET['page'] ? $_GET['page'] : 'home';

// adjust
if (substr($page, -1, 1) == '/') {
	$page = substr($page, 0, -1);
}

// 404 catches with matches
if (!in_array($page, $pages)) {
	$tried = $page;
	$page = 404;
}
if (!is_file(TEMPLATES . $page . '.php')) {
	$tried = $page;
	$page = 404;
}

// load lib
include('common.php');
include('config.php');
include('login.php');

// instantiate
$config = new config();
$login = new login();
if (@$_GET['restart']) {
	$login->restart();
	header('Location: /');
	exit;
}

include(TEMPLATES . 'common/header.php');
include(TEMPLATES . $page . '.php');
include(TEMPLATES . 'common/footer.php');

/*

$spotify = new Spotify();

//$artist = 'Queen';
$artist = $_GET['artist'];

$data = $spotify->search_artist($artist);

echo '<pre>';
//print_r($data);

$result_1 = $data->artists[0];
header('Location: ' . $result_1->href);

*/
