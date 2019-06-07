直接来demo可以研究研究

<?php
header('Content-type:text/html;charset=utf-8');


function login($u,$p){
	$cookie_file = dirname(__FILE__) . '/cookie/'.$u.'.txt';
	$su = base64_encode($u);
	$url_1 = "https://login.sina.com.cn/sso/login.php?client=ssologin.js(v1.4.15)&_=".get_total_millisecond();
	$res_login = get_login_page($url_1,$cookie_file);
	
	$url_sso = "https://login.sina.com.cn/signup/signin.php?entry=sso";
	$res_sso = get_sso_page($url_sso,$cookie_file,$url_1);
	
	$url_pre = "https://login.sina.com.cn/sso/prelogin.php?entry=sso&callback=sinaSSOController.preloginCallBack&su=".$su."&rsakt=mod&client=ssologin.js(v1.4.15)&_=".get_total_millisecond();
	$res_pre = get_pre_page($url_pre,$cookie_file,$url_sso);
	
	preg_match('#\((.*)\)#isU',$res_pre,$l);
	$a_s = json_decode($l[1]);
	
	$url_2 = "https://login.sina.com.cn/sso/login.php?client=ssologin.js(v1.4.15)&_=".get_total_millisecond();
	$lo_data['entry'] = 'sso';
	$lo_data['gateway'] = '1';
	$lo_data['from'] = 'null';
	$lo_data['savestate'] = '30';
	$lo_data['useticket'] = '0';
	$lo_data['pagerefer'] = $url_2;
	$lo_data['vsnf'] = '1';
	$lo_data['su'] = $su;
	$lo_data['service'] = 'sso';
	$lo_data['servertime'] = $a_s->servertime;
	$lo_data['sp'] = $p;
	$lo_data['sr'] = '1920*1080';
	$lo_data['encoding'] = 'UTF-8';
	$lo_data['cdult'] = '3';
	$lo_data['domain'] = 'sina.com.cn';
	$lo_data['prelt'] = '14';
	$lo_data['returntype'] = 'TEXT';
	
	$res_login_one = json_decode(login_post($url_2,$lo_data,$cookie_file,$url_sso));
	if($res_login_one->retcode == '0'){
	   $return=get_cookie($res_login_one->crossDomainUrlList[0],$cookie_file);
	   echo 'ok';
	}else{
		echo '登录失败，请检查配置';
		// get_page($email_url);
	}

}




function get_page($url)
{
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    $return = curl_exec($ch);
    curl_close($ch);
    return $return;
}

function get_cookie($login,$cookie_file)
{
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$login);
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($ch,CURLOPT_COOKIEJAR,$cookie_file);
    $return = curl_exec($ch);
    curl_close($ch);
	return $return;
}

function login_post($url,$data,$cookie_file,$url_sso)
{
    $header[] = 'Host: login.sina.com.cn';
    $header[] = 'Connection: keep-alive';
    $header[] = 'Origin: https://login.sina.com.cn';
    $header[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.139 Safari/537.36';
    $header[] = 'Content-Type: application/x-www-form-urlencoded';
    $header[] = 'Accept: */*';
    $header[] = 'Referer: '.$url_sso;
    $header[] = 'Accept-Language: zh-CN,zh;q=0.9';

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch,CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($ch,CURLOPT_COOKIEJAR, $cookie_file);
    $return = curl_exec($ch);
    curl_close($ch);
    return $return;
}

function get_pre_page($url,$cookie_file,$url_sso)
{
    $header[] = 'Host: login.sina.com.cn';
    $header[] = 'Connection: keep-alive';
    $header[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.139 Safari/537.36';
    $header[] = 'Accept: */*';
    $header[] = 'Referer: '.$url_sso;
    $header[] = 'Accept-Language: zh-CN,zh;q=0.9';

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
    curl_setopt($ch,CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($ch,CURLOPT_COOKIEJAR, $cookie_file);
    $return = curl_exec($ch);
    curl_close($ch);
    return $return;
}

function get_sso_page($url,$cookie_file,$url_1)
{
    $header[] = 'Host: login.sina.com.cn';
    $header[] = 'Connection: keep-alive';
    $header[] = 'Upgrade-Insecure-Requests: 1';
    $header[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.139 Safari/537.36';
    $header[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8';
    $header[] = 'Referer: '.$url_1;
    $header[] = 'Accept-Language: zh-CN,zh;q=0.9';

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1 );
    curl_setopt($ch,CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($ch,CURLOPT_COOKIEJAR, $cookie_file);
    $return = curl_exec($ch);
    curl_close($ch);
    return $return;
}

function get_login_page($url,$cookie_file)
{
    $header[] = 'Host: login.sina.com.cn';
    $header[] = 'Connection: keep-alive';
    $header[] = 'Upgrade-Insecure-Requests: 1';
    $header[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.139 Safari/537.36';
    $header[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8';
    $header[] = 'Accept-Language: zh-CN,zh;q=0.9';

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
    curl_setopt($ch,CURLOPT_COOKIEJAR, $cookie_file);
    $return = curl_exec($ch);
    curl_close($ch);
    return $return;
}

function get_total_millisecond()
{
    $time = explode (" ", microtime () );
    $time = $time [1] . ($time [0] * 1000);
    $time2 = explode ( ".", $time );
    $time = $time2 [0];
    return $time;
}
