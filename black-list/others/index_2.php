<?php
header("content-Type: text/html; charset=gb2312");
/* 
+----------------------------------------------------------------------
|   **����㿴�������˵����ķ�������֧��PHP**
+----------------------------------------------------------------------
|   �ļ����� �����PHP̽��
+---------------------------------------------------------------------- 
|   Copyright  2003-2005 WapCity ��Ȩ���в��������а�Ȩ                  
+---------------------------------------------------------------------- 
|   ��̽������ʱ�ο�������һЩ̽��        
|   ���������߱�ʾ��л                      
+----------------------------------------------------------------------
|   ���ߣ� ����θ��d < anerg@183.ha.cn > [ QQ:1616676 ]
+----------------------------------------------------------------------
*/
$version = "1.3.5";
$AD = '* ��ӭʹ�÷����PHP̽�룬�����򹫿�Դ���룬��������⸴�ơ�������ʹ�á�<BR>
            &nbsp;&nbsp;����Դ��ҵ���վ ( <A 
            href="http://wapcity.org.ru/" target=_blank>http://wapcity.org.ru/</A>), ������֧��վ�����ص�������<BR><a href=./blog/>��˽����ҵ�BLOG</a>';
extract($_GET);extract($_POST);
function getmicrotime()
{ 
    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec); 
} 
$page_time_start=getmicrotime();
//�ű�����ʱ��
function addTime()
{
	$time_start=getmicrotime();
	for($index=0;$index<=500000;$index++);
	{
		$count=1+1;
	}
	$time_end=getmicrotime();
	$time=$time_end-$time_start;
	$time=round($time*1000);
	$time="<font color=red>$time</font>";
	return($time);	
}//END FUNCTION
function sqrtTime()
{
	$test=pi();
	$time_start=getmicrotime();
	for($index=0;$index<=500000;$index++);
	{
		sqrt($test);
	}
	$time_end=getmicrotime();
	$time=$time_end-$time_start;
	$time=round($time*1000);
	$time="<font color=red>$time</font>";
	return($time);
}//END FUNCTION
function echo_info($str)
	{
	echo "<script>alert('$str')</script>";
	}
if ("mysql" == $conn)
{
	if(function_exists("mysql_close")==1)
	{
		$link = @mysql_connect($sql_host.":".$sql_port, $sql_login, $sql_password);
		if ($link) 
		{
			echo_info("�ʺ� $sql_login ���ӵ�MySql���ݿ�����");
		} else
		{
			echo_info("�޷����ӵ�MySql���ݿ⣡");
		}
	}
	else
	{
		echo_info("��������֧��MySQL���ݿ⣡");
	}
}//END IF
if ("psql" == $conn)
{
	if(function_exists("pg_connect")==1)
	{
		$conn_string = "host=$sql_host port=$sql_port dbname=$sql_dbname user=$sql_login password=$sql_password";
		$link = @pg_connect($conn_string);
		if ($link) 
		{
			echo_info("�ʺ� $sql_login ���ӵ�PostgreSQL���ݿ�����");
		} else
		{
			echo_info("�޷����ӵ�PostgreSQL���ݿ⣡");
		}
	}
	else
	{
		echo_info("��������֧��PostgreSQL���ݿ⣡");
	}
}//END IF
//������ʱ��Ƚ�
$svr[] = "�ҵĵ��� (P4/1.7G+256M+Win2k)";
//��������
$svr_atime[] = "404";
//��������
$svr_stime[] = "398";

$svr[] = "chromehost.com (2004-5-14)";
$svr_atime[] = "324";
$svr_stime[] = "314";

$svr[] = "www.psychz.net (2004-5-14)";
$svr_atime[] = "160";
$svr_stime[] = "152";

$svr[] = "cun.jp (2004-5-14)";
$svr_atime[] = "733";
$svr_stime[] = "579";

$svr[] = "�������� ������(L)-200M (2004-5-14)";
$svr_atime[] = "554";
$svr_stime[] = "551";

//��ǰ����
$svr[] = "<font color=red>��ǰ��̨������</font>";
$svr_atime[] = addTime();
$svr_stime[] = sqrtTime();

