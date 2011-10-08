<?php

require_once dirname(__FILE__) . '/../inc/config.inc.php';

/**
 * Connection to Databse.
 * Singleton.
 * @author hovey
 */
class DBConn {

    static private $conn;

    /**
     * Get new instance
     * @return DBConn connection instance 
     */
    final static function new_instance() {
        if (isset(self::$conn)) {
            return self::$conn;
        } else {
            self::$conn = new DBConn;
            return self::$conn;
        }
    }

    private $host_name;
    private $user_name;
    private $passowrd;
    private $database;
    private $link_id;

    final private function __clone() {
        
    }

    final private function __construct() {
        // singleton
        $this->host_name = Config::$db_host_name;
        $this->user_name = Config::$db_user_name;
        $this->passowrd = Config::$db_password;
        $this->database = Config::$db_database;
        $this->link_id = @mysql_connect(
                        $this->host_name, $this->user_name, $this->passowrd);
        if ($this->link_id == FALSE) {
            throw new Exception('Database connection fails.');
        }
        if (mysql_select_db($this->database, $this->link_id) == FALSE) {
            throw new Exception("Database[$this->database] doesn't exists.");
        }

        mysql_set_charset('utf8', $this->link_id);
        //mysql_query("SET CHARACTER SET UTF8", $this->m_hLinkID);
        //mysql_query("SET CHARACTER_SET_RESULTS=UTF8", $this->m_hLinkID);
    }

    function query($query) {
        return mysql_query($query, $this->link_id);
    }

    function num_rows($result) {
        return mysql_num_rows($result);
    }

    function num_fields($result) {
        return mysql_num_fields($result);
    }

    function insert_id() {
        return mysql_insert_id($this->link_id);
    }

    function fetch_row($result) {
        return mysql_fetch_row($result);
    }

    // TODO: it is correct?
    function fetch_object($result, $class_name = null, $params = null) {
        return mysql_fetch_object($result, $class_name, $params);
    }

    function fetch_length($result) {
        return mysql_fetch_lengths($result);
    }

    function fetch_array($result, $result_type = null) {
        return mysql_fetch_array($result, $result_type);
    }

    function fetch_assoc($result) {
        return mysql_fetch_assoc($result);
    }

    function escape_string($unescaped_string) {
        return mysql_real_escape_string($unescaped_string, $this->link_id);
    }

    function affected_rows() {
        return mysql_affected_rows($this->link_id);
    }

    function result($result, $row, $field = null) {
        return mysql_result($result, $row, $field);
    }

}

?>
