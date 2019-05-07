<?php
//载入ucpass类
require_once('lib/Ucpaas.class.php');
require_once('serverSid.php');


$appid = "xxxxxxxxxxxxxxxxxxxxxxx";	//应用的ID，可在开发者控制台内的短信产品下查看
$templateid = "xxx";    //可在后台短信产品→选择接入的应用→短信模板-模板ID，查看该模板ID
$param = $_POST['yzm']; //多个参数使用英文逗号隔开（如：param=“a,b,c”），如为参数则留空
$mobile = $_POST['yzmtel'];
$uid = "";

//70字内（含70字）计一条，超过70字，按67字/条计费，超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。

echo $ucpass->SendSms($appid,$templateid,$param,$mobile,$uid);
