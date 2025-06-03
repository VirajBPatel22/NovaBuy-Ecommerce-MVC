<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class Customer_Controller_Account extends Core_Controller_Customer_Action

{
    protected $_allowedActions = ['login',
        'registration',
        'forget', 
        'forgetpassword', 
        'registerCustomer',
        'validate',
        'verifyotp',
        'verify',
        'createpassword',
        'newPassword'
    ];
    public function loginAction()
    {
        $layout =  Mage::getBlock('core/layout');
        $view = $layout->createBlock('Customer/Account_Login')
            ->setTemplate('customer/account/login.phtml');
        $layout->getChild('content')->addChild('login', $view);
        $layout->toHtml();
    }
    public function registrationAction()
    {
        $layout =  Mage::getBlock('core/layout');
        $view = $layout->createBlock('Customer/Account_Registration')
            ->setTemplate('customer/account/registration.phtml');
        $layout->getChild('content')
            ->addChild('registration', $view);
        $layout->toHtml();
    }
    public function registerCustomerAction()
    {
        $customer = Mage::getModel("core/request")->getParam("customer");
        $emaileexists = Mage::getModel('customer/account')->getCollection()
            ->addFieldToFilter('email', ['=' => $customer['email']])
            ->getdata();
        if ($emaileexists) {
            header("Location:http://localhost/ecommerecemvc/customer/account/login");
        } else {
            $customer = Mage::getSingleton("customer/session")
                ->getCustomer()
                ->setData($customer)
                ->Save();

            header("Location:http://localhost/ecommerecemvc/customer/account/login");
        }
    }

    public function validateAction()
    {
        $request = Mage::getModel('core/request')
            ->getParams();
        $customerModel = Mage::getModel('customer/account')
            ->load($request['customer']['email'], 'email');
        if (is_null($customerModel->getData())) {
            header("Location:http://localhost/ecommerecemvc/customer/account/login");
        } else if ($customerModel->getPassword() != $request['customer']['password']) {
            header("Location:http://localhost/ecommerecemvc/customer/account/login");
        } else {
            $session = Mage::getModel('core/session')
                ->set('customer_id', $customerModel->getCustomerId());
            header("Location:http://localhost/ecommerecemvc/");

        }
    }
    public function logoutAction()
    {
        Mage::getSingleton('core/session')
            ->remove('customer_id');
        header("Location:http://localhost/ecommerecemvc/");
    }
    public function forgetpasswordAction()
    {
        $layout =  Mage::getBlock('core/layout');
        $view = $layout->createBlock('Customer/Account_Forgetpassword')
            ->setTemplate('customer/account/forgetpassword.phtml');
        $layout->getChild('content')
            ->addChild('forgetpassword', $view);
        $layout->toHtml();
    }

    public function forgetAction()
    {
        $request = Mage::getModel('core/request');
        $email = $request->getParam('email');

        $customer = Mage::getModel('customer/account')
            ->getCollection()
            ->addFieldToFilter('email', ['=' => $email])
            ->getdata();

        if (empty($customer)) {
            header("Location: http://localhost/ecommerecemvc/customer/account/forgetpassword");
        } else {
            $otp = rand(1000, 9999);
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'email';//wwrite only email for git push security
                $mail->Password = 'App code of that email ';//write these for security
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Email Details
                $mail->setFrom('email', 'username');//not write the correct for security 
                $mail->addAddress($email);
                $mail->Subject = "Your One-Time Password (OTP)";
                $mail->Body = "Dear User,\n\nYour OTP is: $otp\n\nThis OTP is valid for 10 minutes. Do not share it.";
                $mail->send();

                // Store OTP in session
                $session = Mage::getSingleton('core/session');
                $session->set('otp', $otp);
                $session->set('otp_time', time());
                $session->set('email', $email);
                header("Location: http://localhost/ecommerecemvc/customer/account/verifyotp");
            } catch (Exception $e) {
                print("that was error");
            }
        }
    }
    public function verifyotpAction()
    {
        $layout =  Mage::getBlock('core/layout');
        $view = $layout->createBlock('Customer/Account_verifyotp')
            ->setTemplate('customer/account/verifyotp.phtml');
        $layout->getChild('content')
            ->addChild('verifyotp', $view);
        $layout->toHtml();
    }

    public function verifyAction()
    {
        $request = Mage::getModel('core/request');
        $enteredOtp = $request->getParam('otp');


        if (empty($enteredOtp)) {
            header("Location: http://localhost/ecommerecemvc/customer/account/verify");
        }

        $session = Mage::getSingleton('core/session');
        $storedOtp = $session->get('otp');
        $otpTime = $session->get('otp_time');
        $email = $session->get('email');

        if (empty($storedOtp) || empty($otpTime) || empty($email)) {
            header("Location: http://localhost/ecommerecemvc/customer/account/forgetpassword");
        }

        if (time() - $otpTime > 600) {
            header("Location: http://localhost/ecommerecemvc/customer/account/forgetpassword");
        }

        if ($enteredOtp == $storedOtp) {
            header("Location:http://localhost/ecommerecemvc/customer/account/createpassword");
        } else {
            header("Location: http://localhost/ecommerecemvc/customer/account/verifyotp");
        }
    }
    public function createpasswordAction()
    {

        $layout =  Mage::getBlock('core/layout');
        $view = $layout->createBlock('Customer/Account_Createpassword')
            ->setTemplate('customer/account/createpassword.phtml');
        $layout->getChild('content')
            ->addChild('createpassword', $view);
        $layout->toHtml();
    }
    public function newPasswordAction()
    {
        $session = Mage::getSingleton('core/session');
        $request = Mage::getModel('core/request');
        $password = $request->getParam('password');
        $email = $session->get('email');
        if (empty($password) || empty($email)) {
            header("Location: http://localhost/ecommerecemvc/customer/account/forgetpassword");
        }

        try {
            $customerModel = Mage::getModel('customer/account')
                ->load($email, 'email');

            $customerData = $customerModel->getData();
            $customerData['password'] = $password;
            $setpassword = Mage::getModel('customer/account')
                ->setdata($customerData)
                ->save();

            $session->remove('otp');
            $session->remove('otp_time');
            $session->remove('email');

            header("Location: http://localhost/ecommerecemvc/customer/account/login");
        } catch (Exception $e) {
            header("Location: http://localhost/ecommerecemvc/customer/account/forgetpassword");
        }
    }
}
