<?php

class config() {
	var $twitter_oauth_key = '';
	var $twitter_oauth_secret = '';
	
	var $database = array();
	
	function __construct() {
		$database = array(
			'host'	=> 'localhost',
			'user'	=> 'root',
			'pass'	=> '',
			'name'	=> 'spotify_roulette'
		);
	}
}
