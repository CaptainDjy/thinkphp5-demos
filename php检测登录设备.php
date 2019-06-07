/**

* 检测登录设备

* @return 设备类型

*/

function is_ntorphone(){

$agent = strtolower($_SERVER['HTTP_USER_AGENT']);

//分析数据

$is_pc = (strpos($agent, 'windows nt')) ? true : false;

$is_iphone = (strpos($agent, 'iphone')) ? true : false;

$is_ipad = (strpos($agent, 'ipad')) ? true : false;

$is_android = (strpos($agent, 'android')) ? true : false;

//输出数据

if($is_pc){

return "PC";

}

if($is_iphone){

return "iPhone";

}

if($is_ipad){

return "iPad";

}

if($is_android){

return "Android";

}


}
