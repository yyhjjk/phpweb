<?php
// ========================== �ļ�˵�� ==========================//
// ���ļ�˵�������ݿ�������
// ==============================================================//
error_reporting(7);
// ���غ�̨��������
require_once ("global.php");
cpheader();

//�Ż�/�޸����ݿ�ҳ��
if ($_GET['action']=="optimize" OR $_GET['action']=="repair") {
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"95%\" class=\"tblborder\">\n<tr>\n<td>";
    if ($_GET['action']=="optimize") {
        $cpforms->formheader(array('title'=>'�Ż����ݿ�,��ѡ��Ҫ�Ż��ı�'));
    } else {
        $cpforms->formheader(array('title'=>'�޸����ݿ�,��ѡ��Ҫ�޸��ı�'));
    }

    $tables = mysql_list_tables($dbname);
    if (!$tables) {
        print "DB Error, could not list tables\n";
        print 'MySQL Error: ' . mysql_error();
        sa_exit("���ݿ����", "./index.php?action=main");
    }
    while ($table = $DB->fetch_row($tables)) {
           $cachetables[$table[0]] = $table[0];
           $tableselected[$table[0]] = 1;
    }

    $DB->free_result($tables);

    $cpforms->makeselect(array('text'=>'��ѡ���:',
                                'name'=>'table[]',
                                'option'=>$cachetables,
                                'selected'=>$tableselected,
                                'multiple'=>1,
                                'size'=>15
                                ));


    $cpforms->makehidden(array('name'=>'action',
                                'value'=>"do$_GET[action]"));
    $cpforms->formfooter();
	echo "</td>\n</tr>\n</table>";
}//end optimize and repair

// �Ż�/�޸�����
if ($_POST['action']=="dooptimize" OR $_POST['action']=="dorepair") {

    if ($_POST['action']=="dooptimize") {
        $a = "OPTIMIZE";
        $text = "�Ż�";
    } else {
        $a = "REPAIR";
        $text = "�޸�";
    }
    if (!is_array($table) OR empty($table)) {
        sa_exit("��δѡ���κ�Ҫ${text}�ı�", "javascript:history.go(-1);");
    }

    $table = array_flip($_POST['table']);

    foreach ($table AS $name=>$value) {
             if (isset($value)) {
                 echo "����{$text}��: $name";
                 $result = $DB->query("$a TABLE $name");
                 if ($result) {
                     echo " <b>OK</b>";
                 } else {
                     echo " <font color=\"red\"><b>Failed</b></font>";
                 }
                 echo "<br>\n";
             }
    }

    echo "<p>���б����$text.</p>";
}// �Ż�/�޸���������


//�������ݿ�ҳ��
if ($_GET['action']=="backup") {
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"95%\" class=\"tblborder\">\n<tr>\n<td>";
    $cpforms->formheader(array('title'=>'�������ݿ�,��ѡ��Ҫ���ݵı�'));

    $tables = mysql_list_tables($dbname);
    while ($table = $DB->fetch_row($tables)) {
           $cachetables[$table[0]] = $table[0];
           $tableselected[$table[0]] = 1;
    }

    $DB->free_result($tables);
    $cpforms->makeselect(array('text'=>'��ѡ���:',
                                'name'=>'table[]',
                                'option'=>$cachetables,
                                'selected'=>$tableselected,
                                'multiple'=>1,
                                'size'=>15
                                ));

    $cpforms->makeinput(array('text'=>'���������������·��:<br>��ȷ�ϸ��ļ��е�����Ϊ 0777 ',
                               'name'=>'path',
                               'value'=>"./backup/Article".date("YmdHis",time()).".sql"));
    $cpforms->makehidden(array('name'=>'action','value'=>'dobackup'));
    $cpforms->formfooter();
	echo "</td>\n</tr>\n</table>";
}//backup

// ���ݲ���
if ($_POST['action']=="dobackup") {

    $table = array_flip($_POST[table]);

    $filehandle = fopen($path,"w");
    $result = $DB->query("SHOW tables");
    while ($currow = $DB->fetch_array($result)) {
           if (isset($table[$currow[0]])) {
               sqldumptable($currow[0], $filehandle);
               fwrite($filehandle,"\n\n\n");
           }
    }
    fclose($filehandle);
    sa_exit("���ݿ��ѱ���", "./index.php?action=main");
}// ���ݲ�������


// ��Ҫ����
function sqldumptable($table, $fp=0) {
         global $DB;

         $tabledump = "DROP TABLE IF EXISTS $table;\n";
         $tabledump .= "CREATE TABLE $table (\n";

         $firstfield=1;

         $fields = $DB->query("SHOW FIELDS FROM $table");
         while ($field = $DB->fetch_array($fields)) {
                if (!$firstfield) {
                    $tabledump .= ",\n";
                } else {
                    $firstfield=0;
                }
                $tabledump .= "   $field[Field] $field[Type]";
                if (!empty($field["Default"])) {
                    $tabledump .= " DEFAULT '$field[Default]'";
                }
                if ($field['Null'] != "YES") {
                    $tabledump .= " NOT NULL";
                }
                if ($field['Extra'] != "") {
                    $tabledump .= " $field[Extra]";
                }
         }
         $DB->free_result($fields);

         $keys = $DB->query("SHOW KEYS FROM $table");
         while ($key = $DB->fetch_array($keys)) {
                $kname=$key['Key_name'];
                if ($kname != "PRIMARY" and $key['Non_unique'] == 0) {
                  $kname="UNIQUE|$kname";
                }
                if(!is_array($index[$kname])) {
                  $index[$kname] = array();
                }
                $index[$kname][] = $key['Column_name'];
         }
         $DB->free_result($keys);

         while(list($kname, $columns) = @each($index)){
               $tabledump .= ",\n";
               $colnames=implode($columns,",");

               if ($kname == "PRIMARY") {
                  $tabledump .= "   PRIMARY KEY ($colnames)";
               } else {
                  if (substr($kname,0,6) == "UNIQUE") {
                      $kname=substr($kname,7);
                  }
                  $tabledump .= "   KEY $kname ($colnames)";
               }
         }

         $tabledump .= "\n);\n\n";
         if ($fp) {
             fwrite($fp,$tabledump);
         } else {
             echo $tabledump;
         }

         $rows = $DB->query("SELECT * FROM $table");
         $numfields = mysql_num_fields($rows);
         while ($row = $DB->fetch_array($rows)) {
                $tabledump = "INSERT INTO $table VALUES(";

                $fieldcounter=-1;
                $firstfield=1;
                while (++$fieldcounter<$numfields) {
                       if (!$firstfield) {
                           $tabledump.=", ";
                       } else {
                           $firstfield=0;
                       }

                       if (!isset($row[$fieldcounter])) {
                           $tabledump .= "NULL";
                       } else {
                           $tabledump .= "'".mysql_escape_string($row[$fieldcounter])."'";
                       }
                }

                $tabledump .= ");\n";

                if ($fp) {
                    fwrite($fp,$tabledump);
                } else {
                    echo $tabledump;
                }
         }
         $DB->free_result($rows);
}

cpfooter();

?>
