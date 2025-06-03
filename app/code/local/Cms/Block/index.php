<?php
class Cms_Block_Index extends Core_Block_Template{
    public function getProducts(){
        $id = Mage::getModel('core/request')::getQuery('id'); 
        $product = Mage:: getModel('catalog/product')
            ->getCollection()
            ->joinInner('catalog_media_gallery','main_table.product_id=catalog_media_gallery.product_id',["image"=>"file_path"])
            ->addFieldToFilter('catalog_media_gallery.default_img',['='=>1]);
        $data = $product->getData();
        return $data;
    }

}
?>