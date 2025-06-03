<?php

class Customer_Model_Session extends Core_Model_Session
{
   
    public function getCustomer()
    {
        $customer_id = $this->get('customer_id');

        $customer_id = is_null($customer_id) ? 0 : $customer_id;
       
        $customer =  Mage::getModel("customer/account")
            ->load($customer_id);

        if (!$customer->getCustomerId()) {

        }
    
        return $customer;
    }
}