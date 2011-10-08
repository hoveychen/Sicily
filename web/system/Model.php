<?php

require_once dirname(__FILE__) . '/DBConn.php';

/**
 * An abstract data model for data persistent
 * Usage:
 * Inherit this class and set member variable corresponding to database table
 * @author hovey
 */
abstract class Model {

    private $dbconn;
    private $constraints = array();
    private $orderby = array();
    private $groupby;
    private $having;
    private $limit;
    private $size;
    private $bindings = array();
    private $alias = array();
    private $dbresult;
    private $prop_list;

    /**
     * Inheritor needs to implement this function.
     * @return string the table name
     */
    abstract function get_tablename();

    /**
     * Inheritor needs to implement this function.
     * @return string the *real* primary key column name
     */
    abstract function get_prikey();

    function __construct() {
        $this->dbconn = DBConn::new_instance();
        // Get all the public properties set by inheritor
        $ref = new ReflectionObject($this);
        $props = $ref->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach ($props as $prop) {
            $this->prop_list [] = $prop->getName();
        }
    }

    /**
     * Bind a property with another model if modelname is provided,
     * Otherwise alias it as just another name
     * Only for inherited model
     * @param type $propname property name
     * @param type $keyname key column name
     * @param type $modelname another model class name
     */
    protected function bind($propname, $keyname, $modelname = null) {
        $this->alias[$propname] = $keyname;
        if ($modelname) {
            if (!class_exists($modelname)) {
                throw new Exception("Class [$modelname] is not defined");
            } else if (get_parent_class($modelname) != get_parent_class($this)) {
                throw new Exception("Class [$modelname] does't implement [" .
                        get_parent_class($this) . ']');
            }
            $this->bindings[$propname] = $modelname;
        }
    }

    private $id_propname;

    /**
     * Get the property name related to $this->get_prikey()
     * @return string property name
     */
    private function get_id_propname() {
        if (isset($this->id_propname)) {
            return $this->id_propname;
        }
        foreach ($this->prop_list as $prop) {
            if ($this->get_real_propname($prop) == $this->get_prikey()) {
                $this->id_propname = $prop;
                return $this->id_propname;
            }
        }
        $this->id_propname = 'id';
        return $this->id_propname;
    }

    /**
     * Set ID for model
     * @param mixed $value 
     * @return Model Method Chaining
     */
    function set_id($value) {
        $prop = $this->get_id_propname();
        $this->{$prop} = intval($value);
        $this->reset();
        return $this;
    }

    /**
     * Get ID for model
     * @return integer 
     */
    function get_id() {
        $prop = $this->get_id_propname();
        $id = $this->{$prop};
        if (!is_numeric($id)) {
            return null;
        }
        return intval($id);
    }

    /**
     * Specify property names which are constraints
     * @return Model Method Chaining
     */
    function set_constraints() {
        $argc = func_num_args();
        $argv = func_get_args();
        for ($i = 0; $i < $argc; $i++) {
            $this->set_constraint($argv[$i]);
        }
        $this->reset();
        return $this;
    }

    /**
     * Set thr order of results.
     * Multiple invoking is supported. 
     * The first invoke will be regarded as first priority,
     * the second one as second priority, and so on.
     * @param type $propname Property name
     * @param type $order 'ASC' or 'DESC'
     * @return Model Method Chaining
     */
    function orderby($propname, $order) {
        if (strtoupper($order) == 'DESC') {
            $order = 'DESC';
        } else {
            $order = 'ASC';
        }
        $this->orderby [] = $this->get_real_propname($propname) . " " . $order;
        return $this;
    }

    /**
     * Set GROUP BY option
     * @param type $propname 
     * @return Model Method Chaining
     */
    function groupby($propname) {
        $this->groupby = $propname;
        return $this;
    }

    /**
     * Set HAVING option
     * @param type $propname
     * @return Model Method Chaining
     */
    function having($propname) {
        $this->having = $propname;
        return $this;
    }

