<?php
$cid = isset($_GET["cid"]) ? $_GET['cid'] : "";
if ($cid)
    $navmode = "contest";
require("./navigation.php");

$pid = intval(safeget('pid'));

if ($pid < 1000)
    error("Invalid problem ID!");
if ($cid) {
    // play a contest
    if (!is_contest_accessible($cid))
        error("You can't access to this contest");
    $problem = new ContestProblem($cid, $pid);
} else {
    // normal form problem
    $problem = new ProblemTbl($pid);
}

if (!$problem->Get())
    error("No such problem");
if (!is_admins() && !$problem->detail["avail"])
    error("This problem is not available");

$pcid = intval($problem->detail["cid"]);

if ($pcid && !is_contest_accessible($pcid)) {
    error("You can't view this problem");
}


$title = $problem->detail["title"];
$time_limit = $problem->detail["time_limit"];
$memory_limit = $problem->detail["memory_limit"] / 1024;
$description = $problem->detail["description"];
$input = $problem->detail["input"];
$output = $problem->detail["output"];
$hint = $problem->detail["hint"];
$sample_input = $problem->detail["sample_input"];
$sample_output = $problem->detail["sample_output"];
$author = $problem->detail["author"];
$special_judge = $problem->detail["special_judge"];
$submissions = $problem->detail["submissions"];
$accepted = $problem->detail["accepted"];
$rate_tot = $problem->detail["rate_tot"];
$rate_count = $problem->detail["rate_count"];

$rs = new RecordSet($conn);
$rs->Query("SELECT language, run_time, run_memory, time, sid, status FROM status WHERE pid='$pid' AND uid='$login_uid' ORDER BY time desc");
$submited = $rs->MoveNext();
if ($cid)
    $submited = false;
if (!$cid && $logged) {
    $ratingtbl = new RatingTbl();
    if ($ratingtbl->GetByFields(array('uid' => $login_uid, 'pid' => $pid))) {
        $cur_score = intval($ratingtbl->detail['rate']);
    } else {
        $cur_score = -1;
    }

    echo "<script type=\"text/javascript\">";
    printf("var score=%d;", $cur_score);
    printf("var problem_id=%d;", $pid);
    echo "</script>";
} else {
    echo "<script type=\"text/javascript\">";
    echo "var score=-2;";
    echo "</script>";
}
?>

<link href="css/card.css" rel="Stylesheet" type="text/css" />
<script type="text/javascript" src="js/ZeroClipboard.js"></script>
<script src="js/problem.js" type="text/javascript"> </script>
<script type="text/javascript"> 
    document.title = "Sicily <?= $pid; ?>.<?= $title; ?>";
    function gotourl(url) {
        location.href = url;
    }
