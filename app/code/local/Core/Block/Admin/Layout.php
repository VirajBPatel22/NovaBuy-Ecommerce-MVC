<?php

class Core_Block_Admin_Layout extends Core_Block_Template
{
    public function __construct()
    {
        $this->prepareChildren();
        $this->prepareJsCss();
    
        
        $this->_template = 'page\root.phtml';
    }
    public function prepareChildren() {
        
        $header = $this->createBlock('page/header')
            ->setTemplate("page/admin/header.phtml");    
        $this->addChild('header',$header);
        $message = $this->createBlock('Core/Message');    
         $this->addChild('message',$message);
        $content = $this->createBlock('page/content')
            ->setTemplate("page/admin/content.phtml");    
        $this->addChild('content',$content);
        $footer = $this->createBlock('page/footer')
            ->setTemplate("page/admin/footer.phtml");    
        $this->addChild('footer',$footer);
        $head = $this->createBlock('page/head')
            ->setTemplate("page/admin/head.phtml");
        $this->addChild('head',$head);
    }
    public function prepareJsCss(){
        $head = $this->getChild('head');
        $head->addJs('page/common.js');
        $head->addCss('page/common.css');
        
    }
    public function createBlock($block)
    {
        return Mage::getBlock($block);
    }
}
