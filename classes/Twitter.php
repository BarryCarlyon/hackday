<?php

class Twitter extends HttpRequest {
    
    // Fetches the timeline for a user
    public function user_timeline( $username )
    {
        return $this->query("SELECT * FROM twitter.user.timeline WHERE id='{$username}'")->query;
    }
    
    // Searches the keywords
    public function search( $keyword )
    {
        return $this->query("SELECT * FROM html WHERE url='http://nero.vm.caius.name/twitter.php?keyword={$keyword}' AND xpath='/html/body/div/div/div/ul/li[@class]'");
    }
    
}

?>