if ("phpinfo" == $testinfo)
{
	phpinfo();
	exit;
}//END IF
function temp($temp)
{
	if($temp==1)
	{
	$s='<font color=green>֧��<b>��</b></font>';
	}
	else
	{
	$s='<font color=red>��֧��<b>��</b></font>';
	}
	return $s;
}
/*��ȡ��������Ϣ*/
$info[] = $_SERVER['SERVER_NAME'];//������
$info[] = getenv(SERVER_ADDR); //������IP
$info[] = getenv(SERVER_PORT);//�˿�
$info[] = PHP_OS; //����������ϵͳ
$info[] = $_SERVER['SERVER_SOFTWARE']; //web�������汾
$info[] = PHP_VERSION;//php�汾
$info[] = getenv("HTTP_ACCEPT_LANGUAGE"); //����������
$info[] = zend_version();
$info[] = $_SERVER['DOCUMENT_ROOT']. "<br>".$_SERVER['$PATH_INFO']; //����·��
$info[] = intval(diskfreespace(".") / (1024 * 1024))."M"; //�������ռ��С
$info[] = date("n��j��H��i��s��"); //������ʱ��
$info[] = get_current_user(); //�û�
$info[] = isset($_SERVER["SERVER_ADMIN"])?"<a href=\"mailto:$_SERVER[SERVER_ADMIN]\" title=�����ʼ�>$_SERVER[SERVER_ADMIN]</a>":"<a href=\"mailto:get_cfg_var(sendmail_from)\" title=�����ʼ�>get_cfg_var(sendmail_from)</a>"; //����Ա����
/*PHP��������*/
$dis_func = get_cfg_var("disable_functions");
$php[] = ereg("phpinfo",$dis_func)?"<font color=red>��֧��<b>��</b></font>":"<font color=green>֧��<b>��</b></font><a href=$PHP_SELF?testinfo=phpinfo title=��˲鿴PHPINFOϸ��Ϣ>��˲鿴PHPINFOϸ��Ϣ</a>";
$php[] = get_cfg_var("register_globals")==0?"<font color=red>��֧��<b>��</b></font>":"<font color=green>֧��<b>��</b></font>";
$php[] = get_cfg_var("memory_limit")?get_cfg_var("memory_limit"):"��"; //�����ű�����ʱ��ռ�õ�����ڴ�
$php[] = get_cfg_var("upload_max_filesize")?get_cfg_var("upload_max_filesize"):"�������ϴ�����";   //��PHP�ű��ϴ��ļ���С����
$php[] = get_cfg_var("disable_functions")?get_cfg_var("disable_functions"):"��"; //�����εĺ���
$php[] = get_cfg_var("post_max_size"); //post�����ύ��������
$php[] = get_cfg_var("max_execution_time")."��"; //�ű���ʱʱ��
$php[] = temp(get_cfg_var("display_errors")); 
/*�������*/   
$obj[] = temp(get_magic_quotes_gpc("smtp"));//SMTP
$obj[] = temp(get_cfg_var("safe_mode"));  //PHP��ȫģʽ(Safe_mode)
$obj[] = temp(get_magic_quotes_gpc("XML Support"));//XML ֧��      
$obj[] = temp(get_magic_quotes_gpc("FTP support"));//FTP ֧��
$obj[] = temp(get_cfg_var("allow_url_fopen"));//����ʹ��URL���ļ�
$obj[] = temp(get_cfg_var("enable_dl"));//��̬���ӿ�
/*�������*/
$qobj[] = temp(function_exists("imap_close"));//IMAP�����ʼ�ϵͳ 
$qobj[] = temp(function_exists("JDToGregorian"));//����
$qobj[] = temp(function_exists("gzclose")); //ѹ���ļ�֧��(Zlib)
$qobj[] = temp(function_exists("session_start")); //Session֧��
$qobj[] = temp(function_exists("fsockopen")); //Socket֧��
$qobj[] = temp(function_exists("preg_match"));//PREL�����﷨ PCRE
$qobj[] = temp(function_exists("imageline"));//ͼ�δ��� GD Library
$qobj[] = temp(function_exists("FDF_close"));//FDF�����ϸ�ʽ
$qobj[] = temp(function_exists("iconv"));//ICONV
$qobj[] = temp(function_exists("snmpget"));//SNMP�������Э��


/*���ݿ���Ϣ*/
$sql[] = temp(function_exists("mysql_close")); //mysql���ݿ�
$sql[] = temp(function_exists("odbc_close")); //odbc���ݿ�
$sql[] = temp(function_exists("ora_close")); //ora���ݿ�
$sql[] = temp(function_exists("OCILogOff"));//Oracle 8 ���ݿ�
$sql[] = temp(function_exists("mssql_close"));//SQL Server���ݿ�
$sql[] = temp(function_exists("msql_close"));//msql���ݿ�
$sql[] = temp(function_exists("hw_close"));//Hyperwave���ݿ�
$sql[] = temp(function_exists("dbase_close"));//dbase���ݿ�
$sql[] = temp(function_exists("pg_connect"));//PostgreSQL���ݿ�
$sql[] = temp(function_exists("filepro"));//firePro���ݿ�
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>�����PHP̽��V<?php echo$version?></title>
<STYLE type=text/css>
BODY {
	FONT-SIZE: 9pt; FONT-FAMILY: "Tahoma","Arial","Helvetica","sans-serif"
}
.input
{
	BORDER: #3f5294 1px solid;
	FONT-SIZE: 9pt;
	BACKGROUND-color: #f8f9fc
}
.sub
{
	BACKGROUND-COLOR: #5C72BA;
	BORDER: medium none;
	COLOR: #ffffff;
	HEIGHT: 18px;
	font-size: 9pt
}
TD {
	FONT-SIZE: 9pt; FONT-FAMILY: "Tahoma","Arial","Helvetica","sans-serif"
}
A {
	COLOR: #000000; TEXT-DECORATION: none
}
A:hover {
	COLOR: #ff0000; TEXT-DECORATION: none
}
A.td1o2 {
	BORDER-RIGHT: #333 3px double; PADDING-RIGHT: 5px; BORDER-TOP: #333 3px double; PADDING-LEFT: 5px; BORDER-LEFT: #333 3px double; BORDER-BOTTOM: #333 3px double
}
A.td2o2 {
	BORDER-RIGHT: #333 3px double; PADDING-RIGHT: 5px; BORDER-TOP: #333 3px double; PADDING-LEFT: 5px; BORDER-LEFT: #333 3px double; BORDER-BOTTOM: #333 3px double
}
.tbl1 {
	BORDER-RIGHT: #3f5294 1px solid; BORDER-TOP: #3f5294 1px solid; FONT-SIZE: 9pt; BORDER-LEFT: #3f5294 1px solid; BORDER-BOTTOM: #3f5294 1px solid
}
.td1 {
	BORDER-RIGHT: #ffffff 0px solid; BORDER-TOP: #ffffff 1px solid; BORDER-LEFT: #ffffff 1px solid; COLOR: #336699; BORDER-BOTTOM: #ffffff 0px solid; BACKGROUND-COLOR: #abb6dc
}
.tbl1o1 {
	BACKGROUND-COLOR: #8595cb
}
.td1o1 {
	BORDER-RIGHT: #ffffff 0px solid; BORDER-TOP: #ffffff 1px solid; BORDER-LEFT: #ffffff 1px solid; BORDER-BOTTOM: #ffffff 0px solid; BACKGROUND-COLOR: #e2e7f3
}
.tr1 {
	BACKGROUND-COLOR: #5c72ba
}
.td102a {
	COLOR: #5c72ba
}
.td1o22 {	BACKGROUND-COLOR: #f3f4fa
}
.font1 {
	color: #FFFFFF;
	font-family: Tahoma, Verdana, Arial;
	font-size: 9pt;
	font-weight: bold;
}
</STYLE>
</head>

