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
	$tweet = 'I am playing #SpotifyRoulette, suggest me an artist via reply and I will listen to them!';
	
	$game = new game($tweet);
	echo '<p>We will play the First Response. But you can listen to other responses or even start a Playlist just click the links</p>';
	echo '<p>There is a small chance we will grab a track not available in your Country</p>';

	if ($game->setup($tweet)) {
		if (!isset($_SESSION['tweet_sent'])) {
			$game->start();
			$_SESSION['tweet_sent'] = TRUE;
			
			//
			global $config;
			include('database.php');
			$log = new log();
			$db = new db($config->database);
			
			$avatar = $_SESSION['account_data']->profile_image_url_https;
			$query = 'SELECT ref_id FROM twitter_recent WHERE screen_name = \'' . $_SESSION['twitter_screen_name'] . '\'';
			$result = $db->get_data($query);
			
			if ($db->total_rows) {
				$row = $db->fetch_row($result);
				
				$query = 'UPDATE twitter_recent SET profile_image = \'' . $avatar . '\', tos = NOW() WHERE ref_id = ' . $row['ref_id'];
			} else {
				$query = 'INSERT INTO twitter_recent(screen_name, profile_image) VALUES (\'' . $_SESSION['twitter_screen_name'] . '\', \'' . $avatar . '\')';
			}
			$db->get_query($query);
		}
	} else {
		echo '<p>An Error Occured. As a Result, you have Lost the Game!!!</p>';
	}
}
