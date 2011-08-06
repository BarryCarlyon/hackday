<?php

// play the game
// need to verify connection in sub calls

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
		
		echo '<div id="game_responses"><script type="text/javascript">start_game();</script>Waiting for Responses...</div>';
		return TRUE;
	}
	
	function start() {
//		$result = $this->connection->post('statuses/update', array('status' => $this->tweet));
//		if (isset($result->error)) {
//			echo '<p>An Error Occured: ' . $result->error . '</p>';
//			return FALSE;
//		}
//		$_SESSION['game_tweet_id'] = $result->id;
		$_SESSION['game_tweet_id'] = 99918985038008320;
		return TRUE;
	}
	
	function inprogress() {
		// get responses
		$target_id = $_SESSION['game_tweet_id'];
		$since_id = isset($_SESSION['game_since_id']) ? $_SESSION['game_since_id'] : $_SESSION['game_tweet_id'];
		$reverse_mentions = $this->connection->get('statuses/mentions', array('since_id' => $since_id));
		// meed to reverse
		// and update since_id
		
		$mentions = array_reverse($reverse_mentions);
		$jsons = array();
		
		foreach ($mentions as $mention) {
			$tweet = $mention->text;
			
			$response_to = $mention->in_reply_to_status_id;
			$from = $mention->user->screen_name;

			if ($response_to == $target_id) {
				// a response

				// strip the @
				$test = preg_replace('/(\@\w+)/', '', $tweet);
				// string the #tag
				$test = preg_replace('/(\#\w+)/', '', $test);
				// search for end of string or special character
				// currenly , . or !
				$test = preg_replace('/([,.!] [\w\s]+)/', '', $test);
				
				// trim
				$test = trim($test);
				
				// test whats left for an artist
				$result = $this->end($test);
				
				if ($result) {
					$data = array(
						'user'		=> $from,
						'artist'	=> $test,
						'url'		=> $result
					);
					$jsons[] = $data;
					$_SESSION['game_since_id'] = $mention->id;
				}
			}
		}
		
		echo json_encode($jsons);
	}
	
	function end($artist) {
		// valid response
		// get a playlist
		
		$spotify = new Spotify();

		$data = $spotify->search_artist($artist);

		if ($result_1 = $data->artists[0]) {
			return $result_1->href;
		} else {
			return FALSE;
		}
	}
}