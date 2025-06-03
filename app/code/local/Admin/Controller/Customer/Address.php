<?php

class Admin_Controller_Customer_Address  extends Core_Controller_Admin_Action
{
    public function newAction()
    {
        $view = $this->getLayout()
            ->createBlock('Admin/Customer_Address_New')
            ->setTemplate('admin/product/index/new.phtml');
        $this->getLayout()
            ->getChild('content')
            ->addChild('new', $view);
        $this->getLayout()->toHtml();
    }

    public function listAction()
    {
        $view = $this->getLayout()
            ->createBlock('Admin/Customer_Address_List')
            ->setTemplate('admin/product/index/list.phtml');
        $this->getLayout()
            ->getChild('content')
            ->addChild('list', $view);
        $this->getLayout()->toHtml();
    }

    public function saveAction()
    {
        $layout = $this->getLayout();
        // $layout = Mage::getBlock('core/layout');

        $view = $this->getLayout()
            ->createBlock('Admin/Customer_Address_Save')
            ->setTemplate('admin/product/index/save.phtml');

        $this->getLayout()
            ->getChild('content')
            ->addChild('save', $view);
        $this->getLayout()->toHtml();
    }

    public function deleteAction()
    {
        $view = $this->getLayout()->createBlock('Admin/Customer_Address_Delete')
            ->setTemplate('admin/product/index/delete.phtml');

        $this->getLayout()
            ->getChild('content')
            ->addChild('delete', $view);
        $this->getLayout()->toHtml();
    }
}
