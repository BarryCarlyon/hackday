<?php

set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/../includes/');

include('spotify.php');

$spotify = new Spotify();

//$artist = 'Queen';
$artist = $_GET['artist'];

$data = $spotify->search_artist($artist);

echo '<pre>';
//print_r($data);

$result_1 = $data->artists[0];
header('Location: ' . $result_1->href);
