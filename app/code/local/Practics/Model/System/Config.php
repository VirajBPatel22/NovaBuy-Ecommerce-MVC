<?php
Class Practics_Model_System_Config extends Core_Model_Abstract{
    public function init()
    {
       $this->_resourceClassName = "Practics_Model_Resource_System_Config";
       $this->_CollectionClassName = "Practics_Model_Resource_System_Config_Collection";
    }
}

?>