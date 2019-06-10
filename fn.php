<?php
/*
* 文件名: fn.php
* 作者  : captaindu   微信18362961528 email:1533036253@qq.com
* 日期时间: 2019/6/9  14:07
* 功能  :函数集合
*/
if (!function_exists('dump')) {
    function dump($arr){
        echo '<pre>'.print_r($arr,TRUE).'</pre>';
    }

}
/*
 * POST或GET的curl请求
 * $url 请求地址
 * $data 请求数组
 * */
if (!function_exists('curl')) {
    function curl($url, $data = '')
    {
        $ch = curl_init();
        if (class_exists('\CURLFile')) {
            curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
        } else {
            if (defined('CURLOPT_SAFE_UPLOAD')) {
                curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
            }
        }
        preg_match('/https:\/\//', $url) ? $ssl = TRUE : $ssl = FALSE;
        if ($ssl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $d = curl_exec($ch);
        curl_close($ch);
        return $d;
    }
}
/*
 * 通过oauth2获取公众号用户信息
 * $type snsapi_userinfo表示用户信息  snsapi_base表示openid获取
 * */
if (!function_exists('getoauth')) {
    function getoauth($type = 'snsapi_base', $appid = '', $apps = '', $expired = '600')
    {
        $scheme = $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
        $baseUrl = urlencode($scheme . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);

        if (!isset($_GET['code'])) {
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$baseUrl&response_type=code&scope=$type#wechat_redirect";
            header("location:$url");
            exit();
        } else {
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$apps&code=" . $_GET['code'] . "&grant_type=authorization_code";

            $output = (array)json_decode(curl($url));
            if ($type == 'snsapi_base') {
                return $output['openid'];
            } else {
                $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $output['access_token'] . '&openid=' . $output['openid'] . '&lang=zh_CN';
                $output = (array)json_decode(curl($url));
                return $output;
            }

        }
    }
}
/*
 * 设置缓存
 * $name 缓存名称
 * $value 缓存值,可以是数组字符
 * $expire 过期时间
 * */
if (!function_exists('setcache')) {
    function setcache($name, $value, $expire = 7000)
    {
        $filename = "./$name._cache.php";
        $json = json_encode(array($name => $value, "expire" => time() + $expire));
        $result = file_put_contents($filename, $json);
        if ($result) {
            return true;
        }
        return false;
    }
}
/*
 * 获取缓存
 * */
if (!function_exists('getcache')) {
    function getcache($name)
    {
        $filename = "./$name._cache.php";
        if (!is_file($filename)) {
            return false;
        }
        $content = file_get_contents($filename);

        $arr = json_decode($content, true);
        if ($arr['expire'] <= time()) {
            return false;
        }
        return $content;
    }
}
/*返回ajax状态*/
if (!function_exists('json')) {
    function json($code = 200, $message = '请求成功', $list = array(), $total = 0)
    {
        $json = array(
            'code' => $code,
            'msg' => $message
        );
        if (!empty($list)) {
            $json['list'] = $list;
        }
        if (!empty($total)) {
            $json['total'] = $total;
        }

        header('Content-type: application/json');
        exit(json_encode($json, JSON_UNESCAPED_UNICODE));
    }
}
if (!function_exists('tablearr')) {
    function tablearr($table)
    {
        $table = preg_replace("'<table[^>]*?>'si", "", $table);
        $table = preg_replace("'<tr[^>]*?>'si", "", $table);
        $table = preg_replace("'<td[^>]*?>'si", "", $table);
        $table = str_replace("</tr>", "{tr}", $table);
        $table = str_replace("</td>", "{td}", $table);
        //去掉 HTML 标记
        $table = preg_replace("'<[/!]*?[^<>]*?>'si", "", $table);
        //去掉空白字符
        $table = preg_replace("'([rn])[s]+'", "", $table);
        $table = preg_replace('/&nbsp;/', "", $table);
        $table = str_replace(" ", "", $table);
        $table = str_replace(" ", "", $table);
        $table = str_replace("\r", "", $table);
        $table = str_replace("\t", "", $table);
        $table = str_replace("\n", "", $table);
        $table = explode('{tr}', $table);
        array_pop($table);
        foreach ($table as $key => $tr) {
            $td = explode('{td}', $tr);
            array_pop($td);
            $td_array[] = $td;
        }
        return $td_array;
    }
}
/**查找前面字符串中是否包含后者
 * @param $string 原字符串
 * @param $find 子字符串
 * @return bool
 */
if (!function_exists('findstr')) {
    function findstr($string, $find)
    {
        return !(strpos($string, $find) === FALSE);
    }
}
/**
 * emoji转换成utf8,方便存储,支持还原
 * de是解码  en编码
 */
if (!function_exists('emoji')) {
    function emoji($str, $is = 'en')
    {
        if ('en' == $is) {
            if (!is_string($str)) return $str;
            if (!$str || $str == 'undefined') return '';

            $text = json_encode($str);
            $text = preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i", function ($str) {
                return addslashes($str[0]);
            }, $text);
            return json_decode($text);
        } else {
            $text = json_encode($str);
            $text = preg_replace_callback('/\\\\\\\\/i', function ($str) {
                return '\\';
            }, $text);
            return json_decode($text);
        }
    }
}
/**
 * 时间友好显示,传入时间戳
 */
