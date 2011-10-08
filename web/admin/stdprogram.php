<?php
require("./navigation.php");
$pid = safeget('pid');
$cid = 0;
$problem = new ProblemTbl($pid);
$problem->Get() or error("Invalid Problem ID");
$problem = $problem->detail;
$cid = 0;
$status_vals = array("", "Accepted", "Wrong Answer", "Compile Error", "Runtime Error", "Time Limit Exceeded",
    "Memory Limit Exceeded", "Output Limit Exceeded", "Presentation Error", "Restrict Function",
    "Running", "Other", "Waiting");
$display_status = array("", _("Accepted "), _("Wrong Answer"), _("Compile Error"),
    _("Runtime Error"), _("Time Limit Exceeded"), _("Memory Limit Exceeded"),
    _("Output Limit Exceeded"), _("Presentation Error"), _("Restrict Function"),
    _("Running"), _("Other"), _("Waiting"));


?>

<script language="javascript" type="text/javascript" src="../js/edit_area/edit_area_compressor.php"></script>
<link type="text/css" rel="stylesheet" href="../css/submit.css"/>
<script type="text/javascript">
    function initEditor() {
        var langName = ["c", "cpp", "pas"];
        editAreaLoader.init({
            id : "source"        // textarea id
            ,
            syntax: langName[lang-1]            // syntax to be uses for highgliting
            ,
            start_highlight: true        // to display with highlight mode on start-up
            ,
            replace_tab_by_spaces: 4
            ,
            allow_toggle: false
            ,
            allow_resize: false
            ,
            min_width: 750
            ,
            toolbar: "new_document, |, search, go_to_line, fullscreen, |, undo, redo, |, select_font,|, word_wrap, |, help"
        });
    }

    var lang = GetCookie("lang");

    function onLanguageChange() {
        lang = parseInt($("select[name=language]").val());
        SetCookie("lang", lang);
        editAreaLoader.delete_instance("source");
        initEditor();
    }

    $(function() {
        $("#submit_form input:submit").button();
        if (lang == null) lang = 2; else lang = parseInt(lang);
        $("select[name=language]").val(lang).change(onLanguageChange);
        initEditor();    
    });

