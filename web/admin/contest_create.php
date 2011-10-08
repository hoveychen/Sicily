<?
require("./navigation.php");
include_once ("./FCKeditor/fckeditor.php");

$course_id = tryget('course_id', '');
$courseTbl = new CourseTbl($course_id);
if ($course_id && !$courseTbl->Get())
    error("Course not found");
$course = $courseTbl->detail;

$rs = new RecordSet($conn);
$rs->Query("SELECT max(cid) FROM contests");
$rs->MoveNext();
$cid = $rs->Fields[0];
$rs->Query("SELECT * FROM contests");
?>

<script type="text/javascript">
    function pad0(num) {
        if (num < 10) return "0" + num;
        return num;
    }
    
    function calc_during() {
        if ($("#starttime").val().length == 0) {
            alert("Please set start time first!!");
            return;
        }
        var during = Date.parse($("#endtime").val()) - Date.parse($("#starttime").val());
        during = during / 1000;
        var s = during % 60;
        var m = Math.floor(during / 60) % 60;
        var h = Math.floor(during / 3600);
        $("#during").val(pad0(h) + ":" + pad0(m) + ":" + pad0(s));
    }
    
    function calc_endtime() {
        if ($("#starttime").val().length == 0) {
            alert("Please set start time first!!");
            return;
        }
        var during_time = $("#during").val().split(':', 3);
        if (during_time.length < 3) {
            alert("Please use the correct format in *during* filed");
            return;
        }
        var h = parseInt(during_time[0], 10);
        var m = parseInt(during_time[1], 10);
        var s = parseInt(during_time[2], 10);
        if (isNaN(h) || isNaN(m) || isNaN(s) || 
            h < 0 || m < 0 || m >= 60 || s < 0 || s >= 60 ) {
            alert("Please use the correct format in *during* filed");
            return;
        }
        var during = h * 3600 + m * 60 + s;
        var endtime = new Date();
        endtime.setTime(Date.parse($("#starttime").val()) + during * 1000);
        $("#endtime").val(endtime.getFullYear() + "-" 
            + pad0(endtime.getMonth()+1) + "-"
            + pad0(endtime.getDate()) + " "
            + pad0(endtime.getHours()) + ":"
            + pad0(endtime.getMinutes()) + ":"
            + pad0(endtime.getSeconds()));
    }
    
    $(function() {
        $("#pwdbox").hide();
        $("#authtype").change(function() {
            if ($(this).val() == "password") {
                $("#pwdbox").show();
            } else {
                $("#pwdbox").hide();
            }
        });
        $("form input:submit").click(function(){
            if ($("#title").val() == "" || $("#starttime").val() == "") {
                alert("Some fields are empty'");
                return false;
            }
        });
        
        $("#endtime").change(calc_during);
        $("#during").change(calc_endtime);
        $("#starttime").change(calc_endtime);
    });
</script>
<form action="process.php?act=CreateContest" method="post" enctype="multipart/form-data">
    <input name="ipbind" type="hidden" id="ipbind" size="4" maxlength="4" value="free">
    <input name="course_id" type="hidden" value="<?= $course_id ?>"/>
    <table class="ui-widget tblcontainer ui-widget-content ui-corner-all" width="100%">
        <caption>Create New <?
if ($course_id) {
    echo "Exercise for {$course['name']}";
} else {
    echo "Contest";
}
?></caption>
        <thead >
            <tr class="ui-widget-header">
                <th width="100">Option</th>
                <th>Content</th>
                <th width="150">Example</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Title</td>
                <td><input name="title" type="text" id="title" size="50" maxlength="50"></td>
                <td>Test Contest</td>
            </tr>

            <tr>
                <td>Start Time</td>
                <td><input name="starttime" type="text" id="starttime" size="50" maxlength="50">
                </td>
                <td>2011-11-11 11:11:11</td>
            </tr>

            <tr>
                <td>End Time</td>
                <td><input type="text" id="endtime" size="50" maxlength="50" value="">
                </td>
                <td>2011-11-11 11:11:11</td>
            </tr> 

            <tr>
                <td>During</td>
                <td><input name="during" type="text" id="during" size="12" maxlength="12" value="05:00:00"></td>
                <td>05:00:00</td>
            </tr>
            <tr>
                <td>Accessibility</td>
                <td><select name="perm">
                        <? if (!Config::$onsite): ?>
                            <option selected="selected" value="user">All user</option>
                        <? endif; ?>
                        <? if (is_admins()): ?>
                            <option value="admin">Admin only</option>
                        <? else: ?>
                            <option value="manager">Admin only</option>
                        <? endif; ?>
                        <? if (Config::$onsite): ?>
                            <option value="temp" selected="selected">Temporary user</option>
                        <? else: ?>
                            <option value="temp" >Temporary user</option>
                        <? endif; ?>
                    </select></td>
                <td></td>
            </tr>
            <tr>
                <td>Authorzation</td>
                <td><select name="authtype" id="authtype">
                        <option selected="selected" value="free">Public</option>
                        <option value="password">Password required</option>
                        <option value="internal">Internal network required</option>
                        <option value="netid">Netid-bounded required</option>
                    </select></td>
                <td></td>
            </tr>
            <tr  id="pwdbox">
                <td>Password</td>
                <td><input name="pwd" type="text" id="pwd" size="20" maxlength="20" value=""></td>
                <td></td>
            </tr>
            <tr>
                <td>Add problems to normal problem respostry</td>
                <td><input name="addrepos" type="radio" id="addrepos" value="1" />on
                    <input name="addrepos" type="radio" id="addrepos" value="0" checked="checked"/>off</td>
                <td></td>
            </tr>
            <tr>
                <td>Information</td>
                <td><?
                        $editor = new FCKeditor("information");
                        $editor->BasePath = "./FCKeditor/";
                        $editor->ToolbarSet = "Sicily";
                        $editor->Create();
                        ?></td>
                <td>This is a contest for beginner only!!</td>
            </tr>    
            <tr>
                <td></td>
                <td><input type="submit" /> </td>
                <td> </td>
            </tr>
        </tbody>
    </table>
</form>
<?
require("../footer.php");
?>
