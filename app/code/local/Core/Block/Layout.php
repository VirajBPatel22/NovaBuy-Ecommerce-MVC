<?php

class Core_Block_Layout extends Core_Block_Template
{
    public function __construct()
    {
		
        $this->prepareChildren();
        $this->prepareJsCss();
    
        
        $this->_template = 'page\root.phtml';
    }
    public function prepareChildren() {
        // echo "456";
        // die;
        $header = $this->createBlock('page/header');    
        $this->addChild('header',$header);
        $message = $this->createBlock('Core/Message');    
        $this->addChild('message',$message);
        $content = $this->createBlock('page/content');    
        $this->addChild('content',$content);
        $footer = $this->createBlock('page/footer');    
        $this->addChild('footer',$footer);
        $head = $this->createBlock('page/head');
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