</script>
<div class="background_container">
    <? if ($problem['stdsid']): ?>
        <table class="tblcontainer ui-widget-content ui-corner-all" width="100%" border="0" cellpadding="4" cellspacing="2">
            <thead>
                <tr align="center" class="ui-widget-header"> 
                    <td width="8%" height="20"><b><?= _("Run ID") ?></b></td>
                    <td>&nbsp;&nbsp;</td>
                    <td><b><?= _("User Name") ?></b></td>
                    <td width="10%"><b><?= _("Problem") ?></b></td>
                    <td width="10%"><b><?= _("Language") ?></b></td>
                    <td width="16%"><b><?= _("Status") ?></b></td>
                    <td width="10%"><b><?= _("Run Time") ?></b></td>
                    <td width="10%"><b><?= _("Run Memory") ?></b></td>
                    <td width="10%"><b><?= _("Code Length") ?></b></td>
                    <td width="18%"><b><?= _("Submit Time") ?></b></td>		  
                </tr>
            </thead>
            <?
            global $conn;
            $rs = new RecordSet($conn);
            $rs->query("SELECT * FROM status WHERE sid={$problem['stdsid']}");
            if ($rs->MoveNext()) {
                $user_id = $rs->Fields["uid"];
                global $login_uid;
                if ($user_id == $login_uid)
                    $username = $login_username;
                else {
                    $user = new UserTbl($user_id);
                    if ($user->Get())
                        $username = $user->detail['username'];
                    else
                        $username = 'unknown';
                }
                $problem_id = $rs->Fields["pid"];
                $language = $rs->Fields["language"];
                $status = $rs->Fields["status"];
                $run_time = $rs->Fields["run_time"];
                $run_memory = $rs->Fields["run_memory"];
                $failcase = $rs->Fields["failcase"];
                $codelength = $rs->Fields["codelength"];
                if ($run_time == NULL)
                    $run_time = _("N/A");
                if ($run_memory == NULL)
                    $run_memory = _("N/A");
                if ($run_time != _("N/A"))
                    $run_time .= _("sec");
                if ($run_memory != _("N/A"))
                    $run_memory .= _(" KB");
                if ($codelength == "0") {
                    $codelength = _("N/A");
                } else {
                    $codelength .= _(" Bytes");
                }
                $time = $rs->Fields["time"];
                if (!$cid && $rs->Fields["contest"])
                    continue;
                ?>
                <td height=20 align="center">
                    <?
                    if ($cid == 0) {
                        echo $rs->Fields['sid'];
                    } else {
                        echo $rs->Fields['sid'] - $min_sid;
                    }
                    ?> </td>
                <td>
                    <?
                    if ($cid == 0) {
                        if ($user_id == get_uid() || is_admins() || is_manager()) {
                            echo "<a href=\"../viewsource.php?sid=" . $rs->Fields['sid'] . "\" class=\"black\">";
                            echo "<center><img src='../images/source-code.png' alt='view' title='View source code' /></center>";
                            echo "</a>";
                        }
                    } else {
                        $timestamp = strtotime($time) - strtotime($contest->detail['starttime']);
                        if ($user_id == get_uid() || is_contest_modifiable($cid)) {
                            echo "<a href=\"../viewsource.php?cid=" . $cid . "&sid=" . $rs->Fields['sid'] . "\" class=\"black\">";
                            echo "<center><img src='../images/source-code.png' alt='view' title='View source code' /></center>";
                            echo "</a>";
                        }
                    }
                    ?>

                </td>
                <td>&nbsp;&nbsp;<a href="../user.php?id=<? echo $user_id; ?>" class="black"><? echo $username; ?></a></td>
                <td align="center"><a href="../show_problem.php?pid=<?
            echo $problem_id;
            if ($cid)
                echo "&cid=$cid";
                    ?>" class="black"><? echo $problem_id; ?></a></td>
                <td align="center"><? echo $language; ?></td>
                <td align="center">
                    <?
                    if (($status == "Wrong Answer" || $status == "Memory Limit Exceeded" ||
                            $status == "Time Limit Exceeded" || $status == "Runtime Error" ||
                            $status == "Presentation Error" || $status == "Judging") &&
                            $failcase != -1)
                        $failmsg = "(" . ($failcase + 1) . ")"; else
                        $failmsg = "";
                    if ($status == "Accepted")
                        echo "<font color=\"#008000\">";
                    else
                        echo "<font color=\"#FF0000\">";
                    $sid = $rs->Fields['sid'];
                    if ($status == "Compile Error")
                        echo "<a href=\"../compileresult.php?cid=$cid&amp;sid=$sid\" style=\"text-decoration:none;color:blue\">";
                    for ($i = 1; $i < count($status_vals); ++$i) {
                        if ($status_vals[$i] == $status) {
                            echo $display_status[$i] . $failmsg;
                            break;
                        }
                    }
                    if ($status == "Compile Error")
                        echo "</a>";
                    echo "</font>";
                    ?>
                </td>
                <td align="center">
                    <?
                    if ($status == "Time Limit Exceeded")
                        echo "&gt;";
                    echo $run_time;
                    ?>
                </td>
                <td align="center"><? echo $run_memory; ?></td>
                <td align="center"><? echo $codelength; ?></td>
                <td align="center"><? echo $time; ?></td>		
                </tr>
            </table>
        <? } ?>



    <? endif; ?>

    <div id="submit_container">
        <form action="process.php?act=StdSubmit" method="post" name="form1" id="submit_form">
            <table class="tblcontainer ui-widget-content ui-corner-all" border="0" cellpadding="4" cellspacing="2" class="normal" width="100%">
                <thead>
                    <tr class="ui-widget-header">
                        <td height="20" colspan="2" align="center"><b><?= _("Submit") ?></b></td>
                    </tr>
                </thead>
                <tr>
                    <td width="10%" align="right"><?= _("User name") ?></td>
                    <td align="left"><? echo $login_username; ?>
                    </td>
                </tr>
                <tr>
                    <td align="right"><?= _("Problem ID") ?></td>
                    <td align="left">
                        <input name="pid" type="text" id="pid" value="<?= $pid ?>" size="5" maxlength="5" readonly="true">
                    </td>
                </tr>
                <tr>
                    <td align="right"><?= _("Language") ?></td>
                    <td align="left">
                        <select name="language" size="1" id="language">
                            <option value="1">GNU C 4.4.3</option>
                            <option value="2" selected>GNU C++ 4.4.3</option>
                            <option value="3">Free Pascal 2.4.0-2</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right"><?= _("Source") ?> </td>
                    <td align="left">
                        <textarea name="source" cols="80" rows="20" id="source" style="width:100%"></textarea>
                    </td>
                </tr>
                <tr align="center">
                    <td height="20" colspan="2" align="center">
                        <input type="submit" value="<?= _("Submit") ?>">
                    </td>
                </tr>
            </table>
        </form>
    </div>

</div>

<?php
require("../footer.php");
?>
