<?php

class Mage {
    protected static $_registry=[];
    public static function init() {
        $front = new Core_Controller_Front();
        $front->init();
    }

    public static function getModel($class) {
        $class = str_replace("/", "_Model_", $class);
        $class = ucwords($class, "_");
        return new $class;
    }

    public static function getBlock($class) {

        $class = str_replace("/", "_Block_", $class);
        $class = ucwords($class, "_");
		//echo $class;
		//die;
        return new $class;
    }
    
    public static function getBaseDir() {
        return 'C:\xampp\htdocs\ecommerecemvc';
    }
    
    public static function getBaseUrl() {
        return 'http://localhost/ecommerecemvc/';
    }
    public static function getSingleton($className)
    {
        $class = str_replace("/", "_Model_", $className);
        $class = ucwords($class, "_");
        if (isset(self::$_registry[$class])) {
            return self::$_registry[$class];
        } else {
            return self::$_registry[$class] = new $class;
        }
    }
    public static function getBlockSingleton($className)
    {
        $class = str_replace("/", "_Block_", $className);
        $class = ucwords($class, "_");
        if (isset(self::$_registry[$class])) {
            return self::$_registry[$class];
        } else {
            return self::$_registry[$class] = new $class;
        }
        echo $class;
        return new $class;
    }
    public static function log($data){
        echo"<pre>";
        print_r($data);
        echo"</pre>";
    }
}

?>