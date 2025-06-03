<?php

class Checkout_Controller_Shipping extends Core_Controller_Customer_Action
{
    public function indexAction()
    {
        $layout =  Mage::getBlock('core/layout');
        $view = $layout->createBlock('checkout/shipping_index')
            ->setTemplate('checkout/shipping/index.phtml');
        $layout->getChild('content')->addChild('shipping', $view);
        $layout->toHtml();
    }
    public function saveAction()
    {
        $request = Mage::getModel('core/request');
        $shippingType = $request->getParam('shipping_type');
        $paymentMethod = $request->getParam('payment_method');
        $shippingModel = Mage::getModel('checkout/shipping');
        if (array_key_exists($shippingType, $shippingModel->getAllShipping())) {
            $shippingAmount = $shippingModel->CalculateShippingAmount($shippingType);
            $cartModel = Mage::getSingleton('checkout/session')
                ->getCart();
            $totalAmount = $cartModel->getTotalAmount();
            var_dump((int)$totalAmount + (int)$shippingAmount);
            $cartModel->setPaymentMethod($paymentMethod)
                ->setShippingMethod($shippingType)
                ->setShippingAmount($shippingAmount)
                ->save();
        }
        if($paymentMethod == 'Paypal'){
            header('Location: http://localhost/ecommerecemvc/sale/payment/payment');
        }
        else{
            header("Location: http://localhost/ecommerecemvc/checkout/order/placeorder");
        }
    }
}
