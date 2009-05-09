<?php

/**
* html/includes/email.php
* Its the email libraries
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
* Currently we are only instantiating this class when we need to send an email
*/
class email {
	public $subject		= '';
	public $message		= '';
	public $reply_to	= 'webmaster';
	public $from		= '';

	public $email_status	= array();

	public $m_headers 	= '';
	public $s_headers	= array();

	public $success		= 0;
	public $fail		= 0;

	function setup_headers($destination = '', $name = '') {
		$this->m_headers = "From: LSRfm.com MegaList <megalist@lsrfm.com>" . "\r\n" .
"Reply-To: " . $this->from . " <" . $this->reply_to . ">" . "\r\n" .
'X-Mailer: PHP/' . phpversion() . "/CarlyonCMS\r\n";

		if ($name) {
			$destination = '"' . $name . '" <' . $destination . '>';
		}

// hacked to work with the Leeds University SMTPS Server
		$this->s_headers = array(
			'From'		=> 'LSRfm.com <' . "megalist@lsrfm.com" . '>',
			'From'		=> "megalist@lsrfm.com",
			'To'		=> $destination,
			'Subject'	=> $this->subject,
			'Date'		=> time(),
			'Reply-To'	=> $this->from . ' <' . $this->reply_to . ">",
			'X-Mailer'	=> 'PHP/' . phpversion() . '/CarlyonCMS',
			'Content-Type'	=> 'text/plain',
		);
	}

	/**
	* This function sends a single email to a single person
	*/
	function send_single_email($destination) {
		$this->setup_headers($destination);

//		$this->email_status['s_' . $destination] = $this->smtp_email($destination);
		$this->email_status['s_' . $destination] = $this->mail_email($destination);
	}

	/**
	* Send to a array of emails
	* allow use of nice names
	*/
	function send_to_list($destinations) {
		$this->setup_headers();

		global $template;
		foreach ($destinations as $data) {
			$destination = $data['email'];
			$target_name = $data['name'];

			$this->setup_headers($destination, $target_name);

			$message = $template->swap('PERSON_NAME', $target_name, $this->message);

//			$this->email_status['l_' . $destination] = $this->smtp_email($destination, $message);
			$this->email_status['l_' . $destination] = $this->mail_email($destination, $message);
		}
	}

	/**
	* Function to check the last sent status
	*/
	function last_state() {
		$last = $this->email_status;
		return array_pop($last);
	}

	/**
	* SMTP Email
	*/
	function smtp_email($destination, $message = '') {
		$message = $message ? $message : $this->message;

		require_once 'Mail.php';

		$smtp = Mail::factory('smtp',
			array (
				'host'		=> 'smtps.leeds.ac.uk',
				'auth'		=> FALSE,
				'debug'		=> FALSE,
				'persist'	=> TRUE));
//		ob_start();
		$mail = $smtp->send($destination, $this->s_headers, $message);
//		$output = ob_get_clean();
//echo 'here is the output that the system generated ' . $mail;
		if (PEAR::isError($mail)) {
			$this->fail ++;
			return $mail;
//			return $mail . '<br /><br />' . nl2br($output);
		} else {
			$this->success ++;
			return TRUE;
		}
	}
	/**
	* mail Email
	*/
	function mail_email($destination, $message = '') {
		$message = $message ? $message : $this->message;
/**
* shunt
*/
$message = str_replace('&#039;', "'", $message);
$message = stripslashes($message);

		if (mail($destination, $this->subject, $message, $this->m_headers, "-freturn.webmaster@lsrfm.com")) {
			$this->success ++;
			return TRUE;
		} else {
			$this->fail ++;
			return FALSE;
		}
	}
}

