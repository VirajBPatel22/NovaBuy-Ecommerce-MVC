<?php 
class Sale_Model_Order_Address extends Core_Model_Abstract{
    public function init()
    {
       $this->_resourceClassName = "Sale_Model_Resource_Order_Address";
       $this->_CollectionClassName = "Sale_Model_Resource_Order_Address_Collection";
    }
}
        