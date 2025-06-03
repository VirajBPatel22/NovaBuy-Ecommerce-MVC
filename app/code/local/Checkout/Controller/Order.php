<?php

class Checkout_Controller_Order extends Core_Controller_Customer_Action
{
    public function placeorderAction()
    {
        $transectionId = Mage::getModel('core/Request')
            ->getQuery('Transaction_id');
        $cartModel = Mage::getSingleton('checkout/session')
            ->getCart();
        $orderModel = Mage::getModel('Checkout/Convert_Order')
            ->convert($cartModel);
        $cartModel->setIsActive(0)->save();
        Mage::getModel("core/session")
            ->remove("cart_id");

       
        $paymentModel = Mage::getModel('Sale/order_Payment')
            ->setOrderId($orderModel->getOrderId())
            ->setAmount($orderModel->getTotalAmount())
            ->setPaymentMethod($orderModel->getPaymentMethod())
            ->setTransactionId($transectionId)
            ->setStatus('completed')
            ->save();
             echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";
        // die;
        $layout = Mage::getBlock('core/layout');
        $url = $layout->getUrl("Catalog/product/list");
        header("location:$url");
    }
    public function yourOrderAction()
    {
        $layout =  Mage::getBlock('core/layout');
        $view = $layout->createBlock('customer/Account_YourOrder')
                ->setTemplate('customer/account/yourorder.phtml');
        $layout->getChild('content')->addChild('yourorder', $view);
        $layout->toHtml();
    }
   
}