if (!function_exists('timeline')) {
    function timeline($time)
    {
        if (time() <= $time) {
            return date("Y-m-d H:i:s", $time);
        } else {
            $t = time() - $time;
            $f = array(
                '31536000' => '年',
                '2592000' => '个月',
                '604800' => '星期',
                '86400' => '天',
                '3600' => '小时',
                '60' => '分钟',
                '1' => '秒'
            );
            foreach ($f as $k => $v) {
                if (0 != $c = floor($t / (int)$k)) {
                    return $c . $v . '前';
                }
            }
        }
    }
}
/**
 * 获取文件扩展名
 * @param $file
 * @return string
 */
if (!function_exists('fileext')) {
    function fileext($file)
    {
        return strtolower(pathinfo($file, 4));
    }
}
if (!function_exists('isajax')) {
    function isajax()
    {
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') return true;
        if(isset($_GET['HTTP_X_REQUESTED_WITH'])    && $_GET['HTTP_X_REQUESTED_WITH']    == 'XMLHttpRequest') return true;
        return false;
    }
}
/**
 * 导出csv数据,不支持大数据,大数据用分页导出
 * $arr = array(
 * array('用户名','密码','邮箱'),
 * array(
 * array('A用户','123456','xiaohai1@zhongsou.com'),
 * array('B用户','213456','xiaohai2@zhongsou.com'),
 * array('C用户','123456','xiaohai3@zhongsou.com')
 * ));
 * putcsv("导出文件",$arr);
 *
 * 导出csv模板
 * $arr = array(array('用户名','密码','邮箱'));
 * putcsv("导出模板",$arr);
 * 文件名不带.csv,自动加
 * $filename 导出文件名
 * $arr 导出数组
 */
if (!function_exists('putcsv')) {
    function putcsv($filename, $arr)
    {
        if (empty($arr)) {
            return false;
        }
        $export_str = implode(',', $arr[0]) . "\n";

        if (!empty($arr[1])) {
            foreach ($arr[1] as $k => $v) {

                $export_str .= implode(',', $v) . "\n";

            }
        }
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=" . $filename . date('Y-m-d-H-i-s') . ".csv");
        ob_start();
        ob_end_clean();
        echo "\xEF\xBB\xBF" . $export_str;//解决WPS和excel不乱码
    }
}
/**
 * 导入csv,编码ANSI
 * read.csv数据
 *
 * 商户名称, 昵称, 手机号
 * 惠吃惠喝, 会吃,18291443322
 * egeme, 依加米,18923451622
 * 徐汇区,上海,18291447788
 * 衣服, 买衣服,18291448824
 * 米掌柜, MI,18291448822
 * $path = 'read.csv';
 * $arr= getcsv($path);
 * 导入csv返回数组,注意导入文件一定要是ANSI编码,也就是WPS和excel打开不乱码
 * $path 文件路径
 * */
