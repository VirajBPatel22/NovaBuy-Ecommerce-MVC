<?php  
Class Admin_Block_Widget_Grid_Columns_Dropdown extends Admin_Block_Widget_Grid_Columns_Abstract{
    protected $_data;
    public function __construct() {
        
    }
    public function setdata($data){
        $this->_data =$data;
        return $this;
    }
    
    public function getdata(){
        return $this->_data;
    }

    
}

?>