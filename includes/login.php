<?php

/*
* Login Controller
*/

class login {
	var $is_logged_in = FALSE;
	var $connection;

	function __construct() {
		global $config, $page;
		session_start();
		
		// detcech call backs
		// twitters
//		if (@$_POST['go_login'] == 'twitter') {
		if ($page == 'login' && !$_SESSION['go_login']) {
			$_SESSION['go_login'] = 'twitter';
			$this->twitter_login();
		}
		if (@$_GET['oauth_token'] && @$_GET['oauth_verifier'] && @$_SESSION['go_login'] == 'twitter') {
			unset($_SESSION['go_login']);
			// return from twitter
			$token = $_GET['oauth_token'];
			$verify = $_GET['oauth_verifier'];
			
			// we have keys
			$oauth_token_secret = $_SESSION['oauth_token_secret'];
			
			$this->connection = new TwitterOAuth($config->twitter_oauth_key, $config->twitter_oauth_secret, $token, $oauth_token_secret);
			$access_token = $this->connection->getAccessToken($verify);
			
			if ($access_token['user_id']) {
				$_SESSION['oauth_token'] = $access_token['oauth_token'];
				$_SESSION['oauth_token_secret'] = $access_token['oauth_token_secret'];
				$_SESSION['oauth_verifier'] = $verify;
				$_SESSION['twitter_user_id'] = $access_token['user_id'];
				$_SESSION['twitter_screen_name'] = $access_token['screen_name'];
				
				// make a bash for the country
				include('geo/geoip.inc');
				$gi = geoip_open(INCLUDES . 'geo/GeoIP.dat', GEOIP_STANDARD);
				$country = geoip_country_code_by_addr($gi, $_SERVER['REMOTE_ADDR']);
				
				if (!$country) {
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_URL, 'http://www.geoplugin.net/php.gp?ip=' . $_SERVER['REMOTE_ADDR']);
					$data = curl_exec($ch);
					curl_close($ch);

					$country_data = unserialize($data);
					$country = isset($country_data['geoplugin_countryCode']) ? $country_data['geoplugin_countryCode'] : '';
				}
				if ($country) {
					$_SESSION['country'] = $country;
				}
				
				$this->is_logged_in = TRUE;
				
				header('Location: /');
				exit;
			} else {
				add_error_message('An Error Occurred in the Return Auth');
			}
		} else if (@$_SESSION['go_login'] == 'twitter') {
			add_error_message('An Error Occurred in the Session Controller');
		}
		
		if (@$_SESSION['oauth_token'] && @$_SESSION['oauth_token_secret']) {
			$this->connection = new TwitterOAuth($config->twitter_oauth_key, $config->twitter_oauth_secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
			
			if (@$_GET['debug']) {
				$debug = $this->connection->get('account/rate_limit_status');
				echo '<pre>';
				print_r($debug);
				exit;
			}
			
			$content = $this->connection->get('account/rate_limit_status');
			if ($content->remaining_hits) {
				// load account data from session?
				if (@$_SESSION['account_data']) {
					$this->account_data = $_SESSION['account_data'];
				} else {
					$content = $this->connection->get('account/verify_credentials');
					if ($this->connection->http_code == 200) {
						$this->account_data = $content;
						$_SESSION['account_data'] = $content;
					} else {
						if ($content->error) {
							add_error_message($content->error);
							return;
						}
					}
				}
				$this->is_logged_in = TRUE;
			} else {
				if (@$content->error) {
					add_error_message($content->error);
					return;
				} else {
					add_error_message('Out of API Hits');
					return;
				}
				// session is invalid
//				session_destroy();
//				session_start();
			}
		}
	}
	
	function restart() {
		session_destroy();
		session_start();
	}
	
	function twitter_login() {
		global $config, $db;
		
		$this->connection = new TwitterOAuth($config->twitter_oauth_key, $config->twitter_oauth_secret);
		$request_token = $this->connection->getRequestToken('http://' . $_SERVER['HTTP_HOST'] . '/login/');
		switch ($this->connection->http_code) {
			case 200:
				// user....
				$_SESSION['oauth_token'] = $request_token['oauth_token'];
				$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
				
				$url = $this->connection->getAuthorizeUrl($request_token['oauth_token'], FALSE);
				header('Location: ' . $url);
				exit;
			default:
				add_error_message('Could not connect to Twitter');
		}
	}
	
	function twitter_profile() {
		$return = '';
		
		$img = $this->account_data->profile_image_url_https;
		$return .= '<img src="' . $img . '" alt="You" title="You" class="left" />';
		$return .= '<div>';
		$return .= '<p>Hi, ' . $this->account_data->screen_name . '</p>';
		
		$game = new game();
		if (!@$_POST['game_start']) {
			$return .= $game->form();
		}
		
//		$return .= '<p><a href="/logout/">Logout</a></p>';
		$return .= '</div>';
		
		return $return;
	}
	
	function logout() {
		$this->restart();
		header('Location: /');
		exit;
	}
}
