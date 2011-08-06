<?php

if (@$_POST['game_start']) {
	unset($_SESSION['tweet_sent']);
}

if (@$_SESSION['tweet_sent']) {
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
		$game->start();
	} else {
		echo '<p>An Error Occured</p>';
	}
}
