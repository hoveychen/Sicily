<?php

require_once dirname(__FILE__) . '/../system/Model.php';

/**
 * Description of User
 *
 * @author hovey
 */
class User extends Model {

    /**
     * @assert () == 'uid'
     * @return type 
     */
    public function get_prikey() {
        return 'uid';
    }

    /**
     * @assert () == 'user'
     * @return type 
     */
    public function get_tablename() {
        return 'user';
    }

    public $id;
    public $name;
    public $email;
    public $address;
    public $solved;
    public $submissions;
    public $nickname;
    public $signature;

    public function __construct() {
        parent::__construct();
        $this->bind('name', 'username');
        $this->bind('id', 'uid');
    }

}

?>
