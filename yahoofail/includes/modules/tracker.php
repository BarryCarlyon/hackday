<?php

/**
* html/includes/modules/tracker.php
* Optional Tracker modules
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

define('S_TRACKER_TABLE',       'session_tracker');

class tracker extends session {
	function initialize() {
		global $session;

		//and import
		$this->ip_address	= $session->ip_address;
		$this->current_page	= $session->current_page;
		$this->last_page	= $session->last_page;
		$this->host		= loadvar('HTTP_HOST', '', 'server');
		$this->get		= '';

		foreach ($_GET as $ref => $data) {
			$this->get .= $ref . '=' . $data . '||';
		}

		return;
	}
	function add_track() {
		global $db;

		//create a track entry
		$query = 'INSERT INTO ' . S_TRACKER_TABLE . '(ip_address, this_page, last_page, http_host, get) VALUES (\'' . $this->ip_address . '\', \'' . $this->current_page . '\', \'' . $this->last_page . '\', \'' . $this->host . '\', \'' . $db->protect_data($this->get) . '\')';
		$db->get_data($query);
		//silent
		return;
	}
	function hit_count() {
		global $db, $template;

		//get total hits for current_page
		$query = 'SELECT COUNT(\'' . $this->current_page . '\') FROM ' . S_TRACKER_TABLE;
		$db->get_data($query);
		$row = $db->fetch_row();

		$template->tpl_vars['hits'] = $row['COUNT(\'' . $this->current_page . '\')'];

		return;
	}
}

