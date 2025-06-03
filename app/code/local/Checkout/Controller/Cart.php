<?php

class Checkout_Controller_Cart
{
    public function indexAction()
    {
        // echo get_class() . "<br>" . __FUNCTION__;
        $layout =  Mage::getBlock('core/layout');
        $view = $layout->createBlock('checkout/Cart_Index')
            ->setTemplate('checkout/cart/index.phtml');
        $layout->getChild('content')->addChild('index', $view);
        $layout->toHtml();
    }


    public function updateAction()
    {

        $request = Mage::getModel('core/request')->getParams();
        $item_id = $request['item_id'];
        $quantity = $request['quantity'];

        if (Mage::getSingleton('checkout/session')->getCart()
            ->updateItem($item_id, $quantity)
            ->save()
        ) {
            Mage::getSingleton('core/message')->addMessage("success", "product updated successfully");
           
        }
        echo "123";
        header("Location: http://localhost/ecommerecemvc/checkout/cart/index ");
    }

    public function removeAction()
    {

        $delete_id = Mage::getModel("core/request")->getQuery('item_id');
        Mage::getSingleton('checkout/session')->getCart()
            ->removeItem($delete_id)
            ->save();
        echo "123";
        header("Location: http://localhost/ecommerecemvc/checkout/cart/index");
    }
    
    public function addAction()
    {
        $request = Mage::getModel('core/request');
        $product_id = $request->getParam('product_id');
        $quantity = $request->getParam('quentity');

        $cart = Mage::getSingleton('checkout/session')->getCart();
        

        try {
            $cart->addProduct($product_id, $quantity);
            $cart->save();
            $count = count(Mage::getSingleton('checkout/session')
                ->getCart()
                ->getItemCollection()
                ->getData());
            echo $count;
            exit;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            exit;
        }
    }

   
    public function couponAction()
    {
        $request = Mage::getModel('core/request');
        $couponCode = $request->getParam('coupon_code');
        $totalAmount = $request->getParam('total_price');
        $couponModel = Mage::getModel('checkout/coupon');
        $cart = Mage::getSingleton('checkout/session')->getCart();

        if (array_key_exists($couponCode, $couponModel->getAllCoupon())) {
            $discount = $couponModel->CalculateDiscount($couponCode, $totalAmount);
            $newTotal = $totalAmount - $discount;

            $cart->setTotalAmount($newTotal)
                ->setCouponCode($couponCode)
                ->setCouponDiscount($discount)
                ->save();

            
        } else {
            // Remove any coupon
            $cart->setTotalAmount($totalAmount)
                ->setCouponCode('')
                ->setCouponDiscount(0)
                ->save();

        }

        header("Location: http://localhost/ecommerecemvc/checkout/cart/index");

    }
    public function addressAction()
    {
        // echo get_class() . "<br>" . __FUNCTION__;
        $layout =  Mage::getBlock('core/layout');
        $view = $layout->createBlock('checkout/Cart_address')
            ->setTemplate('checkout/cart/address.phtml');
        $layout->getChild('content')->addChild('address', $view);
        $layout->toHtml();
    }
    public function saveaddressAction()
    {
        $request = Mage::getModel('core/request')
            ->getparams();

        $cartId = Mage::getSingleton('checkout/session')
            ->getCart()
            ->setEmail($request['email'])
            ->save()
            ->getCartId();
        $billing = array_merge($request['personal'], $request['billing']);
        $billing['cart_id'] = $cartId;
        $shipping = array_merge($request['personal'], $request['shipping']);
        $shipping['cart_id'] = $cartId;
        $billing['typeofaddress'] = 'billing';
        $shipping['typeofaddress'] = 'shipping';
        $billinginfo = Mage::getBlock('checkout/cart_address')->billinginfo();
        $shippinginfo = Mage::getBlock('checkout/cart_address')->shippinginfo();
        if ($billinginfo) {
            $billingAddressId = $billinginfo->getfirstItem()->getAddressId();
            $billing['address_id'] = $billingAddressId;
        }
        if ($shippinginfo) {
            $shippingAddressId = $shippinginfo->getfirstItem()->getAddressId();
            $shipping['address_id'] = $shippingAddressId;
        }
        Mage::getModel('checkout/cart_address')->setData($billing)->save();
        Mage::getModel('checkout/cart_address')->setData($shipping)->save();
        header("Location: http://localhost/ecommerecemvc/checkout/cart/shipping");
    }
    
    public function shippingAction()
    {
        $layout =  Mage::getBlock('core/layout');
        $view = $layout->createBlock('checkout/cart_shipping')
            ->setTemplate('checkout/cart/shipping.phtml');
        $layout->getChild('content')->addChild('shipping', $view);
        $layout->toHtml();
    }
    public function saveshippingAction()
    {
        $request = Mage::getModel('core/request');
        // $delimg = $request->getParam('catalog_image_delete');
        $shippingType = $request->getParam('shipping_type');
        $paymentMethod = $request->getParam('payment_method');

        $shippingModel = Mage::getModel('checkout/shipping');
        if (array_key_exists($shippingType, $shippingModel->getAllShipping())) {
            $shippingAmount = $shippingModel->CalculateShippingAmount($shippingType);
            // print($totalAmount-$totalDiscount);
            // die();
            $cartModel = Mage::getSingleton('checkout/session')
                ->getCart();
            $totalAmount = $cartModel->getTotalAmount();
            print($totalAmount);
            print("<br>");
            print($shippingAmount);
            var_dump((int)$totalAmount + (int)$shippingAmount);
            // die;
            $cartModel->setPaymentMethod($paymentMethod)
                ->setShippingMethod($shippingType)
                ->setShippingAmount($shippingAmount)
                ->save();
        }
        header("Location: http://localhost/ecommerecemvc/checkout/cart/index");

        // print('in coupon action');
        // die();
    }


    public function testAction()
    {
        $collection = Mage::getModel('checkout/cart')->getCollection();
        print_r($collection);
    }
    public function countAction()
    {
        $countdata = Mage::getSingleton('checkout/session')
            ->getcart()
            ->getItemCollection()
            ->getdata();
        $count =  count($countdata);
        echo json_encode(['count' => $count]);
        exit;
    }
}