<?php

/**
* html/includes/functions.php
* Some basic functions
* 
* Copyright (c) 2009 Barry Carlyon <barry@barrycarlyon.co.uk>
* 
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
* 
* The above copyright notice and this permission notice shall be included in
* all copies or substantial portions of the Software.
* 
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
* THE SOFTWARE.
* 
* 
* @catergory Websites
* @package LSRfm.com Content Management System
* @author Barry Carlyon <barry@barrycarlyon.co.uk>
* @copyright 2008-2009 Barry Carlyon
* @license http://www.opensource.org/licenses/mit-license.php MIT License
* @version CVS: $Id:$
* @link http://barrycarlyon.co.uk/
* 
*/

/**
* 
* error handler!
* teh big kahuna!
* 
* It is setup to catch non catcable errors for the moment
* 
*/
function cms_error_handler($errno, $errstr, $errfile, $errline, $context) {
	global $errors;
	$message = '';
	$exit = FALSE;

	switch ($errno) {
//		case E_RECOVERABLE_ERROR:
//			echo 'e recov';
//			break;
		case E_STRICT:
			if (strpos($errfile, 'pear') || strpos($errfile, 'email'))
				break;
			$message = '<br /><strong>PHP Coding Error</strong>: ' . $errfile . ':' . $errline . ' - ' . $errstr;
			break;
		case E_USER_NOTICE:
			$message = '<div class="user_notice">' . $errstr . '</div>';
			break;
		case E_USER_WARNING:
			$message = '<div class="user_warning"><strong>Warning</strong>: ' . $errstr . '</div>';
			break;
		case E_USER_ERROR:
			$exit = TRUE;
			if ($errstr == 'SQL_ERROR') {
				//we have a SQL Error to handle, currently set to non fatal,
				//as scripts should detect where rows have been affected, etc
				global $db;
				$message = '<br /><strong>SQL Error Detected</strong>:<br />' . $db->query . '<br />Error: ' .  mysqli_errno($db->connect_id) . ' - ' . mysqli_error($db->connect_id) . '<br />On ' . $errline . ' in ' . $errfile . '<br />';
				$exit = FALSE;
			} else if ($errstr == 'OFFLINE') {
				//override all previous errors
				$errors = '<img src="/images/cms_logo.jpg" alt="Database Offline" title="Database Offine" />';
				$errors .= '<br /><strong>Database</strong>:<br />Unable to connect to the database, the website is offline.<br />Apologies';
			} else if (substr($errstr,0,7) == 'INCLUDE' ) {
				$errors = substr($errstr,8);
				$errors .= '<br />A Fatal Error has Occured';
			} else if ($errstr == 'SITE_OFFLINE') {
				$errors .= '<br /><strong>Site Offline</strong>:<br />This website is currently offline</body></html>';
				echo_errors();
				exit;
			} else {
				$message = 'Undefined User Error' . $errstr;
			}
			break;
		case E_NOTICE:
			//non fatal
			$message = '<br /><strong>Notice</strong>: ' . $errfile . ':' . $errline . ' - ' . $errstr;
			break;
		case E_PARSE:
			echo 'e parse';
			break;
		case E_WARNING:
			//non fatal
			$message = '<br /><strong>Warning</strong>: ' . $errfile . ':' . $errline . ' - ' . $errstr;
			break;
		case E_ERROR:
			//these cant be catched (well can with ob, but failed to implment it, for now)
			$message = '<br /><strong>PHP Fatal Error</strong>: ' . $errfile . ':' . $errline . ' - ' . $errstr;
			$exit = TRUE;
			break;
		default:
			echo 'undef error ' . $errno;
			$exit = TRUE;
	}

	if ($exit) {
		$errors .= '<hr />' . $message;
		if (class_exists('template')) {
			global $template;
			//needs to be passed to the template engine, as we are inside it
			//specifically the compile_and_echo or its derivertives
			//this usually/should occur when inside a template, post header inclusion, but not the templates
			//but still
			if (!defined('HEADER')) {
				//the error appears to have occured whilst generating the header!
				echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . "\n";
				echo '<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">' . "\n";
				echo '<title>Error</title></head><body>' . "\n";
			}
			check_errors();
			//could trip up if a error occurs in the footer, thats a bug to fix if it happens....
			//mind you the error could be in generate common, I hope not
			//thank god for server error logs :-)
			$template->generate_common('footer');
			//no preg match
			echo $template->html;
			die();
		} else {
			//we gotta die, but we have no content yet
			echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . "\n";
			echo '<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">' . "\n";
			echo '<title>Error</title></head><body>' . "\n";
			echo_errors();
			echo "\n" . '</body></html>';
			die();
		}
	} else {
		//non Fatal Error, we can continue! Hurrah!
		//lets log the error to the global error string
		$errors .= '' . $message;
	}
	return TRUE;
}
/*
function ob_error_handler($output) {
	$error = error_get_last();
	$output = "";
//	foreach ($error as $info => $string)
//		$output .= "{$info}: {$string}\n";
	cms_error_handler($error['type'], $error['message'], $error['file'], $error['line'], '');
	return $output;
}
*/
/**
* 
* This function is called if the template engine has not be generated/called/create/instatiated yet
* 
*/
function echo_errors() {
	global $errors;
	echo '<div id="errors" style="text-align: center; width: 100%; margin-left: auto; margin-right: auto;">' . $errors . '</div>';
	return;
}
/**
* 
* this function does the same as above, but passes the errors to the template engine for compile_and_echo
* 
*/
function check_errors() {
	//we are in the template engine, hence this
	global $errors, $template;
	if (strlen($errors)) {
		$template->html .= '<div id="errors" style="text-align: center; width: 100%; margin-left: auto; margin-right: auto;">' . $errors . '</div><br /><br />';
	}
	return;
}

