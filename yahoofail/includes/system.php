<?php

/**
* html/includes/system.php
* Config time
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
* 
* Its the config class
* 
*/
class config {
	public $config = array();

	/**
	* 
	* Initailize and load in
	* Hurrah
	* We will also 'cache' it
	* 
	*/
	function initialize() {
		global $db;
		$query = 'SELECT * FROM ' . CONFIG_TABLE;
		$db->get_data($query);
		while ($row = $db->fetch_row()) {
			$this->config[$row['config_name']] = $row['config_value'];
			$this->$row['config_name'] = $row['config_value'];

			if ($row['config_name'] == 'site_online' && $row['config_value'] == FALSE) {
				trigger_error('SITE_OFFLINE', E_USER_ERROR);
			}
		}
		return;
	}

	/**
	* 
	* Lets set a conf var
	* If it does not exist, create it
	* Dont forget to push the new varible into the 'cached' config
	* 
	*/
	function set_config($c_name, $c_value) {
		global $db;
		$query = 'UPDATE ' . CONFIG_TABLE . ' SET config_value = \'' . $c_value . '\' WHERE config_name = \'' . $c_name . '\'';
		$db->get_data($query);
		if ($db->affected_rows && !$this->conf) {
			$query = 'INSERT INTO ' . CONFIG_TABLE . '(config_name, config_value) VALUES (\'' . $c_name . '\', \'' . $c_value . '\')';
			$db->get_data($query);
		}
		$this->config[$c_name] = $c_value;
		return;
	}

	/**
	* 
	* This function is somewhat redundant
	* 
	*/
	function get_config($conf) {
		if (is_set($this->config[$conf])) {
			return $this->config[$conf];
		} else {
			global $db;
			$query = 'SELECT * FROM ' . CONFIG_TABLE . ' WHERE config_name = \'' . $conf . '\'';
			$db->get_data($query);
			if ($db->total_rows) {
				$row = $db->fetch_row();
				$this->config[$conf] = $row['config_value'];
				return $row['config_value'];
			} else {
				return $false;
			}
		}
	}
}

