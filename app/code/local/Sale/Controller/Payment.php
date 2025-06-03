<?php
class Sale_Controller_Payment
{
    public function paymentAction()
    {
        $cartModel = Mage::getSingleton("checkout/session")
            ->getCart();
        $paypal = new Paypal_Init();
        $paypal = $paypal->getApiContext();

        $payer = new PayPal\Api\Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new PayPal\Api\Amount();
        $amount->setTotal($cartModel->getTotalAmount() * 0.001)->setCurrency('USD');

        $transaction = new PayPal\Api\Transaction();
        $transaction->setAmount($amount)->setDescription('Payment for cart_id :' . $cartModel->getCartId());

        $redirectUrls = new PayPal\Api\RedirectUrls();
        $redirectUrls->setReturnUrl("http://localhost/ecommerecemvc/sale/payment/success/")
            ->setCancelUrl("http://localhost/ecommerecemvc/sale/payment/cancle/");

        $payment = new PayPal\Api\Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions([$transaction]);

        try {
            $payment->create($paypal);
            header("Location: " . $payment->getApprovalLink());
        } catch (Exception $ex) {
            echo "Error: " . $ex->getMessage();
        }
    }
    public function successAction()
    {
        $paypal = new Paypal\Rest\ApiContext(
            new PayPal\Auth\OAuthTokenCredential(
                'Replace with your Client ID',
                'Replace with your Client Secret'
            )
        );

        $paypal->setConfig([
            'mode' => 'sandbox',
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => 'PayPal.log',
            'log.LogLevel' => 'DEBUG'
        ]);

        if (!isset($_GET['paymentId'], $_GET['PayerID'])) {
            die("Invalid request");
        }

        $paymentId = $_GET['paymentId'];
        $payerId = $_GET['PayerID'];

        $payment = PayPal\Api\Payment::get($paymentId, $paypal);
        $execution = new PayPal\Api\PaymentExecution();
        $execution->setPayerId($payerId);

        try {
            $payment->execute($execution, $paypal);
            echo "Payment successful! Transaction ID: " . $payment->getId();
        } catch (Exception $ex) {
            echo "Error: " . $ex->getMessage();
        }
       

        header("Location: http://localhost/ecommerecemvc/checkout/order/placeorder/?Transaction_id=".$payment->getId());
    }

    public function refundAction()
    {
        $orderId = Mage::getModel('core/request')
            ->getQuery('order_id');
        $paymentModel = Mage::getModel('sale/order_payment')
            ->load($orderId, 'order_id');
        // echo "<pre>";
        // print_r($paymentModel);
        if ($paymentModel->getPaymentMethod() == 'Paypal') {
            $status = $this->refund($paymentModel->getTransactionId(), $paymentModel->getAmount()/1000);
            if ($status) {
                $paymentModel->setStatus('refund')
                    ->save();
                Mage::getSingleton('core/message')->addMessage('success', 'refund successfully!');
            } else {
                Mage::getSingleton('core/message')->addMessage('error', 'refund unsuccessfully!');
            }
        } else {
            if ($paymentModel->setStatus('refund')
                ->save()
            ) {
                Mage::getSingleton('core/message')->addMessage('success', 'refund successfully!');
            } else {
                Mage::getSingleton('core/message')->addMessage('error', 'refund unsuccessfully!');
            }
        }
        header('location:http://localhost/ecommerecemvc/checkout/order/yourOrder');
    }
 
public function refund($paymentId,$refundAmount)
    {
        $paypal = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                  'Replace with your Client ID',
                'Replace with your Client Secret'
            )
        );
 
        $paypal->setConfig([
            'mode' => 'sandbox', // Change to 'live' in production
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => 'PayPal.log',
            'log.LogLevel' => 'DEBUG'
        ]);
 
 
        try {
            $payment = \PayPal\Api\Payment::get($paymentId, $paypal);
            $transactions = $payment->getTransactions();
 
            if (empty($transactions)) {
                die("No transactions found for payment ID: " . $paymentId);
            }
 
            $relatedResources = $transactions[0]->getRelatedResources();
 
            if (empty($relatedResources)) {
                die("No related resources found for payment ID: " . $paymentId);
            }
 
            $sale = $relatedResources[0]->getSale();
 
            if (!$sale) {
                die("No sale object found for payment ID: " . $paymentId);
            }
 
            $refund = new \PayPal\Api\Refund();
            $amount = new \PayPal\Api\Amount();
            $amount->setCurrency('USD')->setTotal($refundAmount); // Change USD if needed
            $refund->setAmount($amount);
 
            $refundedSale = $sale->refund($refund, $paypal);
 
            echo "Refund successful. Refund ID: " . $refundedSale->getId();
            return 1;
        } catch (Exception $ex) {
            echo "Refund error: " . $ex->getMessage();
            return 0;
 
        }
    }
 
}
