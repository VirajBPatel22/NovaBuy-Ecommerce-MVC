    <?php
class Admin_Block_Grid_Toolbar extends Core_Block_Template
{
    protected $_limit = 5;
    protected $_page = 1;
    protected $_collection;
    public function prepareToolbar()
    {
        
        $page = Mage::getModel('core/request')->getQuery("page");
        $limit = Mage::getModel('core/request')->getQuery("limit");
        if (is_numeric($page)) {
            $this->_page = $page;
        }


        else{
            $this->_page = 1;
        }
        if (is_numeric($limit)) {

            $this->_limit = intval($limit);
        }
        else{
            $this->_limit = 5;
        }
        $this->_collection = clone $this->getParent()
            ->getCollection();
        $this->getParent()
            ->getCollection()
            ->Limit($this->_limit, ($this->_page-1)*$this->_limit);
        
    }
    public function getTotalRecords()
    {
        return count($this->_collection->getData());
    }
}