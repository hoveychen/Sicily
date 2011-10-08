<?php

class BaseObject {

    //////////////////////////////////////////
    // 以下变量必须在子类重新定义
    // $class_name 为类名
    // $m_strKeyField   为表的主键
    // $m_strTable 为该类使用的表名
    // $m_strTitleField 为表的unique健
    var $class_name = "BaseObject";
    var $m_strKeyField = "id"; //must override
    var $m_strTable = "";
    var $id;
    var $detail = array();
    var $update = array();
    var $field_list = array();
    var $rs = NULL;

    function BaseObject($id=0, $bConnectToDB=TRUE) {
        $this->id = $id;
        if ($bConnectToDB) {
            $this->ConnectToDB();
        }
        //die("此类不能实例化！");
    }

    ///////////////////////////////////////
    // 本函数用于将新的资料加入数据库，数据接口为 $this->detail
    // 使用时将所有要存入数据的数据存入该数组，索引必须是数据库对应的字段
    function Add() {
        $this->rs->Query($this->rs->MakeInsertSql($this->m_strTable, $this->detail));
        if (!$this->rs->affected_rows())
            return false;
        return $this->rs->insert_id();
    }

    function AddFromArray(&$aKey, &$aFields) {
        $cKey = count($aKey);
        $this->detail = NULL;
        for ($i = 0; $i < $cKey; $i++) {
            $this->detail[$aKey[$i]] = trim($aFields[$aKey[$i]]);
        }
    }

    function Get($id = 0) {
        if ($id == 0) {
            if (isset($this->id)) {
                $id = $this->id;
            } else
                return FALSE;
        }

        return $this->GetByField($this->m_strKeyField, $id);
    }

