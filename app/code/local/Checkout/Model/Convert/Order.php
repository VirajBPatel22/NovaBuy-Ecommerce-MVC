<?php
class Checkout_Model_Convert_Order
{
    public function convert($model)
    {
        $data = $model->getData();
        unset($data['cart_id']);
        $ip = $_SERVER['HTTP_CLIENT_IP'] ??
            $_SERVER['HTTP_X_FORWARDED_FOR'] ??
            $_SERVER['HTTP_X_FORWARDED'] ??
            $_SERVER['HTTP_FORWARDED_FOR'] ??
            $_SERVER['HTTP_FORWARDED'] ??
            $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        if ($ip === '::1') {
            $ip = '127.0.0.1';
        }
        $ip = filter_var(trim(explode(",", $ip)[0]), FILTER_VALIDATE_IP) ?: "Invalid IP";
        $order = Mage::getModel('sale/order')
            ->setData($data)
            ->setOrderIp($ip)
            ->setOrderNumber($model->getCartId())
            ->save();
        $ItemData = $model->getItemCollection()
            ->getData();
        foreach ($ItemData as $_ItemData) {
            $_ItemData = $_ItemData->getData();
            unset($_ItemData['item_id']);
            unset($_ItemData['cart_id']);
            print_r(Mage::getModel('sale/order_item')
                ->setData($_ItemData)
                ->setOrderId($order->getOrderId())
                ->save());
        }
        $billingData = $model->getBillingAddress()->getfirstItem()->getData();
        unset($billingData['created_at']);
        unset($billingData['updated_at']);
        unset($billingData['address_id']);
        unset($billingData['cart_id']);
        Mage::getModel('sale/order_address')
            ->setData($billingData)
            ->setOrderId($order->getOrderId())
            ->setTypeofaddress('billing')
            ->save();
        $shippingData = $model->getShippingAddress()->getfirstItem()->getData();
        unset($shippingData['created_at']);
        unset($shippingData['updated_at']);
        unset($shippingData['address_id']);
        unset($shippingData['cart_id']);
        $addressModel = Mage::getModel('sale/order_address')
            ->setData($shippingData)
            ->setOrderId($order->getOrderId())
            ->setTypeofaddress('shipping')
            ->save();
        return $order;
    }
}
