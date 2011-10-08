<?php

class JsonProblem {
    static function search_suggest($term) {
        $ret = array();
        $problem = new Problem;
        $problem->set_id($term);
        if ($problem->pull()) {
            $ret[] = array(
                "id"=> $problem->id,
                "title"=> $problem->title,
                "info"=> "Solved:$problem->solved_num,Submit:$problem->submit_num",
                "match"=> true                    
            );
        }
        $problem = new Problem;
        $problem->id = "!=$term";
        $problem->title = "LIKE%$term%";
        $problem->avail = "=1";
        $problem->set_constraints("id", "title", "avail");
        $problem->set_limit(5);
        
        while ($problem->pull()) {
            $ret[] = array(
                "id"=> $problem->id,
                "title"=> $problem->title,
                "info"=> "Solved:$problem->solved_num,Submit:$problem->submit_num",
                "match"=>false                    
            );
        }
        
        return $ret;
    }
}

?>
