<?php

class Spotify {
	var $api_base = 'http://ws.spotify.com/';
	var $api_key = '';
	
	function __construct($api_key) {
		$this->api_key = $api_key;
	}

	function __destruct() {
	}
	
	function run() {
		// burn baby burn
	}
}
