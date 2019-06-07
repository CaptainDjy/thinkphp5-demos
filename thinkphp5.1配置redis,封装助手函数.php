做个记录有用直接ctrl+c,ctrl+v ......

第一部安装redis,就不写了

直接上代码

第一部: config目录下新建redis配置文件


<?php
/**
 * Created by PhpStorm.
 * User: TXCMS_V1
 * Date: 2019-2-15
 * Time: 11:31
 */
return [
    'host'   => '127.0.0.1',
    'port'   => '6379',
];

第二步: extend目录下新建redis/Redis.php

<?php
namespace redis;
use think\facade\Config;

/**
 * Created by PhpStorm.
 * User: TXCMS_V1
 * Date: 2019-2-15
 * Time: 11:33
 */
class Redis extends \Redis
{
    public static function redis() {
        $con = new Redis();
        $con->connect(Config::get('redis.host'), Config::get('redis.port'), 5);
        return $con;
    }
}
第三步: 封装成助手函数 thinkphp 目录下打开helper.php 
添加:

if (!function_exists('redis')) {
    /**
     * 获取容器对象实例
     * @return Container
     */
    function redis()
    {
        return \redis\Redis::redis();
    }
}
直接redis()就可以使用了, 封装看个人爱好把,毕竟也有些不喜欢用助手函数的
