<?php

class Catalog_Model_Resource_Media extends Core_Model_Resource_Abstract{
    public function _construct(){
        $this->init("catalog_media_gallery","media_id");
       }
}


?>