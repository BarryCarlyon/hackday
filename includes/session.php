<?php

/**
* html/includes/session.php
* Session manager
* Rewritten version of the GSoC version for guest management
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
* Its the session manager
* A session is defined as a users path thruough a website
* If the user does not visit a session managed page within 15 mins
* a new session is generated
* 
* the session id is stored in the database with some other details
* and the session id is pushed to a cookie for session persistance between page loads
* 
*/
class session {
	public $session_id	= '';
	public $ip_address	= '';
	public $last_page	= '';
	public $current_page	= '';
	public $host		= '';

	/**
	* basically lets see if a session cookie exists
	* if so update the session and extended the cookie life
	* otherwise bake a new session
	*/
	function initialize() {
		global $db, $cookie;

		//kill old sessions
		$earlier = time() - (15*60);
		$query = 'DELETE FROM ' . SESSION_TABLE . ' WHERE UNIX_TIMESTAMP(last_active) < ' . $earlier;
		$db->get_data($query);

		$this->ip_address	= loadvar('REMOTE_ADDR', '', 'server');
		$this->last_page	= str_replace('http://' . $cookie['host'], '', loadvar('HTTP_REFERER', '', 'server'));
		$this->current_page	= loadvar('REDIRECT_URL', '', 'server');
		$this->http_host	= loadvar('HTTP_HOST', '', 'server');

		//load that cookie
		if (isset($_COOKIE[$cookie['name'] . '_sesh'])) {
			$this->session_id = $_COOKIE[$cookie['name'] . '_sesh'];
			//the user is returning, currently active
			$query = 'SELECT * FROM ' . SESSION_TABLE . ' WHERE session_id = \'' . $this->session_id . '\'';
			$db->get_data($query);
			if ($db->total_rows == 1) {
				$this->update($db->fetch_row());
//				$this->update();
			} else {
				//eat that cookie!
				$this->kill();
				//assign new session
				$this->create();
			}
		} else {
			//assign new session
			$this->create();
		}
	}

	/**
	* Create a new session
	*/
	function create() {
		global $db, $cookie;
		$this->session_id = time() . md5($this->ip_address);
		$query = 'INSERT INTO ' . SESSION_TABLE . '(session_id, ip_address, last_page, http_host) VALUES ("' . $this->session_id . '", "' . $this->ip_address . '", "' . $this->current_page . '", "' . $this->http_host . '")';
		$db->get_data($query);
		if ($db->affected_rows) {
//			if (setcookie($cookie['name'] . '_sesh', $this->session_id, time() + (60 * 15), $cookie['script_path'], $cookie['host'], $cookie['secure'], $cookie['http_only'])) {
			if (setcookie($cookie['name'] . '_sesh', $this->session_id, time() + (60 * 15), $cookie['script_path'], $cookie['host'])) {
				//do nothing
			} else if (setcookie($cookie['name'] . '_sesh', $this->session_id, time() + (60 * 15), $cookie['script_path'])) {
				//do nothing, we are local
			} else {
				trigger_error('We failed to set your cookie', E_USER_NOTICE);
			}
		} else {
			$message = $db->handle_error();
			trigger_error('Failed to create your session ' . $message, E_USER_WARNING);
		}
	}

	/**
	* Destory a session
	*/
	function kill() {
		global $cookie;
//		@ setcookie($cookie['name'] . '_sesh', 'this_cookie_is_being_eaten', time() - 3600, $cookie['script_path'], $cookie['host'], $cookie['secure'], $cookie['http_only']);
		@ setcookie($cookie['name'] . '_sesh', 'this_cookie_is_being_eaten', time() - 3600);
		return;
	}

	/**
	* Update a valid existing session
	*/
	function update($sesh = array()) {
		$this->last_page = isset($this->last_page) ? $this->last_page : ( isset($sesh['last_page']) ? $sesh['last_page'] : '');
		global $cookie, $db;
		$query = 'UPDATE ' . SESSION_TABLE . ' SET last_page = \'' . $this->current_page . '\', last_active = NOW(), http_host = \'' . $this->http_host . '\' WHERE session_id = \'' . $this->session_id . '\'';
		$db->get_data($query);
		if (setcookie($cookie['name'] . '_sesh', $this->session_id, time() + (60 * 15), $cookie['script_path'], $cookie['host'])) {
			//do nothing
		} else if (setcookie($cookie['name'] . '_sesh', $this->session_id, time() + (60 * 15), $cookie['script_path'])) {
			//do nothin we are local
		} else {
			trigger_error('Failed to Bake Cookie', E_USER_WARNING);
		}
		return;
	}
}

