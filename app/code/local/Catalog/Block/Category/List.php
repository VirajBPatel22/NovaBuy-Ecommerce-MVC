<?php

class Catalog_Block_Category_List extends Core_Block_Template {
    public function getCategory(){
        $product = Mage:: getModel('catalog/category')
        ->getCollection()
        ;
        $data = $product->getData();
        
        return $data;
    }

}

?>