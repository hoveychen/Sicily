<?php

require_once dirname(__FILE__) . '/../system/Model.php';
/**
 * Description of Problem
 *
 * @author hovey
 */
class Problem extends Model{
    public function __construct() {
        parent::__construct();
        $this->bind('id', 'pid');
        $this->bind('solved_num', 'accepted');
        $this->bind('submit_num', 'submissions');
        $this->bind('is_spj', 'special_judge');
        $this->bind('is_fwj', 'has_framework');
        $this->bind('parent_contest', 'cid', 'Contest');
        $this->bind('std_status', 'stdsid', 'Status');
    }
    
    public function get_prikey() {
        return 'pid';
    }
    
    public function get_tablename() {
        return 'problems';
    }
    
    public $id;
    public $title;
    public $time_limit;
    public $memory_limit;
    public $description;
    public $input;
    public $output;
    public $sample_input;
    public $sample_output;
    public $solved_num;
    public $submit_num;
    public $is_spj;
    public $is_fwj;
    public $author;
    public $hint;
    public $avail;
    /**
     *
     * @var Contest
     */
    public $parent_contest;
    public $rate_tot;
    public $rate_count;
    /**
     *
     * @var Status 
     */
    public $std_status;   
    
}

?>
