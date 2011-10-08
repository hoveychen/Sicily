<?php
ob_start();
include_once("inc/global.inc.php");
require("inc/user.inc.php");
global $login_uid;
global $login_username;
global $login_netid;
global $logged;
isset($res_prefix) or $res_prefix = "";
$css_prefix = $res_prefix . "css";
$script_prefix = $res_prefix . "js";
$image_prefix = $res_prefix . "images";
if (isset($_SESSION["msg"])) {
    $alertmsg = $_SESSION['msg'];
    unset($_SESSION['msg']);
}
if (is_debug_mode()) {
    $startTime = microtime(true);
}
if (!isset($navmode))
    $navmode = "normal";
if (Config::$onsite && !$logged) {
    MsgAndRedirect("login.php");
}
if (is_temporary_user() && $navmode != "contest") {
    $clist = get_contests_reg();
    if (!$clist)
        error("This account is forbidden");
    $cid = max($clist);
    MsgAndRedirect("cindex.php?cid=$cid");
}
if (isset($_COOKIE['locale'])) {
    set_language($_COOKIE['locale']);
} else if (Config::$onsite) {
    set_language("cn");
} else {
    set_language();
}

function __autoload($class_name) {
    include_once "model/$class_name.php";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

    <head>
        <title>Sicily Online Judge</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<? echo $image_prefix; ?>/favicon.ico" />
        <link type="text/css" rel="stylesheet" href="<? echo $css_prefix; ?>/global.css"/>
        <link type="text/css" rel="stylesheet" href="<? echo $css_prefix; ?>/project_hord.css"/>
        <link type="text/css" rel="stylesheet" href="<? echo $css_prefix; ?>/jquery-ui-1.8.6.custom.css"/>
        <script type="text/javascript" src="<? echo $script_prefix; ?>/jquery-1.4.2.min.js" > </script>
        <script type="text/javascript" src="<? echo $script_prefix; ?>/jquery-ui-1.8.6.custom.min.js" > </script>
        <script type="text/javascript" src="<? echo $script_prefix; ?>/nav.js" > </script>
        <script type="text/javascript"><?= "var is_logged = " . intval($logged) . ";" ?></script>
        <?php
        if (!empty($alertmsg)) {
            ?>

            <script type="text/javascript">
                $(function(){
                    $("#msg-dialog").text("<?php echo "$alertmsg"; ?>").dialog({
                        resizable : false,
                        dialogClass: 'alert',
                        buttons: {
                            Ok: function (){
                                $(this).dialog("close");
                            }
                        }
                    });
                });
            </script>

            <?php
        }
        ?>

    </head>

    <body>

        <div id="msg-dialog" title="<?= _("Infomation"); ?>"></div>
        <div class="main_frame">

            <div id="sicily_logo">

                <div id="loginbox" >

                    <?php
                    if ($logged) {
                        if (empty($login_netid)) {
                            $msgUnauthorized = _("Unauthorized");
                            echo "<a title='$msgUnauthorized'><img src='$image_prefix/authorize0.gif' alt='$msgUnauthorized'/></a>";
                        } else {
                            echo "<a title='$login_netid'><img src='$image_prefix/authorize2.gif' alt='$login_netid'/></a>";
                        }
                        if (!isset($_SESSION['snickname']) || empty($_SESSION['snickname'])) {
                            echo "<a href='user.php?id=$login_uid' class='nickname'>$login_username</a>";
                        } else {
                            echo "<a href='user.php?id=$login_uid' class='nickname'>{$_SESSION['snickname']}</a>";
                        }
                        $display_signature = htmlspecialchars(tryfetch($_SESSION, 'ssignature', _("You havn't any signature yet.")));
                        echo "<div id='signature'>$display_signature</div>";
                        if (!isset($navmode) || $navmode != 'management' && !Config::$onsite) {
                            echo '<a href="process.php?act=Logout"> ' . _("Logout") . '</a>';
                        }
                    } else {
                        ?>

                        <form id="loginform" action="action.php?act=Login" method="post" name="loginform">
                            <div style="width: 0px; height: 0px; overflow: hidden"><input type="submit" /></div>                            
                            <div style="text-align: right">
                                <div>
                                    <label for="username"><?= _("Username:") ?></label>
                                    <input name="username" type="text" id="username" size="10" maxlength="30" />
                                    <label for="password"><?= _("Password:"); ?></label>
                                    <input name="password" type="password" id="password" size="10" maxlength="16" />
                                    <?= create_button(_("Login"), '$("#loginform").submit()') ?>
                                </div>
                                <div id="msg"><?= _("username or password incorrect."); ?> </div>
                                <? if (!Config::$onsite): ?>
                                    <div>
                                        <input id="lsession" name="lsession" type="checkbox" value="1"/>
                                        <label for="lsession"><?= _("Remember me") ?></label>
                                        | <?= create_link(_("Register"), "register.php", _("Sign up for a new account")) ?>
                                        | <?= create_link(_("Forget"), "profile_forgetpwd.php", _("Forget your password?")) ?>
                                    </div>
                                <? else: ?>
                                    <input id="lsession" name="lsession" type="hidden" value="1"/>
                                <? endif; ?> 
                            </div>

                        </form>


                        <?php
                    }
                    ?>
                </div>



            </div>

            <div style="position: relative;">
                <div id="topbar" >
                </div>
            </div>

            <?
            if ($navmode == "contest") {
                require("inc/contest.inc.php");
            } else {
                require("inc/search.inc.php");
            }
            require("announcement.php");
            ?>
            <div class="topmenu ui-corner-all">
                <ul>

                    <?php
                    $navitems = array();
                    $navitems[] = array(_("Home"), "index.php", _("Return to Homepage"));
                    if ($navmode == "normal") {
                        // normal view
                        $navitems[] = array(_("Problems"), "problem_list.php", _("View all problems"));
                        $navitems[] = array(_("Contests"), "contests.php", _("View all contests"));
                        $navitems[] = array(_("Courses"), "courses.php", _("Edit Courses"));
                        $navitems[] = array(_("Ranklist"), "ranklist.php", _("View user ranklist"));
                        if ($logged) {
                            $navitems[] = array(_("Submit"), "submit.php", _("Submit source code"));
                            $navitems[] = array(_("Setting"), "profile_edit.php", _("Edit my profile setting"));
                        }
                        if (is_admins() || is_manager()) {
                            $navitems[] = array(_("Status"), "status.php");
                            $navitems[] = array(_("Management"), "admin/index.php", _("Enter Management Mode"));
                        } else if (is_logged()) {
                            $navitems[] = array(_("Status"), "status.php?username=$login_username", _("View my submission status"));
                        }
                        $navitems[] = array(_("Discuss"), "post_market.php", _("Discussion about the problems in sicily"));
                    } else if ($navmode == 'management') {
                        $navitems[] = array(_("Exit"), "../index.php", _("Exit Management Mode"));
                        $navitems[] = array(_("Problems"), "problem_list.php", _("Edit problems"));
                        $navitems[] = array(_("Contests"), "contests.php", _("Edit Contests"));
                        $navitems[] = array(_("Courses"), "courses.php", _("Edit Courses"));
                        if (is_admins()) {
                            $navitems[] = array(_("Users"), "users.php", _("Edit Users"));
                        }
                    } else if ($navmode == 'contest') {
                        $navarg = "cid=" . safeget('cid');
                        $navitems[] = array(_("Problems"), "contest_detail.php?$navarg", _("View all contest problems"));
                        if ($logged) {
                            $navitems[] = array(_("My Status"), "status.php?$navarg", _("View my submission status"));
                        }
                        $navitems[] = array(_("Standing"), "standing.php?$navarg", _("View contest standing"));
                        if (is_contest_modifiable(safeget('cid'))) {
                            // hotfix
                            if (isset($_GET['pid'])) {
                                $navitems[] = array(_("Edit problem"), "admin/problem_edit.php?$navarg&pid={$_GET['pid']}", _("Edit this problem"));
                            }
                            $navitems[] = array(_("Edit contest"), "admin/contest_edit.php?$navarg", _("Edit this contest"));
                            $navitems[] = array(_("Manage problems"), "admin/contest_detail.php?$navarg", _("Manage contest problems"));
                        }
                    }
                    foreach ($navitems as $key => $item) {
                        if (isset($item[2]))
                            echo "<li><a href='{$item[1]}' title='{$item[2]}'>{$item[0]}</a></li>";
                        else
                            echo "<li><a href='{$item[1]}'>{$item[0]}</a></li>";
                    }
                    ?>
                </ul>
                <div class="rssbut">
                    <a onclick="javascript: ToggleWidth();" title="<?= _("change window's width");
                    ?>" class="links"> &lt;-> </a>


                </div>
            </div>

