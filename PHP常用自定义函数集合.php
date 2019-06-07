特殊字符过滤

/**
*	特殊字符过滤
*/
	public function xssrepstr($str)
	{
		$xpd  = explode(',','(,), ,<,>,\\,.,*,&,%,$,^,!,@,#,-,+,:,;\'');
		$xpds = array();
		foreach($xpd as $xpd1)$xpds[]='';
		$str = str_replace(',', '',$str);
		return str_ireplace($xpd, $xpds, $str);
	}
获取IP:
//获取IP
	public function getclientip()
	{
		$ip = 'unknow';
		if(isset($_SERVER['HTTP_CLIENT_IP'])){
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}else if(isset($_SERVER['REMOTE_ADDR'])){
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
支持php7.1 encypt加解密

//encfrypt php7.1加密
    public function my_encrypt($data, $key) {
        // Remove the base64 encoding from our key
        $encryption_key = base64_decode($key);
        // Generate an initialization vector
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
        // The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
        return base64_encode($encrypted . '::' . $iv);
    }

//decrypt 解密
    function my_decrypt($data, $key) {
        // Remove the base64 encoding from our key
        $encryption_key = base64_decode($key);
        // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
    }
//使用方法
  echo my_encypt('hello world!',123465)
PHP获取文件扩展名(后缀)

function getExtension($filename){
        $myext = substr($filename, strrpos($filename, '.'));
        return str_replace('.','',$myext);
}

echo getExtension('my.doc')  //doc
PHP替换标签字符

function stringParser($string,$replacer){ 
    $result = str_replace(array_keys($replacer), array_values($replacer),$string); 
    return $result; 
}
使用方法:
$string = 'The {b}anchor text{/b} is the {b}actual word{/b} or words used {br}to describe the link {br}itself'; 
$replace_array = array('{b}' => '<b>','{/b}' => '</b>','{br}' => '<br />'); 
echo stringParser($string,$replace_array);
PHP获取文件夹下文件夹列表

function listDirFiles($DirPath){
        if($dir = opendir($DirPath)){
            while(($file = readdir($dir))!== false){
                if(!is_dir($DirPath.$file))
                {
                    echo "$file<br />";
                }
            }
        }
}
使用方法
echo listDirFiles('D:\gitee')
PHP获取当前页面的URLhttps http

function curPageURL() {
        $pageURL = 'http';
        if (!empty($_SERVER['HTTPS'])) {$pageURL .= "s";}
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }
        return $pageURL;
}
使用 :
echo curPageURL();
PHP强制下载文件:有时我们不想让浏览器直接打开文件，如PDF文件，而是要直接下载文件，那么以下函数可以强制下载文件，函数中使用了application/octet-stream头类型

function download($filename){
        if ((isset($filename))&&(file_exists($filename))){
            header("Content-length: ".filesize($filename));
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            readfile("$filename");
        } else {
            echo "Looks like file does not exist!";
        }
}

echo $this->download('D:\Downloads\xianyan-master.zip');
PHP从头开始截取字符串长度

/**
    Utf-8、gb2312都支持的汉字截取函数
    cut_str(字符串, 截取长度, 开始长度, 编码);
    编码默认为 utf-8
    开始长度默认为 0
     */
    function cutStr($string, $sublen, $start = 0, $code = 'UTF-8'){
        if($code == 'UTF-8'){
            $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
            preg_match_all($pa, $string, $t_string);
            if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."...";
            return join('', array_slice($t_string[0], $start, $sublen));
        }else{
            $start = $start*2;
            $sublen = $sublen*2;
            $strlen = strlen($string);
            $tmpstr = '';
            for($i=0; $i<$strlen; $i++){
                if($i>=$start && $i<($start+$sublen)){
                    if(ord(substr($string, $i, 1))>129){
                        $tmpstr.= substr($string, $i, 2);
                    }else{
                        $tmpstr.= substr($string, $i, 1);
                    }
                }
                if(ord(substr($string, $i, 1))>129) $i++;
            }
            if(strlen($tmpstr)<$strlen ) $tmpstr.= "...";
            return $tmpstr;
        }
    }
使用:
echo cutStr('周杰伦',2);
PHP获取客户端真实IP

//获取用户真实IP 
    function getIp() {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        else
            if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            else
                if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
                    $ip = getenv("REMOTE_ADDR");
                else
                    if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
                        $ip = $_SERVER['REMOTE_ADDR'];
                    else
                        $ip = "unknown";
        return ($ip);
}
