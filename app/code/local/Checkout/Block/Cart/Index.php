<?php
class Checkout_Block_Cart_Index extends Core_Block_Template
{
    public function getCartData()
    {
        $cart_model = Mage::getSingleton("checkout/session")->getCart();
        $cart_item_data = $cart_model->getItemCollection()->getData();
        return $cart_item_data;
    }
    public function getProductAmount(){
        return Mage::getSingleton('checkout/session')->getCart();
        
    }
    
}