if (!function_exists('getcsv')) {
    function getcsv($path)
    {
        $handle = fopen($path, 'r');
        $dataArray = array();
        $row = 0;
        while ($data = fgetcsv($handle)) {
            $num = count($data);

            for ($i = 0; $i < $num; $i++) {
                $dataArray[$row][$i] = mb_convert_encoding($data[$i], "utf-8", 'GBK');
            }
            $row++;

        }

        return $dataArray;
    }
}
/**判断是否微信浏览器
 * @return bool
 */
if (!function_exists('isweixin')) {
    function isweixin()
    {
        $agent = $_SERVER ['HTTP_USER_AGENT'];
        if (!strpos($agent, "icroMessenger")) {
            return false;
        }
        return true;
    }
}
/**
 * 隐藏手机中间4位
 * @param $phone
 * @return mixed
 */
if (!function_exists('hidetel')) {
    function hidetel($phone)
    {
        $IsWhat = preg_match('/(0[0-9]{2,3}[-]?[2-9][0-9]{6,7}[-]?[0-9]?)/i', $phone);
        if ($IsWhat == 1) {
            return preg_replace('/(0[0-9]{2,3}[-]?[2-9])[0-9]{3,4}([0-9]{3}[-]?[0-9]?)/i', '$1****$2', $phone);
        } else {
            return preg_replace('/(1[34578]{1}[0-9])[0-9]{4}([0-9]{4})/i', '$1****$2', $phone);
        }
    }
}
/**
 *
 * @param 生成字符长度 $len
 * @param 生成类型默认大小写数字,0大小写 1数字 2大写 3小写 4中文 $type
 * @param 添加字符后缀 $addChars
 *
 * @return
 */
if (!function_exists('randstr')) {
    function randstr($len = 6, $type = '', $addChars = '')
    {
        $str = '';
        switch ($type) {
            case 0:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
                break;
            case 1:
                $chars = str_repeat('0123456789', 3);
                break;
            case 2:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
                break;
            case 3:
                $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
                break;
            case 4:
                $chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借" . $addChars;
                break;
            default :
                $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
                break;
        }
        if ($len > 10) {
            $chars = $type == 1 ? str_repeat($chars, $len) : str_repeat($chars, 5);
        }
        if ($type != 4) {
            $chars = str_shuffle($chars);
            $str = substr($chars, 0, $len);
        } else {
            for ($i = 0; $i < $len; $i++) {
                $str .= cutstr($chars, 1, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 0);
            }
        }
        return $str;
    }
}
/**
 *
 * @param 字符串 $str
 * @param 长度 $length
 * @param 开始位置 $start
 * @param 是否显示... $suffix
 * @param 编码 $charset
 *
 * 截取字符串
 */
if (!function_exists('cutstr')) {
    function cutstr($str, $length, $start = 0, $suffix = true, $charset = "utf-8")
    {
        if (function_exists("mb_substr"))
            $slice = mb_substr($str, $start, $length, $charset);
        elseif (function_exists('iconv_substr')) {
            $slice = iconv_substr($str, $start, $length, $charset);
            if (false === $slice) {
                $slice = '';
            }
        } else {
            $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = join("", array_slice($match[0], $start, $length));
        }
        return $suffix ? $slice . '...' : $slice;
    }
}
/**
 *
 * @param 字节大小 $size
 * @param 保留小数位数 $dec
 *
 * 格式化文件大小
 */
if (!function_exists('filecount')) {
    function filecount($size, $dec=2) {
        $a = array("B", "KB", "MB", "GB", "TB", "PB");
        $pos = 0;
        while ($size >= 1024) {
            $size /= 1024;
            $pos++;
        }
        return round($size,$dec)." ".$a[$pos];
    }}
