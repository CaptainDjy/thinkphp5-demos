不知道啥时候看到过有人写过历史上的今天这个api,不过只是提供接口不舒服,还得要看源码才行,百度了一圈找到了历史上的今天这个借口,不过数据太杂,自己封装下

lishi

百度原生态接口 :  https://baike.baidu.com/cms/home/eventsOnHistory/06.json

重新折腾下看着才舒服

<?php
$month = date('m',time());
$day = date('d',time());
$url="https://baike.baidu.com/cms/home/eventsOnHistory/".$month.'.json';
$data = file_get_contents($url);
$data2 = json_decode($data,true);
$array = [];
foreach($data2[$month][$month.$day] as $data){
    $array[] = [
         'year'=>$data['year'],
         'title'=>$data['title']
     ];
}
$json_output = [
    $month.$day => $array
        ];
//输出
header('Content-type:text/json');
echo json_encode($json_output,true);
就是这样