    /**
     * Specify property name which is a constraint
     * For safety, only support limited kinds of operators:
     * =, !=, <, <=, >, >=, IS NULL, IS NOT NULL, LIKE
     * @param type $propname 
     */
    private function set_constraint($propname) {
        if (!property_exists($this, $propname)) {
            return;
        }
        $const = trim($this->{$propname});
        $operators = array('=', '!=', '<=', '<', '>=', '>', 'IS NULL',
            'IS NOT NULL', 'LIKE');
        foreach ($operators as $op) {
            if (substr($const, 0, strlen($op)) == $op) {
                // Found operator
                $cmp_val = $this->dbconn->escape_string(substr($const, strlen($op)));
                $this->constraints [] = $this->get_real_propname($propname) . " " . $op . " '$cmp_val'";
                return;
            }
        }
        // No operator found
        throw new Exception("Fail to set constraint '$propname $const'");
    }

    /**
     * Set the return number of items
     * @param integer $num maximum number of items to fetch
     * @param type $start skip the first $start item
     * @return Model 
     */
    function set_limit($num, $start = 0) {
        $this->limit = " LIMIT " . intval($start) . "," . intval($num) . " ";
        return $this;
    }

    /**
     * Get the number of result with current constraint
     * @return type 
     */
    function size() {
        if (isset($this->size)) {
            return $this->size;
        }
        if (isset($this->limit)) {
            $tmp_limit = $this->limit;
            unset($this->limit);
            $const_str = $this->generate_constraints();
            $this->limit = $tmp_limit;
        } else {
            $const_str = $this->generate_constraints();
        }

        if (!$const_str) {
            return 0;
        }

        $query_str = "SELECT count(*) FROM " .
                $this->get_tablename() . $const_str;
        $result = $this->dbconn->query($query_str);
        if (!$result) {
            return 0;
        }
        if ($this->dbconn->num_rows($result) == 0) {
            $this->size = 0;
        } else {
            $this->size = intval($this->dbconn->result($result, 0));
        }

        return $this->size;
    }

    /**
     * Reset the inner counting and result
     * @return Model 
     */
    public function reset() {
        unset($this->size);
        unset($this->dbresult);
        return $this;
    }

    /**
     * Return the alias name of a property in database table 
     * @param type $propname
     * @return type 
     */
    private function get_real_propname($propname) {
        if (array_key_exists($propname, $this->alias)) {
            return $this->alias[$propname];
        }
        return $propname;
    }

    /**
     * Generate the contraints
     * @return type 
     */
    private function generate_constraints() {
        if ($this->get_id()) {
            return " WHERE {$this->get_prikey()} = '{$this->get_id()}'";
        } else if (count($this->constraints) > 0) {
            $query_str = ' WHERE 1=1 ';
            foreach ($this->constraints as $const) {
                $query_str .= " AND $const";
            }
            if (count($this->orderby) > 0) {
                $query_str .= " ORDER BY " . implode(',', $this->orderby);
            }
            if (isset($this->limit)) {
                $query_str .= $this->limit;
            }
            return $query_str;
        }
        return FALSE;
    }

    /**
     * Generate properties strings like a, b, c
     * @return type 
     */
    private function generate_properties() {
        $real_list = array();
        foreach ($this->prop_list as $prop) {
            if (array_key_exists($prop, $this->alias)) {
                $real_list[] = $this->alias[$prop];
            } else {
                $real_list[] = $prop;
            }
        }
        $prop_str = implode(',', $real_list);
        return $prop_str;
    }

    /**
     * Pull back the data from database
     * If this is the first time to invoke, it will perform a query and 
     * pull back the first result.
     * Otherwise, it will try to get a new result if there are more.
     * When no result can be returned, it will return FALSE, and reset 
     * the query counting;
     * @return boolean success or not
     */
    function pull() {
        if (!isset($this->dbresult)) {
            // first time pulling
            $const_str = $this->generate_constraints();
            if (!$const_str) {
                return FALSE;
            }
            $query_str = "SELECT " . $this->generate_properties() . " FROM " .
                    $this->get_tablename() . $const_str;
            $this->dbresult = $this->dbconn->query($query_str);

            if (!$this->dbresult) {
                throw new Exception(
                        "No permission to access table [{$this->get_tablename()}]"
                );
            }
            $this->size = $this->dbconn->num_rows($this->dbresult);
        }

        $result = $this->dbconn->fetch_assoc($this->dbresult);
        if (!$result) {
            // No more rows
            $this->reset();
            return FALSE;
        }
        foreach ($this->prop_list as $prop) {
            $col_name = $this->get_real_propname($prop);
            if (!array_key_exists($prop, $this->bindings)) {
                $this->{$prop} = $result[$col_name];
            } else if (intval($result[$col_name]) > 0) {
                // This may be a valid model reference.
                $class_name = $this->bindings[$prop];
                if (!isset($this->{$prop}) || !is_a($this->{$prop}, $class_name)) {
                    $this->{$prop} = new $class_name();
                }
                $this->{$prop}->set_id($result[$col_name]);
            }
        }
        return TRUE;
    }