/*概率算法
   proArr array(100,200,300，400)
   function  get_prize(){//获取中奖
   $prize_arr = array(
       array('id'=>1,'prize'=>'平板电脑','v'=>1),
       array('id'=>2,'prize'=>'数码相机','v'=>1),
       array('id'=>3,'prize'=>'音箱设备','v'=>1),
      array('id'=>4,'prize'=>'4G优盘','v'=>1),
      array('id'=>5,'prize'=>'10Q币','v'=>1),
      array('id'=>6,'prize'=>'下次没准就能中哦','v'=>95),
   );
   foreach ($prize_arr as $key => $val) {
       $arr[$val['id']] = $val['v'];
   }
   $ridk = getrand($arr); //根据概率获取奖项id

   $res['yes'] = $prize_arr[$ridk-1]['prize']; //中奖项
   unset($prize_arr[$ridk-1]); //将中奖项从数组中剔除，剩下未中奖项
   shuffle($prize_arr); //打乱数组顺序
   for($i=0;$i<count($prize_arr);$i++){
       $pr[] = $prize_arr[$i]['prize'];
   }
   $res['no'] = $pr;
   return $res;
   }
   */
if (!function_exists('getrand')) {
    function getrand($proArr) {
        $result = '';
        $proSum = array_sum($proArr);
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }}
/**
 * 去除空格 换行
 * @param undefined $str
 *
 * @return
 */
function trimstr($str)
{
    $str = trim($str);
    $str = preg_replace("/\t/","",$str);
    $str = preg_replace("/\r\n/","",$str);
    $str = preg_replace("/\r/","",$str);
    $str = preg_replace("/\n/","",$str);
    $str = preg_replace("/ /","",$str);
    return trim($str); //返回字符串
}
//去除数组中值两端空格,支持excel
function trimarray($Input){
    if (!is_array($Input))
        return preg_replace("/(\s|\&nbsp\;|　|\xc2\xa0)/","",$Input);
    return array_map('trimarray', $Input);
}
if (!function_exists('getip')) {
    function getip() {
        static $ip = '';
        $ip = $_SERVER['REMOTE_ADDR'];
        if(isset($_SERVER['HTTP_CDN_SRC_IP'])) {
            $ip = $_SERVER['HTTP_CDN_SRC_IP'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
            foreach ($matches[0] AS $xip) {
                if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                    $ip = $xip;
                    break;
                }
            }
        }
        if (preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $ip)) {
            return $ip;
        } else {
            return '127.0.0.1';
        }
    }
}
/**
 * 生成avatar头像
 * @param 邮箱 $email
 * @param 大小 $s
 * @param undefined $d
 * @param undefined $g
 *
 * @return
 */
function getavatar($email='', $s=40, $d='mm', $g='g') {
    $hash = md5($email);
    $avatar = "http://www.gravatar.com/avatar/$hash?s=$s&d=$d&r=$g";
    return $avatar;
}
/**
 * 获取内存
 * @return string
 */
function getmemory(){
    return round((memory_get_usage()/1024/1024),3)."M";
}
/**
 * 加密解密函数
 * ENCODE 加密
 * @param $string
 * @param string $operation
 * @param string $key
 * @param int $expiry
 * @return string
 */
if (!function_exists('authcode')) {
    function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
    {
        $ckey_length = 4;
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);
        $result = '';
        $box = range(0, 255);
        $rndkey = array();
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'DECODE') {
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc . str_replace('=', '', base64_encode($result));
        }
    }
}
/**
 * 生成随机颜色
 * @return string
 */
if (!function_exists('randcolor')) {
    function randcolor()
    {
        $char = 'abcdef0123456789';
        $str = '';
        for ($i = 0; $i < 6; $i++) {
            $str .= substr($char, mt_rand(0, 15), 1);
        }
        return '#' . $str;
    }
}
/**
 *
 * @param 数组 $arr
 * @param 层级 $level
 * @param undefined $ptagname
 *
 * 数组转换xml
 */
