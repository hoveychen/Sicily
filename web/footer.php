<div id="footer">
    <hr />
    <p style="display: none" id="about">
        Sicily Chan designed by <a href="http://kiki-box.com" class="black">Kikichan</a>
        <br />
        Formerly powered by <a href="mailto:henryouly.bbs@bbs.zsu.edu.cn" class="black">Henry</a> and 
        <a href="mailto:Northming.bbs@bbs.zsu.edu.cn" class="black">Northming</a>.
        <br />
        Powered by 
        <a href="mailto:hoveychen@gmail.com" class="black">Hovey</a> and
        <a href="mailto:tigersoldi@gmail.com" class="black">Tigersoldier</a>.
    </p>

    <p>
        <?php
        global $sicily_version;
        echo _("Sicily Online Judge System") . "($sicily_version)";
        ?>
        <br />
        <?= create_link("中文", "process.php?act=ChangeLocale&locale=cn") ?>
        | <?= create_link("English", "process.php?act=ChangeLocale&locale=en") ?>
        | <?= create_link(_("Archives"), "http://bbs.sysu.edu.cn/bbs0an?path=boards/ACMICPC/D.1044598815.A/D.1111840644.A", _("Archives about sicily")) ?>
        | <?= create_link(_("Help"), "faq.php", _("Submittion guileline etc.")) ?>
        | <?= create_event("About", '$("#about").show()') ?>
        
        
        
        <br>
        <?= _("Copyright © 2005-2011 Informatic Lab in SYSU. All rights reserved."); ?>
    </p>

    <?php
    if (is_debug_mode())
        printf("<strong><p>" . round((microtime(true) - $startTime) * 1000) . " ms</p></strong>");
    ?>
</div>
<?php
if (!Config::$onsite) {
    echo "<script type='text/javascript' src='$script_prefix/ga.js'> </script>";
}
?>

</body>
</html>
<?php ob_end_flush(); ?>