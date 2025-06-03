<?php

class Catalog_Block_Product_List extends Core_Block_Template
{
    public function __construct()
    {
        $product = $this->getLayout()->createBlock('catalog/product_list_product');
        $filter = $this->getLayout()->createBlock('catalog/product_list_filter');
        $this->addChild('filter', $filter);
        $this->addChild('product', $product);
        // $this->init();

    }
    public function getProduct()
    {
        $id = Mage::getModel('core/request')::getQuery('id');
        $product = Mage::getSingleton('catalog/filter')->getProductColllection();
        $data = $product->getData();
        // print_r($data);
        foreach ($data as $img) {
            // echo "<pre>";

            $model = Mage::getModel('catalog/media')->getCollection()->addFieldToFilter('product_id', $img->getProductId())
                ->addFieldToFilter('default_img', 1)->getData();
            $filepath = $model[0]->getFilePath();
            $img->setImage($filepath);
            // print_r($filepath);
            // echo "</pre>";
        }
        // print_r($data[0]->getProductId());
        // die();

        return $data;
    }

    public function filterdata()
    {
        // die();

        $request = Mage::getModel('core/request');
        $category_id = $request->getQuery('id');
        $category_array = explode(',', $category_id);

        if ($category_id) {
            $data = Mage::getModel('catalog/product')->getCollection()
                ->select()
                ->addFieldToFilter('category_id', ['IN' => $category_array])
                ->addAttributeToSelect(['color', 'size', 'brand', 'material']);
        } else {
            $data = Mage::getModel('catalog/product')
                ->getCollection()
                ->select()
                ->addAttributeToSelect(['color', 'size', 'brand', 'material']);
        }


        $finaldata = $data->getData();

        // die();

        return $finaldata;
    }
    public function getcategoryname()
    {
        $data = Mage::getModel(('catalog/category'))->getCollection()->select()->getdata();
        return $data;
    }
}
