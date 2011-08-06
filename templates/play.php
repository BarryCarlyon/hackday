<h2>Playing Spotify Roulette</h2>

<?php

if (@$_SESSION['tweet_sent']) {
	
} else {
	// tweet not sent
	$_SESSION['tweet_sent'] = TRUE;
	
	$tweet = 'I am playing Spotify Roulette, suggest me an artist via reply and I will listen to them! #SpotifyRoulette';
	
	$game = new game($tweet);
}