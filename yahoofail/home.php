<?php

/**
* html/home.php
* Its the main drag and control
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

$start_time = microtime(true);
set_include_path(dirname(__FILE__) . '/includes' . PATH_SEPARATOR . get_include_path() );

//load some basic functions
include('functions.php');

//error handler
$errors = '';
set_error_handler("cms_error_handler");

//set her up
include('config.php');

//time zone
date_default_timezone_set($base_timezone);

//test connect
$db = new db();
$dbtest = $db->test_connect();

if (!$dbtest) {
	trigger_error('OFFLINE', E_USER_ERROR);
}

include('system.php');
include('session.php');
include('template.php');
$config = new config();
$session = new session();
$template = new template();

include('email.php');

$config->initialize();
$template->initialize();
$session->initialize();

include('optionals.php');

//setup complete go for determine and display

/*
* 
* User configuration area, use array to define files to call, website common things such as navigations
* 
* Example:
* $templates = array('navigation','content_top','content_base','side_nav');
*/

$templates = array('navigation');
$post_templates = array();

$template->compile_and_echo($templates, $post_templates);

