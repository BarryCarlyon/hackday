<?php

// play the game

class game {
	function __construct() {
		global $login;
		
		if (!$login->is_logged_in) {
			return;
		}
		$this->connection = $login->connection;
	}
	
	function form() {
		return '
<form action="/play/" method="post"><fieldset>
	<input type="hidden" name="game_start" value="1" />
	<input type="submit" value="Play Spotify Roulette" />
</fieldset></form>
';
	}
	
	function setup($tweet = '') {
		if (!$this->connection) {
			return FALSE;
		}
		if (!$tweet) {
			$tweet = 'I am playing Spotify Roulette, suggest me an artist via reply and I will listen to them! #SpotifyRoulette';
		}
//		$tweet = 'Testing my app ' . time();
		$this->tweet = $tweet;
		
		echo '<div id="game_responses"><script type="text/javascript">start_game();</script></div>';
		return TRUE;
	}
	
	function start() {
		$result = $this->connection->post('statuses/update', array('status' => $this->tweet));
		if (isset($result->error)) {
			echo '<p>An Error Occured: ' . $result->error . '</p>';
			return FALSE;
		}
		$_SESSION['game_tweet_id'] = $result->id;
		return TRUE;
	}
	
	function inprogress() {
		// get responses
		$since_id = $_SESSION['game_tweet_id'];
		$mentions = $this->connection->get('statuses/mentions', array('since_id' => $since_id));
		
		foreach ($mentions as $mention) {
			$tweet = $mention->text;
			
			$response_to = $mention->in_reply_to_status_id;

			if ($response_to == $since_id) {
				// a response
				
				// strip the @
				if (preg_match('/(\@\w+)/', $tweet, $user)) {
					$user = $user[0];
				}
				$test = preg_replace('/(\@\w+)/', '', $tweet);
				// strin the #tag
				$test = preg_replace('/(\#\w+)/', '', $test);
				$test = trim($test);
				
				// test whats left for an artist
				$result = $this->end($test);
				
				if ($result) {
					$data = array(
						'user'		=> $user,
						'artist'	=> $test,
						'url'		=> $result
					);
					echo json_encode($data);
				}
				
				break;
			}
		}
	}
	
	function end($artist) {
		// valid response
		// get a playlist
		
		$spotify = new Spotify();

		$data = $spotify->search_artist($artist);

		$result_1 = $data->artists[0];
//		header('Location: ' . $result_1->href);
//		echo $result_1->href;
		return $result_1->href;
//		exit;
	}
}