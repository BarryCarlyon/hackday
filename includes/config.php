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
	'host'		=> '.lsrfm.com',
	'script_path'	=> '/',
	'secure'	=> 0,
	'http_only'	=> 0,
);
/**
* FTP Details
*/
$ftp = array(
	'server'	=> 'ftp.lsrfm.com',
	'user'		=> 'admin',
	'pass'		=> 'neilcarlyon13',
);

/**
* define tables
*/
define('CONFIG_TABLE',		'config');
define('SESSION_TABLE',		'sessions');
define('REDIRECT_TABLE',	'redirects');

define('ADMIN_SESSIONS',	'admin_sessions');
define('ADMIN_SYSTEMS',		'admin_systems');

define('USER_LIST',		'userlist');
define('PENDING_USER',		'userlist_pending');

define('MEGA_ACCESS',		'mega_access');
define('MEGA_LISTS',		'mega_lists');

/**
* SHOWS
*/
define('SCHEDULE',              'schedule');
define('SHOWS',                 'shows');
define('PRESENTERS',            'presenters');

define('CONTENT',		'website_content');
define('ADVERTS',		'adverts');

define('NEWS_TABLE',            'news');
define('HOME_LAYOUT',           'news_layout');
define('NEWS_CATEGORIES',       'news_categories');
define('PLAYLIST_TABLE',        'playlist');

define('PODCAST_TABLE',		'podcasts');
define('PODCAST_SUB_DATA',	'podcast_subs');

define('LISTINGS',		'listings');

/**
* Admin
*/
define('SHOW_PROPOSALS',	'show_proposal');
define('DOWNLOAD_TRACKER',	'download_tracker');
define('PRODUCTION_FILES',	'production_files');
define('PRODUCTION_GRANT',	'production_grant');
define('ADMIN_PAGE_GRANT',	'admin_page_grant');
define('XML_SOURCES',		'xml_sources');
define('EVENTS',		'admin_events');

define('TRACK_LOG',		'track_log');

/**
* Here be the recaptcha API keys
*/
$recaptcha_key = '';
$recaptcha_prv = '';

/**
* Publihser
*/
$publisher_data = array(
	'twitter'	=> array(
		'enable'	=> TRUE,
		'user'		=> 'lsrfm_com',
		'password'	=> 'lemonsrcool',
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
$page_title_base = 'LSRfm.com';
$copyright = 'LSRfm.com - Leeds Student Radio';
$base_timezone = 'Europe/London';
/**
* Basic template/common files which should not be included on the sitemap
*/
$sitemap_exclude = array('content_base', 'content_top', 'blade', 'side_nav', 'info');

$days = array(
	'0'	=> 'Sunday',
	'1'	=> 'Monday',
	'2'	=> 'Tuesday',
	'3'	=> 'Wednesday',
	'4'	=> 'Thursday',
	'5'	=> 'Friday',
	'6'	=> 'Saturday',
);

