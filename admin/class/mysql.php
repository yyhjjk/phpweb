<?php
// ========================== �ļ�˵�� ==========================//
// ���ļ�˵�������ݿ���
// =============================================================//
error_reporting(7);

class DB_MySQL  {

      var $servername="localhost";
      var $dbname="angel";
      var $dbusername="root";
      var $dbpassword="";

      var $id = 0;
      var $link_id = 0;
      var $query_id = 0;

      var $querycount = 0;
      var $result;
      var $record = array();
      var $rows;
      var $affected_rows = 0;
      var $insertid;

      var $errno;
      var $error;

      function geterrdesc() {
               $this->error = @mysql_error($this->link_id);
               return $this->error;
      }

      function geterrno() {
               $this->errno = @mysql_errno($this->link_id);
               return $this->errno;
      }

      function connect(){
               global $usepconnect;
               if ($usepconnect==1){
                   if (!$this->link_id = @mysql_pconnect($this->servername,$this->dbusername,$this->dbpassword)){
                        $this->halt("���ݿ�����ʧ��");
                   }
               } else {
                   if (!$this->link_id = @mysql_connect($this->servername,$this->dbusername,$this->dbpassword)){
                        $this->halt("���ݿ�����ʧ��");
                   }
               }
               return $this->link_id;
      }

      function selectdb(){
               if(!mysql_select_db($this->dbname)){
                   $this->halt("���ݿ�����ʧ��");
               }
      }

      function query($query_string) {
               $this->result = mysql_query($query_string,$this->link_id);
               if (!$this->result) {
                   $this->halt("SQL ��Ч: ".$query_string);
               }
               $this->querycount++;
               return $this->result;
      }


      function fetch_array($queryid) {
               $this->record = mysql_fetch_array($queryid);
               if (empty($queryid)){
                   $this->halt("Query id ��Ч:".$queryid);
               }
               return $this->record;
      }

      function fetch_row($queryid) {
               $this->record = mysql_fetch_row($queryid);
               if (empty($queryid)){
                    $this->halt("queryid ��Ч:".$queryid);
               }
               return $this->record;
      }

      function fetch_one_array($query) {
               $this->result =  $this->query($query);
               $this->record = $this->fetch_array($this->result);
               if (empty($query)){
                   $this->halt("Query id ��Ч:".$query);
               }
               return $this->record;
      }

      function num_rows($queryid) {
               $this->rows = mysql_num_rows($queryid);
               if (empty($queryid)){
                   $this->halt("Query id ��Ч:".$queryid);
               }
               return $this->rows;
      }

      function affected_rows() {
               $this->affected_rows = mysql_affected_rows($this->link_id);
               return $this->affected_rows;
      }

      function free_result($query){
               if (!mysql_free_result($query)){
                    $this->halt("fail to mysql_free_result");
               }
      }

      function insert_id(){
               $this->insertid = mysql_insert_id();
               if (!$this->insertid){
                    $this->halt("fail to get mysql_insert_id");
               }
               return $this->insertid;
      }

      function close() {
               @mysql_close($this->link_id);
      }


      function halt($msg){
               $message = "<html>\n<head>\n";
               $message .= "<meta content=\"text/html; charset=gb2312\" http-equiv=\"Content-Type\">\n";
               $message .= "<STYLE TYPE=\"text/css\">\n";
               $message .=  "body,td,p,pre {\n";
               $message .=  "font-family : Verdana, sans-serif;font-size : 12px;\n";
               $message .=  "}\n";
               $message .=  "</STYLE>\n";
               $message .= "</head>\n";
               $message .= "<body bgcolor=\"#EEEEEE\" text=\"#000000\" link=\"#006699\" vlink=\"#5493B4\">\n";

                   $content = "<p>���ݿ����:</p><pre><b>".htmlspecialchars($msg)."</b></pre>\n";
                   $content .= "<b>Mysql error description</b>: ".$this->geterrdesc()."\n<br>";
                   $content .= "<b>Mysql error number</b>: ".$this->geterrno()."\n<br>";
                   $content .= "<b>Date</b>: ".date("Y-m-d @ H:i")."\n<br>";
                   $content .= "<b>Script</b>: http://".$_SERVER[HTTP_HOST].getenv("REQUEST_URI")."\n<br>";
                   $content .= "<b>Referer</b>: ".getenv("HTTP_REFERER")."\n<br><br>";

               $message .= $content;
               $message .= "</body>\n</html>";
               echo $message;
               exit;
      }
}
?>
