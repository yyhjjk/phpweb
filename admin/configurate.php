<?php
// ========================== �ļ�˵�� ==========================//
// ���ļ�˵������������
// =============================================================//
error_reporting(7);
// ���غ�̨��������
require_once ("global.php");

if($_POST['action'] == "update")
{
	//�������ݿ�
	foreach ($_POST['config'] AS $name=>$value) {
             $DB->query("UPDATE ".$db_prefix."setting SET value='".addslashes(trim($value))."' WHERE name='".addslashes($name)."'");
    }
	//��������Ϣд�������ļ�
	$config_filename = "settings.php";
    $fp = fopen($config_filename,w);

    $contents = "<?php\n\n\n";
	$settings = $DB->query("SELECT * FROM ".$db_prefix."setting");

    while ($setting = $DB->fetch_array($settings)) {
		$contents .= "/*##### $setting[title] #####*/\n";
		$contents .= "\$configuration[$setting[name]] = \"".$setting['value']."\";\n\n\n";
    }

    $contents .= "?>";

    fwrite($fp,$contents);
    fclose($fp);
	// 
    if(is_dir("../".$oldhtmldir)){
        rename("../".$oldhtmldir."/","../".$config[htmldir]);
    } else {
        mkdir("../".$config[htmldir],0777);
    }
    if(is_dir("../".$oldattachdir."/")){
        rename("../".$oldattachdir,"../".$config[attachdir]);
    } else {
        mkdir("../".$config[attachdir],0777);
    }
    redirect("���³�����Ϣ�ɹ�!", "./configurate.php");
} //end update

cpheader();
?>

<?php
if (!isset($_GET['action']))
{
	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"95%\" class=\"tblborder\">\n<tr>\n<td>";
	$cpforms->formheader(array('title'=>'����ѡ������'));
	$settings = $DB->query("SELECT * FROM ".$db_prefix."setting ORDER BY settingid");
	while ($setting = $DB->fetch_array($settings)) {
		//��ȡ���ݿ���Ϣ�����������������ɲ�ͬ���͵ı�
		if ($setting['type']=="boolean") {            $cpforms->makeyesno(array('text'=>"<b>".htmlspecialchars($setting['title'])."</b><br>".$setting['description']."",
            'name'=>"config[".htmlspecialchars($setting['name'])."]",
            'selected'=>intval($setting['value']),
			));
        } elseif ($setting['type']=="string") {
			$cpforms->makeinput(array('text'=>"<b>".htmlspecialchars($setting['title'])."</b><br>".$setting['description']."",
            'name'=>"config[".htmlspecialchars($setting['name'])."]",
            'value'=>htmlspecialchars($setting['value'])
            ));
        } elseif ($setting['type']=="integer") {
            $cpforms->makeinput(array('text'=>"<b>".htmlspecialchars($setting['title'])."</b><br>".$setting['description']."",
            'name'=>"config[".htmlspecialchars($setting['name'])."]",
            'value'=>intval($setting['value']),
            'maxlength'=>10,
            'size'=>10
            ));
        } elseif ($setting['type']=="templates") {
            echo "<tr class=\"".getrowbg()."\" 
	nowrap><td><b>".htmlspecialchars($setting['title'])."</b><br>".htmlspecialchars($setting['description'])."</td>";
            echo "".showtemplates('../templates',htmlspecialchars($setting['value']))."</tr>";
		} elseif ($setting['type']=="text") {
            $cpforms->maketextarea(array('text'=>"<b>".htmlspecialchars($setting['title'])."</b><br>".$setting['description']."",
            'name'=>"config[".htmlspecialchars($setting['name'])."]",
            'value'=>htmlspecialchars($setting['value'])
            ));
        } elseif ($setting['type']=="yesno") {
	$cpforms->makeyesno(array('text'=>"<b>".htmlspecialchars($setting['title'])."</b><br>".$setting['description']."",
            'name'=>"config[".htmlspecialchars($setting['name'])."]",
            'selected'=>htmlspecialchars($setting['value'])
            ));
        }
     }
    $cpforms->makehidden(array('name'=>'action',
                               'value'=>'update'));
    $cpforms->makehidden(array('name'=>'oldhtmldir',
                               'value'=>$configuration[htmldir]));
    $cpforms->makehidden(array('name'=>'oldattachdir',
                               'value'=>$configuration[attachdir]));
    $cpforms->formfooter();
	echo "</td>\n</tr>\n</table>";
	cpfooter();
}//!isset
function showtemplates($dirpath,$selected){
    $dirhandle = @opendir($dirpath)
        or exit("$dirpath don't find" );
        echo "<td><select name=\"config[template]\" >";
    while (false !== ($name = readdir($dirhandle))){
        if ($name == "." or $name == "..") continue;
        if (is_dir($dirpath.DIRECTORY_SEPARATOR.$name)){
            if ($name == $selected){
            echo "<option value=\"".$name."\" selected>".$name."</option>";
            } else {
            echo "<option value=\"".$name."\">".$name."</option>";
            }
        };//end if
    };//end while
    echo "</select></td>";
    closedir($dirhandle);
}//end funtion showtemplates
if($_GET['action'] == "phpinfo")
{
	phpinfo();
	exit();
}//phpinfo
?>
