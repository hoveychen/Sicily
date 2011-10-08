<?php
    require("./navigation.php");
?>

<script type="text/javascript" src="js/wxyz_jquery.dataTables.min.js" > </script>
<script type="text/javascript" src="js/problem_list.js"> </script>
<link type="text/css" href="css/jquery-ui-1.8.custom.css" rel="stylesheet" />

<div id="problem_list"> 
    <table class="display advtable_fix">
        <thead class="tr_header"><tr><th>Solved</th><th>ID</th><th class="place_left">Title</th><th>Accepted</th><th>Submissions</th><th>Ratio</th><th>Rating</th></tr></thead>
        <tbody></tbody>
        <!--<tfoot class="tr_header"><tr><th>Solved</th><th>ID</th><th class="place_left">Title</th><th>Accepted</th><th>Submissions</th><th>Ratio</th><th>Rating</th></tr></tfoot>-->
    </table>
</div>


<?php
    require("./footer.php");
?>
