在使用浏览器发起的 HTTP 请求中，通常会包含一个识别标识。它名为 User Agent，简称 UA。它是一串包含了客户端基础信息的字符串。通过它可以方便的获取客户端的操作系统，语言，浏览器和版本信息。
在 PHP 中查看客户端 UA 标识的方式是读取系统常量 $_SERVER 中的 HTTP_USER_AGENT 选项：


echo $_SERVER['HTTP_USER_AGENT'];
使用

推荐一个轻松识别客户端信息的composer组件jenssegers/agent，虽然这个扩展官方为laravel框架开发。由于TP5支持composer依赖管理，亲测可用。

源码地址： https://github.com/jenssegers/agent
安装

使用 composer 安装:


composer require jenssegers/agent
基础用法


use Jenssegers\Agent\Agent;
$agent = new Agent();
//设置User Agent，比如在cli模式下用到
$agent->setUserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/537.13+ (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2');
$agent->setHttpHeaders($headers);
//Is方法检测（如：操作系统）
$agent->is('Windows');
$agent->is('Firefox');
$agent->is('iPhone');
$agent->is('OS X');
//魔法方法（如： 厂商产品定位）
$agent->isAndroidOS();
$agent->isNexus();
$agent->isSafari();
//识别移动设备
$agent->isMobile();//手机
$agent->isTablet();//平板
$agent->isDesktop();//桌面端
// 语言
$languages = $agent->languages();
// ['nl-nl', 'nl', 'en-us', 'en']
// 是否是机器人
$agent->isRobot();
// 获取设备信息 (iPhone, Nexus, AsusTablet, ...)
$agent->device();
// 系统信息  (Ubuntu, Windows, OS X, ...)
$agent->platform();
// 浏览器信息  (Chrome, IE, Safari, Firefox, ...)
$agent->browser();
// 获取浏览器版本
$browser = $agent->browser();
$version = $agent->version($browser);
// 获取系统版本
$platform = $agent->platform();
$version = $agent->version($platform);
