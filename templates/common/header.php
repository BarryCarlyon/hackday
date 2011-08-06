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
 
<title>Spotify Roulette | Home</title>

<link rel="SHORTCUT ICON" type="image/vnd.microsoft.icon" href="/favicon.ico" /> 
<link rel="icon" type="image/vnd.microsoft.icon" href="/favicon.ico" /> 
<link rel="icon" type="image/gif" href="/favicon.gif" /> 
<link rel="apple-touch-icon" href="/apple-touch-icon.png" />

<style type="text/css">
html {
	background: #FFF;
}
body {
	width: 980px;
	margin-left: auto;
	margin-right: auto;
}
.center {
	text-align: center;
}

#wrap div, fieldset {
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	border-radius: 10px;
	
	border: 1px solid #000;
}
#left_column {
	width: 680px;
	float: left;
}
#right_column {
	width: 250px;
	float: right;
}
#left_column, #right_column, #profile {
	padding: 10px;
	overflow: hidden;
}
.left {
	float: left;
}
.right {
	float: right;
}
p {
	margin: 5px 3px;
}

</style>

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
</div>
<div id="left_column">
