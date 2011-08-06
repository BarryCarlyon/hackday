<?php

/*
* Login Controller
*/

class login {
	var $is_logged_in = FALSE;
	
	function __construct() {
		session_start();
		
		// detcech call backs
		// twitters
		if (@$_POST['go_login'] == 'twitter') {
			$_SESSION['go_login'] == 'twitter';
			$this->twitter_login();
		}
		if (@$_GET['oauth_token'] && @$_GET['oauth_verifier'] && @$_SESSION['go_login'] == 'twitter') {
			// return from twitter
			$token = $_GET['oauth_token'];
			$verify = $_GET['oauth_verifier']
		} else if (@$_SESSION['go_login'] == 'twitter') {
			add_error_message('An Error Occured');
		}
	}
	
	function restart() {
		session_destroy();
		session_start();
	}
	
	function twitter_login() {
		global $config;
		
		$connection = new TwitterOAuth($config->twitter_oauth_key, $config->twitter_oauth_secret);
		$request_token = $connection->getRequestToken('http://' . $_SERVER['HTTP_HOST'] . '/login/');
		switch ($connection->http_code) {
			case 200:
				$url = $connection->getAuthorizeUrl($request_token['oauth_token'], FALSE);
				header('Location: ' . $url);
				exit;
			default:
				add_error_message('Could not connect to Twitter');
		}
	}
	
}
