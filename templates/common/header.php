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

<link rel="stylesheet" type="text/css" media="screen=" href="/css/bootstrap.min.css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.15/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="http://jquery-ui.googlecode.com/svn/tags/latest/themes/base/jquery.ui.all.css" />
<script type="text/javascript" src="/game.js"></script>
<!--
<link rel="stylesheet" type="text/css" media="screen" href="/css/theme.css" />
-->
<script type="text/javascript" src="/js/bootstrap-modal.js"></script>
<script type="text/javascript" src="/js/bootstrap-alerts.js"></script>
<script type="text/javascript" src="/js/bootstrap-twipsy.js"></script>
<script type="text/javascript" src="/js/bootstrap-popover.js"></script>
<script type="text/javascript" src="/js/bootstrap-dropdown.js"></script>
<script type="text/javascript" src="/js/bootstrap-scrollspy.js"></script>
<script type="text/javascript" src="/js/bootstrap-tabs.js"></script>
<script type="text/javascript" src="/js/bootstrap-buttons.js"></script>

</head>
<body style="padding-top: 40px;">
<div class="container">

<div class="topbar">
	<div class="topbar-inner">
	        <div class="container">
			<h3><a href="/">Spotify Roulette</a></h3>
			<ul class="nav">
				<li><a href="/">Home</a></li>
				<li><a href="/play/">Play</a></li>
			</ul>
			<ul class="secondary-nav">
<?php
if ($login->is_logged_in) {
echo '
				<li><a href="/logout/">Logout</a></li>
';
} else {
echo '
				<li><a href="/login/" id="loginwithtwitter">Login with Twitter to Play</a></li>
';
}
?>
			</ul>
		</div>
	</div>
</div>

<div class="row">
	<div class="span11">
