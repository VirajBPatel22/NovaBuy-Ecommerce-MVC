<?php
class Catalog_Model_Product extends Core_Model_Abstract
{
    public $status = [0 => 'disable', 1 => 'enable'];

    public function init()
    {
        $this->_resourceClassName = "Catalog_Model_Resource_Product";
        $this->_CollectionClassName = "Catalog_Model_Resource_Product_Collection";
    }

    public function getStatusText()
    {
        $condition = $this->getStatus();
        return isset($this->status[$condition]) ? $this->status[$condition] : "NA";
    }

    protected function _afterLoad()
    {
        if ($this->getProductId()) {
            $collection = Mage::getModel("catalog/product_attribute")
                ->getCollection()
                ->addFieldToFilter("product_id", $this->getProductId())
                ->joinLeft(
                    ["attr" => "catalog_attribute"],
                    "attr.attribute_id = main_table.attribute_id",
                    ["name" => "name"]
                );
            $data = $collection->getData();
            foreach ($data as $_data) {
                $this->{$_data->getName()} = $_data->getValue();
            }
            $media = Mage::getModel('catalog/media')->getCollection()
                ->addFieldToFilter('product_id', ['=' => $this->getProductId()]);
            $filepath = [];
            foreach ($media->getData() as $filemedia) {
                $filepath[] = $filemedia->getFilePath();
                if ($filemedia->getDefaultImg()) {
                    $default_image = $filemedia->getFilePath();

                    $this->_data['main_image'] = $default_image;
                }
                $this->_data['file_path'] = $filepath;
            }
        }
        return $this;
    }

    protected function _afterSave()
    {
        
        $request = Mage::getModel('core/request');
        $delimg = $request->getParam('catalog_image_delete');
        if ($delimg) {
            $img = $delimg['images'];
            $n = sizeof($img);
            for ($i = 0; $i < $n; $i++) {
                $model = Mage::getModel('catalog/media')->getCollection()->addFieldToFilter('file_path', ['=' => $img[$i]])->getData();
                $mediamodel = Mage::getModel('catalog/media');
                $mediamodel->load($model[0]->getMediaId());
                $mediamodel->delete();
            }
        }
        $attributes = Mage::getModel('catalog/attribute')->getCollection()->getData();
        foreach ($attributes as $_attribute) {
            $productAttributes = Mage::getModel('catalog/product_attribute')
                ->getCollection()
                ->addFieldToFilter('product_id', $this->getProductId())
                ->addFieldToFilter('attribute_id', $_attribute->getAttributeId())
                ->getData();

            $value = $this->{$_attribute->getName()};
            if (isset($productAttributes[0])) {
                $productAttributes[0]->setValue($value)->save();
            } else {
                Mage::getModel('catalog/product_attribute')
                    ->setAttributeId($_attribute->getAttributeId())
                    ->setProductId($this->getProductId())
                    ->setValue($value)
                    ->save();
            }
        }
        $mainImageName1 = $this->getdata();
        $mainImageName = $mainImageName1['default_image'];
        $finalimage = $mainImageName;
        if (isset($_FILES['catalog_product']['name']['image']) && !empty($_FILES['catalog_product']['name']['image'][0])) {
            $filesCount = count($_FILES['catalog_product']['name']['image']);
            $baseDir = Mage::getBaseDir();
            $uploadDir = $baseDir . DS . "Media" . DS . "Product";
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            for ($i = 0; $i < $filesCount; $i++) {
                $originalFileName = $_FILES['catalog_product']['name']['image'][$i];
                if (!$originalFileName) {
                    continue;
                }
                $fileName = $originalFileName;
                $tmpName = $_FILES['catalog_product']['tmp_name']['image'][$i];
                $type = $_FILES['catalog_product']['type']['image'][$i];
                $type = substr($type, 0, strpos($type, '/'));
                $isMain = ($originalFileName == $mainImageName) ? 1 : 0;
                $productImageData = [
                    "product_id"  => $this->getProductId(),
                    "file_path"   => "Media/Product/" . $fileName,
                    "type"        => $type,
                    "default_img" => $isMain
                ];
                
                $productMedia = Mage::getModel("catalog/media");
                $productMedia->setData($productImageData);
                $piid = $productMedia->save();
                if ($piid) {
                    move_uploaded_file($tmpName, $uploadDir . DS . $fileName);
                } else {
                    echo "File not uploaded for image: " . $originalFileName;
                }
            }
        }

        $productMedia = Mage::getModel("catalog/media");
        $handlemainimg = $productMedia->getCollection()->addFieldToFilter('product_id', ['=' => $this->getProductId()])->getData();
        $size = sizeof($handlemainimg);
        $finalname = 'Media/Product/'.$mainImageName;
        if ($handlemainimg) {
            for ($j = 0; $j < $size; $j++) {
                if ($handlemainimg[$j]->getFilePath() == $finalname) {
                    $id = $handlemainimg[$j]->getMediaId();
                    $pid = $handlemainimg[$j]->getProductId();
                    $fpath = $handlemainimg[$j]->getFilePath();
                    $type = $handlemainimg[$j]->getType();
                    $productImageData = [
                        "media_id" => $id,
                        "product_id"  => $pid,
                        "file_path"   => $fpath,
                        "type"        => $type,
                        "default_img" => 1
                    ];
                    $productMedia->setData($productImageData);
                    $piid = $productMedia->save();
                }
                else{
                    $id = $handlemainimg[$j]->getMediaId();
                    $pid = $handlemainimg[$j]->getProductId();
                    $fpath = $handlemainimg[$j]->getFilePath();
                    $type = $handlemainimg[$j]->getType();
                    $productImageData = [
                        "media_id" => $id,
                        "product_id"  => $pid,
                        "file_path"   => $fpath,
                        "type"        => $type,
                        "default_img" => 0
                    ];
                    $productMedia->setData($productImageData);
                    $piid = $productMedia->save();

                }
            }
        }
        $productMedia = Mage::getModel("catalog/media");
        $handlemainimg = $productMedia->getCollection()->addFieldToFilter('product_id', ['=' => $this->getProductId()])->addFieldToFilter('file_path', ['=' => $mainImageName])->getData();
        if ($handlemainimg) {
            $id = $handlemainimg[0]->getMediaId();
            $pid = $handlemainimg[0]->getProductId();
            $fpath = $handlemainimg[0]->getFilePath();
            $type = $handlemainimg[0]->getType();
            $productImageData = [
                "media_id" => $id,
                "product_id"  => $pid,
                "file_path"   => $fpath,
                "type"        => $type,
                "default_img" => 1
            ];
            $productMedia->setData($productImageData);
            $piid = $productMedia->save();
        }
    }
}
