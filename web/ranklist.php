<?php
require("./navigation.php");
?>


<?
if (isset($_GET['p']))
    $p = $_GET['p']; else
    $p = 1;
$rs = new RecordSet($conn);
if ($logged) {
    $user = new UserTbl();
    if (!$user->Get($login_uid))
        error("Invalid user ID!", "ranklist.php");
    $solved = $user->detail['solved'];
    $submissions = $user->detail['submissions'];
    $rs->Query("SELECT count(*) FROM user WHERE perm LIKE '%user%' AND (solved > '$solved' OR (solved = '$solved' AND submissions < '$submissions') OR (solved = '$solved' AND submissions = '$submissions' AND uid < '$login_uid'))");
    $rs->MoveNext();
    $user_rank = $rs->Fields[0] + 1;
}

$rs->nPageSize = 50;
$rs->PageCount("SELECT count(*) FROM user");
$rs->SetPage($p);
$rs->dpQuery("SELECT uid, username, solved, submissions, netid, nickname, signature FROM user WHERE perm LIKE '%user%' ORDER BY solved DESC, submissions, uid");
?>


<form id="search_user_form">
    <input id="search_user_bar" class="search_bar" type="text" title="<?= _("Search user id or nickname...") ?>" />
    <?= create_button(_("Search"), '$("#search_user_form").submit()') ?>
</form>

<script type="text/javascript">
    // jQuery Input Hints plugin
    // Copyright (c) 2009 Rob Volk
    // http://www.robvolk.com

    jQuery.fn.inputHints=function() {
        // hides the input display text stored in the title on focus
        // and sets it on blur if the user hasn't changed it.

        // show the display text
        $(this).each(function(i) {
            $(this).val($(this).attr('title'))
            .addClass('input_hint');
        });

        // hook up the blur & focus
        return $(this).focus(function() {
            if ($(this).val() == $(this).attr('title'))
                $(this).val('')
            .removeClass('input_hint');
        }).blur(function() {
            if ($(this).val() == '')
                $(this).val($(this).attr('title'))
            .addClass('input_hint');
        });
    };

    var first_suggest_user = "";
    function open_user(id) {
        location.href="user.php?id=" + id;
    }
    $(function(){
        $("#search_user_bar[title]").inputHints();
        $("#search_user_form").submit(function(){
            if (first_suggest_user) {
                open_user(first_suggest_user);
            }
            return false;
        });
        
        $("#search_user_bar").autocomplete({
            source: 'fast_json.php?mod=user&func=search_suggest',
            minLength: 2,
            search: function(event, ui) {
                first_suggest_user = "";
            },
            select: function(event, ui) {
                if (ui.item) {
                    open_user(ui.item.id);
                } else {
                    alert("no");
                }
            },
            focus: function( event, ui ) {
                $( "#search_user_bar" ).val( ui.item.name );
                first_suggest_user = ui.item.id;
                return false;
            }
        }).data( "autocomplete" )._renderItem = function( ul, item ) {
            var str = item.name;
            if (item.nickname) {
                str += "(" + item.nickname + ")";
            }
            str +=  "<br>" + item.info;
            if (item.match || !first_suggest_user) {
                str += "<hr />";
                first_suggest_user = item.id;
            }
            return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>" + str + "</a>" )
            .appendTo( ul );
        };        
    });
    
</script>


<table class="tblcontainer ui-widget-content ui-corner-all" width="100%" border="0" cellpadding="4" cellspacing="2">
    <thead>
        <tr align="center" class="ui-widget-header">

            <td width="10%" height="20"><?= _("Rank") ?></td>
            <td align="left"><?= _("Name") ?></td>
            <td width="40%" align="left"><?= _("Signature") ?></td>
            <td width="15%"><?= _("Solved") ?></td>
            <td width="15%"><?= _("Submissions") ?></td>
        </tr>
    </thead>
    <?
    $rank = ($p - 1) * $rs->nPageSize;
    while ($rs->MoveNext()):
        $rank++;
        $netid = $rs->Fields['netid'];
        if (empty($netid)) {
            $netid_img = "authorize0.gif";
        } else {
            $netid_img = "authorize2.gif";
        }
        $nickname = htmlspecialchars($rs->Fields['nickname']);
        $username = $rs->Fields['username'];
        if (empty($nickname))
            $nickname = $username;
        $signature = htmlspecialchars($rs->Fields['signature']);
        ?>
        <tr style="<?= ($logged && $rs->Fields["uid"] == $login_uid) ? 'font-weight: bold' : '' ?>">
            <td height=20 align="center"><? echo $rank; ?></td>
            <td align="left">
                <a title="<?= $netid ?>"><img src="images/<?= $netid_img ?>"/></a>
                <a href="user.php?id=<? echo $rs->Fields["uid"]; ?>" class = "black" title="<?= $username ?>"><?= $nickname ?></a>
            </td>
            <td align="left"><?= $signature ?></td>
            <td align="center"><? echo $rs->Fields["solved"]; ?></td>
            <td align="center"><? echo $rs->Fields["submissions"]; ?></td>
        </tr>
        <?
    endwhile;
    if ($logged && $user_rank > $rank) {
        ?>
        <tr style="font-weight: bold">
            <td height=20 align="center"><? echo $user_rank; ?></td>
            <td>&nbsp;&nbsp;<a href="user.php?id=<? echo $login_uid; ?>" class = "black"><? echo $login_username; ?></a></td>
            <td></td>
            <td align="center"><? echo $solved; ?></td>
            <td align="center"><? echo $submissions; ?></td>
        </tr>
        <?
    }
    ?>

</table>

<? echo $rs->Navigate() ?>


<?php
require("./footer.php");
?>
