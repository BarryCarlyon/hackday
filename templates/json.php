<?php

if ($_GET['game'] == 1 && @$_SESSION['tweet_sent']) {
	$json = 1;
	include(TEMPLATES . 'play.php');
	exit;
}