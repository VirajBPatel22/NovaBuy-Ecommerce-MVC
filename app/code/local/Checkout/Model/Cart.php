<?php
class Checkout_Model_Cart extends Core_Model_Abstract
{
    public function init()
    {
        $this->_resourceClassName = "Checkout_Model_Resource_Cart";
        $this->_CollectionClassName = "Checkout_Model_Resource_Cart_Collection";
    }
    public function AddProduct($product_id, $quentity)
    {
        $previtem = Mage::getModel('checkout/cart_item')
            ->getCollection()
            ->AddFieldToFilter('cart_id', ['=' => $this->getcartId()])
            ->AddFieldToFilter('product_id', ['=' => $product_id])
            ->getdata();

        if ($previtem) {
            $addproduct = Mage::getModel('checkout/cart_item')
                ->setItemId($previtem[0]->getItemId())
                ->setProductId($product_id)
                ->setCartId($this->getCartId())
                ->setProductQuantity((int)$quentity + (int)$previtem[0]->getProductQuantity())
                ->save();
        } else {
            $addproduct = Mage::getModel('checkout/cart_item')
                ->setProductId($product_id)
                ->setCartId($this->getCartId())
                ->setProductQuantity($quentity)
                ->save();
        }
        return $this;
    }
    public function removeItem($item_id)
    {
        $cartDatas = $this->getItemCollection()
            ->getData();
        foreach ($cartDatas as $cartData) {
            if ($cartData->getItemId() == $item_id) {
                $cartData->delete();
            }
        }
        return $this;
    }

    public function updateItem($item_id, $quantity)
    {
        $cartDatas = $this->getItemCollection()
            ->getData();
        foreach ($cartDatas as $cartData) {
            if ($cartData->getItemId() == $item_id) {
                $cartData->setProductQuantity($quantity)
                    ->save();
            }
        }
        return $this;
    }
    public function _beforesave()
    {
        $totalamount = 0;
        $cartitemdata = $this->getItemCollection()->getdata();
        foreach ($cartitemdata as $cartamount) {
            $totalamount += $cartamount->getSubTotal();
        }
        $totalamount = $totalamount + (int)$this->getShippingAmount();
        $this->setTotalAmount($totalamount - (int)$this->getCouponDiscount());
        $date = new DateTime();
        $this->setUpdatedAt($date->format("Y/m/d H:i:s"));
    }
    public function getItemCollection()
    {
        return Mage::getModel('checkout/cart_item')
            ->getCollection()
            ->addFieldToFilter('cart_id', ['=' => $this->getCartId()]);
    }
    public function getAddressCollection(){
        return Mage::getModel('checkout/cart_address')
            ->getCollection()
            ->addFieldToFilter('cart_id', ['=' => $this->getCartId()]);
    }
    public function getShippingAddress(){
        return $this->getAddressCollection()
            ->addFieldToFilter('typeofaddress','shipping');
    }
    public function getBillingAddress(){
        return $this->getAddressCollection()
            ->addFieldToFilter('typeofaddress','shipping');

    }
}
