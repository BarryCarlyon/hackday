<?php

class Twitter extends HttpRequest {
	public function queryUser($userName, $table) {
		$yqlQuery = "SELECT * FROM $table WHERE id='$userName'";
		$this->setQueryString("q=".$yqlQuery);
	}
}

?>
