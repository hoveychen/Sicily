<?php
sscanf("05:00:00", "%d:%d:%d", $h, $m, $s);
echo $h * 3600 + $m * 60 + $s;

?>
