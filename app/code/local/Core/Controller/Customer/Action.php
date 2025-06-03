<?php
class Core_Controller_Customer_Action extends Core_Controller_Front_Action{
    protected $_allowedActions =[];
    public function __construct()
    {
        $this->_init();
    }
    protected function _init(){
        if(!in_array($this->getRequest()->getActionName(),$this->_allowedActions) ){
            if(is_null($this->getSession()->get('customer_id'))){
                $this->redirect("customer/account/login");
            }
        }
    }
	public function getLayout(){
        return Mage::getBlockSingleton('core/Layout');
    }
}

?>