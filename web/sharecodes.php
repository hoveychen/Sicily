<?php
require("./navigation.php");
$uid = safeget('uid');
$user = new UserTbl($uid);
if (!$user->Get())
	error("Invalid user");
$user = $user->detail;
?>

<script type="text/javascript" src="js/jquery.dataTables.min.js" > </script>
<script type="text/javascript" src="js/sharecodes.js"> </script>
<link type="text/css" rel="stylesheet" href="css/data_table.css"/>
<style>
    .Accepted {
        color: green;
    }
    .Error, .Exceeded, .Wrong {
        color: red;
    }
</style>

<div id="sharecodes"> 
    <table class="display advtable_fix ui-widget-content">
        <thead class="tr_header"><tr><th>Run ID</th><th>Problem</th><th>Title</th><th>Status</th><th>Run time</th><th>Run memory</th><th>Code Length</th><th>View</th>
				<?php
				if ($logged && $login_uid == $uid) {
					echo "<th class='stop'>Stop Sharing</th>";
				}
				?>
        </thead>
        <tbody>
			<?php
			$status = new StatusTbl();
			if ($status->GetByFields(array('uid' => $uid, 'public' => '1'))) {
				do {
					$tbl = $status->detail;
					echo '<tr>';
					echo "<td>{$tbl['sid']}</td>";
					echo "<td>{$tbl['pid']}</td>";
					$problem = new ProblemTbl($tbl['pid']);
					$problem->Get();
					echo "<td>{$problem->detail["title"]}</td>";
					echo "<td>{$tbl['status']}</td>";
					echo "<td>{$tbl['run_time']}</td>";
					echo "<td>{$tbl['run_memory']}</td>";
					echo "<td>{$tbl['codelength']}</td>";
					echo "<td></td>";
					if ($logged && $login_uid == $uid) {
						echo "<td></td>";
					}
					echo '</tr>';
				} while ($status->MoreRows());
			}
			?>

        </tbody>
    </table>
</div>


<?php
require("./footer.php");
?>
