<?php

if (@$_GET['game'] == 1 && @$_SESSION['tweet_sent']) {
	// playing the game
	$json = 1;
	include(TEMPLATES . 'play.php');
	exit;
}

if ($artist = $_GET['playlist']) {
	// load artist
	$json = 1;
	
	include(TEMPLATES . 'playlist.php');
	exit;
}