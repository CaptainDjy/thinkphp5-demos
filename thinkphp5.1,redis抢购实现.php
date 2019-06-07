无聊,网上瞎逛看到的自己摸索着实现了下,挺好玩,做个记录


<?php
namespace app\index\controller;

use think\App;
use think\Controller;
use think\Db;
use think\facade\Config;

class Index extends Controller
{
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->id = input('id');
        $store =  DB::name('goods')->where('id', $this->id)->value('count');
        $redis = new \Redis();
        $redis->connect(Config::get('redis.host'),Config::get('redis.port'),9000);
        $redis->del('goods_store'); // 删除库存列表
        $res=$redis->llen('goods_store'); //返回库存长度，这里已经是0
        $count=$store-$res;
        for($i=0;$i<$count;$i++){
            $redis->lpush('goods_store',1); //列表推进50个，模拟50个商品库存
        }
        $redis->lLen('goods_store');
    }

    public function index()
    {
        $id =  $this->id; //商品编号
        $redis = new \Redis();
        $redis->connect(Config::get('redis.host'),Config::get('redis.port'),9000);
        if(!$id){
            return $this->insertlog(0);//记录失败日志
        }
        $count=$redis->lpop('goods_store'); //减少库存，返回剩余库存数
        if(!$count){ //库存为0

            $this->insertlog(0); //记录秒杀失败的 日志

            return false;

        }else{// 有库存

            $ordersn = $this->build_order_no(); //订单号随机生成
            $uid = 1; //用户id随机生成，正式项目可以启用登录的session

            $status = 1; // 订单状态

            $data = Db::name("goods")->field("count,amount")->where("id",$id)->find();//查找商品
            if(!$data){
                return $this->insertlog(0); //商品不存在
            }



            $result = Db::name("order")->insert(["order_sn"=>$ordersn,"user_id"=>$uid,"goods_id"=>$id,"price"=>$data['amount'],"status"=>$status,'addtime'=>date('Y-m-d H:i:s')]); //订单入库

            $res = Db::name("goods")->where("id",$id)->setDec("count"); //库存减少

            if($res){
                $this->insertlog(); //记录成功的日志
            }else{
                $this->insertlog(0);//记录失败的日志
            }








        }
    }

    //生成唯一订单

    function build_order_no(){

        return date('ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);

    }



// 记录日志 状态1成功 0失败

    function insertlog($status=1){

        return Db::name("log")->insertGetId(["count"=>1,"status"=>$status,"addtime"=>date('Y-m-d H:i:s')]);

    }


}
测试数据库:

CREATE TABLE `goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `count` int(10) unsigned NOT NULL,
  `status` tinyint(255) NOT NULL DEFAULT '1',
  `title` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
CREATE TABLE `log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `addtime` datetime DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `count` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1172 DEFAULT CHARSET=utf8;
CREATE TABLE `order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `goods_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1959 DEFAULT CHARSET=utf8;
ab压测下载地址:https://www.apachehaus.com/cgi-bin/download.plx
