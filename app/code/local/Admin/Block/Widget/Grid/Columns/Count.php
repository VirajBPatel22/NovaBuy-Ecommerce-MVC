<?php  
Class Admin_Block_Widget_Grid_Columns_Count extends Admin_Block_Widget_Grid_Columns_Abstract{
    
    public function render(){
        $count = Mage::getModel('ems/registration')
        ->getCollection()
            ->select(['totalcount' => 'COUNT(reg_id)'])
            ->addFieldToFilter('event_id',$this->getrow()['event_id'])
            ->getFirstitem()
            ->getData();
            $countregistration =  $count['totalcount'];
            

        $callback= $this->_data['callback'];
        return '<a href="'.$this->getlink()->$callback($this->_row).'">'.$this->_data['lable'].'('.$countregistration.')'.'</a>';
    }
    
}

?>