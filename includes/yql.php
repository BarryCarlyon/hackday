<?php

class yql {
	var $api_base = 'http://query.yahooapis.com/v1/public/yql';
	var $jsonobject = FALSE;
	
	function __construct() {
		$this->addParameter('format', 'json');
	}
	function __destruct() {
	}
	
	private function addParameter($name, $value) {
		$this->parameters[$name] = $value;
	}

	private function run() {
		// burn baby burn
		$url = $this->api_base;
		if ($this->parameters) {
			$url .= '?' . http_build_query($this->parameters);
		}
		
		$this->parameters = array();
		$this->addParameter('format', 'json');
		
		$this->url = $url;
		echo $url . '<br />';
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		
		if ($json = curl_exec($ch)) {
			$info = curl_getinfo($ch);
			curl_close($ch);

			$return = json_decode($json, $this->jsonobject);

			$code = $info['http_code'];
			
			if ($code == 200 || $code == 304) {
				// ok 304 means cached
			} else {
				//$this->error($code);
			}
			
			$this->return = $return;

			return $return;
		} else {
			return FALSE;
		}
	}
	
	public function run_query($query) {
		$this->addParameter('q', $query);
		
		return $this->run();
	}
}

include('yql.php');
$yql = new yql();

$query = 'select * from twitter.status.mentions where oauth_token = \'' . $_SESSION['oauth_token'] . '\' AND oauth_token_secret = \'' . $_SESSION['oauth_token_secret'] . '\' AND since_id = \'' . $_SESSION['game_since_id'] . '\'';//' AND oauth_consumer_key oauth_consumer_secret oauth_token oauth_token_secret';

$data = $yql->run_query($query);

echo '<pre>';
print_r($_SESSION);
print_r($data);
echo '</pre>';
