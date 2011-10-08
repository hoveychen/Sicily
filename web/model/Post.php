<?php

require_once dirname(__FILE__) . '/../system/Model.php';

/**
 * Description of Posts
 *
 * @author hovey
 */
class Post extends Model {

    public $id;
    public $catalog;
    public $content;
    public $time;

    /**
     * Author
     * @var User
     */
    public $author;
    public $useful;

    /**
     * Reply post
     * @var Post 
     */
    public $reply;

    /**
     * Problem
     * @var Problem
     */
    public $problem;

    /**
     * @assert () == 'posts'
     * @return type 
     */
    function get_tablename() {
        return 'posts';
    }

    /**
     * @assert () == 'id'
     * @return type 
     */
    function get_prikey() {
        return 'id';
    }

    function __construct() {
        parent::__construct();
        $this->bind('reply', 'reply_id', 'Post');
        $this->bind('author', 'user_id', 'User');
        $this->bind('problem', 'problem_id', 'Problem');
    }

}

?>
