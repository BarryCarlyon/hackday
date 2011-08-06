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

$page = $_GET['page'];

if (!in_array($page, $pages)) {
	$page = 404;
}

include(TEMPLATES . $page . '.php');

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
