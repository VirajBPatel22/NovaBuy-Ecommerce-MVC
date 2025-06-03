<?php

class Catalog_Controller_Category {
    public function listAction() {
        $layout =  Mage::getBlock('core/layout');
        $view = $layout->createBlock('catalog/category_List')
                    ->setTemplate('catalog/category/list.phtml');
        $layout->getChild('content')->addChild('list', $view);
        $layout->toHtml();

    }
}

?>