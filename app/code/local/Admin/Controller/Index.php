<?php

Class Admin_Controller_Index extends Core_Controller_Admin_Action{
    public function IndexAction(){
        $view = $this->getLayout()
            ->createBlock('Admin/Index')
            ->setTemplate('admin/index.phtml');
        $this->getLayout()
            ->getChild('content')
            ->addChild('index', $view);
        $this->getLayout()->toHtml();
    }   
}
?>