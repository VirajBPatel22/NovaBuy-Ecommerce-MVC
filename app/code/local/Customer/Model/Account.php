<?php
class Customer_Model_Account extends Core_Model_Abstract
{
    public function init()
    {
        $this->_resourceClassName = "Customer_Model_Resource_Account";
        $this->_CollectionClassName = "Customer_Model_Resource_Account_Collection";
    }
    public function _aftersave()
    {
        if(empty($this->getAddressCollection()->getdata())){
            $addressModel = Mage::getModel('customer/account_address')
                ->setData($this->getData())
                ->save();
        }
    }
    public function getAddressCollection(){
        return Mage::getModel('customer/account_address')
            ->getCollection()
            ->addFieldToFilter('customer_id',$this->getCustomerId());
    }
}
?>