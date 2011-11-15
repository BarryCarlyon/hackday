<?php

/**
* html/includes/template.php
* Template engine V4 based on V3 GSoC Editior, rewritten for more generic use
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

class template {
	public $prehtml		= '';
	public $html		= '';

	public $page_title	= '&nbsp;';
	public $content_title	= '&nbsp;';
	public $css		= '';
	public $javascript	= '';
	public $meta		= '';

	public $tpl_vars	= array();

	public $status_code	= 404;

	public $template_file	= '';
	public $template_type	= '';

	public $allow_back	= FALSE;

	function initialize() {
		$this->server		= $_SERVER;
		$this->request		= $_REQUEST;
		$this->get		= $_GET;
		$this->post		= $_POST;
		$this->cookie		= $_COOKIE;
		$this->files		= $_FILES;

//		$this->page		= isset($this->request['page']) ? $this->request['page'] : 'home';
//r1 fail
//		$this->page		= loadvar('PATH_INFO', '/', 'server');
		$this->page		= loadvar('REQUEST_URI', '/', 'server');

//		$this->path_info	= loadvar('PATH_INFO', '/', 'server');
		$this->path_info	= str_replace('?' . loadvar('QUERY_STRING', '', 'server'), '', $this->page);
		$this->path_plode	= explode('/', $this->path_info);
		array_shift($this->path_plode);
		return;
	}

	/**
	* 
	* This function will (later) replace {META_REFRESH} in the Header file (or anywhere else in the HTML for that matter)
	* Two args, target aka destination and the time
	* If no destination we try to go back to the last page
	* If the last page is the same we go home
	* Last page is taken from session
	* 
	* Default time is 3 seconds, but 10 is likely to be used more often
	* 
	*/
	function meta_refresh($target = '', $time = 3) {
		global $session;
		if (!$target && ($session->last_page == $session->current_page)) {
			$target = '/';
		}
		$target = $target ? $target : $session->last_page;
		$this->meta = '<meta http-equiv="refresh" content="' . $time . ';url=' . $target . '" />' . "\n";
		return;
	}
	/**
	* 
	* This is now a combo function
	* used to use separate function for CSS Load
	* 
	* jscripts can be a string for a sinlge file, or an array for multiple
	* setting is_css to true, does the same but uses the html for CSS instead
	* 
	* Potential to switch to sprint(f) instead
	* 
	*/
	function load_jscript($jscripts, $is_css = FALSE) {
		if (is_array($jscripts)) {
			foreach ($jscripts as $script) {
				if ($is_css) {
					$this->css .= '<link rel="stylesheet" type="text/css" href="' . $script . '" media="screen" />' . "\n";
				} else {
					$this->javascript .= '<script type="text/javascript" src="' . $script . '"></script>' . "\n";
				}
			}
		} else {
			if ($is_css) {
				$this->css .= '<link rel="stylesheet" type="text/css" href="' . $jscripts . '" media="screen" />' . "\n";
			} else {
				$this->javascript .= '<script type="text/javascript" src="' . $jscripts . '"></script>' . "\n";
			}
		}
	}

	/* generators */
	/**
	* 
	* This function is a common for header and footer
	* and Any file select in the home.php as a common file
	* like navigation
	* 
	* This file will exchange template tags {x} for set values
	* Values can be set by template files
	* Or determined other ways
	* From $_SERVER or as selected elsewhere
	* By this file or template files, or those included
	* 
	* Support for HTML and PHP include
	* 
	*/
	function generate_common($file) {
		global $copyright, $page_title_base;
		global $config, $db, $session;
		global $auth;

		if (is_file('templates/' . $file . '.html')) {
			$this->html .= file_get_contents('templates/' . $file . '.html');
		} else if (is_file('templates/' . $file . '.php')) {
			include('templates/' . $file . '.php');
		} else {
			trigger_error('INCLUDE Unable to include Common File ' . $file, E_USER_ERROR);
		}

		$this->swap('copyright',	'&copy; ' . $copyright . ' {YEAR}'		);
		$this->swap('year',		date('Y',time())				);

		$this->swap('css',		$this->css					);
		$this->swap('javascript',	$this->javascript				);
		$this->swap('meta',		$this->meta					);

		$this->swap('site_title',	$page_title_base . ' | '			);
		$this->swap('page_title',	$page_title_base . ' | ' . $this->page_title	);

		$this->swap('queries',	$db->query_count);
/*
$aquery = '';
foreach ($db->queries as $ref => $query) {
	if (substr($query, 0, 6) == 'SELECT') {
		$aquery .= '<br />' . $ref . ' -- ' . $query;

		$db->get_data(str_replace('SELECT', 'EXPLAIN SELECT', $query));

		$aquery .= '<br />';
		$row = $db->fetch_row();
		foreach ($row as $ref => $data) {
			if (is_numeric($ref)) {
				unset($row[$ref]);
			}
		}

		$aquery .= '<table><tr><td>';
		$aquery .= implode('</td><td style="border: 1px solid #000;">', array_keys($row));
//		$aquery .= '</td></tr>';
		$aquery .= '</td>';

//		$aquery .= '<tr><td>';
//		$aquery .= implode('</td><td style="border: 1px solid #000;">', array_values($row));
//		$aquery .= '</td></tr>';

		foreach ($row as $ref => $r) {
			if ($ref == 'id') {
				$aquery .= '</tr><tr>';
			}
			$aquery .= '<td style="border: 1px solid #000;">' . $r . '</td>';
		}
		$aquery .= '</tr>';
		$aquery .= '</table>';
	}
}
$this->swap('querylist', $aquery);
$this->swap('querylist', loadvar('REDIRECT_STATUS', 'na', 'server'));
*/
		/**
		* check for header image instead of the h2
		*/
		if (is_readable('images/headers/' . strtolower($this->content_title) . '.jpg')) {
			$this->swap('content_title',	$this->content_title ? '<h2 style="display: none;">' . $this->content_title . '</h2><div id="header_image" style="background: url(\'/images/headers/' . strtolower($this->content_title) . '.jpg\'); width: 100%; height: 132px; border: 1px solid #000;" ></div>' : '');
		} else {
			$this->swap('content_title',	$this->content_title ? '<h2>' . $this->content_title . '</h2>' : '');
		}

		foreach ($this->tpl_vars as $ref => $data) {
			$this->swap($ref, $data);
		}

		return;
	}
	function load_content() {
		//composite function

		//determine file to load
		//elements moved to ini

		/**
		* 
		* Lets create a list of possible locations
		* That the address line has generated
		* 
		*/
		$to_check = array();
		$to_check[] = $this->path_info . '/index';
		$to_check[] = str_replace('//', '/', $this->path_info . '/');

		/**
		* Disallow step back for html and txt driven pages
		* In this form HTML file has priorty, then TXT, then PHP driven pages
		*/
		foreach ($to_check as $file) {
			if (is_readable('templates/' . $file . '.html')) {
				$this->template_file = $this->template_file ? $this->template_file : $file;
				$this->template_type = $this->template_type ? $this->template_type : 'html';
			} else if (is_readable('templates/' . $file . '.txt')) {
				$this->template_file = $this->template_file ? $this->template_file : $file;
				$this->template_type = $this->template_type ? $this->template_type : 'txt';
			}
		}

		/**
		* 
		* Path_plode is tainted
		* Here we will create the extended list of files to check
		* By stepping backwards through the array split along /
		* 
		* array_pop does the tainting
		* 
		*/
		$total =  sizeof($this->path_plode);
		for ($x = 0; $x < $total; $x++) {
			array_pop($this->path_plode);
			$to_check[] = '/' . implode('/', $this->path_plode) . '/index';
			$to_check[] = '/' . implode('/', $this->path_plode);
		}

		/**
		* check for a / at the end, and distinct the array
		*/
		foreach ($to_check as $ref => $data) {
			if (substr($data,-1,1) == '/') {
				$data = substr($data,0,-1);
			}
			unset($to_check[$ref]);
			$data = str_replace('//', '/', $data);
			$to_check[$data] = $data;
		}

		/**
		* PHP file step back to allow passing of the address file to those that need it
		*/
		foreach ($to_check as $file) {
			if (is_readable('templates/' . $file . '.php')) {
				$this->template_file = $this->template_file ? $this->template_file : $file;
				$this->template_type = $this->template_type ? $this->template_type : 'php';
			} else if (is_readable('templates/' . $file . '.html')) {
				$this->template_file = $this->template_file ? $this->template_file : $file;
				$this->template_type = $this->template_type ? $this->template_type : 'html';
			} else if (is_readable('templates/' . $file . '.txt')) {
				$this->template_file = $this->template_file ? $this->template_file : $file;
				$this->template_type = $this->template_type ? $this->template_type : 'txt';
			}
		}

		//header time
		if (empty($this->template_file)) {
			//then we failed to find a file, or we need the index!
//			$this->template_type = '404';
			$this->template_file = 'home';
			$this->template_type = 'php';
		}
		/**
		* regenerate path_plode
		* Which we tainted earlier with array_pop
		*/
		$this->path_plode = explode('/', $this->path_info);
		array_shift($this->path_plode);
		$this->template_mode = isset($this->path_plode[2]) ? $this->path_plode[2] : '';
		$this->template_var = isset($this->path_plode[3]) ? $this->path_plode[3] : '';

		/**
		* the home page gets to handle the 404 controller
		* now we have determined what to do..let action that
		* allow php files to set own http header
		*/
		if ($this->template_type == 'php') {
			global $db, $session, $config, $auth;
			/**
			* 
			* Globalise the needed elements, so template dont have to globalise them themseleves
			* 
			* this needs/ought to be passed to the main function, as I dont want to be inside the template engine
			* 
			* However for now we shall arrange to stay within the temlate class and use $this
			* if neccassary later we shall create a way to pass to function
			* however I doubt this will be needed
			* 
			*/
/**
* alternative here
* ob_start and php tpls echo aka equiv to file_get_contents (ob_get_clean
* 
*/
			header('HTTP/1.1 200 OK');
			include('templates/' . $this->template_file . '.php');

			/**
			* 
			* Here comes the 404 step back check
			* 
			* version 3 crapper
			* 
			*/

			/**
			* rekey the array
			*/
			$to_checker = array();
			foreach ($to_check as $data) {
				$to_checker[] = $data;
			}
			if ($this->allow_back != TRUE) {
				/**
				* then we need to check the file path (ala sys)
				* is item 0 or 1 in the to_checker array
				*/
				if ($this->template_file == $to_checker[0] || $this->template_file == $to_checker[1]) {
					/**
					* then ok
					*/
				} else {
					/**
					* 404 time
					*/
					if ($this->template_file != 'home') {
						$this->template_file = 'home';
						$this->template_type = 'php';
						include('templates/' . $this->template_file . '.php');
					}
				}
			}
/**
* 
* Follows are the other file types we can handle
* in this case html
* and txt
* 
*/

		} else if ($this->template_type == 'html') {
			/**
			* Check we havn't fallen back
			*/
			if ($this->template_file != $this->path_info . '/index' && $this->template_file != $this->path_info && $this->template_file . '/' != $this->path_info) {
				global $db, $session, $config;
				include('templates/home.php');
			} else {
				header('HTTP/1.1 200 OK');
				/**
				* assume file is html entitised
				* go for extration of the h2
				*/
				$file = file_get_contents('templates/' . $this->template_file . '.html');
				$file = explode('</h2>', $file);
				$this->page_title = $this->content_title = isset($file[1]) ? str_replace('<h2>','',$file[0]) : ucwords(str_replace('_',' ',str_replace(array('/','index'),'',$this->template_file)));

				$this->prehtml = isset($file[1]) ? $file[1] : $file[0];
			}
		} else if ($this->template_type == 'txt') {
			if ($this->template_file != $this->path_info . '/index' && $this->template_file != $this->path_info) {
				global $db, $session, $config;
				include('templates/home.php');
			} else {
				header('HTTP/1.1 200 OK');
				$this->page_title = $this->content_title = ucwords($this->path_plode[0]);
				/**
				* this block needs testing, for formattting, but is good for email recieve and post
				*/
				$this->prehtml = file_get_contents('templates/' . $this->template_file . '.txt');
				$this->prehtml = htmlentities($this->prehtml, ENT_QUOTES, 'UTF-8');
				$this->prehtml = nl2br($this->prehtml);
//				$this->prehtml = str_replace(array('<br /><br />', "<br />\n<br />"), '</p><p>', $this->prehtml);
				$this->prehtml = '<p>' . $this->prehtml . '</p>';
			}
		}

		return;
	}


	/* system calls */
	/**
	* 
	* This is the most commonly used function
	* 
	* This will swap {TARGET} with victim
	* Note target is a template varible and must always be upper case in name
	* hence in the str_replace it makes the target upper case first
	* 
	* Source is needed as we can send text to the function to exchange
	* or if not, use the previously set HTML
	* and thus send the given text back or put the new html in the old box
	* 
	* Note this is a global search and will replace all occurnaces/matches
	* 
	*/
	function swap($target, $victim, $source = '') {
		$return = TRUE;
		if (empty($source)) {
			$source = $this->html;
			$return = FALSE;
		}

		$source = str_replace('{' . strtoupper($target) . '}', $victim, $source);

		if ($return) {
			return $source;
		} else {
			$this->html = $source;
			return;
		}
	}

	/**
	* 
	* Basic SQL injection protection
	* 
	*/
	function sanitize($in) {
		$this->santitized[] = $in;
		$out = '';
		if ((substr($in,0,7) == '<option') || (substr($in,0,4) == '<div')) {
			$out = $in;
		} elseif (strlen($in) > 4) {
			$out = htmlentities($in, ENT_QUOTES, 'UTF-8');
		}
		return $out;
	}

	/**
	* 
	* This is the main composite function
	* 
	* When called it will load the header files
	* the main ones and the ones selected in the home.php file
	* 
	* pre tpl and post tpl so preHeader templateCode postFooter
	* 
	* This function does the actual echo after all the $html has been generated
	* it calls other sub functions
	* for things like the custom error handler
	* 
	* prehtml is that generated by the Template engine
	* html is header + pre + footer
	* 
	*/
	function compile_and_echo($templates = array(), $post_templates = array()) {
		//composite

		//load needs to go first, as templates need to be able to affect the headers
		$this->load_content();

		$this->generate_common('header');
		define('HEADER',TRUE);
		//load the user defined templates
		foreach($templates as $file) {
			$this->generate_common($file);
		}
		//check for errors
		check_errors();
		$this->html .= $this->prehtml;

		//hack
		if (loadvar('iframe', 0) == 1) {
			$this->html = '<html><head>';
			$this->html .= $this->javascript;
			$this->html .= '</head><body>';
			$this->html .= $this->prehtml;
			$post_templates = array();
			$this->html .= '</body></html>';
		}

		foreach($post_templates as $file) {
			$this->generate_common($file);
		}

		if (loadvar('iframe', 0) != 1) {
		$this->generate_common('footer');
		}

		global $start_time;
		$this->swap('executed', substr((microtime(true) - $start_time), 0, 5) . ' Seconds');
		//strip {}
		$this->html = preg_replace("[\{[\w\d].*\}]", ' ', $this->html);

		//protect plain text emails
		$this->html = preg_replace("[@]", '[ EMAILAT ]', $this->html);

		echo $this->html;

		return;
	}

	/**
	* 
	* Misc functions
	* 
	* The convert function takes data, commonly an array and then pushes it to the new datatype commonly a <select>
	* 
	*/
	function convert($source_data, $default = '', $target_format = 'select', $reford = TRUE) {
		$new = '';

		if ($target_format == 'select' && is_array($source_data)) {
			foreach ($source_data as $ref => $data) {
				$new .= '<option ';
				if (($reford && $ref == $default) || (!$reford && $data == $default)) {
					$new .= 'selected="selected" ';
				}
				if ($reford) {
					$new .= ' value="' . $ref . '"';
				} else {
					$new .= ' value="' . $data . '"';
				}
				$new .= '>' . $data . '</option>' . "\n";
			}
		}
		if ($target_format == 'li' && is_array($source_data)) {
			foreach ($source_data as $ref => $data) {
				$new .= '<li>';
				if ($ref) {
					$new .= '<a href="' . $ref . '" title="' . $data . '">' . $data . '</a>';
				} else {
					$new .= $data;
				}
				$new .= '</li>' . "\a";
			}
		}

		return $new;
	}
	
	/**
	* 
	* HTTP/URL discovery
	*
	* This block of code will take a string of content
	* and will iterate thru the string
	* searching for and replacing http://wahtever/ with a valid html url link
	* as well as passing it to the redirect engine
	* so if it goes external
	* we can keep track of this statsictical data for later analysis
	* 
	*/
	function http_scanner($content, $video = TRUE, $redirect = TRUE) {
		$this->video = $video;
		$this->redirect = $redirect;

		$this->youtube_count = 0;
		// preg match
		// http://groups.google.com/group/habari-dev/browse_thread/thread/691194d7526ebb29?pli=1
		// note that the use for $this->found_urls makes this module php 5.1 only

		$php = str_replace(".", '', phpversion());
		if ($php > 510) {
//			$content = preg_replace( '%https?://\S+?(?=(?:[.:?"!$&\'()*+,=]|)(?:\s|$))%i', "<a href=\"$0\">$0</a>", $content, -1, $this->found_urls);
			$content = preg_replace_callback( '%https?://\S+?(?=(?:[.:?"!$&\'()*+,=]|)(?:\s|$))%i', "url_patch", $content, -1, $this->found_urls);
		} else {
			$content = preg_replace_callback( '%https?://\S+?(?=(?:[.:?"!$&\'()*+,=]|)(?:\s|$))%i', "url_patch", $content);
			// patch for php < 5.1
			$test = explode("<a href=", $content);
			// rough and ready count
			$this->found_urls = count($test);
//			$this->found_urls = $this->found_urls / 2;
			// its been double when it was made a link
		}

		return $content;
		/**
		* This first line adds a space
		* so if the content starts with a http, it can be detected
		* given that a strpos with http at the start returns a position of 0 aka false
		*/
		$content		= ' ' . $content;
		$this->found_urls	= 0;
		$new_content		= '';
		
		/**
		* since we turn all http(whatever)
		* into a local link
		* this wont result in infinity loops
		* and should detect https links too
		*/
		while ($http = strpos($content, 'http')) {
			/**
			* take everything from start to the start of http
			* and put in in the new box
			* and strip the added bit from our store
			*/
			$new_content	.= substr($content, 0, $http);
			$content	= substr($content, $http);
			
			/**
			* locate the next non link character
			*/
			$space		= strpos($content, ' ');
			$newline	= strpos($content, '<br />');
			$sq		= strpos($content, '[');
			$ab		= strpos($content, '<');
			
			/**
			* determine target
			*/
			$target = $space;
			if (($newline < $target && $newline) || ($target)) {
				$target = $newline;
			}
			if (($sq < $target && $sq) || (!$target)) {
				$target = $sq;
			}
			if (($ab < $target && $ab) || (!$target)) {
				$target = $ab;
			}
			if (!$target) {
				/**
				* go for end of content
				*/
				$target = strlen($content);
			}
			
			/**
			* now that we now the start and end point of the url in the text
			* we can extract it
			* and offset content to be after the url location in the text
			*/
			$url = substr($content, 0, $target);
			$content = substr($content, $target);
			/**
			* tidy the link up
			*/
			$rurl = str_replace(array('http://', 'https://'), 'LINK--DONE', $url);
			/**
			* for occasions where you do something like
			* http://myspace.com/
			* http://myspace.com/whatever
			* the second link is destoryed by the following
			* $show_page['entry_content'] = str_replace($url, '<a href="/redirect/' . $rurl . '">' . $rurl . '</a>', $show_page['entry_content']);
			* so we need to rebuild the show page entry_content element :-(
			* most heinous I know
			* 
			* Now, lets check for a pipe (|) for url title, ala wiki style
			* 
			*/
			$rtext = $rurl;
			if (strpos($rurl, '|')) {
				$rurl = explode('|', $rurl);
				$rtext = $rurl[1];
				$rurl = $rurl[0];
			}
			/*
			* Finally is it a youtube link?
			*/
			if (strpos($rurl, 'youtube') || substr($rurl, 0, 7) == 'youtube') {
				$this->youtube_count = isset($this->youtube_count) ? $this->youtube_count : 0;
				$this->youtube_count ++;
				/**
				* create a div and the javascript to go with
				*/
/*
$new_content .= '<div id="youtube_' . $this->youtube_count . '" class="center"><p>This is where a YouTube video goes, but you don&#39;
t appear to have Flash installed on your system.<br />Please Visit <a href="http://www.adobe.com/products/flashplayer/">Adobe Flash Pl
ayer to Download</a><p></div>';
$rurl = str_replace('watch?v=', 'v/', $rurl);
$new_content .= '
<script type="text/javascript">
var yt = new SWFObject("http://' . $rurl . '", "youtube_' . $this->youtube_count . '", "480", "390", "9", "#FFFFFF");
yt.write("youtube_' . $this->youtube_count . '");
</script>
';
*/
			} else {
				$new_content .= '<a href="/redirect/' . $rurl . '">' . $rtext . '</a>';
			}

			$this->found_urls ++;
		}
		/**
		* finally slap the last of the store onto new
		* its worth noting
		* that if http disco,
		* discovered nothing
		* the entire content will be in new content!
		*/
		$new_content .= $content;
		$new_content = str_replace('LINK--DONE', '', $new_content);
		return $new_content;
	}
	
	/**
	* 
	* This function does images
	* like http links before this
	* 
	*/
	function image_discovery($content, $imagedata, $image_alt, $base_dir = 'shows/pages', $folder = '') {
		$imagedata = explode('|', $imagedata);
		$imagedata = array_merge(array('0'=>''), $imagedata);
		if (!empty($imagedata['1'])) {
			/**
			* then we have image data!
			*/
			foreach ($imagedata as $ref => $data) {
				if (!empty($data)) {
					/**
					* swop [image$REF][/image$REF]
					* <img src="url" alt="show name" title="show name" style="float: pos;" />
					* unless center
					*/
					$data = explode(':', $data);
					$pos = $data[1];
					$image = $data[0];

					$output = '';
					if ($pos == 'center') {
						$output .= '<div class="blocker"></div><center>';
					}

					/**
					* do a image set check
					*/
					if ($folder) {
						$image = 'images/' . $base_dir . '/' . $folder . '/' . $image;
					} else {
// glitch patch
$image_alt = str_replace('shows/', '', $image_alt); 
// version 2 page targettting
$page_target = str_replace('&#039;', '_', $image_alt);
$page_target = preg_replace('/[^[:alnum:]_]/', '_', $page_target);
$page_target = strtolower($page_target);
$safe_show = $page_target;
						$image = 'images/' . $base_dir . '/' . $safe_show . '/' . $image;
					}
//echo $image_alt . '--' . $image . '--' . $base_dir . '--' . $folder . '--' . $content . '--' . $imagedata . '--' .  $image_alt . '-----';
					$image_size = getimagesize($image);
					$width = $image_size[0];
					$height = $image_size[1];

					if ($width > 500) {
						$width = 500;
					}

					$output .= '<img src="/' . $image . '" title="' . $image_alt . '" alt="' . $image_alt . '" ';
					$output .= ' style="';
					if ($pos != 'center') {
						$output .= 'float: ' . $pos . ';';
					}
					$output .= 'width: ' . $width . 'px;';
					$output .= '" />';
					if ($pos == 'center') {
						$output .= '</center><div class="blocker"></div>';
					}
					$content = str_replace('[image' . $ref .'][/image'. $ref . ']', $output, $content);
				}
			}
		}
		return $content;
	}
	
	/**
	* 
	* BBCode processor
	* Just to keep things tidy
	* I've wacked it here
	* 
	* $click is for whethers images are show/hide collapse or not
	* 
	* this is pretty much stolen and extended from http://php.net/preg_replace
	* 
	*/
	function bbcodes($content, $click = FALSE) {
/*
		$bbcodes = array(
			'[b]'	=> '<strong>',
			'[/b]'	=> '</strong>',
			'[i]'	=> '<i>',
			'[/i]'	=> '</i>',
			'[u]'	=> '<u>',
			'[/u]'	=> '</u>',
		);
		
		foreach ($bbcodes as $code => $bb) {
			$content = str_ireplace($code, $bb, $content);
		}

		// handle color
//		$content = preg_replace('/\[color=(\w+)([^\]]?)\](.+)([^\[]?)\[\/color\]/i', '<span style="color: $1;">$3</span>$4', $content);
		$content = preg_replace('/\[color= (\w+) [^\]]? \] (.+) [^\[]? \[\/color\] ([\s?])/ix', '<span style="color: $1;">$2</span>$3', $content);
		// handle bg2color
		$content = preg_replace('/\[bg2color=(\w+)([^\]]?)\](.+)([^\[]?)\[\/bg2color\]/i', '<span style="background: $1;">$3</span>', $content);
		// url
		$content = preg_replace('/\[url=(.+)([^\]]?)\](.+)([^\[]?)\[\/url\]/i', '<a href="$1" target="blank">$3</a>', $content);
*/

$bbcode = array(
'/\[b\](.*?)\[\/b\]/is'				=> '<strong>$1</strong>',
'/\[i\](.*?)\[\/i\]/is'				=> '<i>$1</i>',
'/\[u\](.*?)\[\/u\]/is'				=> '<u>$1</u>',

'/\[color=(.*?)\](.*?)\[\/color\]/is'		=> '<span style="color: $1;">$2</span>',
'/\[bg2color=(.*?)\](.*?)\[\/bg2color\]/is'	=> '<span style="background: $1;">$2</span>',

'/\[img=(.*?)\](.*?)\[\/img\]/is'		=> '<img src="$1" alt="$2" title="$2" />',
'/\[img](.*?)\[\/img\]/is'			=> '<img src="$1" alt="$1" title="$1" />',

'/\[url=http:\/\/(.*?)\](.*?)\[\/url\]/is'	=> '<a href="http://$1" target="_blank">$2</a>',
'/\[url=(.*?)\](.*?)\[\/url\]/is'		=> '<a href="http://$1" target="_blank">$2</a>',

'/\[sup\](.*?)\[\/sup\]/is'			=> '<sup>$1</sup>',
'/\[sub\](.*?)\[\/sub\]/is'			=> '<sub>$1</sub>',

'/\[quote\](.*?)\[\/quote\]/is'			=> '<blockquote class="uncited" style="color: #000;">$1</blockquote>',

);

if ($click) {
	// overide current entry
	$bbcode['/\[img=(.*?)\](.*?)\[\/img\]/is'] = '<center><img src="$1" alt="$2" title="$2" style="display: none;" id="img_$2" /><div style="cursor: pointer;" onclick="document.getElementById(\'img_$2\').style.display = \'block\';document.getElementById(\'click_$2\').style.display = \'none\';" id="click_$2" style="text-align: center;">Click to Show Image</div></center>';
	$bbcode['/\[img](.*?)\[\/img\]/is'] = '<center><img src="$1" alt="$1" title="$1" style="display: none;" id="$1_img" /><div style="cursor: pointer;" onclick="document.getElementById(\'$1_img\').style.display = \'block\';document.getElementById(\'$1_click\').style.display = \'none\';" id="$1_click" style="text-align: center;">Click to Show Image</div></center>';
}

$content = preg_replace(array_keys($bbcode), array_values($bbcode), $content);

		return $content;
	}

	/**
	* 
	* Smilie Processor
	* Using smilies stolen from facebook
	* 
	*/
	function smilies($content) {
		$smilies = array(
			':)'	=> 'smile',
			':-)'	=> 'smile',

			':('	=> 'sad',
			':-('	=> 'sad',

			':-P'	=> 'rude',
			':-p'	=> 'rude',
			':P'	=> 'rude',
			':p'	=> 'rude',

			':D'	=> 'bigsmile',
			':-D'	=> 'bigsmile',

			':o'	=> 'oh',
			':-o'	=> 'oh',

			';)'	=> 'wink',
			';-)'	=> 'wink',

			'8)'	=> 'glasses',
			'8-)'	=> 'glasses',

			'8-|'	=> 'sunglasses',

			'>:-('	=> 'grumpy',
/*
			':/'	=> 'duhh',
			':\\'	=> 'duhh',
*/
			':\'('	=> 'cry',

			'3:)'	=> 'devil',

			'O:)'	=> 'angel',

			':-*'	=> 'kiss',
			':*'	=> 'kiss',

			'<3'	=> 'love',
			'3>'	=> 'love',
			'3&gt;'	=> 'love',
			'&lt;3'	=> 'love',

			'^_^'	=> 'happyeyes',

			'0.o'	=> 'woot',
			'O.o'	=> 'woot',

			'-_-'	=> 'dork',

			'>:o'	=> 'lol',

			':v'	=> 'pacman',

			':3'	=> 'cat',
		);

		// borked/hacked to work, works quite well now
		foreach ($smilies as $code => $smile) {
			$content = str_replace($code, '<img src="/elements/site/blank.gif" class="smilie smilie_' . $smile . '" />', $content);
		}
		return $content;
	}
	function word_censor($content) {
		$bad_list = array(
			'fuck',
			'shit',
			'cunt',
			'bastard',
			'damn',
			'shat',
			'bitch',
			'whore',
			'nigger',
		);
		foreach ($bad_list as $word) {
			$content = str_ireplace($word, '&lt;censored&gt;', $content);
		}
		return $content;
	}
	
	/**
	* Render some links
	* for pagination
	*/
	function paginate($start, $next, $back, $total) {
		$text = '<div class="blocker" style="border-bottom: 1px solid #960000;"></div>';
		if ($start > 0) {
			$text .= '<a href="?start=' . $back . '" class="left">Back a Page</a>';
		}
		if ($next < $total) {
			$text .= '<a href="?start=' . $next . '" class="right">Next Page</a>';
		}
		if ($start > 5) {
			$text .= '<a href="?start=0" class="center">Page 1</a>';
		}
		$text .= '<div class="blocker" style="border-bottom: 1px solid #960000;"></div>';
	
		return $text;
	}

	/**
	* Shunting addthis to another place
	*/
	function addthis($url = '[URL]', $title = '[TITLE]') {
		return '<a href="http://www.addthis.com/bookmark.php" onmouseover="return addthis_open(this, \'\', \'' . $url . '\', \'' . $title . '\')" onmouseout="addthis_close()" onclick="return addthis_sendto()"><img src="http://s7.addthis.com/button1-share.gif" alt="Share" title="Share" /></a>';
	}
}

