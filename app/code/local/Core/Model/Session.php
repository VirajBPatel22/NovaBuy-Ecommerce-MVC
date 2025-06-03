<?php
class Core_Model_Session{
    protected $_messages = [
        'messages'=>["success"=>"successfuly",
                "error"=>"error occure",
                "warning"=>"get warning"
        ]
    ];
    public function __construct()
    {
        @session_start();
    }
    public function getId(){
        return session_id();
    }
    public function set($key,$value){
        $_SESSION[$key]=$value;

    }
    public function get($key){
      
        // return $_SESSION[$key];
        return isset($_SESSION[$key])?$_SESSION[$key]:null;


    }
    public function remove($key){
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
        }
        else{
            return "";
        }
    }
    public function removemessage($key){
        if(isset($_SESSION['message'][$key])){
            unset($_SESSION['message'][$key]);
        }
        else{
            return "";
        }
    }
}


?>