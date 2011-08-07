<?php

if (@$_POST['game_start']) {
	unset($_SESSION['tweet_sent']);
}
$json = isset($json) ? $json : 0;

if (@$_SESSION['tweet_sent'] && $json == 1) {
//	print_r($_SESSION);
//	$_SESSION['game_since_id'] = $_SESSION['game_tweet_id'];
	$game = new game();
	$game->inprogress();
	exit;
} else {
	echo '<h2>Playing Spotify Roulette</h2>';
	// tweet not sent
	$tweet = 'I am playing Spotify Roulette, suggest me an artist via reply and I will listen to them! #SpotifyRoulette';
	
	$game = new game($tweet);
	echo '<p>We will play the First Response. But you can listen to other response just click a link</p>';
	echo '<p>There is a small chance we will grab a track not available in your Country</p>';
	if ($game->setup($tweet)) {
		if (!isset($_SESSION['tweet_sent'])) {
			$game->start();
			$_SESSION['tweet_sent'] = TRUE;
		}
	} else {
		echo '<p>An Error Occured</p>';
	}
}
