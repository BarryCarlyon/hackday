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
<form action="/play/" method="post" id="spin_form"><fieldset>
	<input type="hidden" name="game_start" value="1" />
	<label for="custom_tweet">Custom Tweet: <input type="checkbox" name="custom_tweet" id="custom_tweet" /></label>
	<input type="text" name="custom_tweet_text" id="custom_tweet_text" style="display: none;" />
	<input type="submit" value="Play Spotify Roulette" id="play_spotify_roulette" />
</fieldset></form>
';
	}
	
	function setup($tweet = '') {
		if (!@$this->connection) {
			return FALSE;
		}
		if (!$tweet) {
			$tweet = 'I am playing #SpotifyRoulette, suggest me an artist via reply and I will listen to them!';
		}
		$this->tweet = $tweet;
		
		echo '<div id="game_responses"><script type="text/javascript">jQuery(document).ready(function() {start_game();});</script>Waiting for Responses...</div>';
		return TRUE;
	}
	
	function start() {
		$result = $this->connection->post('status/update', array('status' => $this->tweet));
		if (isset($result->error)) {
			echo '<p>An Error Occured: ' . $result->error . '</p>';
			return FALSE;
		}
		if (!$result->id) {
			echo '<pre>';
			print_r($this->connection);
			print_r($result);
			echo '</pre>';
			return FALSE;
		}
		$_SESSION['game_tweet_id'] = $result->id;
		return TRUE;
	}
	
	function inprogress() {
		// get responses
//		print_r($_SESSION);
		$target_id = $_SESSION['game_tweet_id'];
		$since_id = isset($_REQUEST['mid']) ? $_REQUEST['mid'] : $_SESSION['game_tweet_id'];
		$reverse_mentions = $this->connection->get('statuses/mentions', array('since_id' => $since_id, 'include_rts' => '1', 'include_entities' => '1'));
		// meed to reverse
		// and update since_id
		
		$mentions = array_reverse($reverse_mentions);
		$jsons = array();
		
		foreach ($mentions as $mention) {
			$tweet = $mention->text;
//			echo $tweet . '<br />';
			
//			print_r($mention);
			
			$response_to = $mention->in_reply_to_status_id;
			$from = $mention->user->screen_name;
			
//			if ($response_to == $target_id) {
				// a response
				
				$splink = FALSE;
				// check for open.spotify.com
				if ($mention->entities->urls) {
					foreach ($mention->entities->urls as $link) {
						if (FALSE !== strpos($link->expanded_url, 'open.spotify.com')) {
							// found
							$splink = $link->expanded_url;
						}
					}
				}
				// check for spotify direct link
				
				//spotify:track:14cdiU9uL7HSYf8oaHyoCF
				//spotify:artist:6NaTOhsj6iiUNONPrE980Z
				//spotify:user:stanton:playlist:40ykJaHDstCHFP3go1fSFe
				if (FALSE !== strpos($tweet, 'spotify:track')) {
					// spofifydirect link
					preg_match('(spotify:track:\w+)', $tweet, $splink);
					$splink = $splink[0];
				}
				if (FALSE !== strpos($tweet, 'spotify:artist')) {
					// spofifydirect link
					preg_match('(spotify:artist:\w+)', $tweet, $splink);
					$splink = $splink[0];
				}
				
				if ($splink) {
					// convert link
					if (FALSE !== strpos($splink, 'open.spotify.com')) {
						// found
						$url = str_replace('http://', '', $splink);
						list($shit, $method, $key) = explode('/', $url);
					}
					if (FALSE !== strpos($splink, 'spotify:track') || FALSE !== strpos($splink, 'spotify:artist')) {
						list($shit, $method, $key) = explode(':', $splink);
					}
					
					$target = 'lookup_' . $method;
					$spotify = new Spotify();
//					echo 'call ' . $target;
					if ($method == 'artist') {
						$extra = 'album';
					} else if ($method == 'track') {
						$extra = '';
					}
					$data = $spotify->$target('spotify:' . $method . ':' . $key, $extra);

					$akey = 'artist-id';
					if ($method == 'artist') {
						$test = $data->artist->albums[0]->album->artist;
						$artisturi = $data->artist->albums[0]->album->$akey;
					} else if ($method == 'track') {
						$test = $data->track->artists[0]->name;
						$artisturi = $data->track->artists[0]->href;
					}
					// use item link
					$result = array('trackuri' => 'spotify:' . $method . ':' . $key, 'artisturi' => $artisturi);
				} else {
					// strip the @
					$test = preg_replace('/(\@\w+)/', '', $tweet);
					// string the #tag
					$test = preg_replace('/(\#\w+)/', '', $test);
					// search for end of string or special character
					// currenly , . or !
					$test = preg_replace('/([,!] [\w\s]+)/', '', $test);
					$test = preg_replace('/([. ] [\w\s]+)/', '', $test);
				
					// trim
					$test = trim($test);
					
					// test whats left for an artist
					$result = $this->end($test);
				}
				
				if ($result) {
					$data = array(
						'user'		=> $from,
						'artist'	=> $test,
						'url'		=> $result['trackuri'],
						'mid'		=> $mention->id_str,
						'string'	=> $from . ' suggested <a href="' . $result['trackuri'] . '" class="playartist" artist="' . $test . '" suggest="' . $from . '">' . $test . '</a> <a href="#playlist" class="getplaylist" artist="' . $test . '" artisturi="' . $result['artisturi'] . '">Get Playlist</a>'
					);
					$jsons[] = $data;
				}
//			}
//			$_SESSION['game_since_id'] = $mention->id;
//			print_r($_SESSION);
		}
		
		echo json_encode($jsons);
	}
	
	function end($artist) {
		// valid response
		// get a playlist
		$spotify = new Spotify();

		$data = $spotify->search_artist($artist);

		if ($result_1 = @$data->artists[0]) {
			// artist found
			// album
			$artist_id = $result_1->href;
			
			$albums = $spotify->lookup_artist($artist_id, 'album');
			$albums = $albums->artist->albums;
			
			// stop
			// non various artists please
			$misc_albums = $albums;
			$albums = array();
			
			$key = 'artist-id';
			
			foreach ($misc_albums as $album) {
//				if (strtolower($album->album->artist) == strtolower($artist)) {
				if (isset($album->album->$key)) {
					if ($album->album->$key == $artist_id) {
						// keeper
						$albums[] = $album;
					}
				}
			}
			
			shuffle($albums);
			$album = array_pop($albums);
			
			if (@$_SESSION['country']) {
				$territories = strtolower($album->album->availability->territories);
				$territories = explode(' ', $territories);
				$albumuri = FALSE;
				if (in_array(strtolower($_SESSION['country']), $territories)) {
					$albumuri = $album->album->href;
				}
				
				while (!in_array(strtolower($_SESSION['country']), $territories) && count($albums)) {
					shuffle($albums);
					$album = array_pop($albums);
					
					$territories = strtolower($album->album->availability->territories);
					$territories = explode(' ', $territories);
					
					if (in_array(strtolower($_SESSION['country']), $territories)) {
						$albumuri = $album->album->href;
					}
				}
				
				if (!$albumuri) {
					return FALSE;
				}
			} else {
				// hmm
				if (isset($album->ablum->href)) {
					$albumuri = $album->album->href;
				} else {
					return FALSE;
				}
			}
			
			
			// track
			$tracks = $spotify->lookup_album($albumuri, 'track');
			$tracks = $tracks->album->tracks;
			
			shuffle($tracks);
			$track = array_pop($tracks);
			$trackuri = FALSE;
			if ($track->available) {
				$trackuri = $track->href;
			}

			while (!$track->available && count($tracks)) {
				shuffle($tracks);
				$track = array_pop($tracks);
				if ($track->available) {
					$trackuri = $track->href;
				}
			}
			
			if (!$trackuri) {
				return FALSE;
			}
			
			return array('trackuri' => $trackuri, 'artisturi' => $artist_id);
//			return $result_1->href;
		} else {
			return FALSE;
		}
	}
}
