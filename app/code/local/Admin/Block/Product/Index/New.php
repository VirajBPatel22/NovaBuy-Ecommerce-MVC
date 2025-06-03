<?php
class Admin_Block_Product_Index_New extends Core_Block_Template{
    protected $_product;
    protected $_attribute;
    public function getProduct(){
        $id = Mage::getModel('core/request')::getQuery('id');
            $this->_product = Mage::getModel('catalog/product')->load($id);
        return $this->_product;
    }
    public function getCategory(){
            $category = Mage::getModel('catalog/category')->getCollection()->getData();
        
        return $category;
    }
    public function getAttribute(){
        $attribute = Mage::getModel('catalog/attribute')->getCollection()->getData();
    
    return $attribute;
}
public function getAttridata(){
    $id = Mage::getModel('core/request')::getQuery('id');
    
    $attridata = Mage::getModel('catalog/product_attribute')->getCollection()->addFieldToFilter('product_id',['='=>$id])->getData();
    return $attridata;
}
public function getImagedata(){
    
    $id = Mage::getModel('core/request')::getQuery('id');

    $img = Mage::getModel('catalog/media')->getCollection()->addFieldToFilter('product_id',['='=>$id])->getData();
    return $img;
   
}
    public function getHtmlField($field,$data){
        $field = ucfirst(strtolower($field));
        $classname = sprintf("Admin_Block_Html_Elements_%s",$field);
        $element = new $classname($data);
        return $element->render();
    }
}


?>