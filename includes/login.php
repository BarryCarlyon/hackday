<?php

/*
* Login Controller
*/

class login {
	function __construct() {
		session_start();
	}
	
	function restart() {
		session_destroy();
		session_start();
	}
	
	function twitter_login() {
		
	}
	
}
