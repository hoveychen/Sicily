<?
require("./navigation.php");
include_once ("./FCKeditor/fckeditor.php");
$course_id = safeget("course_id");
$course = new CourseTbl($course_id);
if (!$course->Get())
    error("Course Not Found");
if (!is_course_modifiable($course_id))
    error("No permission");
?>  

<form action="process.php?act=EditCourse" method="post" enctype="multipart/form-data">
    <input type="hidden" name="course_id" value="<?= $course->detail['course_id'] ?>"/>
    <input type="hidden" name="avail" value="1"/>
    <table class="ui-widget tblcontainer ui-widget-content ui-corner-all" width="100%">
        <caption>Edit Course</caption>
        <thead >
            <tr class="ui-widget-header">
                <th width="100">Option</th>
                <th>Content</th>
                <th width="150">Example</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Course Name</td>
                <td><input type="text" id="name" name="name" maxlength="50" size="50" value="<?= $course->detail['name'] ?>"/> </td>
                <td> Data Structure 2011 CS </td>
            </tr>
            <tr>
                <td>Teacher</td>
                <td><input type="text" id="teacher" name="teacher" maxlength="50" size="50" value="<?= $course->detail['teacher'] ?>"/> </td>
                <td> Lingsheng Xu </td>
            </tr>
            <tr>
                <td><label for="require_cinfo">Require Complete Infomation</label></td>
                <td><input type="checkbox" id="require_cinfo" name="require_cinfo" <?= $course->detail['require_cinfo']? 'checked="true"': '' ?>/></td>
                <td></td>
            </tr>
            <tr>
                <td><label for="require_bound">Require Bound with netid</label></td>
                <td><input type="checkbox" id="require_bound" name="require_bound" <?= $course->detail['require_bound']? 'checked="true"': '' ?>/></td>
                <td></td>
            </tr>

            <tr>
                <td>Description</td>
                <td>
                    <?
                    $editor = new FCKeditor("description");
                    $editor->BasePath = "./FCKeditor/";
                    $editor->ToolbarSet = "Sicily";
                    $editor->Value = $course->detail['description'];
                    $editor->Create();
                    ?>
                </td>
                <td> Anything about the course, including schedule and homework? </td>
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
