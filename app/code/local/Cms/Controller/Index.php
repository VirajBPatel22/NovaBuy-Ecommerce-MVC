<?php

class Cms_Controller_Index extends Core_Controller_Front_Action {
    public function indexAction() {
        // echo get_class() . "----" . __FUNCTION__;
        $product = Mage::getModel('catalog/product');
        $layout =  Mage::getBlock('core/layout');
        $view = $layout->createBlock('Cms/index')
        ->setTemplate('cms\slider.phtml');
        // //print_r($view->toHtml());
        
        $layout->getChild('content')->addChild('list', $view);
        $layout->toHtml();
    }
}

?>