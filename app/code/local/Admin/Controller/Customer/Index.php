<?php

class Admin_Controller_Customer_Index  extends Core_Controller_Admin_Action {
    public function newAction() {
        $view = $this->getLayout()
            ->createBlock('Admin/Customer_Index_New')
            ->setTemplate('admin/product/index/new.phtml');
        
        $this->getLayout()
            ->getChild('content')
            ->addChild('new', $view);
        $this->getLayout()->toHtml();

    }

    public function listAction() {
        $view = $this->getLayout()
            ->createBlock('Admin/Customer_Index_List')
            ->setTemplate('admin/product/index/list.phtml');
        
        $this->getLayout()
            ->getChild('content')
            ->addChild('list', $view);
        $this->getLayout()->toHtml();
    }

    public function saveAction() {
        $view = $this->getLayout()
            ->createBlock('Admin/Customer_Index_Save')
            ->setTemplate('admin/product/index/save.phtml');
        
        $this->getLayout()
            ->getChild('content')
            ->addChild('save', $view);
        $this->getLayout()->toHtml();

    }
    
    public function deleteAction() {
        $view = $this->getLayout()
            ->createBlock('Admin/Customer_Index_Delete')
            ->setTemplate('admin/product/index/delete.phtml');
        
        $this->getLayout()->getChild('content')
            ->addChild('delete', $view);
        $this->getLayout()->toHtml();
    }

}

?>