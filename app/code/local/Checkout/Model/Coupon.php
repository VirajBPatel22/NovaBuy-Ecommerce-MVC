<?php
class Checkout_Model_Coupon extends Core_Model_Abstract
{
    protected $_coupon = [
        "viraj123"=>"10%",
        "first100"=>'100'

    ];
    public function getAllCoupon(){
        return $this->_coupon;

    }
    public function CalculateDiscount($coupon_code,$totalprice){
        $value = $this->_coupon[$coupon_code];
        if(str_contains($value, '%')){
            $totalDiscount = (($totalprice*(int)substr($value,0,-1))/100);

        }
        else{
            $totalDiscount = $value;
        }
        return $totalDiscount;
        // print('in calculate discount');
        // print_r($value);
        // die();


    }


}

?>
