<?php
require_once 'navigation.php';

$p = intval(tryget('p', 1));
// configuation
$MAX_DISPLAY_REPLY = 2;
$MAX_DISPLAY_POST = 10;


$post = new Post();
$catalog = 'general';
$pid = 0;
$post->orderby('time', 'desc');
$post->reply = "=0";
$post->set_constraints('reply');
$post->set_limit($MAX_DISPLAY_POST, ($p - 1) * $MAX_DISPLAY_POST);

$first_post = true;
$no_post = true;
?>

<link type="text/css" rel="stylesheet" href="css/post.css" />
<link type="text/css" rel="stylesheet" href="css/pagination.css" />
<script type="text/javascript" src="js/jquery.pagination.js" ></script>

<script type="text/javascript" >
    function on_pagination_click(page_index, container) {
        location.href = "post_market.php?p=" +
            (page_index + 1);
        return false;
    }
    $(function(){
        $("#pagination").pagination(<?= $post->size() ?>, {
            items_per_page: <?= $MAX_DISPLAY_POST ?>,
            callback: on_pagination_click,
            current_page: <?= $p - 1 ?>
        });
    });
</script>

<script type="text/javascript">
    function toggle_reply(id) {
        $('#reply_' + id).toggle();
        $('#edit_' + id).focus();
        $('html, body').animate({ scrollTop: $("#edit_"+id).offset().top - 300}, 500);
    }
    
    function toggle_new_post() {
        $('#banner').toggle();
        $('#banner_extend').toggle();
        $('#new_edit').focus();
        $('html, body').animate({ scrollTop: $("#new_post").offset().top -300}, 500);
    }
    
    function escape_special_html(str) {
        return $('<div/>').text(str).html();
    }
    
    function display_reply(id, reply, anchor) {
        var display_author = reply.name;
        if (reply.nickname) display_author += "(" + reply.nickname + ")";
        display_author = "<a href='user.php?id=" + reply.user_id + "' class='hord_link'>"
            + escape_special_html(display_author)
            + "</a>";
        
        $("#reply_template")
        .clone()
        .show()
        .prependTo(anchor + id)
        .find('.post_header').html(display_author 
            + "<div class='addition_info'> - " 
            + reply.time +"</div>")
        .end()
        .find('.post_body').text(reply.content);        
    }
    
    function fetch_more_reply(id, start) {
        $.post("json.php?mod=post&func=more_replies", {'id':id, 'start':start}, function(data){
            var num_replies = 0;
            for (var index in data) {
                ++num_replies;
                display_reply(id, data[index], "#more_reply_anchor_");
            }
            $("#more_reply_" + id).hide();
        }, 'json');
    } 
    
    function reply_post(id) {
        var content = $("#edit_" + id).val();
        $("#edit_" + id).val("");
        toggle_reply(id);
        $.post("json.php?mod=post&func=reply_post", {'id':id, 'content':content}, 
        function(data){
            if (typeof(data)=='number') {
                $.post('json.php?mod=post&func=get_post', {'id':data}, function(post){
                    display_reply(id, post, "#reply_anchor_");
                }, 'json');                
            } else if (typeof(data) == 'string') {
                alert(data);
            } else {
                alert("An error has occured.");
            }
        }, 'json');
    }
    
    function new_post() {
        toggle_new_post();
        $.post("json.php?mod=post&func=new_post",
        {   'catalog': $("#new_post_catalog").val(), 
            'content': $("#new_edit").val(), 
            'problem': '<?= $pid ?>' 
        },
        function(data){
            if (typeof(data)=='number') {
                location.reload();
            } else if (typeof(data) == 'string') {
                alert(data);
            } else {
                alert("An error has occured.");
            }
        }, 'json');
    }
    
    function rate_useful(id) {
        $.post("json.php?mod=post&func=rate_useful", {'id': id},
        function(data){
            if (typeof(data)=='number') {
                $("#useful_rating_" + id).text(data);
            } else if (typeof(data) == 'string') {
                alert(data);
            } else {
                alert("An error has occured.");
            }
        }, 'json');
    }
    
</script>

