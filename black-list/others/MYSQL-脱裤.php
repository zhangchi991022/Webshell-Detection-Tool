<?php
//blackbap.org
$mysql_servername = "localhost";//���ݿ������
$mysql_username = "user";//���ݿ��½�˻�
$mysql_password ="pass";//���ݿ��½����
$mysql_database ="dataname";//���ݿ�����
$mysql_link=@mysql_connect($mysql_servername , $mysql_username , $mysql_password);
mysql_select_db($mysql_database);
mysql_query("SET NAMES gbk");
$mysql= "";
$q1=mysql_query("show tables");
while($t=mysql_fetch_array($q1)){
  $table=$t[0];
  $q2=mysql_query("show create table `$table`");
  $sql=mysql_fetch_array($q2);
  $mysql.=$sql['Create Table'].";\r\n\r\n";
  $q3=mysql_query("select * from `$table`");
  while($data=mysql_fetch_assoc($q3))
    {
    $keys=array_keys($data);
    $keys=array_map('addslashes',$keys);
    $keys=join('`,`',$keys);    
    $keys="`".$keys."`";
    $vals=array_values($data);
    $vals=array_map('addslashes',$vals);
    $vals=join("','",$vals);
    $vals="'".$vals."'";
    $mysql.="insert into `$table`($keys) values($vals);\r\n";
    }
  $mysql.="\r\n";
}
$filename=date('Ymj').".sql";
$fp = fopen($filename,'w');
fputs($fp,$mysql);
fclose($fp);
echo "<br><center>���ݱ��ݳɹ������ݿ��ļ���".$filename."//blackbap.org</center>";
?>