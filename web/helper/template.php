<?php

/**
 * Create a anchor in web page
 * @param type $display
 * @param type $herf
 * @param type $title
 * @return string html content
 */
function create_link($display, $herf, $title = "") {
    return "<a href='$herf' title='$title' class='hord_link'>$display</a>";
}

function create_event($display, $js_callback, $title = "") {
    return "<a href='#' title='$title' class='hord_link' onclick='$js_callback;return false'>$display</a>";
}

function create_button($display, $js_callback = "", $theme = "") {
    if ($theme) {
        $theme = "hord_theme_" . $theme;
    }
    return "<div class='hord_button $theme' onclick='$js_callback;return false'>$display</div>";
}

/**
 * Create a list in web page
 * @param array $items item array
 * @return string html content
 */
function create_list($items) {
    return wrap_tag('ul', wrap_tags('li', $items), "class='hord_list'");
}

function create_def_list($items) {
    $str = "";
    foreach ($items as $key => $value) {
        $str .= wrap_tag('dt', $key, "class='hord_dt'"). wrap_tag('dd', $value, "class='hord_dd'");
    }
    return wrap_tag('dl', $str, "class='hord_list'");
}


/**
 * Wrap all the items in array with tags
 * Like td li tr
 * @param type $tag
 * @param type $items
 * @param mixed $attrs Attributes, either a string "attr=value" or an array with [attr => value]
 * @return string html content
 */
function wrap_tags($tag, $items, $attrs = "") {
    $str = "";
    foreach ($items as $item) {
        $str .= wrap_tag($tag, $item, $attrs);
    }
    return $str;
}

/**
 * Wrap tag for item
 * @param type $tag
 * @param type $item
 * @param mixed $attrs Attributes, either a string "attr=value" or an array with [attr => value]
 * @return string html content
 */

function wrap_tag($tag, $item, $attrs = "") {
    if (is_array($attrs)) {
        $buf = "";
        foreach ($attrs as $key => $val) {
            $val = htmlspecialchars($val);
            $buf .= " $key = \"$val\"";
        }
        $attrs = $buf;
    }
    return "<$tag $attrs>$item</$tag>";
}

?>
