<?php

/*
 * 2004-1-26
 */

class Conn {

    var $class_name = "Conn";
    var $strHostName = "";
    var $strUserName = "";
    var $strPassword = "";
    var $strDatabase = "";
    var $m_hLinkID = 0;
    var $m_bDebug = TRUE;

    /*
      function Conn()
      {
      }
     */

    function connect($strHostName="", $strUserName="", $strPassword="", $strDatabase="") {
        if ($strHostName != "")
            $this->strHostName = $strHostName;
        if ($strUserName != "")
            $this->strUserName = $strUserName;
        if ($strPassword != "")
            $this->strPassword = $strPassword;

        $this->m_hLinkID = @mysql_connect($this->strHostName, $this->strUserName, $this->strPassword);
        $this->p_Debug("connect()");
        $this->select_db($this->strDatabase);

        mysql_query("SET NAMES 'utf8'", $this->m_hLinkID);
        mysql_query("SET CHARACTER SET UTF8", $this->m_hLinkID);
        mysql_query("SET CHARACTER_SET_RESULTS=UTF8", $this->m_hLinkID);

        return $this->m_hLinkID;
    }

    function select_db($strDBName="") {
        if ($strDBName != "")
            $this->strDatabase = $strDBName;

        return @mysql_select_db($this->strDatabase, $this->m_hLinkID);
    }

    function execute($sql) {
        return mysql_query($sql, $this->m_hLinkID);
    }

    function p_Debug($strMessage="") {
        $nErrorNO = mysql_errno($this->m_hLinkID);
        if ($nErrorNO) {
            if ($this->m_bDebug) {
                if ($strMessage != "")
                    print("error from $strMessage<br>");
                die(mysql_error());
            }
            else {
                die("发生错误！请与管理员联系！");
            }
        }
    }

    function close() {
        @mysql_close($this->m_hLinkID);
    }

    function affected_rows() {
        return mysql_affected_rows($this->m_hLinkID);
    }

}

class Connection extends Conn {

    var $class_name = "Connection";

    function Connection() {
        $this->strHostName = Config::$db_host_name;
        $this->strUserName = Config::$db_user_name;
        $this->strPassword = Config::$db_password;
        $this->strDatabase = Config::$db_database;
    }

}

;

class RecordSet {

    //数据库连接的引用
    var $m_constDbConn = NULL;
    var $m_hResultID = 0;
    var $strDatabase;
    //字段
    var $Fields = array();
    var $nPageSize = 20;
    var $nCurPage;
    var $nTotalPage;
    var $nTotalRecord;
    var $m_bDebug = TRUE;
    var $m_bAutoFree = TRUE;
    var $m_strLastSQL;

    function RecordSet(&$connection) {
        if (!$connection) {
            global $conn;
            if (!isset($conn)) {
                $conn = new Connection();
                if (!$conn->connect()) {
                    die("服务器忙！");
                }
            }
            $connection = $conn;
        }
        $this->m_constDbConn = &$connection;
    }

    function GetConnection() {
        return $this->m_constDbConn;
    }

    function SetAutoFree($b=TRUE) {
        $this->m_bAutoFree = $b;
    }

    function Query($strQuery) {
        if ($strQuery == "")
            return 0;

        $strQuery = trim($strQuery);
        $strQueryType = strtok($strQuery, " ");
        $strQueryType = strtolower($strQueryType);

        $this->m_strLastSQL = $strQuery;
        $ret = @mysql_query($strQuery, $this->m_constDbConn->m_hLinkID);
        $this->p_Debug("Query()");

        if ($strQueryType == "select") {
            $this->m_hResultID = $ret;
        }

        return $ret;
    }

    function MoveNext() {
        if (!$this->m_hResultID)
            return FALSE;

        $this->Fields = @mysql_fetch_array($this->m_hResultID);
        $this->p_Debug("MoveNext()");

        //return TRUE for there is a record, FALSE for the end
        $ret = is_array($this->Fields);
        if (!$ret && $this->m_bAutoFree)
            $this->free_result();

        return $ret;
    }

//end MoveNext

