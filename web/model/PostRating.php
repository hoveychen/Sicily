<?php

require_once dirname(__FILE__) . '/../system/Model.php';

/**
 * Description of PostRating
 *
 * @author hovey
 */
class PostRating extends Model {
    public function __construct() {
        parent::__construct();
        $this->bind('post', 'post_id', 'Post');
        $this->bind('user', 'user_id', 'User');
    }
    
    public function get_prikey() {
        return 'id';
    }
    
    public function get_tablename() {
        return 'post_rating';
    }
    
    public $id;
    /**
     *
     * @var Post
     */
    public $post;
    /**
     *
     * @var User
     */
    public $user;
}

?>
