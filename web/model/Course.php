<?php

require_once dirname(__FILE__) . '/../system/Model.php';
/**
 * Description of Course
 *
 * @author hovey
 */
class Course extends Model{
    public function __construct() {
        parent::__construct();
        $this->bind('id', 'course_id');
        $this->bind('owner', 'owner', 'User');
    }
    
    public function get_prikey() {
        return 'course_id';
    }
    
    public function get_tablename() {
        return 'courses';
    }
    
    public $id;
    public $name;
    public $teacher;
    public $description;
    public $avail;
    /**
     *
     * @var User
     */
    public $owner;
    
}

?>
