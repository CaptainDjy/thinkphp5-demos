php电脑网站支付demo下载地址： https://docs.open.alipay.com/270/106291/


$payRequestBuilder = new AlipayTradePagePayContentBuilder();
改成


$payRequestBuilder = new \AlipayTradePagePayContentBuilder();
如果报错没有tmp目录就在sdk目录新建个tmp文件夹

然后修改AopSdk.php   18行


define("AOP_SDK_WORK_DIR", "/tmp/");
改成如下


define("AOP_SDK_WORK_DIR",dirname(__FILE__) . "/tmp/");
如果遇到each（）错误改成如下

while改成foreach


//     while (list ($key, $val) = each ($para_temp)) {
  foreach ($para_temp as $key => $val) {
