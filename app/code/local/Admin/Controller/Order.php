<?php
class Admin_Controller_Order extends Core_Controller_Admin_Action
{
    public function listAction()
    {
        $layout = $this->getLayout();
        $view = $this->getLayout()
            ->createBlock('admin/order_list');
            // ->setTemplate('admin/order/list.phtml');
        $this->getLayout()
            ->getChild('content')
            ->addChild('list', $view);
        $this->getLayout()->toHtml();
    }
    public function exportCsvAction()
    {
        Mage::getModel('admin/csv')
            ->exportCsv(Mage::getModel('sale/order'));
    }
    public function viewAction()
    {
        $orderId = Mage::getModel('core/request')->getQuery('order_id');
        $order = Mage::getModel('sale/order')->load($orderId);


        // $layout = Mage::getBlock("core/layout");
        $viewOrder = $this->getLayout()
            ->createBlock("admin/order_view");


        $this->getLayout()
            ->getChild("content")
            ->addChild("order", $viewOrder);
        $viewOrder->setOrder($order);

        $orderInfo =$this->getLayout()
            ->createBlock("admin/order_view_info");
        $this->getLayout()->getChild("content")
            ->getChild("order")
            ->addChild("order_info", $orderInfo);

        $orderAddressinfo = $this->getLayout()
            ->createBlock("admin/order_view_address");
        $this->getLayout()->getChild("content")
            ->getChild("order")
            ->addChild("order_address", $orderAddressinfo);

        $orderIteminfo = $this->getLayout()
            ->createBlock("admin/order_view_item");
        $this->getLayout()
            ->getChild("content")
            ->getChild("order")
            ->addChild("order_item", $orderIteminfo);

        $this->getLayout()->toHtml();
    }
    public function updateStatusAction()
    {
        $request = Mage::getModel('core/request')
            ->getParams();
        $orderModel = Mage::getModel('sale/order')
            ->setOrderId($request['order_id'])
            ->setOrderStatus($request['order_status'])
            ->save();
        header('location:http://localhost/ecommerecemvc/admin/order/list');
    }
}
