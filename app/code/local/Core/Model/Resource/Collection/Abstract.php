<?php
class Core_Model_Resource_Collection_Abstract
{
    protected $_resource;
    protected $_model;
    protected $_select;
    public function setResource($resource)
    {
        $this->_resource = $resource;
        return $this;
    }
    public function setModel($model)
    {
        $this->_model = $model;
        return $this;
    }

    public function select($columns = ["*"])
    {
        $this->_select['FROM'] = ["main_table" => $this->_resource->getTableName()];
        $this->_select['COLUMNS'] = [];
        $columns = is_array($columns) ? $columns : [$columns];
        foreach ($columns as $alias => $column) {
            // Mage::log($alias);
            // Mage::log($column);
            // die;
            if (is_integer($alias)) {
                $this->_select['COLUMNS'][] = "main_table." . $column;
            } else {
                $this->_select['COLUMNS'][] = $column . " AS " . $alias;
            }
        }
        return $this;
    }
    public function getData()
    {
        $data = $this->_resource->getAdapter()->featchAll($this->prepareQuery());
        foreach ($data as &$_data) {
       
            $_model = new $this->_model;
            $_data = $_model->setData($_data);
        }
        return $data;
    }
    public function addFieldToFilter($field, $condition)
    {
        if (!is_array($condition)) {
            $condition = ['=' => $condition];
        }
        $this->_select['WHERE'][$field][] = $condition;
        return $this;
    }
    public function prepareQuery()
    {

        $this->_select[''] = array_filter($this->_select['COLUMNS']);
        $COLUMNS = $this->_select['COLUMNS'] ? implode(',', $this->_select['COLUMNS']) : '*';

        $query = sprintf("SELECT %s FROM `%s` AS %s", $COLUMNS, array_values($this->_select['FROM'])[0], array_keys($this->_select['FROM'])[0]);


        if (isset($this->_select['JOIN_LEFT'])) {

            $leftjoinsql = "";
            foreach ($this->_select["JOIN_LEFT"] as $leftjoin) {
                $leftjoinsql .= sprintf(
                    " LEFT JOIN %s AS %s ON %s ",
                    array_values($leftjoin['tablename'])[0],
                    array_keys($leftjoin['tablename'])[0],
                    $leftjoin['condition']
                );
            }
            $query .= " " . $leftjoinsql;
        }
        if (isset($this->_select['JOIN_RIGHT'])) {
            $joinsql = "";
            foreach ($this->_select['JOIN_RIGHT'] as $joinLeft) {
                $joinsql .= sprintf(" RIGHT JOIN  %s ON %s ", $joinLeft['tablename'], $joinLeft['condition']);
            }
            $query = $query . " " . $joinsql;
        }
        if (isset($this->_select['JOIN_INNER'])) {

            $leftjoinsql = "";
            foreach ($this->_select["JOIN_INNER"] as $leftjoin) {
                if (!is_array($leftjoin['tablename'])) {
                    $leftjoin['tablename'] = [$leftjoin['tablename'] => $leftjoin['tablename']];
                }

                $leftjoinsql .= sprintf(" INNER JOIN %s AS %s ON %s ", array_values($leftjoin['tablename'])[0], array_keys($leftjoin['tablename'])[0],  $leftjoin['condition']);
            }
            $query .= " " . $leftjoinsql;
        }
        if (isset($this->_select['FULL_OUTER_JOIN'])) {
            $joinsql = "";
            foreach ($this->_select['FULL_OUTER_JOIN'] as $joinLeft) {
                $joinsql .= sprintf(" FULL OUTER JOIN  %s ON %s ", $joinLeft['tablename'], $joinLeft['condition']);
            }
            $query = $query . " " . $joinsql;
        }
        if (isset($this->_select['WHERE'])) {
            $wheresql = "";
            $count = count($this->_select['WHERE']);
            $conditions = [];
            foreach ($this->_select['WHERE'] as $field => $value) {
                foreach ($value as $_value) {
                    $conditions[] = $this->where($field, $_value);
                }
            }

            $wheresql .= " WHERE " . implode(' AND ', $conditions);

            $query = $query . " " . $wheresql;
        }
        if (isset($this->_select['GROUP_BY'])) {

            $groupby = " GROUP BY " . implode(',', $this->_select['GROUP_BY']);
            $query = $query . " " . $groupby;
        }
        if (isset($this->_select['HAVING'])) {
            $query .= " HAVING " . implode(' AND ', $this->_select['HAVING']);
        }
        if (isset($this->_select['ORDER_BY'])) {
            $orderbysql = " ORDER BY " . implode(',', $this->_select['ORDER_BY']);
            $query = $query . " " . $orderbysql;
        }
        if (isset($this->_select['LIMIT'])) {
            $orderbysql = " LIMIT " . $this->_select['LIMIT']['limit'] . " OFFSET " . $this->_select['LIMIT']['offset'];
            $query = $query . " " . $orderbysql;
        }
        
        return $query;
    }
    public function where($field, $value)
    {
        if (is_array($value)) {

            foreach ($value as $operator => $_value) {
                switch (strtoupper($operator)) {
                    case 'IN':
                    case 'NOT IN':

                        $_value = (is_array($_value)) ? $_value : [$_value];

                        foreach ($_value as $key => $val) {

                            $inarryvalues[] = (is_string($val)) ? "'{$val}'" : "{$val}";
                        }
                        $_value = implode(',', $inarryvalues);
                        $where  = " {$field} {$operator} ({$_value}) ";
                        break;

                    case 'BETWEEN':
                    case 'NOT BETWEEN':
                        foreach ($value as $key => $val) {
                            if (is_array($val)) {
                                foreach ($val as $limits) {
                                    $betweenvalues[] = (is_string($limits)) ? "'{$limits}'" : "{$limits}";
                                }
                            } else {
                                $betweenvalues[] = (is_string($val)) ? "'{$val}'" : "{$val}";
                            }
                        }
                        $betweenvaluestring = implode(' AND ', $betweenvalues);
                        $where  =   " {$field} {$operator} {$betweenvaluestring}";
                        break;
                    case 'eq':
                        $where = "{$field} = '{$_value}'";
                        break;

                    default:
                        $where = " {$field} {$operator} '{$_value}' ";
                        break;
                }
            }
        }
        return $where;
    }
    public function joinLeft($tableName, $condition, $columns)
    {

        $this->_select["JOIN_LEFT"][] = [
            "tablename" => $tableName,
            "condition" => $condition,
            "columns" => $columns
        ];

        foreach ($columns as $alias => $columnname) {
            $this->_select['COLUMNS'][] = sprintf(
                "%s.%s AS %s",
                array_keys($tableName)[0],
                $columnname,
                $alias
            );
        }
        return $this;
    }
    public function joinRight($tablename, $condition, $columns)
    {
        $this->_select['JOIN_RIGHT'][] = ['tablename' => $tablename, 'condition' => $condition, 'columns' => $columns];
        foreach ($columns as $alias => $columnname) {

            $this->_select['COLUMNS'][] = sprintf("%s.%s AS %s", $tablename, $columnname, $alias);
        }
        return $this;
    }
    public function joinInner($tableName, $condition, $columns)
    {

        $this->_select["JOIN_INNER"][] = ["tablename" => $tableName, "condition" => $condition, "columns" => $columns];
        foreach ($columns as $alias => $columnname) {
            if (!is_array($tableName)) {
                $tableName = [$tableName => $tableName];
            }
            $this->_select['COLUMNS'][] = sprintf("%s.%s AS %s", array_keys($tableName)[0], $columnname, $alias);
        }
        return $this;
    }
    public function joinFullOuter($tablename, $condition, $columns)
    {
        $this->_select['FULL_OUTER_JOIN'][] = ['tablename' => $tablename, 'condition' => $condition, 'columns' => $columns];
        foreach ($columns as $alias => $columnname) {

            $this->_select['COLUMNS'][] = sprintf("%s.%s AS %s", $tablename, $columnname, $alias);
        }
        return $this;
    }
    public function orderBy($condition)
    {
        foreach ($condition as $alias => $columnname) {
            $this->_select['ORDER_BY'][] = sprintf('%s', $columnname);
        }
        return $this;
    }
    public function groupBy($condition)
    {
        foreach ($condition as $alias => $columnname) {
            // //print_r($this->_select);
            // die();
            $this->_select['GROUP_BY'][] = sprintf('%s', $columnname);
        }
        return $this;
    }
    public function having($field, $operator, $value)
    {
        $this->_select['HAVING'][] = "{$field} {$operator} {$value}";
        return $this;
    }


    private function getTableAlias($table)
    {
        return array_keys($table)[0];
    }

    private function getTableName($table)
    {
        return array_values($table)[0];
    }
    public function getfirstItem()
    {
        $data = $this->getdata();
        if (isset($data[0])) {
            return $data[0];
        } else {
            return $this->_model;
        }
    }
    public function Limit($limit, $offset = 0)
    {
        // $offset = ($offset - 1) * $limit;    
        // print($offset);
        $this->_select["LIMIT"] = ["limit" => $limit, "offset" => $offset];
        return $this;
    }
}