if (!function_exists('arr2xml')) {
    function arr2xml($arr, $level = 1, $ptagname = '')
    {
        $s = $level == 1 ? "<xml>" : '';
        foreach ($arr as $tagname => $value) {
            if (is_numeric($tagname)) {
                $tagname = $value['TagName'];
                unset($value['TagName']);
            }
            if (!is_array($value)) {
                $s .= "<{$tagname}>" . (!is_numeric($value) ? '<![CDATA[' : '') . $value . (!is_numeric($value) ? ']]>' : '') . "</{$tagname}>";
            } else {
                $s .= "<{$tagname}>" . arr2xml($value, $level + 1) . "</{$tagname}>";
            }
        }
        $s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
        return $level == 1 ? $s . "</xml>" : $s;
    }
}
/**
xml转换数组
 */
if (!function_exists('xml2arr')) {
    function xml2arr($xml)
    {
        if (empty($xml)) {
            return array();
        }
        $result = array();
        $xmlobj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($xmlobj instanceof \SimpleXMLElement) {
            $result = json_decode(json_encode($xmlobj), true);
            if (is_array($result)) {
                return $result;
            } else {
                return array();
            }
        } else {
            return $result;
        }
    }
}
/*
创建文件或文件夹
参数是数组
["qq/","qq.txt","qqq/tt/"];
*/
if (!function_exists('filecreate')) {
    function filecreate($files)
    {
        foreach ($files as $key => $value) {
            if (substr($value, -1) == '/') {
                if (!is_dir($value)) {
                    mkdir($value, 0777, true);
                }
            } else {
                if (!file_exists($value)) {
                    file_put_contents($value, '');
                }
            }
        }
    }
}
/**
 *
 * @param undefined $type 弹出类型 1,2,3
 * @param undefined $info 提示语
 * @param undefined $url 跳转地址
 *
 * @return
 */
if (!function_exists('alert')) {
    function alert($type = 1, $info = "", $url = "")
    {
        if (1 == $type) {//自动关闭
            $strs = empty($info) ? "" : "alert('$info');";
            echo "<script>" . $strs . "document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
WeixinJSBridge.call('closeWindow');});</script>";
            exit;
        } elseif (2 == $type) {//显示跳转中...
            $urls = empty($url) ? "" : 'location.href="' . $url . '";';
            $strs = empty($info) ? "正在跳转中..." : $info;
            echo "<meta charset='utf-8'>";
            die('<script type="text/javascript">document.write("<meta name=\"viewport\" content=\"width=device-width,initial-scale=1,user-scalable=0\"><div style=\"font-size:16px;margin:30px auto;text-align:center;\">' . $strs . '</div>");' . $urls . ';</script>');
        } elseif (3 == $type) {//普通弹出,跳转
            $strs = empty($info) ? "" : "alert('$info');";
            $urls = empty($url) ? "" : 'location.href="' . $url . '";';
            die('<script type="text/javascript">' . $strs . $urls . '</script>');
        } elseif (4 == $type) {//蓝色i
            echo "<meta charset='utf-8'>";
            die('<script>document.write("<title>提示</title><meta name=\"viewport\" content=\"width=device-width, initial-scale=1, user-scalable=0\"><link rel=\"stylesheet\"  href=\"https://res.wx.qq.com/open/libs/weui/0.4.3/weui.min.css\"><div class=\"weui_msg\"><div class=\"weui_icon_area\"><i class=\"weui_icon_info weui_icon_msg\"></i></div><div class=\"weui_text_area\"><h4 class=\"weui_msg_title\">' . $info . '</h4></div></div>");document.addEventListener("WeixinJSBridgeReady", function onBridgeReady() {WeixinJSBridge.call("hideOptionMenu");});</script>');

        } elseif (5 == $type) {//红色警告!
            echo "<meta charset='utf-8'>";
            die('<script>document.write("<title>提示</title><meta name=\"viewport\" content=\"width=device-width, initial-scale=1, user-scalable=0\"><link rel=\"stylesheet\"  href=\"https://res.wx.qq.com/open/libs/weui/0.4.3/weui.min.css\"><div class=\"weui_msg\"><div class=\"weui_icon_area\"><i class=\"weui_icon_msg weui_icon_warn\"></i></div><div class=\"weui_text_area\"><h4 class=\"weui_msg_title\">' . $info . '</h4></div></div>");document.addEventListener("WeixinJSBridgeReady", function onBridgeReady() {WeixinJSBridge.call("hideOptionMenu");});</script>');

        } elseif (6 == $type) {//绿色成功√
            echo "<meta charset='utf-8'>";
            die('<script>document.write("<title>提示</title><meta name=\"viewport\" content=\"width=device-width, initial-scale=1, user-scalable=0\"><link rel=\"stylesheet\"  href=\"https://res.wx.qq.com/open/libs/weui/0.4.3/weui.min.css\"><div class=\"weui_msg\"><div class=\"weui_icon_area\"><i class=\"weui_icon_msg weui_icon_success\"></i></div><div class=\"weui_text_area\"><h4 class=\"weui_msg_title\">' . $info . '</h4></div></div>");document.addEventListener("WeixinJSBridgeReady", function onBridgeReady() {WeixinJSBridge.call("hideOptionMenu");});</script>');

        }
    }
}
/**
 * 字符串与数组互相转换,转换自动判断是否数组
 */
