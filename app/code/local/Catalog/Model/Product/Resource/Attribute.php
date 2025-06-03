<?php

class Catalog_Model_Product_Resource_Attribute extends Core_Model_Resource_Abstract{
    public function _construct(){
        $this->init("catalog_product_attribute","value_id");
       }
}


?>