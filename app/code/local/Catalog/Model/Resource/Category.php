<?php

class Catalog_Model_Resource_Category extends Core_Model_Resource_Abstract{
    public function _construct(){
        $this->init("catalog_category","category_id");
       }
}


?>