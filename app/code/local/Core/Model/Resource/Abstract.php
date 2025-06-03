

<?php

class Core_Model_Resource_Abstract
{
    protected $_tablename = "";
    protected $_primaryKey = "";
    public function init($tablename, $primaryKey)
    {
        $this->_tablename = $tablename;
        $this->_primaryKey = $primaryKey;
    }

    public function __construct()
    {
        $this->_construct();
    }
    public function _construct()
    {
        return $this;
    }
    public function getAdapter()
    {
        return new Core_Model_DB_Adapter();
    }
    public function load($value,$field=null)
    {
        $field = (is_null($field))?$this->_primaryKey:$field;
        $sql = "SELECT * FROM `{$this->_tablename}` WHERE {$field}='$value' LIMIT 1";
    
        return $this->getAdapter()->feachRow($sql);
        
    }
    public function gettablename()
    {
        return $this->_tablename;
    }
    protected function _getDbcolums(){
        $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'{$this->_tablename}'";
        $column_name = $this->getAdapter()->fetchCol($sql, 'COLUMN_NAME');
       
        return $column_name;
    }
    public function save($model)

    {
        $dbColumn = $this->_getDbcolums();
        
        $primaryId = 0; 
        $data = $model->getData();
        if (isset($data[$this->_primaryKey]) && $this->_primaryKey) {
            $primaryId = $data[$this->_primaryKey];
        }
        if ($primaryId) {
            
            $columns = [];
            unset($data[$this->_primaryKey]);
            foreach ($data as $key => $value) {
                if(in_array($key,$dbColumn)){
    
                    $value = ($value != null) ? $value : '';
                    $columns[] = sprintf("`{$key}` = '%s'", addslashes(($value)));
                }
            }
            $columns = implode(",", $columns);
            $sql = sprintf(
                "UPDATE `%s` SET %s WHERE %s=%d",
                $this->_tablename,
                $columns,
                $this->_primaryKey,
                $primaryId
            );
           

            $this->getAdapter()->query($sql);
            
        } else {
            $columns = [];
            $collums_value = [];
            $query = "insert into `{$this->_tablename}`";

            foreach ($data as $key => $value) {
                if(in_array($key,$dbColumn)){

                
                $columns[] = $key;
                $value = ($value != null) ? $value : '';
                $collums_value[] = sprintf("%s", addslashes($value));
                }
            }
             $query .= "( `" . (implode("`,`", $columns)) . "`) values (' " . (implode("','", $collums_value)) . "')";
            
            $id = $this->getAdapter()->insert($query);
            $model->{$this->_primaryKey} = $id;
            return $id;
        }
    }
    public function delete($model){
        $data = $model->getData();

        $primaryId = $data[$this->_primaryKey];
        $sql = "DELETE FROM {$this->_tablename} WHERE {$this->_primaryKey } = $primaryId ";
        $id = $this->getAdapter()->insert($sql);
            $model->load($id);

    }
    
   
}
