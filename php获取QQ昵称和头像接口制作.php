新建一个php 名字随意 如：getqq.php 放进你网站目录 或者你想指定的目录下;

打开 getqq.php 里面添加：


<?php
header("Content-type: application/json; charset=utf-8"); 
$qq = $_GET['qq'];
$html = file_get_contents('http://r.qzone.qq.com/fcg-bin/cgi_get_portrait.fcg?uins='.$qq);
$nic = explode(',',$html);
$name = trim(mb_convert_encoding($nic[6], "UTF-8", "GBK"),'"');
$img = file_get_contents('http://ptlogin2.qq.com/getface?appid=1006102&uin='.$qq.'&imgtype=3');
preg_match('/pt.setHeader\((.*?)\);/',$img,$picc);
$pic = json_decode($picc[1]);
$json['name'] = $name;
$json['pic'] = $pic->$qq;
echo $_GET['callback'].'('.json_encode($json).')';
?>
