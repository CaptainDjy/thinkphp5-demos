<?php 
    //alert提示 
    function alert($msg){ 
        echo "<script>alert('$msg');</script>"; 
    } 
    //把一些预定义的字符转换为 HTML 实体 
    function d_htmlspecialchars($string) { 
    if(is_array($string)) { 
        foreach($string as $key => $val) { 
            $string[$key] = d_htmlspecialchars($val); 
        } 
    } else { 
        $string = str_replace('&', '&', $string); 
        $string = str_replace('"', '"', $string); 
        $string = str_replace(''', ''', $string); 
        $string = str_replace('<', '<', $string); 
        $string = str_replace('>', '>', $string); 
        $string = preg_replace('/&(#\d;)/', '&\1', $string); 
    } 
        return $string; 
    } 
    //在预定义字符前加上反斜杠，包括 单引号、双引号、反斜杠、NULL，以保护数据库安全 
    function d_addslashes($string, $force = 0) { 
        if(!$GLOBALS['magic_quotes_gpc'] || $force) { 
        if(is_array($string)) { 
            foreach($string as $key => $val) $string[$key] = d_addslashes($val, $force); 
        } 
        else $string = addslashes($string); 
        } 
        return $string; 
    } 
    //生成随机字符串，包含大写、小写字母、数字 
    function randstr($length) { 
        $hash = ''; 
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz'; 
        $max = strlen($chars) - 1; 
        mt_srand((double)microtime() * 1000000); 
        for($i = 0; $i < $length; $i++) { 
            $hash .= $chars[mt_rand(0, $max)]; 
        } 
        return $hash; 
    } 
    //转换时间戳为常用的日期格式 
    function trans_time($timestamp){ 
        if($timestamp < 1) echo '无效的Unix时间戳'; 
        else return date("Y-m-d H:i:s",$timestamp); 
        } 
    //获取IP 
    function get_ip() { 
        if ($_SERVER["HTTP_X_FORWARDED_FOR"]) 
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"]; 
            else if ($_SERVER["HTTP_CLIENT_IP"]) 
            $ip = $_SERVER["HTTP_CLIENT_IP"]; 
            else if ($_SERVER["REMOTE_ADDR"]) 
            $ip = $_SERVER["REMOTE_ADDR"]; 
            else if (getenv("HTTP_X_FORWARDED_FOR")) 
            $ip = getenv("HTTP_X_FORWARDED_FOR"); 
            else if (getenv("HTTP_CLIENT_IP")) 
            $ip = getenv("HTTP_CLIENT_IP"); 
            else if (getenv("REMOTE_ADDR")) 
            $ip = getenv("REMOTE_ADDR"); 
        else 
            $ip = "Unknown"; 
        return $ip; 
    } 
    //计算时间差：默认返回类型为“分钟” 
    //$old_time 只能是时间戳，$return_type 为 h 是小时，为 s 是秒 
    function timelag($old_time,$return_type='m'){ 
        if($old_time < 1){ 
            echo '无效的Unix时间戳'; 
        }else{ 
            switch($return_type){ 
            case 'h': 
            $type = 3600; break; 
            case 'm': 
            $type = 60; break; 
            case 's': 
            $type = 1; break; 
            case '': 
            $type = 60; break; 
            } 
        $dif = round( (time()-$old_time)/$type ) ; 
        return $dif; 
        } 
    } 
    //获取当前页面的URL地址 
    function url_this(){ 
        $url = "http://".$_SERVER ["HTTP_HOST"].$_SERVER["REQUEST_URI"]; 
        $return_url = "<a href='$url'>$url</a>"; 
        return $return_url; 
    } 
    //跳转函数 
    function url_redirect($url,$delay=''){ 
        if($delay == ''){ 
            echo "<script>window.location.href='$url'</script>"; 
        }else{ 
            echo "<meta http-equiv='refresh' content='$delay;URL=$url' />"; 
        } 
    } 
} 
//end func 

?>
--------------------- 
