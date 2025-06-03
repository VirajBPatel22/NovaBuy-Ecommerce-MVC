<?php

class Practics_Controller_Config 
{
    public function indexAction()
    {
        $layout = Mage::getBlock('core/Layout');
        $view = $layout->createBlock('Practics/Config');
        $layout->getChild('content')->addChild('config', $view)->toHtml();
    }
    public function saveAction(){
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
            $file = $_FILES["image"]["tmp_name"];
            $handle = fopen($file, "r");
            $header = fgetcsv($handle);
            while (($data = fgetcsv($handle)) !== FALSE) {
                $model = Mage::getModel('practics/system_config');
                for($i=0;$i<count($header);$i++){
                    $colname = $header[$i];
                    $val = $data[$i];
                    $setmethod = 'set'.str_replace(' ', '', ucwords(str_replace('_', ' ', $colname)));
                    $model->$setmethod($val);
                }
                $model->save();
            }
        }
        header('location:http://localhost/ecommerecemvc/practics/config/index');
    }
    
}