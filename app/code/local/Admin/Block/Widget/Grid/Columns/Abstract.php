<?php  
Class Admin_Block_Widget_Grid_Columns_Abstract{
    protected $_data;
    protected $_row;
    protected $_link;
    public function setData($data){
        $this->_data = $data;    
    }
    
    public function setrow($row){
        $this->_row = $row; 
        return $this;   
    }
    public function getrow(){
        return $this->_row;
    }
    public function setlink($link){
        $this->_link = $link; 
        return $this;   
    }
    public function getlink(){
        return $this->_link;
    }
    
    public function render(){
        // $this->_data = $data;    

        return "<span>".$this->getValue()."</span>";
    }
    public function getValue(){
        return $this->_row[$this->_data['data_index']];
    }
    public function getfliter(){
        $data = $this->_data['filter'];
        $block = Mage::getBlock('Admin/Widget_Grid_Filter_'.$data);
        $block->setData($this->_data);
        return $block;
    }
    public function getData(){
        return $this->_data;
    }

}

?>