    function GetByField($field, $value, $get_field="*") {
        $value = mysql_real_escape_string($value);
        $this->rs->Query("SELECT $get_field FROM " . $this->m_strTable . " WHERE $field = '$value'");

        if ($this->rs->MoveNext()) {
            $this->detail = $this->rs->Fields;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function GetByFields($field_array, $get_field="*") {
        $field_str = "";
        while (list($field, $value) = each($field_array)) {
            if ($field_str != "") {
                $field_str .= " AND ";
            }
            $value = mysql_real_escape_string($value);
            $field_str .= "$field = '$value'";
        }

        $this->rs->Query("SELECT $get_field FROM " . $this->m_strTable . " WHERE $field_str");
        if ($this->rs->MoveNext()) {
            $this->detail = $this->rs->Fields;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function MoreRows() {
        if ($this->rs == null)
            return FALSE;
        if ($this->rs->MoveNext()) {
            $this->detail = $this->rs->Fields;
            return true;
        } else {
            return false;
        }
    }

    function SetFields(&$fields) {
        $fields_cnt = count($this->field_list);
        if ($fields_cnt == 0)
            return;

        for ($i = 0; $i < $fields_cnt; $i++) {
            if (isset($fields[$this->field_list[$i]])) {
                $this->detail[$this->field_list[$i]] = $fields[$this->field_list[$i]];
            }
        }
    }

    function UpdateFields(&$fields) {
        $fields_cnt = count($this->field_list);
        if ($fields_cnt == 0)
            return;

        for ($i = 0; $i < $fields_cnt; $i++) {
            if (isset($fields[$this->field_list[$i]])) {
                $this->update[$this->field_list[$i]] = $fields[$this->field_list[$i]];
            }
        }
    }

    /*
     * 用于释放资源，不一定要调用
     */

    function EndGet() {
        $this->FreeResource();
    }

    //////////////////////////////////////////////////////
    // 本函数使用时必需在子类重新定义成员变量 $m_strKeyField 否则出错
    function Del(&$id) {
        /*
          if ( $this->m_strKeyField == "" ) {
          die($this->class_name." 类没有重定义 $m_strKeyField 变量");
          }
         */

        if (empty($id)) {
            return 0;
        }
        if (is_array($id)) {
            $idlist = mysql_real_escape_string(implode(",", $id));
            if (empty($idlist))
                return 0;
            $sql = "DELETE FROM " . $this->m_strTable . " WHERE " . $this->m_strKeyField . " IN ($idlist)";
        }
        else {
            $id = mysql_real_escape_string($id);
            $sql = "DELETE FROM " . $this->m_strTable . " WHERE " . $this->m_strKeyField . " = $id";
        }
        $this->rs->Query($sql);
        return $this->rs->affected_rows();
    }

    //////////////////////////////////////////////
    // 重置 $this->update 数组
    function StartUpdate() {
        $this->update = NULL;
    }

    function Update($id=0) {
        if ($id == 0) {
            if (isset($this->id)) {
                $id = $this->id;
            } else
                return FALSE;
        }

        reset($this->update);
        $id = mysql_real_escape_string($id);
        $sql = $this->rs->MakeUpdateSql($this->m_strTable, $this->update, $this->m_strKeyField . "=$id");
        $this->rs->Query($sql);

        return $this->rs->affected_rows();
    }

    function UpdateMisc($field_array) {
        $field_str = "";
        while (list($field, $value) = each($field_array)) {
            if ($field_str != "") {
                $field_str .= " AND ";
            }
            $field_str .= "$field = '$value'";
        }

        reset($this->update);
        $sql = $this->rs->MakeUpdateSql($this->m_strTable, $this->update, $field_str);
        $this->rs->Query($sql);

        return $this->rs->affected_rows();
    }

    function UpdateByFields($field_array) {
        return $this->UpdateMisc($field_array);
    }

    function UpdateFromArray($id, &$aArray, &$aFields) {
        $cArray = count($aFields);
        for ($i = 0; $i < $cArray; $i++) {
            $this->update[$aFields[$i]] = trim($aArray[$aFields[$i]]);
        }
        return $this->Update($id);
    }

    ///////////////////////////////
    // 用于连接数据库
    function ConnectToDB() {
        global $conn;
        if (!isset($conn)) {
            $conn = new Connection();
            if (!$conn->connect()) {
                die("服务器忙！");
            }
        }
        if (!is_object($this->rs)) {
            $this->rs = new RecordSet($conn);
        }
    }

    /*
     * 用于释放资源，不一定要调用
     */

    function FreeResource() {
        $this->rs->free_result();
    }

}

class UserTbl extends BaseObject {

    var $class_name = "UserTbl";
    var $m_strKeyField = "uid"; //must override
    var $m_strTable = "user";
    var $field_list = array(
        "username", "email", "address", "solved", "submissions", "list",
        "reg_time", "perm", "netid", "applynetid", "authcode", "authtime",
        'nickname', 'signature', 'cn_name', 'en_name', 'gender', 'grade',
        'class', 'major', 'student_id');

}

class ContestRegistrationTbl extends BaseObject {

    var $class_name = "ContestRegistrationTbl";
    var $m_strKeyField = "cid"; //must override
    var $m_strTable = "registration";
    var $field_list = array("uid", "cid", "restrict_ip");

}

class CourseRegTbl extends BaseObject {

    var $class_name = "CourseRegTbl";
    var $m_strKeyField = "course_id"; //must override
    var $m_strTable = "course_reg";
    var $field_list = array('course_id', 'uid');

}

class ProblemTbl extends BaseObject {

    var $class_name = "ProblemTbl";
    var $m_strKeyField = "pid"; //must override
    var $m_strTable = "problems";
    var $field_list = array(
        "title", "time_limit", "memory_limit",
        "description", "input", "output", "sample_input",
        "sample_output", "accepted", "submissions", "special_judge",
        "has_framework", "avail", "rate_tot", "rate_count",
        "cid", 'author', 'stdsid');

}

class ContestProblemTbl extends BaseObject {

    var $class_name = "ContestProblemTbl";
    var $m_strKeyField = "cid"; //must override
    var $m_strTable = "contest_problems";
    var $field_list = array("cpid", "pid");

}

class QueueTbl extends BaseObject {

    var $class_name = "QueueTbl";
    var $m_strKeyField = "qid"; //must override
    var $m_strTable = "queue";
    var $field_list = array("sid", "cid", "cpid");

}

class StatusTbl extends BaseObject {

    var $class_name = "StatusTbl";
    var $m_strKeyField = "sid"; //must override
    var $m_strTable = "status";
    var $field_list = array(
        "uid", "pid", "language", "status", "run_time", "run_memory", "time",
        "failcase", "contest", "codelength", "public");

}

class ContestsTbl extends BaseObject {

    var $class_name = "ContestsTbl";
    var $m_strKeyField = "cid"; //must override
    var $m_strTable = "contests";
    var $field_list = array(
        "title", "starttime", "during", "perm", "ipbind",
        "authtype", "pwd", "owner", "addrepos", "information",
        "avail", "course_id");

}

class CourseTbl extends BaseObject {

    var $class_name = "CourseTbl";
    var $m_strKeyField = "course_id"; //must override
    var $m_strTable = "courses";
    var $field_list = array('name', 'teacher', 'description', 'avail', 'owner', 'require_cinfo', 'require_bound');

}

class RanklistTbl extends BaseObject {

    var $class_name = "RanklistTbl";
    var $m_strKeyField = "cid"; //must override
    var $m_strTable = "ranklist";
    var $field_list = array("uid", "pid", "accepted", "submissions", "ac_time");

}

class RatingTbl extends BaseObject {

    var $class_name = "RatingTbl";
    var $m_strKeyField = "uid";
    var $m_strTable = "rating";
    var $field_list = array("uid", "pid", "rate");

}

class ContestObject extends BaseObject {

    var $cid;
    var $class_name = "ContestObject";
    var $m_strKeyField = "id"; //must override
    var $m_strTable = "";
    var $field_list = array('cid');
    var $field_property = array();

    function ContestObject($cid, $id = 0) {
        BaseObject::BaseObject($id, true);
        $this->cid = $cid;
        $field_list['cid'] = $cid;
    }

    function GetByField($field, $value, $get_field="*") {
        $this->rs->Query("SELECT $get_field FROM " . $this->m_strTable . " WHERE $field = '$value' AND cid = '" . $this->cid . "'");

        if ($this->rs->MoveNext()) {
            $this->detail = $this->rs->Fields;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function Update($id=0) {
        if ($id <= 0 && ($id = $this->id) <= 0)
            return FALSE;

        reset($this->update);
        $sql = $this->rs->MakeUpdateSql($this->m_strTable, $this->update, $this->m_strKeyField . "='$id' AND cid='" . $this->cid . "'");
        $this->rs->Query($sql);

        return $this->rs->affected_rows();
    }

}

class ContestProblem extends ContestObject {

    var $class_name = "ContestProblem";
    var $m_strKeyField = "cpid"; //must override
    var $m_strTable = "contest_problems";
    var $field_list = array(
        "pic", "title", "time_limit", "memory_limit", "description",
        "input", "output", "sample_input", "sample_output",
        //"contest_problems.accepted AS accepted", 
        //"contest_problems.submissions AS submisstions",
        "accepted", "submisstions", "has_framework",
        "special_judge", "avail");

    function GetByField($field, $value, $get_field="*") {
        $value = mysql_real_escape_string($value);
        $this->rs->Query("SELECT $get_field FROM {$this->m_strTable} LEFT JOIN problems ON problems.pid = contest_problems.pid WHERE contest_problems.cid = '{$this->cid}' AND $field = '$value'");

        if ($this->rs->MoveNext()) {
            $this->detail = $this->rs->Fields;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function GetByFields($field_array, $get_field="*") {
        $field_str = "";
        while (list($field, $value) = each($field_array)) {
            if ($field_str != "") {
                $field_str .= " AND ";
            }
            $value = mysql_real_escape_string($value);
            $field_str .= "$field = '$value'";
        }
        if (empty($field_str))
            $field_str = "1 = 1";

        $this->rs->Query("SELECT $get_field FROM {$this->m_strTable} LEFT JOIN problems ON problems.pid = contest_problems.pid WHERE contest_problems.cid = '{$this->cid}' AND $field_str");
        if ($this->rs->MoveNext()) {
            $this->detail = $this->rs->Fields;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function Add() {
        //die($this->rs->MakeInsertSql($this->m_strTable, $this->detail));
        //$this->rs->Query( $this->rs->MakeInsertSql("problems", $this->detail) );
        //$this->rs->Query(" INSERT INTO problems ()");
        $this->rs->Query(" INSERT INTO contest_problems (cpid, cid, pid) VALUES ('" . implode("','", $this->detail) . "')");
        return $this->rs->insert_id();
    }

}

class ContestStatus extends ContestObject {

    var $class_name = "ContestStatus";
    var $m_strKeyField = "csid"; //must override
    var $m_strTable = "contest_status";
    var $field_list = array(
        "uid", "pid", "language", "status",
        "run_time", "run_memory", "time");

    function GetByField($field, $value, $get_field="*") {
        $value = mysql_real_escape_string($value);
        $this->rs->Query("SELECT $get_field FROM {$this->m_strTable} LEFT JOIN status ON status.sid = contest_status.sid WHERE contest_status.cid = '{$this->cid}' AND $field = '$value'");

        if ($this->rs->MoveNext()) {
            $this->detail = $this->rs->Fields;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function GetByFields($field_array, $get_field="*") {
        $field_str = "";
        while (list($field, $value) = each($field_array)) {
            if ($field_str != "") {
                $field_str .= " AND ";
            }
            $value = mysql_real_escape_string($value);
            $field_str .= "$field = '$value'";
        }
        if (empty($field_str))
            $field_str = "1 = 1";
        $this->rs->Query("SELECT $get_field FROM {$this->m_strTable} LEFT JOIN status ON status.sid = contest_status.sid WHERE contest_status.cid = '{$this->cid}' AND $field_str");
        if ($this->rs->MoveNext()) {
            $this->detail = $this->rs->Fields;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function Add() {
        $cid = $this->detail['cid'];
        $sid = $this->detail['sid'];
        $cpid = $this->detail['cpid'];
        $this->rs->Query("INSERT INTO contest_status (cid, sid, cpid) VALUES ($cid, $sid, $cpid)");
    }

}

class ContestIPTbl extends BaseObject {

    var $class_name = "ContestIPTbl";
    var $m_strKeyField = "sid"; //must override
    var $m_strTable = "contest_ip";
    var $field_list = array("cid", "username", "userip");

    function GetByField($field, $value, $get_field="*") {
        $this->rs->Query("SELECT $get_field FROM " . $this->m_strTable .
                " WHERE $field = '$value' AND cid = '" . $this->cid . "'");

        if ($this->rs->MoveNext()) {
            $this->detail = $this->rs->Fields;
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

?>