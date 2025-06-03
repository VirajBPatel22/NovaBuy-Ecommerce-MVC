<?php
class Catalog_Model_Filter extends Core_Model_Abstract
{
    public function getProductColllection()
    {
        $collection = Mage::getModel('catalog/product')->getCollection();
        // echo "<pre>";
        // print_r($collection);
        // echo "</pre>";
        // die();
        $this->applyFilter($collection);
        return $collection;
    }
    public function applyFilter($collection)
    {
        $request = Mage::getSingleton("core/request");

        $parameter = $request->getQuery();
        if (isset($parameter["id"])) {
            $category_ids = is_array($parameter["id"])?$parameter['id']:explode(',',$parameter['id']);
            $collection->addCategoryFilter($category_ids);
            unset($parameter["id"]);
        }
        if(!empty($parameter)){
            $attribute_collection = Mage::getModel("catalog/attribute")
                ->getCollection()
                ->addFieldToFilter("name", ['IN' => array_keys($parameter)]);
            foreach ($attribute_collection->getData() as $attributedata) {
               
                $collection->addAttributeToFilter($attributedata->getName(), $parameter[$attributedata->getName()]);
            }

        }
    }
}
