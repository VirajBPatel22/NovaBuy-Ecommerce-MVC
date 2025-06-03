<?php

class Admin_Controller_Dashboard extends Core_Controller_Admin_Action
{
    public function listAction()
    {
        $layout = $this->getLayout();
        $list = $this->getLayout()
            ->createBlock('admin/dashboard_list')
            ->setTemplate('admin/dashboard/list.phtml');
        $this->getLayout()
            ->getChild('content')
            ->addChild('list', $list);
        $this->getLayout()
            ->getChild('head')
            ->addCss('admin/dashboard/list.css');
        $this->getLayout()->toHtml();
    }
    public function exportCsvAction()
    {
        Mage::getModel('admin/csv')
            ->exportCsv(Mage::getModel('sale/order'));
        Mage::getModel('admin/csv')
            ->exportCsv(Mage::getModel('catalog/product'));
        Mage::getModel('admin/csv')
            ->exportCsv(Mage::getModel('catalog/category'));
    }
}
