<?php

require_once dirname(__FILE__) . '/../system/Model.php';

/**
 * Description of Contest
 *
 * @author hovey
 */
class Contest extends Model{
    public function __construct() {
        parent::__construct();
        $this->bind('id', 'cid');
        $this->bind('password', 'pwd');
        $this->bind('owner', 'owner', 'User');
        $this->bind('parent_course', 'course_id', 'Course');
    }
    
    public function get_prikey() {
        return 'cid';
    }
    
    public function get_tablename() {
        return 'contests';
    }
    
    public $id;
    public $title;
    public $starttime;
    public $during;
    public $perm;
    public $authtype;
    public $password;
    /**
     *
     * @var User
     */
    public $owner;
    public $addrepos;
    public $information;
    /**
     *
     * @var Course
     */
    public $parent_course;
    public $avail;
}

?>
