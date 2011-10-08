<?php
require_once 'navigation.php';

$pid = safeget('pid');

$problem = new Problem;
$problem->set_id($pid)->pull() or error('No such problem');
if (!is_admins() && !$problem->avail)
    error('Problem inavailable');
if ($problem->parent_contest) {
    $cid = $problem->parent_contest->get_id();
    if (!is_contest_accessible($cid) ||
            !is_contest_ended($cid)) {
        error('Contest Problem inavailable');
    }
}

$left_bar = array();
$left_bar[] = create_link(_("Statistics"), "problem_status.php?pid=$pid");
if (is_logged()) {
    $left_bar[] = create_link(_("Source code"), "status.php?pid=$pid&username=" . get_username());
}
$left_bar[] = create_link(_("Discuss"), "post.php?pid=$pid&catalog=general");
$left_bar[] = create_link("-&nbsp;" . _("Solutions"), "post.php?pid=$pid&catalog=solution");
$left_bar[] = create_link("-&nbsp;" . _("Clarifications"), "post.php?pid=$pid&catalog=clarification");
?>

<link type="text/css" rel="stylesheet" href="css/post.css" />

<script type="text/javascript">
    function openurl(url) {
        location.href = url;
    }
</script>

<div id="catalog">
    <div id="oldlink">
        <?= create_link(_("Switch to old style >>"), "show_problem.php?pid=$pid") ?>
    </div>
    <?= wrap_tag('h1', $problem->id . ". " . $problem->title) ?>
    <?= create_list(array(create_event(_("Problem Description"), ""))) ?>
    <?=
    create_def_list(array(
        _("Solved Number") => $problem->solved_num,
        _("Submit Number") => $problem->submit_num,
        _("Time Limit") => $problem->time_limit . " secs",
        _("Memory Limit") => $problem->memory_limit / 1024 . " MB",
        _("Special Judge") => ($problem->is_spj ? "Yes" : "No"),
        _("Framework Judge") => ($problem->is_fwj ? "Yes" : "No")
    ))
    ?>
    <?=
    create_list($left_bar);
    ?>

</div>
<div id="posts">
    <div id="banner">
        <?= create_button(_("Submit"), "openurl(\"submit.php?problem_id=$pid\")", "blue") ?>
    </div>
    <div id="banner_extend">
        <div class="post_box ui-corner-all">
            <div class="new_post_box" id="new_post">
                <textarea id="new_edit" class="new_edit_box"></textarea>
                <?= create_button(_("Submit Source"), "new_post()", "blue") ?>
                <?= create_button(_("Cancel"), "toggle_new_post()") ?>
            </div>
        </div>
    </div>

    <div class="post_box ui-corner-all">
        <div class="post_body">
            <center><h1><?= $problem->id . ". " . $problem->title ?></h1></center>
            <? if ($problem->description): ?>
                <h1><?= _("Description") ?></h1>
                <p><?= $problem->description ?></p>
            <? endif; ?>
            <? if ($problem->input): ?>
                <h1><?= _("Input") ?></h1>
                <p><?= $problem->input ?></p>
            <? endif; ?>
            <? if ($problem->output): ?>
                <h1><?= _("Output") ?></h1>
                <p><?= $problem->output ?></p>
            <? endif; ?>
            <? if ($problem->sample_input): ?>
                <h1><?= _("Sample Input") ?></h1>
                <code><pre><?= $problem->sample_input ?></pre></code>
            <? endif; ?>
            <? if ($problem->sample_output): ?>
                <h1><?= _("Sample Output") ?></h1>
                <code><pre><?= $problem->sample_output ?></pre></code>
            <? endif; ?>
            <? if ($problem->hint): ?>
                <h1><?= _("Hint") ?></h1>
                <p><?= $problem->hint ?></p>
            <? endif; ?>
            <? if ($problem->author): ?>
                <h1><?= _("Problem Source") ?></h1>
                <p><?= $problem->author ?></p>
            <? endif; ?>
        </div>
        <!--
                <div class="post_footer">
        <?= create_event("Rate", "") ?>
                    &nbsp;&nbsp;-&nbsp;&nbsp;
        <?= create_event("Share", "") ?>
                </div>-->

    </div>
    <?= create_button(_("Submit"), "openurl(\"submit.php?problem_id=$pid\")", "blue") ?>
</div>
<div style="clear:both"></div>
<?
require_once 'footer.php';
?>
