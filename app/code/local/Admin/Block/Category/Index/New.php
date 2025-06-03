<?php
class Admin_Block_Category_Index_New extends Core_Block_Template{
    protected $_category;
    public function getCategory(){
        $id = Mage::getModel('core/request')::getQuery('id');
        $this->_category = Mage::getModel('catalog/category')->load($id);
        return $this->_category;
    }
    public function getParentcategory(){
        $category = Mage::getModel('catalog/category')->getCollection()
            ->getData();
    
    return $category;
}
}


?>