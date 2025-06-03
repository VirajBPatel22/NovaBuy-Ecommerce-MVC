<?php  
Class Admin_Block_Widget_Grid_Filter_Text extends Admin_Block_Widget_Grid_Filter_Abstract{
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
    public function render(){
        return '<input type="text" name="'.$this->_data['data_index'].'" placeholder = "Enter '.$this->_data['data_index'].'">';
    }
    
}

?>