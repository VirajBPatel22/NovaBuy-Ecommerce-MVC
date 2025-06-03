<?php
class Core_Controller_Admin_Action extends Core_Controller_Front_Action{
    protected $_allowedActions =[];
    protected $_roleAllowedAction = ['dashboard','event','replay'];
    public function __construct()
    {
        $this->_init();
    }
    protected function _init(){
        $isLogin = $this->getSession()
        ->get('login');
        $id = $this->getSession()->get('id');
        $role_id = Mage::getModel('admin/user')->load($id)->getRoleId();
        if(!in_array($this->getRequest()->getActionName(),$this->_allowedActions) ){
            if($isLogin === $id && !empty($isLogin)){
                $adminId  = Mage::getSingleton('admin/user')
                                ->load($id);
             
                $roleId = Mage::getSingleton('admin/role')
                              ->load($adminId->getRoleId());
                $json = json_decode($roleId->getPermissions(),true)['menu'];
                foreach($json as $value){
                    $this->_roleAllowedAction[] = $value;
                }
                $controller = $this->getRequest()->getControllerName();
                $controllername = explode('_',$controller);
                if(!in_array($controllername[0],$this->_roleAllowedAction))
                {
                    $this->redirect("admin/account/login");
                }
    
            }
            else{
                
                $this->redirect("admin/account/login");
            }
        }   
    }



    public function getLayout(){
        return Mage::getBlockSingleton('core/Admin_Layout');
    }
}

?>
