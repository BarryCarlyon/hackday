<?php

abstract class HttpRequest {
	private $baseUrl = 'http://query.yahooapis.com/';
	private $uriPath = 'v1/public/yql';
	private $queryString;
	private $response;

	private $format = 'json';
	private $env = 'http://datatables.org/alltables.env';

	public function __construct() {
	}

	public function doRequest() {
		$requestUrl = str_replace('%2A', '*', $this->baseUrl.$this->uriPath.'?q='.rawurlencode($this->queryString).'&format='.rawurlencode($this->format).'&env='.rawurlencode($this->env).'&callback=');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $requestUrl);
		$this->response = curl_exec($ch);
	}


	// PROPERTIES

	public function getResponse() {
		return $this->response;
	}

	public function getBaseUrl() {
		return $this->baseUrl;
	}

	public function setUriPath($value) {
		$this->uriPath = $value;
	}

	public function getUriPath() {
		return $this->uriPath;
	}

	public function setQueryString($value) {
		$this->queryString = $value;
	} 

	public function getQueryString() {
		return $this->queryString;
	}
}
?>
