<?php

abstract class HttpRequest {
	private $baseURL = 'http://query.yahooapis.com/v1/public/yql';
	private $queryString;
	private $response;

	private $format = 'json';
	private $env = 'http://datatables.org/alltables.env';


    public function query($yql)
    {
        $url = $this->build_url($yql);
        $this->response = $this->fetch_and_parse($url);
        return $this->response;
    }
    
    private function fetch_and_parse( $url )
    {
        $raw_data = $this->fetch($url);
        return $this->parse_data($raw_data);
    }
    
    private function fetch( $url )
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_VERBOSE, null);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($ch);
    }
    
    private function parse_data( $data )
    {
        return json_decode($data);
    }
    
    private function build_url( $query = "" )
    {
        $url[] = $this->baseURL;
        $url[] = $this->query_string(array(
            "q"        => $query,
            "format"   => $this->format,
            "env"      => $this->env,
            "callback" => ""
        ));
        
        return implode($url, '');
    }
    
    private function query_string( $keyvalues )
    {
        $str = array();
        foreach ($keyvalues as $key => $value) {
            $v = rawurlencode($value);
            $str[] = "{$key}={$v}";
        }
        return "?" . implode($str, '&');
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