    /**
     * Push the property into database.
     * If any contraints are provided, it will perform an insert query.
     * Otherwise, it will perform a update query.
     * @return boolean success or not
     */
    function push() {
        $vals = array();
        $cols = array();
        foreach ($this->prop_list as $prop) {
            $col_name = $this->get_real_propname($prop);
            if ($col_name == $this->get_prikey()) {
                /**
                 * No pri key is allowed to insert.
                 * We will obtain it later
                 */
                continue;
            }
            if (in_array($prop, $this->constraints)) {
                /**
                 * Constrains are not datas
                 */
                continue;
            }

            if (isset($this->{$prop})) {

                $cols[] = $col_name;
                if (array_key_exists($prop, $this->bindings)) {
                    if (is_a($this->{$prop}, $this->bindings[$prop])) {
                        $vals[] = $this->{$prop}->get_id();
                    } else {
                        $vals[] = intval($this->{$prop});
                    }
                } else {
                    $vals[] = "'" . $this->dbconn->escape_string($this->{$prop}) . "'";
                }
            }
        }

        $const_str = $this->generate_constraints();
        if (!$const_str) {
            // No contraints are provied, so an insert query is performed.
            return $this->insert($cols, $vals);
        } else {
            // Regard it as an update query.
            return $this->update($cols, $vals, $const_str);
        }
    }

    private function update($cols, $vals, $const_str) {
        $sets = array();
        for ($index = 0; $index < count($cols); $index++) {
            $sets[] = $cols[$index] . " = " . $vals[$index];
        }
        $set_str = implode(',', $sets);
        $query_str = "UPDATE " . $this->get_tablename() . " SET " .
                $set_str . $const_str;

        if (!$this->dbconn->query($query_str)) {
            throw new Exception(
                    "Fail to update ($set_str) in table [{$this->get_tablename()}]"
            );
        }
        $num_rows = $this->dbconn->affected_rows();
        if ($num_rows == -1) {
            return FALSE;
        }
        return TRUE;
    }

    private function insert($cols, $vals) {
        $val_str = implode(',', $vals);
        $col_str = implode(',', $cols);
        $query_str = "INSERT INTO " . $this->get_tablename() .
                " ( $col_str ) VALUES ( $val_str )";
        if (!$this->dbconn->query($query_str)) {
            throw new Exception(
                    "Fail to insert ($col_str)=($val_str) into table {$this->get_tablename()}"
            );
        }
        $insert_id = $this->dbconn->insert_id();
        if ($insert_id == FALSE) {
            throw new Exception("Connection Failed when inserting");
        }
        if ($insert_id == 0) {
            return FALSE;
        }
        $this->set_id($insert_id);
        return TRUE;
    }

    /**
     * Delete items from database.
     * If any contraints are provides, it will perform an delete query.
     * Otherwise, nothing happened.
     * @return boolean success or not
     */
    function remove() {
        $const_str = $this->generate_constraints();
        if (!$const_str) {
            return FALSE;
        }
        $query_str = "DELETE FROM " . $this->get_tablename() . $const_str;
        if (!$this->dbconn->query($query_str)) {
            throw new Exception("Fail to delete from table {$this->get_tablename()}");
        }
        $num_rows = $this->dbconn->affected_rows();
        if ($num_rows == -1) {
            return FALSE;
        }
        return TRUE;
    }

}

?>
