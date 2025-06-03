<?php 
Class Checkout_Model_Session extends Core_Model_Session{
    public function getcart(){
        $cart_id = $this->get('cart_id');
        $cart_id = (is_null($cart_id))?0:$cart_id;
        $cart = Mage::getModel('checkout/cart')
            ->load($cart_id);
        
        if(!$cart->getCartId()){
            $cart->setCustomerId($this->get('customer_id'))
            ->save();
            // $cart_iis$cartdata->getCartId());

            $cartId = $cart->getcartId();
            $this->set('cart_id',$cartId);
            
        }
        // else{
        //     return $cartdata ;
        //     $cartdata = Mage::getModel('checkout/cart')->load($cart_id);
        // }
        return $cart;
    }
}


?>