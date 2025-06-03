<?php

class Admin_Block_Category_Index_List extends Admin_Block_Widget_Grid
{    
    protected $_collection;
    public function __construct()
    {
        $this->addColumns('category_id', [
            'render' => 'number',
            'filter' => 'Number',
            'data_index' => 'category_id',
            'lable' => 'category id',
        ]);
        $this->addColumns('Name', [
            'render' => 'text',
            'filter' => 'Text',
            'data_index' => 'name',
            'lable' => 'category name',
        ]);
        $this->addColumns('Description', [
            'render' => 'text',
            'filter' => 'Text',
            'data_index' => 'description',
            'lable' => 'category description',
        ]);
        $this->addColumns('parentId', [
            'render' => 'number',
            'filter' => 'Number',
            'data_index' => 'parent_id',
            'lable' => 'parent id ',
        ]);
        $this->addColumns('edit', [
            'filter' => 'edit',
            'columns' => 'Link',
            'primary_key' => 'category_id',
            'callback' =>'getediturl',
            'data_index' => 'edit',
            'lable' => 'edit',
        ]);
        $this->addColumns('delete', [
            'filter' => 'delete',
            'columns' => 'Link',
            'callback' =>'getdeleteurl',
            'primary_key' => 'category_id',
            'data_index' => 'delete',
            'lable' => 'delete',
        ]);

        $this->setCollection( Mage::getModel('catalog/category')
        ->getCollection());
        parent :: __construct();

        $this->init();
    }
    public function getediturl($data){
        return $this->getUrl('*/*/new').'/?id='.$data['category_id'];
    }
    public function getdeleteurl($data){
        return $this->getUrl('*/*/delete').'/?id='.$data['category_id'];
    }
    public function init()
    {
        $layout = $this->getLayout();
        $toolbar_block = $layout->createBlock("Admin/grid_toolbar")
            ->setTemplate("admin/grid/toolbar.phtml");
        $this->addChild("toolbar", $toolbar_block);

        $this->_collection =  Mage::getModel("catalog/category")
            ->getCollection();

        $toolbar_block->prepareToolbar();
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
