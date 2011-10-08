<?php
$navmode = "contest";
require("./navigation.php");
$cid = safeget("cid");
$contest = new ContestsTbl($cid);
$contest->Get() or error("No such contest");
?>

<h1><?= $contest->detail['title'] ?></h1>
<h2>Contest starts at <?= $contest->detail['starttime'] ?></h2>
<div><?= $contest->detail['information'] ?>
</div>


<?php
require("./footer.php");
?>
