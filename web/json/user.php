<?php

class JsonUser {
    static private function search_user($propname, $term, &$ret, &$id_list) {
        $user = new User();
        $user->$propname = "LIKE%$term%";
        $user->set_constraints($propname);
        $user->set_limit(3);
        while($user->pull()) {
            if (in_array($user->id, $id_list)) continue;
            $ret[] = array(
                "id"=> $user->id,
                "name" => $user->name,
                "nickname" =>$user->nickname,
                "info"=> "Solved:$user->solved,Submit:$user->submissions",
                "match"=> ($user->name == $term)?true:false
            );
            $id_list[] = $user->id;
        }        
    }
    static function search_suggest($term) {
        $ret = array();
        $id_list = array();
        self::search_user('name', $term, $ret, $id_list);
        self::search_user('nickname', $term, $ret, $id_list);        
        
        return $ret;
    }
}

?>
