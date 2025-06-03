<?php

class Admin_Controller_Product_Index extends Core_Controller_Admin_Action
{
    public function newAction()
    {
        $product = Mage::getModel('catalog/product');
        $view = $this->getLayout()->createBlock('Admin/Product_Index_New')
            ->setTemplate('admin/product/index/new.phtml');
        $this->getLayout()->getChild('content')->addChild('new', $view);
        $this->getLayout()->toHtml();
    }
    public function listAction()
    {
        $view = $this->getLayout()
            ->createBlock('Admin/Product_Index_List');
        $this->getLayout()->getChild('content')
            ->addChild('list', $view);
        $this->getLayout()->toHtml();
    }

        public function saveAction()
        {
            $request = Mage::getModel("core/request");
            $data = $request->getParam('catalog_product');
            $name = substr($data["name"], 0, 5);
            $sku = $data["category_id"] . $name;
            $data["sku"] = $sku;
            if(Mage::getModel('catalog/product')
                ->setData($data)
                ->save()){
                    Mage::getSingleton('core/message')->addMessage("success", "product add successfully");
                }
            $url = Mage::getBaseUrl() . $request->getModuleName() . "/" . $request->getControllerName() . "/list";
            header("location: {$url}");
            exit();
        }

    public function deleteAction()
    {
        $request = Mage::getModel("core/request");
        $product = Mage::getModel('catalog/product');
        $id = $request->getQuery('id');
        $product->load($id);
        $product->delete();
        header("Location: " . Mage::getBaseUrl() . "admin/product_index/list");
        exit();
    }
    public function exportCsvAction()
    {
        Mage::getModel('admin/csv')
            ->exportCsv(Mage::getModel('catalog/product'));
    }
}
