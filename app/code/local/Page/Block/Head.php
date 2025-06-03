<?php
class Page_Block_Head extends Core_Block_Template{
    protected $_css;
    protected $_js;
    public function __construct(){
        $this->setTemplate('page/head.phtml');

    }
    public function addCss($file){
        // echo" ";
        // //print_r($file);
        // //print_r("hi");
        // die();
        $this->_css[]=$file;
        // echo "$file";
        return $this;
        
    }
    public function addJs($file){
        $this->_js[]=$file;
        // echo $file;
        return $this;

    }
    public function getJs(){
        return $this->_js;

    }
    public function getCss(){
        return $this->_css;

    }
}

?>