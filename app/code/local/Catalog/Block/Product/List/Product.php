<?php
class Catalog_Block_Product_List_Product extends Catalog_Block_Product_List
{
    protected $_collection;
    public function __construct()
    {

        $layout = $this->getLayout();

        $Toolbar = $layout->createBlock('Catalog/Grid_Toolbar')
            ->setTemplate('catalog/grid/toolbar.phtml');
        // die;
        $this->addChild('Toolbar', $Toolbar);
        // $this->init();

        $this->setTemplate('catalog/product/list/product.phtml');
    }
    public function getCollection()
    {
        return $this->_collection;
    }
    public function getProduct()
    {
        $id = Mage::getModel('core/request')::getQuery('id');
        $product = Mage::getSingleton('catalog/filter')->getProductColllection();
        $this->_collection = $product;
        // echo "<pre>";
        // print_r($this);
        // echo "</pre>";
        $this->getChild('Toolbar')->prepareToolbar();

        $data = $this->_collection->getData();
        // print_r($data);
        foreach($data as $img){
            // echo "<pre>";

            $model = Mage::getModel('catalog/media')->getCollection()->addFieldToFilter('product_id',$img->getProductId())
            ->addFieldToFilter('default_img',1)->getData();
            $filepath = $model[0]->getFilePath();
            $img->setImage($filepath);
            // print_r($filepath);
            // echo "</pre>";
        }
        // print_r($data[0]->getProductId());
        // die();

        return $data;
    }


}
