<?php

	/*
	��ΪPHP ���� zip ѹ��������
	faisun (faisun@sina.com) ��д
	http://www.artweaver.net
	*/

class PHPzip
{
	var $file_count = 0 ;
	var $datastr_len   = 0;
	var $dirstr_len = 0;
	var $filedata = ''; //�ñ���ֻ�����ⲿ�������

	/*
	�����ļ����޸�ʱ���ʽ.
	ֻΪ�����ڲ���������.
	*/
    function unix2DosTime($unixtime = 0) {
        $timearray = ($unixtime == 0) ? getdate() : getdate($unixtime);

        if ($timearray['year'] < 1980) {
        	$timearray['year']    = 1980;
        	$timearray['mon']     = 1;
        	$timearray['mday']    = 1;
        	$timearray['hours']   = 0;
        	$timearray['minutes'] = 0;
        	$timearray['seconds'] = 0;
        }

        return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) |
               ($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
    }
	/*
	�����е� zip �ļ����з���,
	�õ����Ľṹ.
	ֻΪ�����ڲ���������.
	*/
	function parsefile($gzfilename,&$dirstr){
		clearstatcache();
		if(!file_exists("$gzfilename")) return '';
		
		$newsize=filesize($gzfilename)-22;
		if($newsize==-22){ return ''; }
		elseif($newsize<0){	die("$gzfilename is a bad file.");	}

		$fp=fopen($gzfilename,"r");
		rewind($fp);
		fseek($fp,$newsize);
		$endstr = fread($fp,22);
		$this -> file_count = implode('',unpack('v',substr($endstr,8,2)));		//�����ļ�����
		$this -> dirstr_len = implode('',unpack('V',substr($endstr,12,4)));		//Ŀ¼��Ϣ����
		$this -> datastr_len =  implode('',unpack('V',substr($endstr,16,4)));	//�ļ����ݳ���
		if($newsize != ($this->dirstr_len) + ($this->datastr_len)){	die("$gzfilename is a bad file.");	}

		rewind($fp);
		fseek($fp,$this->datastr_len);
		$dirstr=fread($fp,$this->dirstr_len);	//Ŀ¼��Ϣ�ַ���
		fclose($fp);
	}

	/*
	��ʼ���ļ�,�����ļ�Ŀ¼,
	�������ļ���д��Ȩ��.
	��ִ�� addfile() ֮ǰ,��һ��Ҫ���ñ�����
	*/
	function startfile($gzfilename){
		$path=$gzfilename;
		$mypathdir=array();
		do{
			$mypathdir[] = $path = dirname($path);
		}while($path != '.');
		@end($mypathdir);
		while(@prev($mypathdir)){
			$path = @current($mypathdir);
			@mkdir($path);
		}

		if($fp=@fopen($gzfilename,"w")){
			fclose($fp);
			return true;
		}
		return false;
	}

