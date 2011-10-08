<?php

class JsonPost {

    static function more_replies($id, $start) {
        $posts = new Post();
        $posts->reply = "=$id";
        $posts->set_limit(100, $start)->set_constraints('reply')->orderby('time', 'desc');
        $replies = array();
        while ($posts->pull()) {
            $posts->author->pull();

            $replies[] = array(
                'id' => $posts->id,
                'user_id' => $posts->author->id,
                'name' => $posts->author->name,
                'nickname' => $posts->author->nickname,
                'content' => $posts->content,
                'time' => $posts->time
            );
        }
        return $replies;
    }

    static function new_post($content, $catalog, $problem) {
        if (!is_logged()) return _("You must login first");
        if (strlen(trim($content)) < 10) return _("Your post is too short");
        
        $catalog_names = array('solution', 'clarification', 'question', 'general');
        $catalog = array_select(strtolower($catalog), $catalog_names, 'general');
        
        if ($problem != '0') {
            $prob = new Problem();
            $prob->set_id($problem);
            if (!$prob->size()) return _("Invalid problem");
        }
        $post = new Post();
        $post->author = get_uid();
        $post->problem = $problem;
        $post->content = $content;
        $post->catalog = $catalog;
        if (!$post->push()) {
            return null;
        }
        return $post->get_id();
    }

    static function reply_post($id, $content) {
        if (!is_logged()) return _("You must login first");
        if (strlen(trim($content)) < 10) return _("Your reply is too short");
        
        $post = new Post();
        $post->reply = $id;
        $post->content = $content;
        $post->author = get_uid();
        if (!$post->push()) return null;
        return $post->get_id();
    }
    
    static function get_post($id) {
        $post = new Post();
        $post->set_id($id);
        if ($post->pull()) {
            $post->author->pull();
            return array(
                'id' => $post->id,
                'user_id' => $post->author->id,
                'name' => $post->author->name,
                'nickname' => $post->author->nickname,
                'content' => $post->content,
                'time' => $post->time
            );
        }
        return null;
    }
    
    static function rate_useful($id) {
        if (!is_logged()) return _("You must login first");
        
        $rating = new PostRating();
        $rating->post = "=" . $id;
        $rating->user = "=" . get_uid();
        
        if ($rating->set_constraints('post', 'user')->size()) {
            // It has been rated by this user
            return _("You have rated this post as useful.");
        }
        $rating = new PostRating();
        $rating->post = $id;
        $rating->user = get_uid();
        if (!$rating->push()) {
            return null;
        }
        $post = new Post;
        $post->set_id($id)->pull();
        $post->useful++;
        if (!$post->push()) {
            return null;
        }
        return $post->useful;
    }    
}

?>
