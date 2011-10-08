<?
require("./navigation.php");
include_once ("./FCKeditor/fckeditor.php");

$cid = tryget('cid', 0);
$rs = new RecordSet($conn);
if ($cid) {
    $contest = new ContestsTbl($cid);
    $contest->Get() or error("contest not exists");
    $contest_title = $contest->detail['title'];
    $problems_n = gen_new_cpid($cid);
} else {
    $contest_title = "";
    $problems_n = gen_new_pid();
}
?>  

<script type="text/javascript">

    var datanum=0;

    function insert()
    {
        var box = "";
        box += "<tr>";
        box += "<td>Input Data"+datanum+"</td>";
        box += "<td><input type=file name=\"input_data"+datanum+"\" id=\"input_data"+datanum+"\"></td>";
        box += "</tr>";

        box += "<tr>";
        box += "<td> Output Data"+datanum+"</td>";
        box += "<td> <input type=file name=\"standard_output"+datanum+"\" id=\"standard_output"+datanum+"\"></td>";
        box += "</tr>";

        $("#action_bar").before(box);	
	
        datanum++;
    }
	
    function validate(editmode) {
        if (editmode == 'raw') {
            return $("#cid").val() != ""
                && $("#pid").val() != "" 
                && $("#title").val() != "";
        } else if (editmode == 'import') {
            return true;
        } else if (editmode == 'refer') {
            return $("#ipid").val() != "";
        }
        return false;
    }

    $(function(){
        $("input:radio[name=editmode]").click(function(){
            $(".modecnt").hide();
            $("#"+$(this).val()+"mode").show();
        }).filter('[value=raw]').click();
		
        insert();
        $("form input:submit").click(function(){
            var editmode = $("input:radio[name=editmode]:checked").val(); 
            if (!validate(editmode)) {
                alert("Some required fields is empty");
                return false;
            }
            return true;
        });
    });
	
</script>
<form action="process.php?act=AddContestProblem" method="post" enctype="multipart/form-data">
    <input type="hidden" name="avail" value="1"/>
    <input name="cid" type="hidden" id="cid" value="<? echo $cid; ?>" size="5" maxlength="5"/>
    <table class="ui-widget tblcontainer ui-widget-content ui-corner-all" width="100%">
        <caption>Create New Problem</caption>
        <thead>
            <tr class="ui-widget-header">
                <th width="150">Option</th>
                <th>Content</th>
            </tr>
        </thead>
        <tbody class="ui-widget-content">
            <? if ($cid): ?>
                <tr>
                    <td>Contest Title</td>
                    <td><?= $contest_title ?></td>
                </tr>
            <? endif; ?>
            <tr>
                <td>Problem ID</td>
                <td><input name="pid" type="text" id="pid" value="<? echo $problems_n; ?>" size="5" maxlength="5" readonly="readonly" /> </td>
            </tr>
            <tr>
                <td>Edit Style</td>
                <td>
                    <input type="radio" name="editmode" value="raw" id="raw"/> <label for="raw">Raw : Complete new problem </label><br>
                    <? if ($cid): ?>
                        <input type="radio" name="editmode" value="refer" id="refer"/><label for ="refer"> Refer: From existing repository problems</label>
                    <? endif; ?>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="modecnt" id="rawmode">
        <table class="ui-widget tblcontainer ui-widget-content ui-corner-all" width="100%">
            <caption>Raw Mode</caption>
            <thead >
                <tr class="ui-widget-header">
                    <th width="150">Option</th>
                    <th>Content</th>
                </tr>
            </thead>
            <tbody class="ui-widget-content">
                <tr>
                    <td>Title*</td>
                    <td>
                        <input name="title" type="text" id="title" size="50" maxlength="100" />
                    </td>
                </tr>

                <tr>
                    <td>Time Limit</td>
                    <td>
                        <input name="time_limit" type="text" id="time_limit" size="5" maxlength="5" value="1"> Seconds
                    </td>
                </tr>

                <tr>
                    <td>Memory Limit</td>
                    <td>
                        <input name="memory_limit" type="text" id="memory_limit" size="8" maxlength="8" value="262144">

                        KB
                    </td>
                </tr>

                <tr>
                    <td>Description</td>
                    <td>
                        <?
                        $editor = new FCKeditor("description");
                        $editor->BasePath = "./FCKeditor/";
                        $editor->ToolbarSet = "Sicily";
                        $editor->Create();
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>Input</td>
                    <td>
                        <?
                        $editor = new FCKeditor("input");
                        $editor->BasePath = "./FCKeditor/";
                        $editor->ToolbarSet = "Sicily";
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
                        $editor->Create();
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>Author</td>
                    <td>
                        <textarea name="author" cols="75" rows="2" id="author" style="width:100%"><?= $contest_title ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td>Hint</td>
                    <td>
                        <?
                        $editor = new FCKeditor("hint");
                        $editor->BasePath = "./FCKeditor/";
                        $editor->ToolbarSet = "Sicily";
                        $editor->Create();
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>Sample Input</td>
                    <td>
                        <textarea name="sample_input" cols="75" rows="5" id="sample_input" style="width:100%"></textarea>
                    </td>
                </tr>

                <tr>
                    <td>Sample Output</td>
                    <td>
                        <textarea name="sample_output" cols="75" rows="5" id="sample_output" style="width:100%"></textarea>
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
                                <li> Sample Files: <a href="../stuff/spj_sample.cpp"> 1150spj </a> </li>
                            </ul>			
                        </div>
                    </td>
                </tr>
                <tr>
                    <td> Framework Judge</td>
                    <td>
                        <label for="usefw">Enable:</label> <input type="checkbox" id="usefw" name="usefw" onclick = "$('#fw_hint').toggle()"/>
                        <div id = "fw_hint" style="display:none">
                            Framework source code: <input type="file" name="fwfile" id="fwfile" /> 
                            <hr />
                            Usage: #include "source.cpp" in the framework source. <br />
                            For example: 
                            <pre><code>
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
};</code></pre>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td> Archive Upload </td>
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
                <tr id ="action_bar">
                    <td colspan="2">
            <center><input type="button" value="+ More Testdata" onclick="insert()" /></input></center>
            </td>
            </tr>
            <tr>
                <td colspan="2"><center><input type="submit" /> </center></td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="modecnt" id="importmode">
        <table class="ui-widget tblcontainer ui-widget-content ui-corner-all" width="100%">
            <caption>Import Mode</caption>
            <thead >
                <tr class="ui-widget-header">
                    <th width="150">Option</th>
                    <th>Content</th>
                </tr>
            </thead>
            <tbody class="ui-widget-content">
                <tr>
                    <td>Upload archive file(.zip)</td>
                    <td> <input type="file" name="importfile" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" /> </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="modecnt" id="refermode">
        <table class="ui-widget tblcontainer ui-widget-content ui-corner-all" width="100%">
            <caption>Refer Mode</caption>
            <thead >
                <tr class="ui-widget-header">
                    <th width="100">Option</th>
                    <th>Content</th>
                </tr>
            </thead>
            <tbody class="ui-widget-content">
                <tr>
                    <td>Imported Problem ID</td>
                    <td> PID: <input name="ipid" type="text" id="ipid" size="5" maxlength="5" /> <hr>
                        <a href="../problem_list.php?vol=0" title="View full problem list" target="_blank">
                            View full problem list.							
                        </a></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value="Submit New Problem"/> </td>
                </tr>
            </tbody>
        </table>
    </div>


</form>
<?
require("../footer.php");
?>
