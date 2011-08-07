<h1>Spotify Roulette</h1>
<h2>You never know what you are gonna get!</h2>

<h4>What is Spotify Roulette?</h4>

<p>Ever wanted to find something random to listen to on Spotify but couldn&#39;t?</p>
<p>Lets Crowd source something to listen to.</p>
<p>Login with Twitter. Hit Play Roulette and Wait.</p>
<p>When a Follower tweets you back with an Artist we can find, we&#39;ll automagically pop open Spotify with a Track from the Suggested Artist&#39;s back Catalogue and Generate a Drag and Drop playlist to listen to if you want more.</p>
<p>Just watch our for Rick Astely. He is never gonna give you up....</p>

</div>
<div id="listened">
<?php

global $config;
include('database.php');
$log = new log();
$db = new db($config->database);

$query = 'SELECT *, UNIX_TIMESTAMP(tos) AS unixtos FROM twitter_recent ORDER BY UNIX_TIMESTAMP(tos) DESC LIMIT 5';
$data = $db->get_data($query);

while ($row = $db->fetch_row($data)) {
	echo '<div>';
	echo '<img src="' . $row['profile_image'] . '" alt="' . $row['screen_name'] . '" title="' . $row['screen_name'] . '" />';
	echo $row['screen_name'] . ' ';
	
	if ($row['unixtos'] > (time() - 2500)) {
		echo 'is';
	} else {
		echo 'was';
	}
	
	echo ' listening to ' . $row['played'] . ' suggested by ' . $row['refer'];
	echo '</div>';
}