	/*
	���һ���ļ��� zip ѹ������.
	���� zip �ļ��Ѵ���,�������ݱ�������ȷ��.
	������������ļ��� $name ����ȷ��,�������뱣֤���ǺϷ���,�����޷���ѹ,
	����Ҳ�����Ǵ�Ŀ¼�ĵ�ַ,���� faisunsql/index.php
	ÿ�ε��ñ�����,�����ɵ� zip �ļ�����������,����������������ȡ���ѹ.
	*/
    function addfile($data, $name, $gzfilename){
	
		$this->parsefile($gzfilename,$pre_dirstr);	//����ѹ����

		$fp = fopen("$gzfilename","a");
		ftruncate($fp,$this->datastr_len); //����ѹ������ֻʣ�ļ�����

        $name     = str_replace('\\', '/', $name);
        $dtime    = dechex($this->unix2DosTime());
        $hexdtime = '\x' . $dtime[6] . $dtime[7]
                  . '\x' . $dtime[4] . $dtime[5]
                  . '\x' . $dtime[2] . $dtime[3]
                  . '\x' . $dtime[0] . $dtime[1];
        eval('$hexdtime = "' . $hexdtime . '";');

        $unc_len = strlen($data);
        $crc     = crc32($data);
        $zdata   = gzcompress($data);
        $c_len   = strlen($zdata);
        $zdata   = substr(substr($zdata, 0, strlen($zdata) - 4), 2);
		
		//�����ļ����ݸ�ʽ��:
        $datastr  = "\x50\x4b\x03\x04";
        $datastr .= "\x14\x00";            // ver needed to extract
        $datastr .= "\x00\x00";            // gen purpose bit flag
        $datastr .= "\x08\x00";            // compression method
        $datastr .= $hexdtime;             // last mod time and date
        $datastr .= pack('V', $crc);             // crc32
        $datastr .= pack('V', $c_len);           // compressed filesize
        $datastr .= pack('V', $unc_len);         // uncompressed filesize
        $datastr .= pack('v', strlen($name));    // length of filename
        $datastr .= pack('v', 0);                // extra field length
        $datastr .= $name;
        $datastr .= $zdata;
        $datastr .= pack('V', $crc);                 // crc32
        $datastr .= pack('V', $c_len);               // compressed filesize
        $datastr .= pack('V', $unc_len);             // uncompressed filesize

		fwrite($fp,$datastr);	//д���µ��ļ�����
		$my_datastr_len = strlen($datastr);
		unset($datastr);

		fwrite($fp,$pre_dirstr);	//д��ԭ�е��ļ�Ŀ¼��Ϣ
		unset($pre_dirstr);
		
		//�����ļ�Ŀ¼��Ϣ
        $dirstr  = "\x50\x4b\x01\x02";
        $dirstr .= "\x00\x00";                	// version made by
        $dirstr .= "\x14\x00";                	// version needed to extract
        $dirstr .= "\x00\x00";                	// gen purpose bit flag
        $dirstr .= "\x08\x00";                	// compression method
        $dirstr .= $hexdtime;                 	// last mod time & date
        $dirstr .= pack('V', $crc);           	// crc32
        $dirstr .= pack('V', $c_len);         	// compressed filesize
        $dirstr .= pack('V', $unc_len);       	// uncompressed filesize
        $dirstr .= pack('v', strlen($name) ); 	// length of filename
        $dirstr .= pack('v', 0 );             	// extra field length
        $dirstr .= pack('v', 0 );             	// file comment length
        $dirstr .= pack('v', 0 );             	// disk number start
        $dirstr .= pack('v', 0 );             	// internal file attributes
        $dirstr .= pack('V', 32 );            	// external file attributes - 'archive' bit set
        $dirstr .= pack('V',$this->datastr_len ); // relative offset of local header
        $dirstr .= $name;
		
		$this -> file_count ++;
		$this -> dirstr_len += strlen($dirstr);
		$this -> datastr_len += $my_datastr_len;
		
		//ѹ����������Ϣ,�����ļ�����,Ŀ¼��Ϣ��ȡָ��λ�õ���Ϣ
		$endstr = "\x50\x4b\x05\x06\x00\x00\x00\x00" .
					pack('v', $this -> file_count) .
					pack('v', $this -> file_count) .
					pack('V', $this -> dirstr_len) .
					pack('V', $this -> datastr_len) .
					"\x00\x00";

		fwrite($fp,$dirstr.$endstr);//д�������ļ�Ŀ¼��Ϣ��ѹ����������Ϣ
		fclose($fp);
    }
}

	/*
	Ӧ�þ���:
	
	$faisunZIP = new PHPzip;
	$gzfilename = "zipfiles/mygz.zip";	//ѹ����·��
	$faisunZIP -> startfile($gzfilename); //�� $gzfilename ��һ���Ϸ���ѹ����,������ļ���ӵ��ð���,�ú�������ʡ��
	$faisunZIP -> addfile("Created by faisun(faisun@sina.com),2005","faisun/copyright.txt",$gzfilename);
	$faisunZIP -> addfile("http://www.artweaver.net","faisun/my homepage.txt",$gzfilename);
	*/

?>