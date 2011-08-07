<?php

class Spotify {
	var $api_base = 'http://ws.spotify.com/';
	var $api_key = '';
	var $version = 1;
	var $jsonobject = FALSE;
	
	function __construct() {//}$api_key) {
//		$this->api_key = $api_key;
	}

	function __destruct() {
	}
	
	private function addParameter($name, $value) {
		$this->parameters[$name] = $value;
	}
	
	private function run() {
		// burn baby burn
		$url = $this->api_base;
		$url .= $this->service . '/';
		$url .= $this->version . '/';
		$url .= $this->method;
//		$url .= '.json';
		if ($this->parameters) {
			$url .= '?' . http_build_query($this->parameters);
		}
		$this->parameters = array();
		$this->url = $url;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
		
		if ($json = curl_exec($ch)) {
			$info = curl_getinfo($ch);
			curl_close($ch);

			$return = json_decode($json, $this->jsonobject);

			$code = $info['http_code'];
			
			if ($code == 200 || $code == 304) {
				// ok 304 means cached
			} else {
				$this->error($code);
			}
			
			$this->return = $return;

			return $return;
		} else {
			return FALSE;
		}
	}
	private function error($code) {
		$message = '';
		switch ($code) {
			case 400:
				$message = 'Bad Request';
			case 403:
				$message = $message ? $message : 'Rate Limit';
			case 404:
				$message = $message ? $message : 'Not Found';
			case 406:
				$message = $message ? $message : 'Bad Format';
			case 500:
				$message = $message ? $message : 'Fuuuuu';
			case 503:
				$message = $message ? $message : 'Is Down';
			default:
				$message = $message ? $message : 'Unknown';
		}
		
		$str = 'Error Code: ' . $code . ': ' . $message . ': ' . $this->url;
		trigger_error($str, E_USER_WARNING);
		$this->error = $str;
		$this->error_code = $code;
		$this->error_message = $message;
		return false;
	}
	
	public function search_artist($artist) {
		$this->service = 'search';
		$this->method = 'artist';
		
		$this->addParameter('q', $artist);
		
		return $this->run();
	}
	public function lookup_artist($artisturi, $extras = '') {
		$this->service = 'lookup';
		$this->method = '';
		
		$this->addParameter('uri', $artisturi);
		if ($extras) {
			$this->addParameter('extras', $extras);
		}
		
		return $this->run();
		$data = $this->run();
		echo '<pre>';
		print_r($data);
		exit;
	}
	public function lookup_album($albumruri, $extras = '') {
		$this->service = 'lookup';
		$this->method = '';
		
		$this->addParameter('uri', $albumruri);
		if ($extras) {
			$this->addParameter('extras', $extras);
		}
		
		return $this->run();
	}
	public function lookup_track($trackuri, $null = '') {
		$this->service = 'lookup';
		$this->method = '';
		
		$this->addParameter('uri', $trackuri);
		
		return $this->run();
	}
}
