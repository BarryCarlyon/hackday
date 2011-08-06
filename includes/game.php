<?php

// play the game

class game {
	function __construct($tweet = '';) {
		global $login;
		
		if (!$login->is_logged_in) {
			return;
		}
		
		if (!$tweet) {
			$tweet = 'I am playing Spotify Roulette, suggest me an artist via reply and I will listen to them! #SpotifyRoulette';
		}
		$this->tweet = $tweet;
		
		$this->connection = $login->connection;
	}
	
	function start() {
		$this->connection->post('statuses/update', array('status' => $tweet));
		return;
	}
	
	function inprogress() {
		// get responses
		
	}
	
	function end($artist) {
		// valid response
		// get a playlist
		
		$spotify = new Spotify();

		$data = $spotify->search_artist($artist);

		$result_1 = $data->artists[0];
		header('Location: ' . $result_1->href);
		exit;
	}
}