<?php
class Customer_Controller_Account_Profile extends Core_Controller_Customer_Action{
    public function dashboardAction(){
        $customerId = Mage::getSingleton('core/session')
            ->get('customer_id');
        $customer = Mage::getModel('customer/account')->load($customerId);
        $layout = Mage::getBlock("core/layout");
        $viewCustomer = $layout->createBlock("customer/account_profile_dashboard");

        
        $layout->getChild("content")->addChild("dashboard", $viewCustomer);
        $viewCustomer->setCustomer($customer);
        $dashboardDetails = $layout->createBlock("customer/account_profile_dashboard_details");
        $layout->getChild("content")
            ->getChild("dashboard")
            ->addChild("dashboard_details", $dashboardDetails);
        
        $dashboardAddress = $layout->createBlock("customer/account_profile_dashboard_address");
        $layout->getChild("content")
            ->getChild("dashboard")
            ->addChild("dashboard_address", $dashboardAddress);

        
        $layout->toHtml();
    }
    public function saveAction(){
        $request = Mage::getModel('core/request')
            ->getParam('customer');
        Mage::getModel('customer/account')->setData($request)
            ->save();
            header("Location: http://localhost/ecommerecemvc/customer/account_profile/dashboard");
        
    }
}
?>