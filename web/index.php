<?php
require("./navigation.php");
global $conn;
$rs = new RecordSet($conn);
$rs->Query("SELECT content FROM sicilychan WHERE avail = 1 ORDER BY RAND() LIMIT 1");
$rs->MoveNext();
$talk = $rs->Fields['content'];
?>
<center>
    <img src="images/logo.jpg" />
</center>
<style>
    #sicily_chan {
        background-image: url("images/cartoon/sicilychan2.png");
        background-repeat: no-repeat;
        background-position: center;
        height: 508px;
        width: 210px;
        right: 150px;
        top: auto;
        bottom: 0px;
        position: fixed;
    }
    #chanbox {
        position: absolute;
        left: -200px;
        top: 0px;
    }
    #boxtop {
        background-image: url("images/cartoon/box-1.png");
        background-repeat: no-repeat;
        width: 219px;
        height: 17px;
    }
    #boxbottom {
        background-image: url("images/cartoon/box-3.png");
        background-repeat: no-repeat;
        width: 219px;
        height: 31px;
    }

    #boxcnt {
        background-image: url("images/cartoon/box-2.png");
        background-repeat: repeat-y;
        padding-left: 15px;
        padding-right: 15px;
        width: 189px;
    }
</style>

<script type="text/javascript">
    $(function(){
        $("#sicily_chan").draggable();
    });
        
</script>
<div id="sicily_chan">
    <div id="chanbox">
        <div id="boxtop"> </div>
        <div id="boxcnt"> <?= $talk ?>
        </div>
        <div id="boxbottom"> </div>

    </div>
</div>
<?php
require("./footer.php");
?>
