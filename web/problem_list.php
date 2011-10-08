<?php

require("./navigation.php");
?>

<script type="text/javascript" src="js/jquery.dataTables.min.js" > </script> 
<script type="text/javascript" src="js/FixedHeader.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/problem_list.js"></script>

<link type="text/css" rel="stylesheet" href="css/data_table.css"/>

<div id="problem_list"> 

    <div id="tabs">
        <ul>
            <li><a href="problem_tab.php?vol=1">Volumn I</a></li>
            <li><a href="problem_tab.php?vol=2">Volumn II</a></li>
            <li><a href="problem_tab.php?vol=3">Volumn III</a></li>
            <li><a href="problem_tab.php?vol=4">Volumn IV</a></li>
            <li><a href="problem_tab.php?vol=5">Volumn V</a></li>
            <li><a href="problem_tab.php?vol=6">Volumn VI</a></li>
            <li><a href="problem_tab.php?vol=7">Volumn VII</a></li>
            <li><a href="problem_tab.php?vol=8">Volumn VIII</a></li>
            <li><a href="problem_tab.php?vol=9">Volumn IX</a></li>
            <li><a href="problem_tab.php?vol=0">Full</a></li>
        </ul>
    </div>

</div>


<?php

require("./footer.php");
?>
