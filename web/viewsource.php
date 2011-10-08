<?php
$cid = isset($_GET["cid"]) ? $_GET['cid'] : "";
if ($cid)
    $navmode = "contest";
require("./navigation.php");
?>


<?
global $app_config;
$sid = safeget('sid');
if (isset($_GET['cid']))
    $cid = $_GET['cid']; else
    $cid = '';

if (!$cid)
    $status = new StatusTbl();
else
    $status = new ContestStatus($cid);

if (!$status->Get($sid))
    error("No such source code!");

if ($status->detail['public'] == '0') {
    if ($status->detail['uid'] != $login_uid && !is_admins() && !is_manager())
        error("Sorry, you can't get this source code!");
    if (!$logged)
        error("Please login first");
}

$sid = $status->detail['sid'];
$pid = $status->detail['pid'];
$lang = $status->detail['language'];
$content = GetSource($sid, $lang);
$content = eregi_replace("<", "&lt;", $content);
$content = eregi_replace(">", "&gt;", $content);
$types = array('C' => 'cpp', 'C++' => 'cpp', 'Pascal' => 'delphi', 'Java' => 'java');
$type = $types[$lang];
?>

<link href="./css/card.css" rel="Stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="css/sh/shCore.css"/>
<link type="text/css" rel="stylesheet" href="css/sh/shThemeDefault.css"/>

<script type="text/javascript">
<?
if ($status->detail['uid'] == $login_uid || is_admins() || is_manager()) {
    echo "var owner = 1;";
} else {
    echo "var owner = 0;";
}
echo "var sid = " . $sid . ";";
?>

</script>

<script type="text/javascript" src="js/sh/shCore.js"></script>
<script type="text/javascript" src="js/sh/shBrushCpp.js"></script>
<script type="text/javascript" src="js/sh/shBrushDelphi.js"></script>
<script type="text/javascript" src="js/sh/shBrushJava.js"></script>
<script type="text/javascript" src="js/viewsource.js"></script>


<div id="container">

    <div id="titles" class="entry" >
        <table class="card">
            <tbody class="card-tbody">
                <tr>
                    <td class="ctl c">
                    </td>
                    <td class="ct c">
                    </td>
                    <td class="ctr c">
                    </td>
                </tr>
                <tr>
                    <td class="cl c">
                    </td>

                    <td class="cc c">
                        <div class="entry-container">
                            <div class="entry-main">


                                <span id="public">
                                    <a class='black' href="#" id="share_link"><img src='images/icon_share.gif' alt="share" />Share this code</a>                                    
                                </span>
                                 | <a class='black' href='show_problem.php?pid=<?=$pid?>'>View problems</a>

                                <?
                                echo '<pre class="brush: ' . $type . '">';
                                echo '// source code of submission ' . $sid;
                                echo ', Zhongshan University Online Judge System' . "\n";
                                echo $content;
                                echo '</pre>';
                                ?>

                            </div>
                        </div>
                    </td>
                    <td class="cr c">
                    </td>
                </tr>


                <tr class="card-bottomrow">
                    <td class="cbl c">&nbsp;</td>
                    <td class="cb c">&nbsp;</td>
                    <td class="cbr c">&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


<?php
require("./footer.php");
?>
