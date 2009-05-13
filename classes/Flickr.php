<?php

    /**
    * 
    */
    class Flickr extends HttpRequest
    {
        
        public function search( $keyword )
        {
            $r = $this->query("SELECT * from flickr.photos.search where text='{$keyword}' OR tags='{$keyword}'");
            $r = array_map(array($this, "generate_photo_url"), $r->query->results);
            
        }
        
        private function generate_photo_url( $data )
        {
            $data["photo_url"] = "http://farm{$data["farm"]}.static.flickr.com/{$data["server"]}/{$data["id"]}_{$data["secret"]}.jpg";
            return $data;
        }
        
    }
    

?>