    function EnumerateRecord($fnCallBack, $vUserParam=NULL) {
        while ($this->MoveNext()) {
            $fnCallBack($vUserParam, $this->Fields);
        }
    }

    function PageCount($strQuery, $nPage = -1) {
        $this->Query($strQuery);
        $this->MoveNext();
        $this->nTotalRecord = $this->Fields[0];
        $this->nTotalPage = ceil($this->nTotalRecord / $this->nPageSize);

        if ($nPage != -1) {
            $this->SetPage($nPage);
        }
    }

    function dpQuery($strQuery) {
        $this->Query($strQuery . " limit " . (($this->nCurPage - 1) * $this->nPageSize) . "," . $this->nPageSize);
    }

    function SetPage($p=1) {
        $this->nCurPage = max(1, min($this->nTotalPage, intval($p)));
        return $this->nCurPage;
    }

// end SetPage()
    ///////////////////////////////////////////////////
    // mysql_* functions
    function affected_rows() {
        return mysql_affected_rows($this->m_constDbConn->m_hLinkID);
    }

    function select_db($strDBName="") {
        return $this->m_constDbConn->select_db($strDBName);
    }

    function num_rows() {
        return @mysql_num_rows($this->m_hResultID);
    }

// end num_rows
    //must call it just after Query
    function insert_id() {
        return @mysql_insert_id($this->m_constDbConn->m_hLinkID);
    }

    function data_seek($row_num=0) {
        return @mysql_data_seek($this->m_hResultID, $row_num);
    }

    function free_result() {
        return @mysql_free_result($this->m_hResultID);
    }

    // end mysql_* function
    ///////////////////////////////////////////////////////

    function MakeInsertSql($table, &$arrs) {
        reset($arrs);
        while (list( $field, $val ) = each($arrs)) {
            $field_arr[] = $field;
            $value_arr[] = "'" . $val . "'";
        }

        $query_str = "insert into $table ";
        $query_str.= "(" . implode(",", $field_arr) . ")";
        $query_str.= " values(" . implode(",", $value_arr) . ")";
        return $query_str;
    }

    function MakeUpdateSql($table, &$arrs, $condition = "") {
        unset($assign);
        while (list( $field, $val ) = each($arrs)) {
            $assign[] = "$field='$val'";
        }

        if ($condition == "") {
            die("Update缺乏条件！");
        }

        $query_str = "update $table set ";
        $query_str .= implode(',', $assign);
        $query_str .= " where " . $condition;
        //die($query_str);
        return $query_str;
    }

    //Helper functions
    function p_Debug($strMessage="") {
        $nErrorNO = mysql_errno($this->m_constDbConn->m_hLinkID);
        if ($nErrorNO) {
            if ($this->m_constDbConn->m_bDebug) {
                if ($strMessage != "")
                    print("error from $strMessage<br>");
                print("Last error:{$this->m_strLastSQL}<br>");
                die(mysql_error());
            }
            else {
                die("发生错误！请与管理员联系！");
            }
        }
    }

    function Navigate($extra = "") {
        global $PHP_SELF, $_GET;
        $curpage = $this->nCurPage;
        $totalpage = $this->nTotalPage;

        reset($_GET);

        if ($extra == "") {
            while (list($key, $value) = each($_GET)) {
                if ($key == "p")
                    continue;
                $extra .= "$key=$value&";
            }
        }
        else {
            $extra .="&";
        }
        reset($_GET);

        $str = "<div style='text-align:center'>";
        $buf = "<a href=$PHP_SELF?{$extra}p=%d class=\"black\"><img src=\"images/%s1.gif\" width=\"120\" height=\"35\" border=\"0\"></a>";
        $buf2 = "<img src=\"images/%s0.gif\" width=\"120\" height=\"35\" border=\"0\">";

        if ($curpage > 1) {
            $str .= sprintf($buf, $curpage - 1, "prev");
        } else {
            $str .= sprintf($buf2, "prev");
        }

        $str .= _("Page") . " $curpage / $totalpage";
        if ($curpage < $totalpage)
            $str .= sprintf($buf, $curpage + 1, "next");
        else
            $str .= sprintf($buf2, "next");

        return $str . "</div>";
    }

}

;
?>
