<?php

require_once dirname(__FILE__) . '/../system/Model.php';

/**
 * Description of Contest
 *
 * @author hovey
 */
class Judge extends Model{
    public function __construct() {
        parent::__construct();
    }
    
    public function get_prikey() {
        return 'id';
    }
    
    public function get_tablename() {
        return 'judge';
    }
    
    public $id;
    public $username;
    public $password;
    public $token;
    public $lasttime;
}

?>
