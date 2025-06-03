<?php

class Admin_Controller_Category_Index extends Core_Controller_Admin_Action
{
    public function newAction()
    {
        $view = $this->getLayout()->createBlock('Admin/Category_Index_New')
            ->setTemplate('admin/category/index/new.phtml');
        $this->getLayout()->getChild('content')
            ->addChild('new', $view);
        $this->getLayout()->toHtml();
    }

    public function listAction()
    {
        $view = $this->getLayout()
        ->createBlock('Admin/Category_Index_List')
        ->setTemplate('admin/category/index/list.phtml');
            $this->getLayout()->getChild('content')
            ->addChild('list', $view);
        $this->getLayout()->toHtml();
    }

    public function saveAction()
    {
        $request = Mage::getModel("core/request");
        $product = Mage::getModel('catalog/category');
        $data = $request->getParam('catalog_category');
        if (empty($data['parent_id'])) {
            $data['parent_id'] = 1;
        }
        $model = $product->setData($data)
            ->getData();
        $product->save($model);
        header("Location: http://localhost/ecommerecemvc/admin/category_index/list");

        $layout =  Mage::getBlock('core/layout');
        $view = $layout->createBlock('Admin/Product_Index_Save')
            ->setTemplate('admin/product/index/save.phtml');
        $layout->getChild('content')
            ->addChild('save', $view);
        $layout->toHtml();
    }

    public function deleteAction()
    {
        $request = Mage::getModel("core/request");
        $product = Mage::getModel('catalog/category');
        $id = $request->getQuery('id');
        $product->load($id);
        $product->delete();
        header("Location: http://localhost/ecommerecemvc/admin/category_index/list");
        $layout =  Mage::getBlock('core/layout');
        $view = $layout->createBlock('Admin/Product_Index_Delete')
            ->setTemplate('admin/product/index/delete.phtml');
        $layout->getChild('content')
            ->addChild('delete', $view);
        $layout->toHtml();
    }
    public function exportCsvAction()
    {
        Mage::getModel('admin/csv')
            ->exportCsv(Mage::getModel('catalog/category'));
    }
}
