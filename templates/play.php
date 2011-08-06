<?php

if (@$_POST['game_start']) {
	unset($_SESSION['tweet_sent']);
}
$json = isset($json) ? $json : 0;

if (@$_SESSION['tweet_sent'] && $json == 1) {
//	print_r($_SESSION);
	$_SESSION['game_since_id'] = $_SESSION['game_tweet_id'];
	$game = new game();
	$game->inprogress();
	exit;
} else {
	echo '<h2>Playing Spotify Roulette</h2>';
	// tweet not sent
	$_SESSION['tweet_sent'] = TRUE;
	
	$tweet = 'I am playing Spotify Roulette, suggest me an artist via reply and I will listen to them! #SpotifyRoulette';
	
	$game = new game($tweet);
	if ($game->setup($tweet)) {
		echo '<p>We will play the First Response. But you can listen to other response just click a link</p>';
		$game->start();
	} else {
		echo '<p>An Error Occured</p>';
	}
}
