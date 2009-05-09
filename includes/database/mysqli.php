<?php

/**
* /html/includes/database/mysqli.php
* Is the mysqli version of the database class
* Relicensed Version of the GSoC Edition File
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

//class
class db {
	public $query_count	= 0;
	public $query		= '';
	public $queries		= array();

	function test_connect() {
		global $database;
		$this->host = $database['host'];
		$this->name = $database['name'];
		$this->user = $database['user'];
		$this->pass = $database['pass'];

		if (!($connection = @ mysqli_connect($database['host'], $database['user'], $database['pass'], $database['name'])))
		{
			return FALSE;
		}
		// store the connection id for things like total rows and affected rows
		$this->connect_id = $connection;
		return ($connection);
	}
	function protect_data($data)
	{
		$data = mysqli_real_escape_string($this->connect_id,$data);
//		$data = stripslashes($data);
		return $data;
	}
	function get_data($query) {
		$this->insert_id	= '';
		$this->affected_rows	= '';
		$this->total_rows	= '';

		if (!$query)
		{
			global $content;
			trigger_error('The Database was asked a question, but no question was given', E_USER_ERROR);
			return;
		}
		$query = str_replace(array('"NOW()"',"'NOW()'"),'NOW()',$query);
		$this->queries[] = $query;
		$this->query = $query;

		if (substr($query, 0, 7) != 'EXPLAIN') {
			$this->query_count ++;
		}

                if(!($result = @ mysqli_query ($this->connect_id,$query)))
		{
			$this->handle_error();
		}
		else
		{
			$this->result		= $result;
			if (substr($query,0,11) == 'INSERT INTO')
			{
				$this->insert_id = mysqli_insert_id($this->connect_id);
			}
			if ( (substr($query,0,6) == 'UPDATE') || (substr($query,0,11) == 'INSERT INTO') || (substr($query,0,11) == 'DELETE FROM')  )
			{
				$this->affected_rows = mysqli_affected_rows($this->connect_id);
			}
			else if (substr($query,0,6) == 'SELECT')
			{
				$this->total_rows = mysqli_num_rows($this->result);
			}
			return $result;
		}
		return FALSE;
	}
	function fetch_row($result = '')
	{
		$result = $result ? $result : $this->result;
		return mysqli_fetch_array($result);
	}
	function handle_error($error = '') {
		trigger_error('SQL_ERROR', E_USER_ERROR);
		return;
	}
	function close() {
		@ mysqli_close($this->connect_id);
		return;
	}
}

