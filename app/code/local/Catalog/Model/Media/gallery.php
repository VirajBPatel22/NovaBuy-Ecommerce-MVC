<?php
class Catalog_Model_Media_Gallery extends Core_Model_Abstract{
    public function init(){
        $this->_resourceClassName = "Catalog_Model_Media_Resource_Gallery";
        $this->_CollectionClassName = "Catalog_Model_Media_Resource_Gallery_Collection";
}
    
}

?>