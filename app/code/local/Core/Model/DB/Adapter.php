<?php
class Core_Model_DB_Adapter
{
    protected $_config = [
        "hostname" => "localhost",
        "dbname" => "ccc",
        "username" => "viraj",
        "password" => "Virajkumar2204@"
    ];
    public $connect = null;
    public function connect()
    {
        if ($this->connect == null) {
            $this->connect = mysqli_connect(
                $this->_config["hostname"],
                $this->_config["username"],
                $this->_config["password"],
                $this->_config["dbname"]
            );
        }
        return $this->connect;
    }
    public function featchAll($query)
    {
        
        $result = mysqli_query($this->connect(), $query);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
    public function fetchCol($query, $columnName)
    {
        $result = mysqli_query($this->connect(), $query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row[$columnName];
        }
        return $data;
    }
    public function feachRow($query)
    {        
        $result = mysqli_query($this->connect(), $query);

        while ($row = $result->fetch_assoc()) {
            
            return $row;
        }
    }
    public function insert($query)
    {
        
        $result = mysqli_query($this->connect(), $query);
        
        while ($result) {
            return mysqli_insert_id($this->connect());
        }
        return false;
    }
    public function query($query)
    {
        $result = mysqli_query($this->connect(), $query);
        return $result;
    }
}
