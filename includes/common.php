<?php

$messages = array();
function add_error_message($message) {
	global $messages;
	$messages[] = $message;
	return;
}
function run_error_message() {
	global $messages;
	
	$return = '';
	if (count($messages) > 0) {
		$return = '<div id="error_messages"><ul>';
		
		$messages = implode($messages, '</li><li>');
		$return .= '<li>' . $messages . '</li>';
		
		$return .= '</ul></div>';
	}
	
	return $return;
}