</script>

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
                                <div style="float:right;position: absolute">

                                </div>

                                <div class="headline">
                                    <div class="cent">    
                                        <?
                                        echo $pid . ". " . $title;
                                        if ($special_judge == "1")
                                            echo _("[Special judge]");
                                        ?>                                
                                    </div>
                                </div>

                            </div>
                        </div>
                    </td>
                    <td class="cr c">
                    </td>
                </tr>

                <tr class="card-actionrow">
                    <td class="cal c">
                    </td>
                    <td class="ca c">
                        <div class="entry-actions">
                            <div class="viceheadline">

                                <table border="0" id="info_board" cellpadding="4" cellspacing="2">
                                    <tr>
                                        <td><?= _("Total:") ?> </td>
                                        <td><? echo $submissions ?></td>
                                        <td><?= _("Accepted:") ?></td>
                                        <td><? echo $accepted ?></td>
                                        <?
                                        if (!$cid) {
                                            echo "<td>" . _("Rating:") . "</td><td><div id=\"rating_board\">";
                                            if ($rate_count) {
                                                printf("%.1lf/5.0(%d " . _("votes") . ")", $rate_tot / $rate_count, $rate_count);
                                            } else {
                                                echo _("Unrated");
                                            }
                                            echo "</td>";
                                            echo "<td></div><div id=\"score_board\"></div></td>";
                                        }
                                        ?>
                                    </tr>
                                </table>
                                <? ?>
                            </div>
                        </div>
                    </td>
                    <td class="car c"></td>
                </tr>


                <tr class="card-bottomrow">
                    <td class="cbl c">&nbsp;</td>
                    <td class="cb c">&nbsp;</td>
                    <td class="cbr c">&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </div>


    <div class="entry">
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

                                <div class="rtbar">
                                    <? echo _("Time Limit: ") . $time_limit . _("sec") ?> &nbsp;&nbsp;
                                    <? echo _("Memory Limit:") . $memory_limit . _("MB") ?>
                                </div>            
                                <div class="headline"><?= _("Description") ?></div>
                                <div class="description"> <? echo $description; ?> </div>

                                <?
                                if (!empty($input))
                                    echo "<div class='headline'>" . _("Input") . "</div> <div class='description'> $input </div>";
                                if (!empty($output))
                                    echo "<div class='headline'>" . _("Output") . "</div> <div class='description'> $output </div>";
                                if (!empty($sample_input)) {
                                    ?>
                                    <div class="headline"><?= _("Sample Input") ?></div>
                                    <div id="d_clip_button">
                                        <img src="images/clipboard.jpg" />
                                        <?= _("Copy sample input to clipboard") ?>
                                    </div>
                                    <div style="overflow:auto">
                                        <pre id="sample_input"><? echo htmlspecialchars($sample_input); ?></pre>
                                    </div>

                                    <?
                                }
                                if (!empty($sample_output)) {
                                    ?>
                                    <div class="headline"><?= _("Sample Output") ?></div>
                                    <div style="overflow:auto">
                                        <pre><? echo htmlspecialchars($sample_output); ?></pre>
                                    </div>
                                    <?
                                }
                                if (!empty($hint))
                                    echo "<div class='headline'>" . _("Hint") . "</div> <div class='description'> $hint </div>";
                                ?>

                                <? if ($author): ?>
                                    <h3> Problem Source: <?= $author ?></h3>
                                <? endif; ?>


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

    <div class="entry">
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
                                <div class="viceheadline">
                                    <? if (!$cid): ?>
                                        <?= create_button(_('Status'), 'gotourl("problem_status.php?pid=' . $pid . '")', 'green') ?>
                                    <? endif; ?>
                                    <?= create_button(_('Submit'), 'gotourl("submit.php?problem_id=' . $pid . ($cid?"&cid=$cid":"") . '")', 'blue') ?>
                                    <? if ($logged && $submited == true): ?>
                                        <?= create_button(_('Source Code'), 'show_source_list()', 'red') ?>
                                    <? endif; ?>

                                </div>
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



    <?
    if ($logged && $submited == true) {
        ?>

        <script type="text/javascript">
            function show_source_list()
            {
                var ll=document.getElementById("sourcelist");
                ll.style.display="block";
            }
        </script>
        <div id="sourcelist" style="display:none">
            <div class="entry">
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
                                        <?
                                        $count = 0;
                                        echo '<span class="description">';
                                        echo '<table width="100%">';

                                        do {
                                            echo '<tr><td width="30">';
                                            echo $count;
                                            echo '</td><td width="30">';

                                            echo $rs->Fields['language'];
                                            echo '</td><td width="30">';
                                            echo $rs->Fields['run_time'];
                                            echo "s";
                                            echo '</td><td width="50">';
                                            echo $rs->Fields['run_memory'];
                                            echo "KB";
                                            echo '</td><td width="100">';
                                            echo $rs->Fields['time'];
                                            echo '</td><td width="50"> ';
                                            echo $rs->Fields['status'];
                                            echo '</td><td width="50"> ';
                                            echo '<a href="./viewsource.php?sid=';
                                            echo $rs->Fields['sid'];
                                            echo '"> View </a></td>';
                                            $count++;
                                            echo '</td></tr>';
                                        } while ($rs->MoveNext());
                                        echo '</table>';
                                        echo "</span>";
                                        ?>        

                                    </div>
                                </div>
                            </td>
                            <td class="cr c">
                            </td>
                        </tr>            

                        <tr class="card-actionrow">
                            <td class="cal c">
                            </td>
                            <td class="ca c">
                                <div class="entry-actions">
                                    <tr class="card-bottomrow">
                                        <td class="cbl c">&nbsp;</td>
                                        <td class="cb c">&nbsp;</td>
                                        <td class="cbr c">&nbsp;</td>
                                    </tr>
                                </div>
                    </tbody>
                </table>
            </div>
            <?
        }
        ?>    

    </div>
</div>


<?php
require("./footer.php");
?>
