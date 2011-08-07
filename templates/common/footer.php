	</div>
</div>

<div id="listened">
<?php

$query = 'SELECT *, UNIX_TIMESTAMP(tos) AS unixtos FROM twitter_recent WHERE played != \'\' ORDER BY UNIX_TIMESTAMP(tos) DESC LIMIT 5';
$data = $db->get_data($query);

while ($row = $db->fetch_row($data)) {
	$line = '<div>';
	$line .= '<img src="' . $row['profile_image'] . '" alt="' . $row['screen_name'] . '" title="' . $row['screen_name'] . '" />';
	$line .= $row['screen_name'] . ' ';
	
	if ($row['unixtos'] > (time() - 300)) {
		$line .= 'is';
	} else {
		$line .= 'was';
	}
	
	$line .= ' listening to ' . $row['played'] . ' suggested by ' . $row['refer'];
	$line .= '</div>';
	
	echo $line;
/*	
	echo '
<script type="text/javascript">
	jQuery(\'' . $line . '\').hide().prependTo(\'#listened\').slideDown();
</script>
';
*/
}

?>
</div>

<div id="footer">
	<a href="http://spotify.com">
		<img src="/images/spotify.png" alt="Spotify" title="Spotify" />
	</a>
	<p>Built by <a href="http://BarryCarlyon.co.uk/">Barry Carlyon</a> at LeedsHack 2011</p>
	<p>Powered By the <a href="http://spotify.com">Spotify</a> Meta Data API</p>
	<p>This product uses a SPOTIFY API but is not endorsed, certified or otherwise approved in any way by Spotify. Spotify is the registered trade mark of the Spotify Group.</p>
</div>

</body>
</html>