/**
* 
* Composite function to load data
* most commonly from $_REQUEST
* its also sanitizes it (in function) for use
* 
* It can call data from other elements
* as long as that element has been created
* by the template when instantiated
* 
* AKA common PHP super globals
* 
* data load and protect
* 
*/
function loadvar($var, $default = '', $load = '') {
	global $template;
	if (empty($load)) {
		$load = $template->request;
	} else {
		$load = $template->$load;
	}
	$res = isset($load[$var]) ? (is_array($load[$var]) ? $load[$var] :  htmlentities($load[$var], ENT_QUOTES, 'UTF-8')) : $default;
	$res = !empty($res) ? $res : $default;
	return $res;
}

/**
* 
* My favourite function for checking emails exist
* email check for teh mx record
* 
*/
function check_email_mx($email) {
	if ( (preg_match('/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/', $email)) || (preg_match('/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/',$email)) ) {
		$host = explode('@', $email);
		if (function_exists('checkdnsrr')) {
			if (checkdnsrr($host[1].'.', 'MX') ) return true;
			if (checkdnsrr($host[1].'.', 'A') ) return true;
			if (checkdnsrr($host[1].'.', 'CNAME') ) return true;
		}
	}
	return FALSE;
}

function url_patch($matches) {
	global $template;
	$video_enable = $template->video;
	$redirect_enable = $template->redirect;

	if ($redirect_enable) {
		$linky = str_replace('http://', '/redirect/', $matches[0]);
	} else {
		$linky = $matches[0];
	}

	$text = explode('://', $matches[0]);
	$text = str_replace($text[0] . '://', '', $matches[0]);

	if (substr($text, -1, 1) == '/') {
		$text = substr($text, 0, -1);
	}

	// check it for you tube
	if ($video_enable) {
		if (strpos($matches[0], 'youtube.com') !== FALSE) {
			$template->youtube_count ++;
			// watch?v= = v/
			$url = str_replace('watch?v=', 'v/', $matches[0]);
			return '
<div id="youtube_' . $template->youtube_count . '" class="center"><p>This is where a YouTube video goes, but you don&#39;t appear to have Flash installed on your system.<br />Please Visit <a href="http://www.adobe.com/products/flashplayer/">Adobe Flash Player to Download</a><p></div>
<script type="text/javascript">
	var yt = new SWFObject("' . $url . '", "youtube_' . $template->youtube_count . '", "480", "390", "9", "#FFFFFF");
	yt.write("youtube_' . $template->youtube_count . '");
</script>
';
		}
		// check for 360
		if (strpos($matches[0], '360gaming.net') !== FALSE) {
			$template->youtube_count ++;
		// append .swf then streplace /.swf with .swf
			$url = str_replace('/.swf', '.swf', $matches[0] . '.swf');
			return '[
<div id="g360_' . $template->youtube_count . '" class="center"><p>This is where a 360gaming.net video goes, but you don&#39;t appear to have Flash installed on your system.<br />Please Visit <a href="http://www.adobe.com/products/flashplayer/">Adobe Flash Player to Download</a><p></div>
<script type="text/javascript">
	var yt = new SWFObject("' . $url . '", "g360_' . $template->youtube_count . '", "480", "390", "9", "#FFFFFF");
	yt.write("g360_' . $template->youtube_count . '");
</script>
';
		}
	}

	return '<a href="' . $linky . '" target="_blank">' . $text . '</a>';
}

