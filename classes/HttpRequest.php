<?php

abstract class HttpRequest {
	private $baseUrl = 'http://query.yahooapis.com';
	private $uriPath = 'v1/yql';
	private $queryString;
	private $response;

	private $format = 'json';
	private $env = 'env=http://datatables.org/alltables.env'

	public __construct() {
	}

	public doRequest() {
		$requestUrl = $baseUrl.$uriPath.'?'.htmlentities($queryString.'&format='.$this->format.'&env='.$this->env);
		$ch = curl_init();
		curl_setopt($ch, CURL_OPT, $requestUrl);
		curl_setopt($ch, CURL_POST, 1);
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
		$this->querySring = $value;
	} 

	public function getQueryString() {
		return $this->queryString;
	}
}
?>
