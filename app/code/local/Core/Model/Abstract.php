<?php
class Core_Model_Abstract
{
    protected $_resourceClassName ="";
    protected $_CollectionClassName="";
    
    protected $_data = null;
    public function init()
    {
        // echo "$this";
    }
    public function __construct()

    {
       
        $this->init();
    }

    public function getResource()
    {
        return new $this->_resourceClassName;
    }
    public function __get($name){
        return isset($this->_data[$name])?$this->_data[$name]:"";

    }
    public function __set($name, $value)
    {
        $this->_data[$name]=$value;
        
    }
    public function setData($data){
        $this->_data = $data;
        return $this;
    }
    public function getData(){

        
        return $this->_data;
    }
    public function __call($method, $args)
    {
        // //print_r($args);
        $f = substr($method, 0, 3);
        if ($f == 'get' && strpos($method,"_")===false) {
            $value = substr($method, 3);
            $field = $this->camelToSnake($value);

            return isset($this->_data[$field]) ? $this->_data[$field] : "";
        }
        else if($f=='set'){
            $value = substr($method, 3);
            $field = $this->camelToSnake($value);
            $this->_data[$field] =$args[0];
            return $this;

        }
        throw new Exception('invalid method');
    }
    function camelToSnake($input)
    {

        $snakeCase = preg_replace_callback(
            '/[A-Z]/',
            function ($matches) {
                return '_' . strtolower($matches[0]);
            },
            $input
        );
        return ltrim($snakeCase, '_');
    }
    // public function load($value,$field){

    //     $this->_data = $this->getResource()->load($value,$field);
    //     $this->_afterLoad();
        
    //     return $this;
    // }
    public function load($value, $field=null) 

    {
        $this->_data =  $this->getResource()->load($value, $field);
        $this->_afterLoad(); 
        return $this; 
    }
    protected function _afterLoad(){
        
    }
    public function getcollection(){
    $collection = new $this->_CollectionClassName;   
        $collection->setResource($this->getResource())
        ->setModel($this)
        ->select();
        
        return $collection;
    }
    public function save(){
       
        // die();
        $this->_beforeSave();
        $this->getResource()->save($this);
        $this->_afterSave();
        return $this;
    }
    
    protected function _beforeSave(){
        return $this;
    }
    protected function _afterSave(){
        // return $this;
    }
    public function delete(){
        $this->getResource()->delete($this);
        return $this;
    }

}
