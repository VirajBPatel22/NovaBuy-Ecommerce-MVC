<?php

class Core_Controller_Front_Action {
    public function getRequest(){
        return Mage::getModel('core/request');
    }
    public function getSession(){
        return Mage::getSingleton('core/session');
    }
    public function redirect($url){
        header('location:'.Mage::getBaseUrl().$url);
        return $this;
    }

}

?>