<?php 

require_once("classes/HttpRequest.php");
require_once("classes/Twitter.php");

$twitter = new Twitter();
$twitter->queryUser('phoenix_rises', 'twitter_user_timeline');

var_dump($twitter->getResponse());

?>
