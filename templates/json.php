<?php

if (@$_GET['game'] == 1 && @$_SESSION['tweet_sent']) {
	// playing the game
	$json = 1;
	include(TEMPLATES . 'play.php');
	exit;
}

if ($artist = @$_GET['playlist']) {
	// load artist
	$json = 1;
	
	include(TEMPLATES . 'playlist.php');
	exit;
}

if (@$_GET['playing'] == 'Rick' && !@$_GET['refer']) {
	$_GET['playing'] = 'Rickrolled';
	$_GET['refer'] = 'Rick Astley';
}
if (@$_GET['playing'] && @$_GET['refer']) {
	$playing = urldecode($_GET['playing']);
	
	$avatar = $_SESSION['account_data']->profile_image_url_https;
	$query = 'SELECT ref_id FROM twitter_recent WHERE screen_name = \'' . $_SESSION['twitter_screen_name'] . '\'';
	$result = $db->get_data($query);
	
	if ($db->total_rows) {
		$row = $db->fetch_row($result);
		
		$ref_id = $row['ref_id'];
	} else {
		$query = 'INSERT INTO twitter_recent(screen_name, profile_image) VALUES (\'' . $_SESSION['twitter_screen_name'] . '\', \'' . $avatar . '\')';
		$db->get_data($query);
		
		$ref_id = $db->insert_id;
	}
	
	$query = 'UPDATE twitter_recent SET played = \'' . addslashes($playing) . '\', refer = \'' . $_GET['refer'] . '\' WHERE ref_id = ' . $ref_id;
	$db->get_data($query);
	exit;
}
