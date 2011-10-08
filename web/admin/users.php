<?
require("./navigation.php");
?>
<?
$p = @$_GET['p'];
if ($p == "")
	$p = 1;
$rs = new RecordSet($conn);
$rs->nPageSize = 16;
$rs->PageCount("SELECT count(*) FROM user");
$rs->SetPage($p);
$rs->dpQuery("SELECT uid, username, solved, submissions FROM user WHERE perm LIKE '%user%' ORDER BY solved DESC, submissions, uid");
?>

<script type="text/javascript">
    function resetPassword(obj, username) {

        $.post("../action.php?act=ResetPwd", {"username": username}, function(data) {
            if (data.success) {
                $(obj).text("Done");
            }
        }, 'json' );
    }
</script>
<table width="100%" border="0" cellpadding="4" cellspacing="2">
    <tr align="center" bgcolor="#0071BD" class="white" class="ui-widget-header"> 
        <td width="10%" height="20">Rank</td>
        <td>User name</td>
        <td width="15%">Solved</td>
        <td width="15%">Submissions</td>
        <td width="15%">Reset password</td>
    </tr>
	<?
	$rank = ($p - 1) * $rs->nPageSize;
	while ($rs->MoveNext()) {
		$rank++;
		printf("<tr bgcolor=\"#%s\">\n", $rank % 2 ? "EEEEEE" : "FCFCFC");
		?>
		<td height=20 align="center"><? echo $rank; ?></td>
		<td>&nbsp;&nbsp;<a href="../user.php?id=<? echo $rs->Fields["uid"]; ?>" class = "black"><? echo $rs->Fields["username"]; ?></a></td>
		<td align="center"><? echo $rs->Fields["solved"]; ?></td>
		<td align="center"><? echo $rs->Fields["submissions"]; ?></td>
		<td align="center">
	        <a href="#" onclick="resetPassword(this, '<? echo $rs->Fields['username'] ?>');return false;">Reset</a>
	        </tr>
		<? } ?>
</table>
<? echo $rs->Navigate() ?>

<?
require("../footer.php");
?>
