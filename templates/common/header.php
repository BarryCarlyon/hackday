<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-gb" xml:lang="en-gb" dir="ltr"> 
<head> 
 
<meta http-equiv="content-type" content="text/html; charset=UTF-8" /> 
<meta name="resource-type" content="document" /> 
<meta name="language" content="en-gb" /> 
<meta name="distribution" content="global" /> 
<meta name="copyright" content="&copy;  2011" /> 
 
<meta http-equiv="content-style-type" content="text/css" /> 
<meta http-equiv="imagetoolbar" content="no" /> 
 
<title>Spotify Roulette | <?php echo ucwords($page); ?></title>

<link rel="SHORTCUT ICON" type="image/vnd.microsoft.icon" href="/favicon.ico" /> 
<link rel="icon" type="image/vnd.microsoft.icon" href="/favicon.ico" /> 
<link rel="icon" type="image/gif" href="/favicon.gif" /> 
<link rel="apple-touch-icon" href="/apple-touch-icon.png" />

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.15/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="http://jquery-ui.googlecode.com/svn/tags/latest/themes/base/jquery.ui.all.css" />
<script type="text/javascript" src="/game.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="/css/theme.css" />

</head>
<body>

<div id="wrap">

<?php
echo run_error_message();

echo '<div id="right_column">
<div id="profile">
';

if ($login->is_logged_in) {
	echo $login->twitter_profile();
} else {
	include(TEMPLATES . 'login.php');
}

?>
	</div>
	
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
</div>
<div id="left_column">
