<?php

class Catalog_Block_Product_View extends Core_Block_Template
{
    public function getSingleproduct()
    {
        $request =  Mage::getModel('core/request');
        $id = $request->getQuery('id');
        $product = Mage::getModel('catalog/product')->getCollection()
            ->addFieldToFilter('catalog_media_gallery.product_id', ['=' => $id])
            ->joinInner('catalog_media_gallery', 'main_table.product_id=catalog_media_gallery.product_id', ["image" => "file_path"]);
        $mainimage = Mage::getModel('catalog/product')
            ->getCollection()
            ->addFieldToFilter('catalog_media_gallery.product_id', ['=' => $id])
            ->addFieldToFilter('catalog_media_gallery.default_img', ['=' => 1])
            ->joinInner('catalog_media_gallery', 'main_table.product_id=catalog_media_gallery.product_id', ["image" => "file_path"]);
        $data = $product->getData();
        $attribute = Mage::getModel('catalog/product_attribute')
            ->getCollection()
            ->addFieldToFilter('main_table.product_id', ['=' => $id])
            ->joinInner('catalog_attribute', 'main_table.attribute_id=catalog_attribute.attribute_id', ["name" => "name"]);
        $data1 = $attribute->getData();
        $data2 = $mainimage->getData();
        return [$data, $data1, $data2];
    }
}
