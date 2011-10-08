<?
require("./navigation.php");
$p = @$_GET['p'];
if ($p == "")
    $p = 1;

$rs = new RecordSet($conn);
$query_str = "SELECT cid, title, starttime, during FROM contests WHERE course_id = 0 ";
$count_str = "SELECT count(*) FROM contests WHERE course_id = 0 ";

$rs->nPageSize = 20;
$rs->PageCount($count_str);
$rs->SetPage($p);

$query_str .= "ORDER BY cid DESC";
$rs->dpQuery($query_str);

$now = time();
?>
<div id="contest">
    <div class="blue_anchor">
        <fieldset>
            <legend>Basic Operation</legend>
            <a href="contest_create.php">[New Contest]</a>
        </fieldset>
    </div>
    <table width="100%" border="0" cellpadding="4" cellspacing="2" class="ui-widget tblcontainer ui-widget-content ui-corner-all">
        <thead>
            <tr align="center" class="ui-widget-header"> 

                <td height=25 width="6%">ID</td>
                <td>Title</td>
                <td width="30%">Schedule</td>
                <td width="8%">Edit Contest</td>
                <td width="8%">Add Problem</td>
            </tr>
        </thead>
        <?
        $i = 0;
        while ($rs->MoveNext()) {
            $id = $rs->Fields["cid"];
            if (!is_contest_modifiable($id))
                continue;
            $i++;
            if ($i % 2 == 0)
                echo "<tr bgcolor=\"#FCFCFC\">\n"; else
                echo "<tr bgcolor=\"#EEEEEE\">\n";

            $time = $rs->Fields["starttime"];
            $title = $rs->Fields["title"];
            $during = $rs->Fields["during"];
            ?>
            <td height=25 align="center"><? echo $id; ?></td>
            <td><a href="contest_detail.php?cid=<? echo $id; ?>" class="black"><? echo $title; ?></a></td>
            <td align=center>
                <?
                $timestamp = strtotime($time);
//	  $start = date("Y.m.d G:i:s", $timestamp);
                $start = $timestamp;
                sscanf($during, "%d:%d:%d", $h, $m, $s);
                $end = $timestamp + $h * 3600 + $m * 60 + $s;
                if ($now < $start) {
                    //echo "<font color=red>Starting at ".date('l dS of F Y h:i:s A', $timestamp);
                    echo "<font color=red>" . date('l, F dS Y h:i:s A', $timestamp);
                    printf(", %02d:%02d:%02d", ($start - $now) / 3600, ($start - $now) % 3600 / 60, ($start - $now) % 60);
                    echo " Left";
                } else if ($now < $end) {
                    echo "<font color=red>Running, ";
                    printf("%02d:%02d:%02d", ($end - $now) / 3600, ($end - $now) % 3600 / 60, ($end - $now) % 60);
                    echo " Left";
                }
                else
                    echo "<font color=green>Finished";
                ?>
            </td>
            <?
            $start = 0;
            echo "<td align=center><a href=contest_edit.php?cid=" . $id . " >[Edit]</a></td>";
            echo "<td align=center><a href=problem_create.php?cid=" . $id . " >[Add]</a></td>";
            ?>
            </tr>
        <? } ?>
    </table>
</td>
</tr>
<tr align="center" valign="top">
    <td height="42" colspan="2" background="images/bg2.gif">
        <? echo $rs->Navigate(); ?>
        &nbsp;
    </td>
</tr>
</table>
<?php
require("../footer.php");
?>