<div id="posts">
    <div id="banner">
        <?= create_button(_("New Post"), "toggle_new_post()", "blue") ?>

    </div>
    <div id="banner_extend">
        <div class="post_box ui-corner-all">
            <?= _("Select Catalog:") ?>
            <select id="new_post_catalog">
                <optgroup label="<?= _("Default") ?>">
                    <option value="General"><?= _("General") ?></option>
                </optgroup>
                <optgroup label="<?= _("Special") ?>">
                    <option value="Solution"><?= _("Solution") ?></option>
                    <option value="Clarification"><?= _("Clarification") ?></option>                        
                </optgroup>
            </select>
            <div class="new_post_box" id="new_post">
                <textarea id="new_edit" class="new_edit_box"></textarea>
                <?= create_button(_("Submit Post"), "new_post()", "blue") ?>
                <?= create_button(_("Cancel"), "toggle_new_post()") ?>
            </div>
        </div>
    </div>

    <div class='catalog_title'><?= ucfirst($catalog) ?></div>
    <? while ($post->pull()): ?>
        <?
        $no_post = false;
        $rating_highlight = "";
        if ($first_post) {
            $first_post = false;
            if ($post->useful) {
                $rating_highlight = "useful_rating_highlight";
            }
        }
        $post->author->pull();
        $replies = new Post();
        $replies->reply = "=$post->id";
        $reply_num = $replies->set_constraints('reply')->size();
        $replies->set_limit($MAX_DISPLAY_REPLY, max(0, $reply_num - $MAX_DISPLAY_REPLY))->orderby('time', 'asc');
        $display_author = $post->author->name;
        if ($post->author->nickname) {
            $display_author .= "(" . $post->author->nickname . ")";
        }
        ?>
        <div class="post_box post_box_extend ui-corner-all">
            <div class="post_left_bar">
                <div id="useful_rating_<?= $post->id ?>" class="useful_rating ui-corner-all <?= $rating_highlight ?>">
                    <?= $post->useful ?>
                </div>
                <? if ($post->catalog == 'Solution'): ?>
                    <div class="solution_icon">
                    </div>
                <? endif; ?>
                <? if ($post->catalog == 'Clarification'): ?>
                    <div class="clarification_icon">
                    </div>
                <? endif; ?>
            </div>
            <div class="post_header">
                <?= create_link($display_author, "user.php?id=" . $post->author->id) ?>
                <div class="addition_info">
                    <? if ($post->problem): ?>
                        - <?= create_link("Problem {$post->problem->get_id()}", "problem.php?pid={$post->problem->get_id()}") ?>
                    <? endif; ?>
                    - <?= $post->time ?>            

                </div>
            </div>
            <div class="post_body post_content"><?= htmlspecialchars($post->content) ?></div>

            <? if ($reply_num > $MAX_DISPLAY_REPLY): ?>
                <div id="more_reply_anchor_<?= $post->id ?>"></div>
                <div class="more_reply_box" id="more_reply_<?= $post->id ?>"
                     onclick="fetch_more_reply(<?= $post->id ?>, <?= $MAX_DISPLAY_REPLY ?>); return false">
                         <?= ($reply_num - $MAX_DISPLAY_REPLY) . " older replies." ?>
                </div>
            <? endif; ?>

            <? while ($replies->pull()): ?>
                <?
                $replies->author->pull();
                $display_author = $replies->author->name;
                if ($replies->author->nickname) {
                    $display_author .= "(" . $replies->author->nickname . ")";
                }
                ?>
                <div class="reply_post_box">
                    <div class="post_header">
                        <?= create_link($display_author, "user.php?id=" . $replies->author->id) ?>
                        <div class="addition_info">
                            - <?= $replies->time ?>      
                        </div>
                    </div>
                    <div class="post_body post_content"><?= htmlspecialchars($replies->content) ?></div>
                </div>
            <? endwhile; ?>

            <div id="reply_anchor_<?= $post->id ?>"></div>

            <div class="post_footer">
                <?= create_event("Reply", "toggle_reply($post->id)") ?>
                &nbsp;&nbsp;-&nbsp;&nbsp;
                <?= create_event("Useful", "rate_useful($post->id)") ?>
                <!--
                &nbsp;&nbsp;-&nbsp;&nbsp;
                <?= create_event("Share", "") ?>
                
                -->
            </div>

            <div class="quick_reply_box" id="reply_<?= $post->id ?>">
                <form>
                    <input type="hidden" name="pid" value="<?= $post->id ?>" />
                    <textarea name="content" class="edit_box" id="edit_<?= $post->id ?>"></textarea>
                    <?= create_button('Post Reply', "reply_post($post->id)", "blue") ?>
                    <?= create_button('Cancel', "toggle_reply($post->id)") ?>
                </form>
            </div>
        </div>
    <? endwhile; ?>
    <div class="reply_post_box" id="reply_template" style="display:none">
        <div class="post_header">
        </div>
        <div class="post_body post_content"></div>
    </div>

    <? if ($no_post): ?>
        <div class="post_box ui-corner-all">
            <div class="post_body">
                <?= _("There aren't any posts yet.") ?>
            </div>
        </div>
    <? else: ?>
        <div id="pagination"></div>
    <? endif; ?>


</div>
<div style="clear:both"></div>
<?
require_once 'footer.php';
?>
