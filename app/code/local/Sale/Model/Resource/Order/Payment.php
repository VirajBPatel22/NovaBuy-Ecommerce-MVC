<?php
Class Sale_Model_Resource_Order_Payment extends Core_Model_Resource_Abstract{
    public function __construct() {
        $this->init("order_payment","payment_id");
    }
}
?>