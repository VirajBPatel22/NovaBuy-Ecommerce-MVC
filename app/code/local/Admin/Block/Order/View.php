<?php
Class Admin_Block_Order_View extends Core_Block_Template{
    protected $_orderModel = null;
    public function __construct()
    {
        $this->setTemplate("admin/order/view.phtml");
    }

    public function setOrder($model){
        $this->_orderModel=$model;
        return $this;
    }
    public function getOrder(){
        return $this->_orderModel;
    }
}

?>