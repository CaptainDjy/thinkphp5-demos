cURL 是一个利用URL语法规定来传输文件和数据的工具，支持很多协议，如HTTP、FTP、TELNET等，我们使用它来发送HTTP请求。它给我 们带来的好处是可以通过灵活的选项设置不同的HTTP协议参数，并且支持HTTPS。本文将介绍cURL的一些特性，以及在PHP中如何运用它。


使用CURL的PHP扩展完成一个HTTP请求的发送一般有以下四个步骤：

1.初始化连接句柄curl_init()；

2.设置CURL选项curl_setopt() ；

3.执行并获取结果curl_exec()；

4.释放VURL连接句柄curl_close()。

cURL实现GET

//初始化
$ch = curl_init();
//设置选项，包括URL
curl_setopt($ch, CURLOPT_URL, "http://www.helloweba.net");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
//执行并获取HTML文档内容
$output = curl_exec($ch);
//释放curl句柄
curl_close($ch);
//打印获得的数据
print_r($output);
上述代码中使用到了四个函数

curl_init() 和 curl_close() 分别是初始化CURL连接和关闭CURL连接，都比较简单。

curl_exec() 执行CURL请求，如果没有错误发生，该函数的返回是对应URL返回的数据，以字符串表示满意；如果发生错误，该函数返回 FALSE。需要注意的是，判断输出是否为FALSE用的是全等号，这是为了区分返回空串和出错的情况。

CURL函数库里最重要的函数是curl_setopt(),它可以通过设定CURL函数库定义的选项来定制HTTP请求。上述代码片段中使用了三个重要的选项：

CURLOPT_URL 指定请求的URL；

CURLOPT_RETURNTRANSFER 设置为1表示稍后执行的curl_exec函数的返回是URL的返回字符串，而不是把返回字符串定向到标准输出并返回TRUE；

CURLLOPT_HEADER设置为0表示不返回HTTP头部信息。

CURL的选项还有很多，可以到PHP的官方网站（http://www.php.net/manual/en/function.curl-setopt.php）上查看CURL支持的所有选项列表。

cURL实现POST

$url = "http://localhost/server.php";
$post_data = array ("username" => "bob","key" => "12345");
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// post数据
curl_setopt($ch, CURLOPT_POST, 1);
// post的变量
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
$output = curl_exec($ch);
curl_close($ch);
//打印获得的数据
print_r($output);
PHP实现的封装
<?php 
class Curl
{
   /**
     * @brief                  get请求
     * @param $url             请求的url
     * @param array $param     请求的参数
     * @param int $timeout     超时时间
     * @param int $log       是否启用日志
     * @return mixed
     */
    public static function get($url, $param=array(), $timeout=10, $log=1)
    {
        $ch = curl_init();

        if (is_array($param)) {
            $url = $url . '?' . http_build_query($param);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); // 允许 cURL 函数执行的最长秒数

        $data = curl_exec($ch);

        if ($log) {
            $data .= "\r\n";
            $data .= self::logInfo($ch, $param, $data);
        }
        curl_close($ch);

        return $data;
    }
 
    /**
     * @brief                   post请求
     * @param $url              请求的url地址
     * @param array $param      请求的参数
     * @param int $log          是否启用日志
     * @return mixed
     */
    public static function post($url, $param=array(), $header=array(), $timeout=10, $log=1)
    {
        $ch = curl_init();
        if (is_array($param)) {
            $urlparam = http_build_query($param);
        } else if (is_string($param)) { //json字符串
            $urlparam = $param;
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); //设置超时时间
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //返回原生的（Raw）输出
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1); //POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, $urlparam); //post数据
        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        $data = curl_exec($ch);
        if ($log) {
            $data .= "\r\n";
            $data .= self::logInfo($ch, $param, $data);
        }
        
        curl_close($ch);
        return $data;
    }
 
 
    /**
     * 请求信息记录日志
     * @param $ch       curl句柄
     * @param $request  请求参数
     * @param $response 响应结果
     */
    private static function logInfo($ch, $request, $response)
    {
        $info = curl_getinfo($ch);
        $resultFormat =  "耗时:[%s] 返回状态:[%s] 请求的url[%s] 请求参数:[%s] 响应结果:[%s] 大小:[%s]kb 速度:[%s]kb/s";
        $resultLogMsg =  sprintf($resultFormat, $info['total_time'], $info['http_code'], $info['url'], var_export($request,true),var_export($response,true), $info['size_download']/1024, $info['speed_download']/1024);
        return $resultLogMsg;
    }
 
}
用法:

// get请求
echo Curl::get('http://www.baidu.com');
// post请求
$arr = Curl::post('http://localhost:9090/test.php', ['a'=>1,'b'=>2]);
print_r($arr);
