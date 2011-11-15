<?php

/**
* html/config.php
* Its a config file
* 
* Copyright (c) 2008 Barry Carlyon <barry@barrycarlyon.co.uk>
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
* @copyright 2008-2008 Barry Carlyon
* @license http://www.opensource.org/licenses/mit-license.php MIT License
* @version CVS: $Id:$
* @link http://barrycarlyon.co.uk/
* 
*/

/**
* Setup and define the database connect
*/
$database = array(
	'type'	=> 'mysqli',
	'host'	=> 'localhost',
	'name'	=> 'hackday',
	'user'	=> 'hackday',
	'pass'	=> 'hackdaypass',
);
/**
* Setup and define the cookie details
*/
$cookie = array(
	'name'		=> 'fudge',
	'host'		=> ".{$_SERVER["HTTP_HOST"]}",
	'script_path'	=> '/',
	'secure'	=> 0,
	'http_only'	=> 0,
);
/**
* FTP Details
*/
$ftp = array(
	'server'	=> '',
	'user'		=> '',
	'pass'		=> '',
);

/**
* define tables
*/
define('CONFIG_TABLE',		'config');
define('SESSION_TABLE',		'sessions');

/**
* Publihser
*/
$publisher_data = array(
	'twitter'	=> array(
		'enable'	=> TRUE,
		'user'		=> '',
		'password'	=> '',
	),
	'rss'		=> array(
		'enable'	=> TRUE,
	),
	'itunes'	=> array(
		'enable'	=> FALSE,
	),
);

/**
* call database library
*/
include('database/' . $database['type'] . '.php');

/**
* website configuration
* titles an the like
*/
$page_title_base = 'Dumb Deeds';
$copyright = 'Dumb Deeds';
$base_timezone = 'Europe/London';
/**
* Basic template/common files which should not be included on the sitemap
*/
$sitemap_exclude = array();

$days = array(
	'0'	=> 'Sunday',
	'1'	=> 'Monday',
	'2'	=> 'Tuesday',
	'3'	=> 'Wednesday',
	'4'	=> 'Thursday',
	'5'	=> 'Friday',
	'6'	=> 'Saturday',
);

