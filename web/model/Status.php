<?php

require_once dirname(__FILE__) . '/../system/Model.php';

/**
 * Description of Status
 *
 * @author hovey
 */
class Status extends Model{
    public function __construct() {
        parent::__construct();
        $this->bind('id', 'sid');
        $this->bind('submitter', 'uid', 'User');
        $this->bind('problem', 'pid', 'Problem');
        $this->bind('parent_contest', 'contest', 'Contest');
    }
    
    public function get_prikey() {
        return 'sid';
    }
    
    public function get_tablename() {
        return 'status';
    }
    
    public $id;
    /**
     *
     * @var User
     */
    public $submitter;
    /**
     *
     * @var Problem
     */
    public $problem;
    public $language;
    public $status;
    public $run_time;
    public $run_memory;
    public $time;
    public $failcase;
    public $parent_contest;
    public $codelength;
    public $public;
    public $sourcecode;
    public $compilelog;
}

?>
