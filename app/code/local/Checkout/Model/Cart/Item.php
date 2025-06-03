<?php
class Checkout_Model_Cart_Item extends Core_Model_Abstract{
    protected $_product;

    public function init(){
        $this->_resourceClassName = "Checkout_Model_Resource_Cart_Item";
        $this->_CollectionClassName = "Checkout_Model_Resource_Cart_Item_Collection";
}

protected function _beforesave(){

    $price = $this->getProduct()->getPrice();
    $this->setPrice($price);
    $subTotal = strval($price * $this->getProductQuantity());
    $this->setSubTotal($subTotal);
}
public function getProduct(){
   
    $_product = Mage::getModel('catalog/product')->load($this->getProductId());
    return $_product;
}
}

?>