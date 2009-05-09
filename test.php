<?php 

require_once("classes/HttpRequest.php");
require_once("classes/Twitter.php");

$twitter = new Twitter();
$twitter->queryUser('phoenix_rises');

var_dump($twitter->getResponse());

?>
