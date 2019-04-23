/**
* 数据脱敏
* @param $str
* @return null|string|string[]
*/
public static function hideStr($str) {
if (strpos($str, '@')) {
$email_array = explode("@", $str);
//邮箱前缀
$prevfix = (strlen($email_array[0]) < 4) ? "" : substr($str, 0, 3);
$count = 0;
$str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $str, -1, $count);
$rs = $prevfix . $str;
}else {
//正则手机号
$pattern = '/(1[3458]{1}[0-9])[0-9]{4}([0-9]{4})/i';
if (preg_match($pattern, $str)) {
$rs = preg_replace($pattern, '$1****$2', $str); // substr_replace($name,'****',3,4);
}else {
$rs = substr($str, 0, 3) . "***" . substr($str, -1);
}
}
return $rs;
}
