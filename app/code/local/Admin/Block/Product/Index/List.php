<?php

class Admin_Block_Product_Index_List extends Admin_Block_Widget_Grid
{
    public function __construct()
    {
        $this->addColumns(
            'product_id',
            [
                'render' => 'number',
                'filter' => 'Number',
                'data_index' => 'product_id',
                'lable' => 'product id',
            ]
        );
        $this->addColumns('Name', [
            'render' => 'text',
            'filter' => 'Text',
            'data_index' => 'name',
            'lable' => 'product name',
        ]);
        $this->addColumns('Description', [
            'render' => 'text',
            'filter' => 'Text',
            'data_index' => 'description',
            'lable' => 'product description',
        ]);
        $this->addColumns('sku', [
            'render' => 'text',
            'filter' => 'Text',
            'data_index' => 'sku',
            'lable' => 'sku',
        ]);
        $this->addColumns('price', [
            'render' => 'number',
            'filter' => 'Number',
            'data_index' => 'price',
            'lable' => 'price',
        ]);
        $this->addColumns('stock_quantity', [
            'render' => 'number',
            'filter' => 'Number',
            'data_index' => 'stock_quantity',
            'lable' => 'stock Quantity',
        ]);
        $this->addColumns('category name', [
            'render' => 'text',
            'filter' => 'Text',
            'data_index' => 'category_name',
            'lable' => 'category name',
        ]);
        $this->addColumns('color', [
            'render' => 'text',
            'filter' => 'Text',
            'data_index' => 'color',
            'lable' => 'color',
        ]);
        $this->addColumns('material', [
            'render' => 'text',
            'filter' => 'Text',
            'data_index' => 'material',
            'lable' => 'material',
        ]);

        $this->addColumns('edit', [
            'filter' => 'edit',
            'columns' => 'Link',
            'primary_key' => 'product_id',
            'callback' => 'getediturl',
            'lable' => 'edit',
        ]);
        $this->addColumns('delete', [
            'filter' => 'delete',
            'columns' => 'Link',
            'primary_key' => 'category_id',
            'callback' => 'getdeleteurl',
            'lable' => 'delete',
        ]);

        // $this->setCollection(Mage::getModel('catalog/product'));
        // echo "<pre>";
        // print_r($this);
        // echo "</pre>";
        // echo "<pre>";
        // print_r($this);
        // echo "</pre>";
        parent::__construct();

        $this->init();
    }
    public function init()
    {
        $layout = $this->getLayout();
        $toolbar_block = $layout->createBlock("Admin/grid_toolbar")
            ->setTemplate("admin/grid/toolbar.phtml");
        $this->addChild("toolbar", $toolbar_block);
        $product = Mage::getModel("catalog/product");

        if (count(Mage::getModel('core/request')->getQuery())) {
            $list = Mage::getModel('core/request')->getQuery();
            $this->_collection = $product->getCollection()
                ->joinleft(["cc" => "catalog_category"], " cc.category_id = main_table.category_id ", ["category_name" => "name"])
                ->addAttributeToSelect(["color", "material", "size"]);

                foreach($list as $key=>$value){
                    if($key == 'page' || $key == 'limit'){
                        continue;
                    }
                    else{

                        if(is_array($value)){
                            if(!(empty($value['min']) && empty($value['max']))){
                                echo $key;
                                
                                $this->_collection->addFieldToFilter('main_table.'.$key, ['between' => [$list[$key]['min'], $list[$key]['max']]]);
                            }
                        }else{
                            if(!empty($value)){
                                $this->_collection->addFieldToFilter('main_table.'.$key,$value);
                            }
                        }
                    }
                }
        } else {
            // echo "123";
            // die;
            $this->_collection = $product->getCollection()
                ->joinleft(["cc" => "catalog_category"], " cc.category_id = main_table.category_id ", ["category_name" => "name"])
                ->addAttributeToSelect(["color", "material", "size"]);

        }
        $toolbar_block->prepareToolbar();
    }
    public function getediturl($data)
    {
        return $this->getUrl('*/*/new') . '/?id=' . $data['product_id'];
    }
    public function getdeleteurl($data)
    {
        return $this->getUrl('*/*/delete') . '/?id=' . $data['product_id'];
    }

    public function listdata()
    {
        return $this->getCollection()
            ->getData();
    }
    public function getCollection()
    {
        return $this->_collection;
    }
}


// class Admin_Block_Product_Index_List extends Core_Block_Template
// {
//     protected $_collection;
//     public function __construct()
//     {

//         $this->init();
//     }
//     public function init()
//     {
//         $layout=$this->getLayout();
//         $toolbar_block=$layout->createBlock("Admin/grid_toolbar")
//                         ->setTemplate("admin/grid/toolbar.phtml");
        
//         $product = Mage::getModel("catalog/product");
//        $this->addChild("toolbar",$toolbar_block);
    

//         $this->_collection = $product->getCollection();
        
//         $toolbar_block->prepareToolbar();
//     }
    
//     public function listdata()
//     {
//         return $this->getCollection()
//             ->getData();
        
//     }
//     public function getCollection()
//     {
//         return $this->_collection;
//     }
// }
