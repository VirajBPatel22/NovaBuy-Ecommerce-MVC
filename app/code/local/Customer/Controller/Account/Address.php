<?php

class Customer_Controller_Account_Address extends Core_Controller_Customer_Action
{
    public function saveAddressAction()
    {
        $address = Mage::getModel('core/request')
            ->getParams()['address'];
        $customerId = Mage::getSingleton('core/session')
            ->get('customer_id');
        $address['customer_id'] = $customerId;
        Mage::getModel('customer/account_address')
            ->setData($address)
            ->save();
        header("Location: http://localhost/ecommerecemvc/customer/account_profile/dashboard");
    }
    public function deleteAddressAction()
    {
        $address = Mage::getModel('core/request')
            ->getQuery('address_id');
        $deletedata = Mage::getModel('customer/account_address')->getCollection()
            ->addFieldToFilter('address_id',['='=>$address])
            ->addFieldToFilter('default_address',['='=>1])
            ->getdata();
        if($deletedata){
            header("Location: http://localhost/ecommerecemvc/customer/account_profile/dashboard");
        }
        else{
            Mage::getModel('customer/account_address')
                ->load($address)
                ->delete();
                header("Location: http://localhost/ecommerecemvc/customer/account_profile/dashboard");
        }
    }
    public function setdefaultAction()
    {
        $addressId = Mage::getModel('core/request')->getQuery('address_id');
        $customerId = Mage::getSingleton('core/session')->get('customer_id');
        $addressModel = Mage::getModel('customer/account_address');


        $addressData = $addressModel->getCollection()
            ->addFieldToFilter('customer_id',$customerId);          
        $address = $addressData->addFieldToFilter('default_address',1)->getData()[0];
        $addressModel->setDefaultAddress(0)
            ->setAddressId($address->getAddressId())
            ->save();


        $addressData = $addressModel->getCollection()
            ->addFieldToFilter('customer_id',$customerId);
        $address = $addressData->addFieldToFilter('address_id',$addressId)->getData()[0];
        $addressModel->setDefaultAddress(1)
            ->setAddressId($address->getAddressId())
            ->save();
        header("Location: http://localhost/ecommerecemvc/customer/account_profile/dashboard");
        
    }
}
