<?php 

require_once("classes/HttpRequest.php");
require_once("classes/Twitter.php");

$twitter = new Twitter();
$twitter->queryUser('phoenix_rises', 'twitter.user.timeline');
$twitter->doRequest();

var_dump($twitter->getResponse());

?>
