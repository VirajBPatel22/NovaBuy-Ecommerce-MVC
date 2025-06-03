<?php
class Admin_Controller_Account extends Core_Controller_Admin_Action
{
    protected $_allowedActions = [
        'login',
        'loginPost'
    ];
    public function loginAction()
    {
        
        $layout = $this->getLayout();
        $view = $layout->createBlock('Admin/Account_Login')
            ->setTemplate('admin/account/login.phtml');
            $layout->getChild('content')
                ->addchild('login', $view);
            $layout->toHtml();
    }
    public function loginPostAction()
    {
        $session = Mage::getSingleton('core/session');
        $params = $this->getRequest()->getParam('admin_user');
        $adminUser = Mage::getModel('admin/user');
        $adminData = $adminUser->load($params['username'],"username");
        if($params['username']== $adminData->getUsername() && $params['password_hash']==$adminData->getPasswordHash())
        {
           $session->set('login',$adminData->getAdminId());
           $session->set('id',$adminData->getAdminId());
           $this->redirect("admin/product_index/list");
        }
        else{
            $session->remove("login");
            $session->remove('id');
            $this->redirect('admin/account/login');
        }
    }
 
    public function logoutAction(){
        $sessionModel = Mage::getSingleton('core/session');
        $sessionModel->remove("login");
        $sessionModel->remove("id");
    }
}
