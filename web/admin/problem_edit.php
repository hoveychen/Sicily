<?php
require("./navigation.php");
?>

<?
include_once ("./FCKeditor/fckeditor.php");
$cid = @$_GET['cid'];
$pid = @$_GET['pid'];
if ($cid) {
    $problem = new ContestProblem($cid);
    $contest = new ContestsTbl($cid);
    $contest->Get();
    $contest_title = $contest->detail['title'];
} else {
    $problem = new ProblemTbl();
    $cid = 0;
}
if (!$problem->Get($pid))
    MsgAndRedirect("No such problem", "index.php");
$title = $problem->detail['title'];
?>
<form action="process.php?act=EditContestProblem" method="post" enctype="multipart/form-data">
    <input type="hidden" name="avail" value="1" />
    <input name="cid" type="hidden" id="cid" value="<? echo $cid; ?>">
    <table id="tb" width="100%" class="ui-widget tblcontainer ui-widget-content ui-corner-all">
        <caption>Edit problems</caption>
        <thead>
            <tr class="ui-widget-header">
                <th width="150">Option</th>
                <th>Content</th>
            </tr>
        </thead>
        <tbody class="ui-widget-content">
            <tr>
                <td>Navigation</td>
                <td><a href="../show_problem.php?pid=<?= $pid ?>&cid=<?= $cid ?>">Preview Problem</a>
                    <a href="contest_detail.php?cid=<?= $cid ?>">Go back to Contest</a>
                </td>
            </tr>
            <? if ($cid): ?>
                <tr>
                    <td>Contest Title</td>
                    <td><?= $contest_title ?></td>
                </tr>
            <? endif; ?>
            <tr>
                <td>ID</td>
                <td>
                    <input name="pid" type="text" id="pid" value="<? echo $pid; ?>" size="5" maxlength="5" readonly="readonly">
                </td>
            </tr>

            <tr>
                <td> Title</td>

                <td>
                    <input name="title" type="text" id="title" size="50" maxlength="100" value="<? echo $title; ?>">
                </td>
            </tr>

            <tr>
                <td>Time Limit</td>
                <td>
                    <input name="time_limit" type="text" id="time_limit" size="5" maxlength="5" value="<? echo $problem->detail['time_limit'] ?>">
                    Seconds 
                </td>
            </tr>

            <tr>
                <td>Memory Limit</td>

                <td>
                    <input name="memory_limit" type="text" id="memory_limit" size="8" maxlength="8" value="<? echo $problem->detail['memory_limit'] ?>">
                    KB 
                </td>
            </tr>

            <tr>
                <td>Problem</td>

                <td>
                    <?
                    $editor = new FCKeditor("description");
                    $editor->BasePath = "./FCKeditor/";
                    $editor->ToolbarSet = "Sicily";
                    $editor->Value = $problem->detail['description'];
                    $editor->Create();
                    ?>
                </td>
            </tr>

            <tr>
                <td>Input</td>

                <td>
                  <!--<textarea name="input" cols="75" rows="5" id="input"></textarea>-->
                    <!--textarea name="input" cols="75" rows="5" id="sample_output"></textarea-->

                    <?
                    $editor = new FCKeditor("input");
                    $editor->BasePath = "./FCKeditor/";
                    $editor->ToolbarSet = "Sicily";
                    $editor->Value = $problem->detail['input'];
                    $editor->Create();
                    ?>
                </td>
            </tr>

            <tr>
                <td>Output</td>

                <td>
                    <?
                    $editor = new FCKeditor("output");
                    $editor->BasePath = "./FCKeditor/";
                    $editor->ToolbarSet = "Sicily";
                    $editor->Value = $problem->detail['output'];
                    $editor->Create();
                    ?>
                </td>
            </tr>

            <tr class="cmode">
                <td>Author</td>
                <td>
                    <textarea name="author" cols="75" rows="5" id="author"><?= $problem->detail['author']
                    ?></textarea>
                </td>
            </tr>

            <tr>
                <td>Hint</td>

                <td>
                  <!--<textarea name="input" cols="75" rows="5" id="input"></textarea>-->
                    <!--textarea name="input" cols="75" rows="5" id="sample_output"></textarea-->

                    <?
                    $editor = new FCKeditor("hint");
                    $editor->BasePath = "./FCKeditor/";
                    $editor->ToolbarSet = "Sicily";
                    $editor->Value = $problem->detail['hint'];
                    $editor->Create();
                    ?>
                </td>
            </tr>
            <tr>
                <td>Sample Input</td>

                <td>
                    <textarea name="sample_input" cols="75" rows="5" id="sample_input"><? echo $problem->detail['sample_input'] ?></textarea>
                </td>
            </tr>

            <tr>
                <td>Sample Output</td>

                <td>
                    <textarea name="sample_output" cols="75" rows="5" id="sample_output"><? echo $problem->detail['sample_output'] ?></textarea>
                </td>
            </tr>

            <tr>
                <td> Speical Judge </td>
                <td>
                    <label for="spj">Enable:</label> <input type="checkbox" id="spj" name="spj" onclick = "$('#spj_hint').toggle()"/> 

                    <div id = "spj_hint" style="display: none">
                        Spj source code: <input type="file" name="spjfile" id="spjfile" />
                        <hr />
                        Special judge code format: 
                        <ul>
                            <li> argv[1] for testdata input file path. </li>
                            <li> argv[2] for program output file path. </li>
                            <li> argv[3] for standard output file path. </li>
                            <li> print result to standard output. "y" or "Y" for accept, otherwise wrong answer.</li>
                            <li> Sample files: <a href="../stuff/spj_sample.cpp"> 1150spj </a> </li>
                        </ul>
                    </div>
                </td>
            </tr>

            <tr>
                <td>Framework Judge</td>
                <td>
                    <label for="usefw">Enable:</label> <input type="checkbox" id="usefw" name="usefw" onclick = "$('#fw_hint').toggle()"/>
                    <div id = "fw_hint" style="display:none">
                        Framework source code: <input type="file" name="fwfile" id="fwfile" /> 
                        <hr />
                        Usage: #include "source.cpp" in the framework source. <br />
                        For example: 
                        <pre>
