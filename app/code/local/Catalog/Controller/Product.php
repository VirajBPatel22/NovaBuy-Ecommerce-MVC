<?php

class Catalog_Controller_Product
{
    public function viewAction()
    {

        $product = Mage::getModel('catalog/product');
        // $product->getResourceModel();
        $layout =  Mage::getBlock('core/layout');
        $view = $layout->createBlock('catalog/product_view')
            ->setTemplate('catalog/product/view.phtml');
        $layout->getChild('content')->addChild('view', $view);
        $layout->toHtml();
    }       


    public function listAction()
    {
        $layout =  Mage::getBlockSingleton('Core/Layout');
        $list = $layout->createBlock('catalog/product_list')
            ->setTemplate('catalog/product/List.phtml');
        $layout->getChild('head')->addJs('catalog/filter.js');
        $layout->getChild('content')->addChild('list', $list);
        $layout->toHtml();
    }
    public function Test1Action()
    {

        // $collections = Mage::getModel("catalog/filter")->getProductColllection();
        // $query = $collections->prepareQuery();
        // print($query);
        // $collections->PrepareQuery();
        // $collections = Mage::getSingleton('checkout/session')->getcart()
        //     ->getItemCollection()
        //     ->select(['sum(main_table.sub_total)'=>'SubTotal','item_id']);


        // Mage::log($collections->PrepareQuery());
        $paypal = new Paypal_Init();
        $paypal = $paypal->getApiContext();

        $payer = new PayPal\Api\Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new PayPal\Api\Amount();
        $amount->setTotal('11.00')->setCurrency('USD');

        $transaction = new PayPal\Api\Transaction();
        $transaction->setAmount($amount)->setDescription('Payment for Order #1234');

        $redirectUrls = new PayPal\Api\RedirectUrls();
        // $redirectUrls->setReturnUrl("http://yourwebsite.com/paypal_success.php")
        $redirectUrls->setReturnUrl("http://localhost/paypal/paypal_success.php")
            ->setCancelUrl("http://localhost/paypal/paypal_cancel.php");
        // C:\xampp\htdocs\paypal\paypal_success.php

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

        // $paypal = new ApiContext(
        //     new OAuthTokenCredential(
        //         'AWkpuOJ9kF4McXPr4qlyFYVPLnMWzWPS8yuQWCmvEfkaKR_XmY9-KWfsNO50iljMI_bRscgBubc8O6H8',
        //         'EAwkGDcX-ITy6IYiw1rNkB-8aoMTwgjNTNb4BC991opub9a-xmT3a-y0EFUxp9EWSJmOHuFOsUYyzTix'


        //     )
        // );
    }
    public function Test2Action()
    {
        $query = Mage::getModel('catalog/product')
            ->getCollection()
            ->select(['product_count' => 'COUNT(main_table.product_id)'])
            ->joinleft(["cc" => "catalog_category"], " cc.category_id = main_table.category_id", ["category_name" => "name"])
            ->groupBy(['main_table.category_id'])
            ->prepareQuery();
            print($query);
    }
    // public function TestAction(){
    //     $query = Mage::getModel('ems/registration')->getCollection()
    //     ->joinLeft(
    //     ['r' => 'registrations'],
    //     'r.event_id = main_table.event_id AND r.status IN ("approved", "pending")',
    //     ['total_registrations' => 'COUNT(r.reg_id)']
    //     )
    //     ->addFieldToFilter("STR_TO_DATE(main_table.date, '%Y-%m-%d')", ['>=' => date('Y-m-d')])
    //     ->groupBy(['event_id'])
    //     ->addFieldToFilter('COUNT(r.reg_id)', ['<' => 'main_table.capacity'])
    //     ->having('COUNT(r.reg_id)', '<', 'main_table.capacity')
    //     ->prepareQuery();
    //     echo "<pre>";
    //     print_r($query);
    //     echo "</pre>";
    // }
//     public function TestAction()
// {
//     $query = Mage::getModel('ems/registration')->getCollection()
//         ->joinLeft(
//             ['r' => 'registrations'],
//             'r.event_id = main_table.event_id AND r.status IN ("approved", "pending")',
//             ['total_registrations' => 'COUNT(r.reg_id)']
//         )
//         ->addFieldToFilter("STR_TO_DATE(main_table.date, '%Y-%m-%d')", ['>=' => date('Y-m-d')])
//         ->groupBy(['event_id'])
//         ->having('COUNT(r.reg_id)', '<', 'main_table.capacity')
//         ->prepareQuery();

//     echo "<pre>";
//     print_r($query);
//     echo "</pre>";
// }
public function Test3Action()
{
    $ans = [];
    $arr= [[2,2,4],[1,2,3],[4,6,7]];
    foreach($arr as $a){
      $chance = 0;
    $count = 0;
    for($i = 0;$i<count($a);$i++){
        if($a[$i]%2 ==0){
            $count++;
        }
    }
    if($count ==2){
        $chance = -1;
        echo $chance ;
        die;        
    }
    else{
        $max = max($a);
        $maxinde = array_search($max,$a);
        if($maxinde == 1){
            $a[$maxinde] = $a[$maxinde] - 1;
            $a[2]= $a[2]+1;
            $a[3]= $a[3]+1;
        }
        else if($maxinde == 2){
            $a[$maxinde] = $a[$maxinde] - 1;
            $a[1]= $a[1]+1;
            $a[3]= $a[3]+1;
        }
        if($maxinde == 3){
            $a[$maxinde] = $a[$maxinde] - 1;
            $a[2]= $a[2]+1;
            $a[1]= $a[1]+1;
        }
        $chance = $chance + 1;
    }
    $ans [] = $chance;
  }
}
public function TestAction(){
    // ;
    // ->joinLeft(
        //     ['r' => 'registrations'],
        //     'r.event_id = main_table.event_id',
        //     []
        // )
        // ->addFieldToFilter("main_table.date", ['>=' => date('Y-m-d')])
        // ->addFieldToFilter("r.status",['IN'=>['approved','pending']])
        // ->groupBy(['main_table.event_id'])
        // ->having('main_table.location', '=', '`ahmedabad`')
        // ->prepareQuery();
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        $query =  Mage::getModel('replay/comment')
            ->setAnswers("hfjerjf")
            ->setParentId(0)
            ->save();

        echo "<pre>";
        print_r($query);
        echo "</pre>";

}


}
