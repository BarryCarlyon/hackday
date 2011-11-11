	</div>

	<div class="span5">

<?php
//echo run_error_message();

if ($login->is_logged_in) {
	echo $login->twitter_profile();
} else {
	include(TEMPLATES . 'login.php');
}

?>
	
<?php
if ($page != 'play') {
	// the widget breaks the ajax response.....
?>
<div id="twitter_widget">
	<script type="text/javascript" src="http://widgets.twimg.com/j/2/widget.js"></script>
	<script type="text/javascript">
	new TWTR.Widget({
	  version: 2,
	  type: 'profile',
	  rpp: 4,
	  interval: 6000,
	  width: 250,
	  height: 300,
	  theme: {
	    shell: {
	      background: '#333333',
	      color: '#ffffff'
	    },
	    tweets: {
	      background: '#000000',
	      color: '#ffffff',
	      links: '#4aed05'
	    }
	  },
	  features: {
	    scrollbar: false,
	    loop: false,
	    live: false,
	    hashtags: true,
	    timestamp: true,
	    avatars: false,
	    behavior: 'all'
	  }
	}).render().setUser('SpotifyRoulette').start();
	</script>
</div>
<?php	
}
?>

	</div><!-- span6 -->
</div><!-- row -->

<div id="listened">
<?php

$query = 'SELECT *, UNIX_TIMESTAMP(tos) AS unixtos FROM twitter_recent WHERE played != \'\' ORDER BY UNIX_TIMESTAMP(tos) DESC LIMIT 5';
$data = $db->get_data($query);

while ($row = $db->fetch_row($data)) {
	$line = '<div>';
	$line .= '<img src="' . $row['profile_image'] . '" alt="' . $row['screen_name'] . '" title="' . $row['screen_name'] . '" />';
	$line .= $row['screen_name'] . ' ';
	
	if ($row['played'] == 'amwaiting') {
		$line .= ' is currently waiting for a Suggestion';
	} else {
		if ($row['unixtos'] > (time() - 300)) {
			$line .= 'is';
		} else {
			$line .= 'was';
		}
	
		$line .= ' listening to ' . $row['played'] . ' suggested by ' . $row['refer'];
	}
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

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-837747-21']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</div>
</body>
</html>