// =====================
// framework source code
// =====================
#include &lt;stdio.h&gt;
#include "source.cpp"
int main() {
	MyClass * mc = new MyClass();
	mc->print("hello world");
	return 0;
}
// ==========
// source.cpp
// ==========
class MyClass {
public: 
	void print(char * str) {
		printf("%s\n", str);
	}
};
                        </pre>
                    </div>

                </td>
            </tr>


            <tr>
                <td>Test Data</td>
                <td>
                    <input type="radio" name="data_mode" value="0" onclick="$('.newdata').hide()" checked="1" id="unchange"/>
                    <label for="unchange">Unchanged</label>
                    <input type="radio" name="data_mode" value="1" onclick="$('.newdata').show()" id="rewrite"/>
                    <label for="rewrite">Rewrite</label>
                    <input type="radio" name="data_mode" value="2" onclick="$('.newdata').show()" id="append"/> 
                    <label for="append">Append</label>
                </td>
            </tr>

            <tr class='newdata'>
                <td> Archive Upload</td>
                <td>
                    <label for="arcupload">Enable:</label> <input type="checkbox" id="arcupload" name="arcupload" onclick = "$('#arc_hint').toggle()"/>
                    <div id = "arc_hint" style="display:none">
                        Archive file(.rar or .zip): <input type="file" name="arcfile" id="arcfile" /> <br />
                        Input file format: 
                        <input name="infile" type="text" id="infile" size="50" maxlength="100" /> <br />
                        Output file format: 
                        <input name="outfile" type="text" id="outfile" size="50" maxlength="100" /> <br />
                        Testcases starting number: 
                        <input name="startnumber" type="text" id="startnumber" size="5" maxlength="5" /> <br />
                        Testcases counting number: 
                        <input name="countnumber" type="text" id="countnumber" size="5" maxlength="5" /> <hr />
                        For example: test01.in test02.in ... test20.in test01.out test02.out ... test20.out 
                        <ul>
                            <li> Input file format : test%02d.in </li>
                            <li> Output file format : test%02d.out </li>
                            <li> starting number : 1 </li>
                            <li> counting number : 20 </li>
                        </ul>
                    </div>
                </td>
            </tr>




            <tr align="center" id ="action_bar">
                <td height="20" colspan="2" align="center">
                    <input class="newdata" type="button" value="add testdata" onclick="insert()"/>

                    <input type="submit" value="Submit Modification">
            </tr>
        </tbody>
    </table>
</form>


<script type="text/javascript">
    var datanum=0;

    function insert()
    {
        var box = "";
        box += "<tr class='newdata'>";
        box += "<td>Input Data"+datanum+"</td>";
        box += "<td><input type=file name=\"input_data"+datanum+"\" id=\"input_data"+datanum+"\"></td>";
        box += "</tr>";

        box += "<tr class='newdata'>";
        box += "<td>Output Data"+datanum+"</td></td>";
        box += "<td><input type=file name=\"standard_output"+datanum+"\" id=\"standard_output"+datanum+"\"></td>";
        box += "</tr>";

        $("#tb").append(box);	
        $("#tb").append($("#action_bar"));
        datanum++;
    }
    $(function(){
        insert();
        $("form input:submit").click(function(){
            if ($("#cid").val() == ""
                || $("#pid").val() == ""
                || $("#title").val() == "") {
                alert("Some required fields is empty");
                return false;
            }
            return true;
        });
        $(".newdata").hide();
    });

<?php
echo "//" . $problem->detail['special_judge'] . "  " . $problem->detail['has_framework'] . "\n";
if ($problem->detail['special_judge']) {
    echo "$('#spj').click();";
}

if ($problem->detail['has_framework']) {
    echo "$('#usefw').click();";
}
?>


</script>
<?
require("../footer.php");
?>
