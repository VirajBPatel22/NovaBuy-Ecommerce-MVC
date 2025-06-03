<?php
class Paypal_Init
{

    public function __construct()
    {
        echo "123";
    }
    public function getApiContext()
    {

        $paypal = new Paypal\Rest\ApiContext(
            new PayPal\Auth\OAuthTokenCredential(
                // 'AWkpuOJ9kF4McXPr4qlyFYVPLnMWzWPS8yuQWCmvEfkaKR_XmY9-KWfsNO50iljMI_bRscgBubc8O6H8',
                // 'EAwkGDcX-ITy6IYiw1rNkB-8aoMTwgjNTNb4BC991opub9a-xmT3a-y0EFUxp9EWSJmOHuFOsUYyzTix'

                // 'AejusOzo_tUcq8QWElCFFmk2bwxNCcTBwnuYeuaJyxL4R2lXl6zizKYHgJcBMKYvG2IwlfJpzBe4aOyB',

                // 'EKygYzkWWPlKaqiBSDi3P_bD5YQFFIhsWfYDuHMTIHvrBjDnPfUG3MlmK3Fn7pgT83QsC2GiIxf7E9Rg'

                'AWkpuOJ9kF4McXPr4qlyFYVPLnMWzWPS8yuQWCmvEfkaKR_XmY9-KWfsNO50iljMI_bRscgBubc8O6H8',
                'EAwkGDcX-ITy6IYiw1rNkB-8aoMTwgjNTNb4BC991opub9a-xmT3a-y0EFUxp9EWSJmOHuFOsUYyzTix'
                // 'EKygYzkWWPlKaqiBSDi3P_bD5YQFFIhsWfYDuHMTIHvrBjDnPfUG3MlmK3Fn7pgT83QsC2GiIxf7E9Rg'


            )
        );

        $paypal->setConfig([
            'mode' => 'sandbox', // Change to 'live' in production
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => 'PayPal.log',
            'log.LogLevel' => 'DEBUG'
        ]);
        return $paypal;
    }
}
