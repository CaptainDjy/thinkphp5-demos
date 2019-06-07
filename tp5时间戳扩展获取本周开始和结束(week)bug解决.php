在tp5文档扩展Time获取本周开始和结束有个BUG官方一直不给解决,5.1好像也是如此,输出如下


//获取本周开始和结束时间戳
list($start,$end) = Time::week();
dump(date('Y-m-d H:i:s',$start));  //string(19) "2018-11-26 00:00:00"
dump(date('Y-m-d H:i:s',$end));   //string(19) "2018-11-25 23:59:59"
代码如上调用week方法获取本周开始和结束时间戳,但是通过测试输出明显牛头不对马嘴,我要的是本周开始和结束啊

好吧点进去看看源码 如下
/**
*返回本周开始和结束的时间戳
*
*@return array
*/
public static function week(){
$timestamp=time();
return [
  strtotime(date('Y-m-d',strtotime('+0 week Monday',$timestamp))),
  strtotime(date('Y-m-d',strtotime('+0 week Sunday',$timestamp)))+24*3600-1
  ];
  }
tp5

主要看这段z


strtotime(date('Y-m-d', strtotime("+0 week Monday", $timestamp)))
这段执行结果获取到了下周一开始也就是下周一的 00:00:00

解决方法

把+0 改成 -1 就可以了 如下图
/**
*返回本周开始和结束的时间戳
*
*@return array
*/
public static function week(){
  $timestamp=time();
  return[
     strtotime(date('Y-m-d',strtotime('-1 week Monday',$timestamp))),
     strtotime(date('Y-m-d',strtotime('+0 week Sunday',$timestamp)))+24*3600-1
     ];
     }

tp5