if (!function_exists('str2arr')) {
    function str2arr($var, $str = ',')
    {
        if (is_array($var)) {
            return implode($str, $var);
        } else {
            return explode($str, $var);
        }
    }
}
/**
 * 计算两地之间距离
 * @param undefined $lat1 经度1
 * @param undefined $lng1 纬度1
 * @param undefined $lat2 经度2
 * @param undefined $lng2 纬度2
 * @param 1 $len_type 1是米,2,千米
 * @param undefined $decimal,保留两位小数
 *
 * @return
 */
if (!function_exists('getdistance')) {
    function getdistance($lat1, $lng1, $lat2, $lng2, $len_type = 1, $decimal = 2)
    {
        $pi = 3.1415926000000001;
        $er = 6378.1369999999997;
        $radLat1 = ($lat1 * $pi) / 180;
        $radLat2 = ($lat2 * $pi) / 180;
        $a = $radLat1 - $radLat2;
        $b = (($lng1 * $pi) / 180) - (($lng2 * $pi) / 180);
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + (cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))));
        $s = $s * $er;
        $s = round($s * 1000);
        if (1 < $len_type) {
            $s /= 1000;
        }
        return round($s, $decimal);
    }
}
/*
* 根据ip获取省市
参数是地图key
默认是百度,可选填qq地图
*/
if (!function_exists('getaddress')) {
    function getaddress($ak = '', $type = "baidu")
    {

        if ($type == 'baidu') {
            $ak = (empty($ak)) ? "8SlSbHObMgN8HeOwGUQXU5XM" : $ak;
            $url = "https://api.map.baidu.com/location/ip?ak=$ak&coor=bd09ll&ip=" . getip();
            $rs = json_decode(curl($url), 1);
            if ($rs['status'] == 0) {
                return $rs['content'];
            } else {
                return $rs['message'];
            }
        } else {
            $ak = (empty($ak)) ? "ACEBZ-FDXWP-WFRDV-VGS5Q-S2Q5K-HQBNA" : $ak;
            $url = "https://apis.map.qq.com/ws/location/v1/ip?ip=" . getip() . "&key=$ak";
            $rs = json_decode(curl($url), 1);
            if ($rs['status'] == 0) {
                return $rs['result'];
            } else {
                return $rs['message'];
            }
        }


    }
}
/**
 * html转换实体
 */
if (!function_exists('htmlencode')) {
    function htmlencode($var)
    {
        if (is_array($var)) {
            foreach ($var as $key => $value) {
                $var[htmlspecialchars($key)] = html2($value);
            }
        } else {
            $var = str_replace('&amp;', '&', htmlspecialchars($var, ENT_QUOTES));
        }
        return $var;
    }
}
/**
 * html还原
 */
if (!function_exists('htmldecode')) {
    function htmldecode($var)
    {
        return htmlspecialchars_decode($var);
    }
}
/**
 * 生成唯一id字符串
 */
if (!function_exists('id')) {
    function id()
    {
        return md5(uniqid());
    }
}