<body>
<div align="center">
  <a name="top"></a>
  <TABLE cellSpacing=0 cellPadding=0 width=750 border=0>
    <TBODY>
      <TR align="left">
        <TD width=230 style="FONT-FAMILY: Verdana, Arial, Helvetica">
          <P 
      style="MARGIN-TOP: 0px; FONT-SIZE: 9pt; MARGIN-BOTTOM: -5px">�����PHP̽��</P>
          <P style="MARGIN-TOP: 0px; MARGIN-BOTTOM: -8px">&nbsp;<STRONG style="FONT-SIZE: 24pt">PHP ENV</STRONG> <FONT color=#666666>v
		  <?php echo$version?>
          </FONT></P>
        <P style="MARGIN-TOP: 0px">&nbsp;<FONT style="FONT-SIZE: 9pt" 
      color=#333333><U>Server Environment Probe</U></FONT></P></TD>
        <TD>
          <TABLE 
      style="BORDER-RIGHT: black 1px solid; BORDER-TOP: black 1px solid; PADDING-LEFT: 10pt; BORDER-LEFT: black 1px solid; WIDTH: 480px; BORDER-BOTTOM: black 1px solid; HEIGHT: 60px; TEXT-ALIGN: left" 
      cellSpacing=0 cellPadding=0 border=0>
            <TBODY>
              <TR>
                <TD height=56><SPAN id=divCcAd name="divCcAd"><?php echo$AD?></SPAN></TD>
              </TR>
            </TBODY>
        </TABLE></TD>
      </TR>
    </TBODY>
  </TABLE>
  <table width="750" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="633" class="td102a"> ���������������ʾ���Ŀռ䲻֧��PHP�� 1�����ʱ��ļ�ʱ��ʾ���ء� 2�����ʱ��ļ�ʱ�������ơ�&lt;?php?&gt;�������֡� </td>
      <td width="117" align="right"> <a href="<?php echo$_SERVER[PHP_SELF]?>">ˢ�� </a><a href="#bottom">�ײ��� </a> </td>
    </tr>
  </table>
  <TABLE class=tbl1 cellSpacing=1 cellPadding=3 width=750 border=0 >
    <TBODY align="left">
      <TR>
        <TD class=tr1>
          <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
            <TBODY>
              <TR>
                <TD><FONT class="font1">�����������йز���:::...</FONT></TD>
              </TR>
            </TBODY>
        </TABLE></TD>
      </TR>
      <TR>
        <TD bgColor=#f8f9fc>
          <TABLE class=tbl1o1 cellSpacing=1 cellPadding=3 width="100%" border=0>
            <TBODY>
              <TR>
                <TD class=td1 width=130>&nbsp;��Ŀ</TD>
                <TD class=td1 colSpan=3>&nbsp;ֵ</TD>
              </TR>
              <TR>
                <TD class=td1o1 width=130>&nbsp;����<BR>
                    <FONT color=#666666>&nbsp;Domain Name</FONT></TD>
                <TD class=td1o22 colSpan=3>&nbsp;<?php echo $info[0]?>&nbsp;&nbsp;-&nbsp;&nbsp;<?php echo $info[1]?></TD>
              </TR>
              <TR>
                <TD class=td1o1 width=130>&nbsp;�������˿�<BR>
                    <FONT color=#666666>&nbsp;Server Port</FONT></TD>
                <TD class=td1o22 width=240>&nbsp;<?php echo $info[2]?></TD>
                <TD class=td1o1 width=130>&nbsp;����������ϵͳ<BR>
                    <FONT color=#666666>&nbsp;Operating System</FONT></TD>
                <TD class=td1o22 width=240>&nbsp;<?php echo $info[3]?></TD>
              </TR>
              <TR>
                <TD class=td1o1 width=130>&nbsp;WEB�������汾<BR>
                    <FONT color=#666666>&nbsp;Web Server Version</FONT></TD>
                <TD class=td1o22 width=240>&nbsp;<?php echo $info[4]?></TD>
                <TD class=td1o1 noWrap width=130>&nbsp;PHP�汾<BR>
                    <FONT color=#666666>&nbsp;PHP Version</FONT></TD>
                <TD class=td1o22 width=240>&nbsp;<?php echo $info[5]?></TD>
              </TR>
              <TR>
                <TD class=td1o1 width=130>&nbsp;����������<BR>
                    <FONT color=#666666>&nbsp;Server Language</FONT></TD>
                <TD class=td1o22 width=240>&nbsp;<?php echo $info[6]?></TD>
                <TD class=td1o1 width=130>&nbsp;ZEND�汾<BR>
                    <FONT color=#666666>&nbsp;ZEND Version</FONT></TD>
                <TD class=td1o22 width=240>&nbsp;<?php echo $info[7]?></TD>
              </TR>
              <TR>
                <TD class=td1o1 noWrap width=130>&nbsp;����·��<BR>
                    <FONT color=#666666>&nbsp;Full path</FONT></TD>
                <TD class=td1o22 noWrap width=240>&nbsp;<?php echo $info[8]?></TD>
                <TD class=td1o1 noWrap>&nbsp;������ʣ��ռ�<BR>
                    <FONT color=#666666>&nbsp;Disk Free Space</FONT></TD>
                <TD class=td1o22 width=240>&nbsp;<?php echo $info[9]?></TD>
              </TR>
              <TR>
                <TD class=td1o1 width=130>&nbsp;������ʱ��<BR>
                    <FONT color=#666666>&nbsp;Server Current Time</FONT></TD>
                <TD class=td1o22 colSpan=3>&nbsp;<?php echo $info[10]?></TD>
              </TR>
            </TBODY>
        </TABLE></TD>
      </TR>
      <TR>
        <TD class=tr1 height=5></TD>
      </TR>
    </TBODY>
  </TABLE>
  <br>  
  <TABLE class=tbl1 cellSpacing=1 cellPadding=3 width=750 border=0 >
    <TBODY align="left">
      <TR>
        <TD class=tr1>
          <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
            <TBODY>
              <TR>
                <TD><FONT class="font1">��PHP��������:::...</FONT></TD>
              </TR>
            </TBODY>
        </TABLE></TD>
      </TR>
      <TR>
        <TD bgColor=#f8f9fc>
          <TABLE class=tbl1o1 cellSpacing=1 cellPadding=3 width="100%" border=0>
            <TBODY>
              <TR>
                <TD class=td1 width=130>&nbsp;��Ŀ</TD>
                <TD class=td1>&nbsp;ֵ</TD>
                <TD class=td1>&nbsp;��Ŀ</TD>
                <TD class=td1>&nbsp;ֵ</TD>
              </TR>
              <TR>
                <TD class=td1o1 width=130>&nbsp;PHP��ϢPHPINFO<br>
                <FONT color=#666666>&nbsp;PHPINFO</FONT></TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $php[0]?></TD>
                <TD class=td1o1 width=130>&nbsp;�Զ���ȫ�ֱ���<br>
                <FONT color=#666666>&nbsp;register_globals </FONT></TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $php[1]?></TD>
              </TR>
              <TR>
                <TD class=td1o1 width=130>&nbsp;�ű����п�ռ����ڴ�<br>
                <FONT color=#666666>&nbsp;memory_limit </FONT>                  <br></TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $php[2]?></TD>
                <TD class=td1o1 width=130>&nbsp;�ű��ϴ��ļ���С����<br>
                <FONT color=#666666>&nbsp;upload_max_filesize</FONT> </TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $php[3]?></TD>
              </TR>
              <TR>
                <TD class=td1o1 width=130>&nbsp;�����εĺ���<br>
                <FONT color=#666666>&nbsp;disable_functions</FONT> </TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $php[4]?></TD>
                <TD class=td1o1 width=130>&nbsp;POST�����ύ����<br>
                <FONT color=#666666>&nbsp;post_max_size</FONT> </TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $php[5]?></TD>
              </TR>
              <TR>
                <TD class=td1o1 noWrap width=130>&nbsp;�ű���ʱʱ��<br>
                <FONT color=#666666>&nbsp;max_execution_time</FONT> </TD>
                <TD class=td1o22 noWrap width=240>&nbsp;
                    <?php echo $php[6]?></TD>
                <TD class=td1o1 noWrap>&nbsp;��ʾ������Ϣ<BR>
                    <FONT color=#666666>&nbsp;display_errors</FONT></TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $php[7]?></TD>
              </TR>
            </TBODY>
        </TABLE></TD>
      </TR>
      <TR>
        <TD class=tr1 height=5></TD>
      </TR>
    </TBODY>
  </TABLE>
  <br>
  <TABLE class=tbl1 cellSpacing=1 cellPadding=3 width=750 border=0 >
    <TBODY align="left">
      <TR>
        <TD class=tr1>
          <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
            <TBODY>
              <TR>
                <TD><FONT class="font1">�����������Ϣ:::...</FONT></TD>
              </TR>
            </TBODY>
        </TABLE></TD>
      </TR>
      <TR>
        <TD bgColor=#f8f9fc>
          <TABLE class=tbl1o1 cellSpacing=1 cellPadding=3 width="100%" border=0>
            <TBODY>
              <TR>
                <TD class=td1 width=130>&nbsp;�������</TD>
                <TD class=td1>&nbsp;֧�����</TD>
				<TD class=td1>&nbsp;�������</TD>
                <TD class=td1>&nbsp;֧�����</TD>
              </TR>
              <TR>
                <TD class=td1o1 width=130>&nbsp;SMTP֧��<br>
                    <FONT color=#666666>&nbsp;smtp</FONT></TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $obj[0]?></TD>
                <TD class=td1o1 width=130>&nbsp;PHP��ȫģʽ<br>
                    <FONT color=#666666>&nbsp;Safe_mode</FONT></TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $obj[1]?></TD>
              </TR>
              <TR>
                <TD class=td1o1 width=130>&nbsp;XML ֧��<br>
                    <FONT color=#666666>&nbsp;XML Support</FONT> <br></TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $obj[2]?></TD>
                <TD class=td1o1 width=130>&nbsp;FTP ֧��<br>
                    <FONT color=#666666>&nbsp;FTP support</FONT> </TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $obj[3]?></TD>
              </TR>
              <TR>
                <TD class=td1o1 width=130>&nbsp;����ʹ��URL���ļ�<br>
                    <FONT color=#666666>&nbsp;allow_url_fopen</FONT> </TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $obj[4]?></TD>
                <TD class=td1o1 width=130>&nbsp;��̬���ӿ�֧��<br>
                    <FONT color=#666666>&nbsp;enable_dl</FONT> </TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php /*echo $obj[5]?></TD>
              </TR>
            </TBODY>
        </TABLE>
		</TD>
      </TR>
      <TR>
        <TD class=tr1 height=5></TD>
      </TR>
    </TBODY>
  </TABLE>
  <br>
  <TABLE class=tbl1 cellSpacing=1 cellPadding=3 width=750 border=0 >
    <TBODY align="left">
      <TR>
        <TD class=tr1>
          <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
            <TBODY>
              <TR>
                <TD><FONT class="font1">�����������Ϣ:::...</FONT></TD>
              </TR>
            </TBODY>
        </TABLE></TD>
      </TR>
      <TR>
        <TD bgColor=#f8f9fc>
          <TABLE class=tbl1o1 cellSpacing=1 cellPadding=3 width="100%" border=0>
            <TBODY>
              <TR>
                <TD class=td1 width=130>&nbsp;�������</TD>
                <TD class=td1>&nbsp;֧�����</TD>
				<TD class=td1>&nbsp;�������</TD>
                <TD class=td1>&nbsp;֧�����</TD>
              </TR>
              <TR>
                <TD class=td1o1 width=130>&nbsp;IMAP�����ʼ�ϵͳ</TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $qobj[0]?></TD>
                <TD class=td1o1 width=130>&nbsp;��������</TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $qobj[1]?></TD>
              </TR>
              <TR>
                <TD class=td1o1 width=130>&nbsp;ѹ���ļ�֧��(Zlib)</TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $qobj[2]?></TD>
                <TD class=td1o1 width=130>&nbsp;Session֧��</TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $qobj[3]?></TD>
              </TR>
              <TR>
                <TD class=td1o1 width=130>&nbsp;Socket֧��</TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $qobj[4]?></TD>
                <TD class=td1o1 width=130>&nbsp;PREL�����﷨ PCRE</TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $qobj[5]?></TD>
              </TR>
			  <TR>
                <TD class=td1o1 width=130>&nbsp;ͼ�δ��� GD Library</TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $qobj[6]?>
					<?php 
					if(function_exists("imageline")==1 && function_exists("gd_info")==1) 
					{$gd=gd_info();echo "<br>&nbsp;&nbsp;�汾:".$gd["GD Version"];} 
					?>
				</TD>
                <TD class=td1o1 width=130>&nbsp;FDF�����ϸ�ʽ</TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $qobj[7]?></TD>
              </TR>
			  <TR>
                <TD class=td1o1 width=130>&nbsp;Iconv����ת��</TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $qobj[8]?></TD>
                <TD class=td1o1 width=130>&nbsp;SNMP�������Э��</TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $qobj[9]?></TD>
              </TR>
            </TBODY>
        </TABLE>
		</TD>
      </TR>
      <TR>
        <TD class=tr1 height=5></TD>
      </TR>
    </TBODY>
  </TABLE>
  <br>
  <TABLE class=tbl1 cellSpacing=1 cellPadding=3 width=750 border=0 >
    <TBODY align="left">
      <TR>
        <TD class=tr1>
          <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
            <TBODY>
              <TR>
                <TD><FONT class="font1">�����ݿ�֧����Ϣ:::...</FONT></TD>
              </TR>
            </TBODY>
        </TABLE></TD>
      </TR>
      <TR>
        <TD bgColor=#f8f9fc>
          <TABLE class=tbl1o1 cellSpacing=1 cellPadding=3 width="100%" border=0>
            <TBODY>
              <TR>
                <TD class=td1 width=130>&nbsp;���ݿ�</TD>
                <TD class=td1>&nbsp;֧�����</TD>
				<TD class=td1>&nbsp;���ݿ�</TD>
                <TD class=td1>&nbsp;֧�����</TD>
              </TR>
              <TR>
                <TD class=td1o1 width=130>&nbsp;Mysql���ݿ�</TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $sql[0]?></TD>
                <TD class=td1o1 width=130>&nbsp;OBDC���ݿ�</TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $sql[1]?></TD>
              </TR>
              <TR>
                <TD class=td1o1 width=130>&nbsp;Oracle���ݿ�</TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $sql[2]?></TD>
                <TD class=td1o1 width=130>&nbsp;Oracle 8 ���ݿ�</TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $sql[3]?></TD>
              </TR>
              <TR>
                <TD class=td1o1 width=130>&nbsp;SQL Server���ݿ�</TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $sql[4]?></TD>
                <TD class=td1o1 width=130>&nbsp;mSQL���ݿ�</TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $sql[5]?></TD>
              </TR>
			  <TR>
                <TD class=td1o1 width=130>&nbsp;Hyperwave���ݿ�</TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $sql[6]?></TD>
                <TD class=td1o1 width=130>&nbsp;dBase���ݿ�</TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $sql[7]?></TD>
              </TR>
			  <TR>
                <TD class=td1o1 width=130>&nbsp;PostgreSQL���ݿ�</TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $sql[8]?></TD>
                <TD class=td1o1 width=130>&nbsp;filePro���ݿ�</TD>
                <TD class=td1o22 width=240>&nbsp;
                    <?php echo $sql[9]?></TD>
              </TR>
            </TBODY>
        </TABLE>
		</TD>
      </TR>
      <TR>
        <TD class=tr1 height=5></TD>
      </TR>
    </TBODY>
  </TABLE>
  <br>
  <TABLE class=tbl1 cellSpacing=1 cellPadding=3 width=750 border=0 >
    <TBODY align="left">
      <TR>
        <TD class=tr1>
          <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
            <TBODY>
              <TR>
                <TD><a name="objcheck"></a><FONT class="font1">�����֧��������:::...</FONT></TD>
              </TR>
            </TBODY>
        </TABLE></TD>
      </TR>
      <TR>
        <TD bgColor=#f8f9fc>
          <TABLE class=tbl1o1 cellSpacing=1 cellPadding=3 width="100%" border=0>
            <TBODY>
              <TR>
                <TD class=td1>&nbsp;��������������������Ҫ���������<a href=# title="?:variables_order:gpc_order:magic_quotes_gpc:asp_tags:session.save_path">ProgId��ClassId</a></TD>
              </TR>
			<FORM action=<?php echo $_SERVER[PHP_SELF]?>#objcheck method=post id=form1 name=form1>
				<tr height="18">
					<td height=30 class=td1o1>
					&nbsp;<input class=input type=text value="" name="classname" size=40>
					<input name="ft" value="check" type="hidden">
					<INPUT type=submit value=" ȷ �� " class=sub id=submit1 name=submit1>
					<INPUT type=reset value=" �� �� " class=sub id=reset1 name=reset1> 
					</td>
				</tr>
			</FORM>
			</TBODY>
			</TABLE>
			<?php if($ft=='check'){ ?>
			<TABLE class=tbl1o1 cellSpacing=1 cellPadding=3 width="100%" border=0>
            <TBODY>
              <TR>
                <TD class=td1 width=130>&nbsp;�� ѯ �� �� �� ��</TD>
                <TD class=td1>&nbsp;����</TD>
				</TR>
              <TR>
                <TD class=td1o1 width=130>&nbsp;<?php echo $classname?></TD>
                <TD class=td1o22 width=240>&nbsp;�뿴����Ĳ���</TD>
                </TR>
              <TR>
                <TD class=td1o1 width=130>&nbsp;Getenv��ʽ</TD>
                <TD class=td1o22 width=240>&nbsp;<?php echo getenv("$classname")?></TD>
                </TR>
              <TR>
                <TD class=td1o1 width=130>&nbsp;Get_cfg_var��ʽ</TD>
                <TD class=td1o22 width=240>&nbsp;<?php echo get_cfg_var("$classname")?></TD>
                </TR>
			  <TR>
                <TD class=td1o1 nowrap>&nbsp;Get_magic_quotes_gpc��ʽ</TD>
                <TD class=td1o22 width=240>&nbsp;<?php echo get_magic_quotes_gpc("$classname")?></TD>
                </TR>
			  <TR>
                <TD class=td1o1 nowrap>&nbsp;Get_magic_quotes_runtime��ʽ</TD>
                <TD class=td1o22 width=240>&nbsp;<?php echo get_magic_quotes_runtime("$classname")?></TD>
                </TR>
            </TBODY>
        </TABLE>
	<?php;}?>
		</TD>
      </TR>
      <TR>
        <TD class=tr1 height=5></TD>
      </TR>
    </TBODY>
  </TABLE>
  <BR>
  <A name="function"></A>
  <TABLE class=tbl1 cellSpacing=1 cellPadding=3 width=750 border=0 >
    <TBODY align="left">
      <TR>
        <TD class=tr1>
          <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
            <TBODY>
              <TR>
                <TD></a><FONT class="font1">������֧��������:::...</FONT></TD>
              </TR>
            </TBODY>
        </TABLE></TD>
      </TR>
      <TR>
        <TD bgColor=#f8f9fc>
          <TABLE class=tbl1o1 cellSpacing=1 cellPadding=3 width="100%" border=0>
            <TBODY>
              <TR>
                <TD class=td1>&nbsp;��������������������Ҫ����Ƿ���õĺ���</TD>
              </TR>
			<FORM action=<?php echo $_SERVER[PHP_SELF]?>#function method=post id=form1 name=form1>
				<tr height="18">
					<td height=30 class=td1o1>
					&nbsp;<input class=input type=text value="" name="fname" size=40>
					<input name="fc" value="check" type="hidden">
					<INPUT type=submit value=" ȷ �� " class=sub id=submit1 name=submit1>
					<INPUT type=reset value=" �� �� " class=sub id=reset1 name=reset1> 
					</td>
				</tr>
				<?php
				if ("check" == $fc)
				 {
					 $ss=temp(function_exists($fname));
					 echo "
					 <tr>
						 <td class=td1o1>�����ĺ�����<b> $fname </b>$ss</td>
					 </tr>
					 ";
				 }//END IF 
				?>
			</FORM>
			</TBODY>
			</TABLE>
		</TD>
      </TR>
      <TR>
        <TD class=tr1 height=5></TD>
      </TR>
    </TBODY>
  </TABLE>
  <br>
  <TABLE class=tbl1 cellSpacing=1 cellPadding=3 width=750 border=0 >
    <TBODY align="left">
      <TR>
        <TD class=tr1>
          <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
            <TBODY>
              <TR>
                <TD><FONT class="font1">�����������ܲ���:::...</FONT></TD>
              </TR>
            </TBODY>
        </TABLE></TD>
      </TR>
      <TR>
        <TD bgColor=#f8f9fc>
          <TABLE class=tbl1o1 cellSpacing=1 cellPadding=3 width="100%" border=0>
            <TBODY>
              <TR>
                <TD class=td1>&nbsp;��������������50��Ρ�1��1���ļ���</TD>
                <TD class=td1 width=240>&nbsp;���ʱ��</TD>
              </TR>
			  <?php for ($i = 0; $i < count($svr); $i++) { ?>			  
              <TR>
                <TD class=td1o1>&nbsp;<?php echo $svr[$i]?></TD>
                <TD class=td1o22>&nbsp;<?php echo $svr_atime[$i]?>����</TD>
              </TR>
			 <?php } ?>
			  <TR>
                <TD class=td1>&nbsp;��������������50���ƽ�����ļ���</TD>
                <TD class=td1 width=240>&nbsp;���ʱ��</TD>
              </TR>
			  <?php for ($i = 0; $i < count($svr); $i++) { ?>
              <TR>
                <TD class=td1o1>&nbsp;<?php echo $svr[$i]?></TD>
                <TD class=td1o22>&nbsp;<?php echo $svr_stime[$i]?>����</TD>
              </TR>
			  <?php } ?>
            </TBODY>
        </TABLE>
		<TABLE class=tbl1o1 cellSpacing=1 cellPadding=3 width="100%" border=0>
		<tr>
			<td class=td1>
			�ر���ʾ:��������͸���������������˷���������������,
			����������̨���������ٶ�<font color="#FF0000">�޹�</font>��
			</td>
		</tr>
		</TABLE>
		</TD>
      </TR>
      <TR>
        <TD class=tr1 height=5></TD>
      </TR>
    </TBODY>
  </TABLE>
  <br>
  <TABLE class=tbl1 cellSpacing=1 cellPadding=3 width=750 border=0 >
    <TBODY align="left">
      <TR>
        <TD class=tr1>
          <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>            
			<TBODY>
              <TR>
                <TD><a name="objcheck"></a><FONT class="font1">�����ݿ����Ӳ���:::...</FONT></TD>
              </TR>
            </TBODY>
        </TABLE></TD>
      </TR>
      <TR>
        <TD bgColor=#f8f9fc>
          <TABLE class=tbl1o1 cellSpacing=1 cellPadding=3 width="100%" border=0>
            <TBODY>
              <TR>
                <TD class=td1>&nbsp;MySQL���ݿ����Ӳ���</a></TD>
              </TR>
				<FORM METHOD=POST ACTION="<?php echo $_SERVER[PHP_SELF]?>#bottom">
				<tr height="18">
					<td height=30 class=td1o1>
						<TABLE width=100%>
						<TR>
							<TD>
							&nbsp;��ַ: <input class=input type=text value="localhost" name="sql_host" size=10>
							&nbsp;�˿�: <input class=input type=text value="3306" name="sql_port" size=10>
							&nbsp;�ʺ�: <input class=input type=text name="sql_login" size=10>
							&nbsp;����: <input class=input type=text name="sql_password" size=10>
							&nbsp;<input name="conn" value="mysql" type="hidden">
						</TD><TD align=right>
							<INPUT type=submit value=" ȷ �� " class=sub id=submit1 name=submit1>
							<INPUT type=reset value=" �� �� " class=sub id=reset1 name=reset1>
						</TD>
						</TR>
						</TABLE>
					</td>
				</tr>
				</FORM>
				<TR>
                <TD class=td1>&nbsp;PostgreSQL���ݿ����Ӳ���</a></TD>
				</TR>
				<FORM METHOD=POST ACTION="<?php echo $_SERVER[PHP_SELF]?>#bottom">
				<tr height="18">
					<td height=30 class=td1o1>
						<TABLE width=100%>
						<TR>
							<TD>
							&nbsp;��ַ: <input class=input type=text value="localhost" name="sql_host" size=10>
							&nbsp;�˿�: <input class=input type=text value="7890" name="sql_port" size=10>
							&nbsp;�ʺ�: <input class=input type=text name="sql_login" size=10>
							&nbsp;����: <input class=input type=text name="sql_password" size=10>
							&nbsp;���ݿ���:<input class=input type=text name="sql_dbname" size=10>
							&nbsp;
							</TD><TD align=right>
							<input name="conn" value="psql" type="hidden">
							<INPUT type=submit value=" ȷ �� " class=sub id=submit1 name=submit1>
							<INPUT type=reset value=" �� �� " class=sub id=reset1 name=reset1>
							</TD>
						</TR>
						</TABLE>
					</td>
				</tr>
				</FORM>
			</TBODY>
		  </TABLE>
		</TD>
      </TR>
      <TR>
        <TD class=tr1 height=5></TD>
      </TR>
    </TBODY>
  </TABLE>
  <BR>
  <TABLE class=tbl1 cellSpacing=1 cellPadding=3 width=750 border=0 >
    <TBODY align="left">
      <TR>
        <TD class=tr1>
          <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
            <TBODY>
              <TR>
                <TD></a><FONT class="font1">���ʼ�����֧��������:::...</FONT></TD>
              </TR>
            </TBODY>
        </TABLE></TD>
      </TR>
      <TR>
        <TD bgColor=#f8f9fc>
          <TABLE class=tbl1o1 cellSpacing=1 cellPadding=3 width="100%" border=0>
            <TBODY>
              <TR>
                <TD class=td1>&nbsp;������������������һ���ʼ���ַ</TD>
              </TR>
			<FORM action=<?php/* echo $_SERVER[PHP_SELF]?>#bottom method=post id=form1 name=form1>
				<tr height="18">
					<td height=30 class=td1o1>
					<TABLE width=100%>
						<TR>
							<TD>
					&nbsp;<input class=input type=text value="@" name="toemail" size=40>
					</TD><TD align=right>
					<input name="mt" value="check" type="hidden">
					<INPUT type=submit value=" ȷ �� " class=sub id=submit1 name=submit1>
					<INPUT type=reset value=" �� �� " class=sub id=reset1 name=reset1> 
							</TD>
						</TR>
					</TABLE>
					</td>
				</tr>
				<?php
				if ("check" == $mt)
				 {
					 if (1 == function_exists("mail"))
					 {
						 if (!eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$",$toemail))
						 {
							 echo_info("��������ȷ��E-mail��ַ��");
						 }//END if
						 else
						 {
							 $message="����һ������ʼ����� �������PHP̽�� v".$version."�� �������ڲ��Է�������mail�������ܡ�\n��ӭʹ�÷����PHP̽�룬�����򹫿�Դ���룬��������⸴�ơ�������ʹ�á�\n����Դ��ҵ���վ ( http://wapcity.org.ru/ ), ������֧��վ�����ص�������";
							 @mail($toemail, "�����ʼ�", $message);
							 echo "
								<tr>
								<td class=td1o1>һ������ʼ��Ѿ����͵�����<b>$toemail</b></td>
								</tr>
								";
						 }
					 }//END IF;
					 else
					 {
						 echo_info("���ķ�������֧��MAIL������");
					 }
				 }//END IF 
				?>
			</FORM>
			</TBODY>
			</TABLE>
		</TD>
      </TR>
      <TR>
        <TD class=tr1 height=5></TD>
      </TR>
    </TBODY>
  </TABLE>
  <table width=750>
  <tr>
  	<td></td>
  	<td align=right><a name="bottom"></a>
	<a href="<?php echo $_SERVER[PHP_SELF]?>">ˢ�� </a><a href="#top">������ </a> 
	</td>
  </tr>
  </table>
</div>
<center>
<?php
$page_time_end=getmicrotime(); 
$pageTime = round(($page_time_end-$page_time_start)*1000000)/1000;
echo "ҳ��ִ��ʱ��".$pageTime."����";
?>
<!--
<br>
��ӭ���� <a href="http://wapcity.org.ru" target="_blank">http://wapcity.org.ru</a> <br>
�������ɷ���θ��d(<a href="mailto:anerg@183.ha.cn">anerg@183.ha.cn</a>)��д��ת��ʱ�뱣����Щ��Ϣ
