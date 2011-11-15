<pre><?php 

require("classes/HttpRequest.php");
require("classes/Twitter.php");
require("classes/Flickr.php");

# $twitter = new Twitter();
# $data = $twitter->search('@caius');

$flickr = new Flickr();
$data = $flickr->search("openhacklondon");

var_dump( $data );

?>
