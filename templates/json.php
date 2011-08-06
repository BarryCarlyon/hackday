<?php

if ($_GET['game'] == 1 && @$_SESSION['tweet_sent']) {
	include(TEMPLATES . 'play.php');
	exit;
}