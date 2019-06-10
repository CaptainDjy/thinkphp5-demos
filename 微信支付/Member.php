<?php

namespace app\api\controller;
use EasyWeChat\Foundation\Application;
use app\api\controller\WechatController;
use app\common\controller\Api;
use app\api\model\Member as MemberModel;
use think\Db;
use Yansongda\Pay\Pay;
use Yansongda\Pay\Log;

// 指定允许其他域名访问   跨域
header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:*');
// 响应头设置
header('Access-Control-Allow-Headers:*');
header('Access-Control-Allow-Credentials: true');
class Member extends Api
{
    protected $noNeedLogin = ['*'];




    public function Pay(){

        $config = [
            'app_id' => 'wxdd8979589794a59e',// 公众号 APPID
            'mch_id' => '1533390891',//'1533390891',
            'key' => '15333908911533390891153339089199',
            'notify_url' =>request()->domain().'/api/member/updateLevel',
            'cert_client' => './cert/apiclient_cert.pem', // optional，退款等情况时用到
            'cert_key' => './cert/apiclient_key.pem',// optional，退款等情况时用到
            'log' => [
                'file' => './logs/wechat.log',
                'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
                'type' => 'single', // optional, 可选 daily.
                'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
            ],
        ];

//        print_r($config);exit;
        $data=$this->request->param();   //只需要传id和level_id,
        $cash=db('level')->where('level_id',$data['level_id'])->find();
        $open=db('member')->where('id',$data['id'])->find();
        $level_id=$data['level_id'];
        $level_num=$open['level_num'];
        if($level_id<$level_num){
            $this->error('不可降级充值!');
        }
        $ord=new Member();
        $order_id=$ord->order($cash,$open);
        $no=db('order')->where('id',$order_id)->find()['order_id'];
        $state=db('order')->where('id',$order_id)->find()['state'];
//        if($state=1){
//            $this->error();
//        }
        $order = [
            'out_trade_no' =>$no,
            'total_fee' => $cash['level_cash'], // **单位：分**
            'body' => 'test body - 测试',
            'openid' => $open['openid'],
        ];

        $pay = \Yansongda\Pay\Pay::wechat($config)->mp($order);


        $this->success('返回数据成功',$pay);
    }


    public function order($cash,$open){
        mt_srand((double) microtime() * 1000000);
        $order['order_id']=date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $order['user_id']=$open['id'];
        $order['cash']=$cash['level_cash'];
        $order['create_time']=date('Y-m-d H:i:s',time());
        $order['level_id']=$cash['level_id'];
        return db('order')->insertGetId($order);
    }
    public function updateLevel(){
        $config = [
            'app_id' => 'wxdd8979589794a59e',// 公众号 APPID
            'mch_id' => '1533390891',//'1533390891',
            'key' => '15333908911533390891153339089199',
            'notify_url' =>request()->domain().'api/member/updateLevel',
            'cert_client' => './cert/apiclient_cert.pem', // optional，退款等情况时用到
            'cert_key' => './cert/apiclient_key.pem',// optional，退款等情况时用到
            'log' => [
                'file' => './logs/wechat.log',
                'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
                'type' => 'single', // optional, 可选 daily.
                'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
            ],
            ];
        $wechat = Pay::wechat($config);
        $notify_data = $wechat->verify();
        //file_put_contents('test.json',json_encode($notify_data));
        $order_id=$notify_data['out_trade_no'];
        //$order_id=2019060993078;
        $result=db('order')->where('order_id',$order_id)->find();
        $state=db('order')->where('order_id',$order_id)->update(['state'=>1]);
        //print_r($result);exit;
        //$result=$this->request->param();
        $id=$result['user_id'];
        $level_id=$result['level_id'];
        $mem=db('member')->where('id',$id)->find();
        $level=db('level')->where('level_id',$level_id)->find();
        $mem['level_name']=$level['level_name'];
        if($level_id<1){
            $mem['expire_time']=date('Y-m-d');
        }elseif($level_id>=1){
            $a=30;
            if($level_id > $mem['level_num']) {
                $mem['level_num'] = $level_id;
                $mem['expire_time'] = date("Y-m-d ", strtotime('+' . "$a" . 'days'));
            } elseif ($level_id == $mem['level_num']) {
                $mem['expire_time'] = date("Y-m-d ", strtotime('+' . "$a" . 'days', strtotime($mem['expire_time'])));
            } else {
                $this->error('暂不可降级！');
            }
        }
        if(MemberModel::update($mem)){
            $this->success('会员充值成功');
        } else {
            $this->error('会员充值失败');
        }
        }




//    public function updateLevel($data,$pay)    //新写的只需要传一个level_id和一个id
//    {
//        $id = $this->request->param();
//        $cash = $data['cash'];
//        $level_id = $data['level_id'];
//        $mem = db('member')->where('id', $id)->find();
//        $level = db('level')->where('level_id', $level_id)->find();
//        $mem['level_name'] = $level['level_name'];
//      	if ($level_id < 1) {
//            $mem['expire_time'] = date("Y-m-d ");
//        }elseif($level_id>=1) {
//            $t = $cash / $level['level_cash'];
//            $a=(int)($t*30);
//             if($level_id > $mem['level_num']) {
//                $mem['level_num'] = $level_id;
//                $mem['expire_time'] = date("Y-m-d ", strtotime('+' . "$a" . 'days'));
//            } elseif ($level_id == $mem['level_num']) {
//                $mem['expire_time'] = date("Y-m-d ", strtotime('+' . "$a" . 'days', strtotime($mem['expire_time'])));
//            } else {
//                $this->error('暂不可降级！');
//            }
//        }
//        if(MemberModel::update($mem)){
//            $this->success('会员充值成功',$pay);
//        } else {
//            $this->error('会员充值失败');
//        }
//        }


    public function show()
    {
        if($res = db('member')->order('id desc')->paginate(10)) {

            $this->success('查询成功！');
        }else{
            $this->error('查询失败！');
        }
    }
    public function member(){
        $id=request()->param('id');
        $member=db('member')->where('id',$id)->find();
        $level_id=$member['level_num'];
        $level=db('level')->where('level_id',$level_id)->find();
        $b[]=[$member,$level];
        if($member){
            $this->success('会员展示成功!',$b['0']);
        }else{
            $this->error('没有该用户!');
        }
    }

    public function delete()
    {
        $id = request()->param('id');
        if ($res = db('member')->delete($id)) {
            $this->success('用户删除成功!');
        } else {
            $this->error('用户删除失败!');
        }

    }
  
  public function login_check($data)
  {

      $openid = $data['openid'];

      $mem = db('member')->where('openid', $openid)->find();
      if (empty($mem)) {
          # 写注册方法
          $mem1=db('member')->insertGetId($data);
          if($mem1){
              header('location: http://www.yhpkj.com/index.html?id='.$mem1);exit;
          }
      } elseif (!empty($mem)) {
          //session('userid', $mem['id']);
          header('location: http://www.yhpkj.com/index.html?id='.$mem['id']);exit;
      }
  }

  public function getUserInfo() {
        //halt(request()->post());
        $data = Db::table('fa_member')->where('id', $this->request->param('id'))->find();
        if ($data) {
            $this->result('获取成功', $data, 200);
        }else{
            $this->result('获取失败', $data, 201);
        }
  }


  
  public function wechat(){
  		 $wechat = new WechatController();
    //获取openid和accessToken
        $this->success('请求成功',$wechat->getOpenid(),'321');
    //获取用户信息
      // return $this->responseJson(200,$wechat->getUserInfo(),'321');
		//$openid = json_decode(json_encode($wechat->getUserInfo()),true);
   
  }



	 public function signup_check()
    {
        if (request()->isPost()) {
            $username = trim(input('username'));
            $password = trim(input('password'));
            $password1 = trim(input('password1'));
            $image =input('image');	
            if (strlen($username) < 6 || strlen($password) < 6) {
                $this->error('用户名或密码长度不得小于6位！');
            }
            if ($password != $password1) {
                $this->error('两次密码输入不相同！');
            }
            $username_data = Db::name('member')->where('username', $username)->select();
            if ($username_data) {
                $this->error('该用户名已经存在，请换一个重试！');
            }
            $data = [
                'username' => $username,
                'password' => md5($password),
                'image'=>$image
            ];

            if ($status =\app\api\model\Member::create($data)) {
                $this->success('恭喜您注册成功，现在前往登录页！');
            } else {
                $this->error('注册时出现问题，请重试或联系管理员！');
            }
        }
}
  

    //public function login_check()
    //{
      //  $username = trim(input('username'));
       // $password = md5(trim(input('password')));
       // $data = Db::name('member')->where('username',$username)->select();
       // if (!$data) {
        //    $this->error('用户名不存在，请确认后重试！');
        //}
        //if ($data[0]['password'] == $password) {
         //   session('username',$data[0]);
       //     $this->success('登录成功！',$data);
        //}else{
         //   $this->error('用户名和密码不匹配，请确认后重试！');
        //}
   // }

	public function wxlogi() {
      	$option = [
        ];
    	$app = new Application($option);
    }
  	
  	public function config() {
    	 [
            /**
             * Debug 模式，bool 值：true/false
             *
             * 当值为 false 时，所有的日志都不会记录
             */
            'debug'  => true,

            /**
             * 账号基本信息，请从微信公众平台/开放平台获取
             */
            'app_id'  => '',         // AppID
            'secret'  => 'your-app-secret',     // AppSecret
            'token'   => 'your-token',          // Token
            'aes_key' => '',                    // EncodingAESKey，安全模式与兼容模式下请一定要填写！！！

            /**
             * 日志配置
             *
             * level: 日志级别, 可选为：
             *         debug/info/notice/warning/error/critical/alert/emergency
             * permission：日志文件权限(可选)，默认为null（若为null值,monolog会取0644）
             * file：日志文件位置(绝对路径!!!)，要求可写权限
             */
            'log' => [
                'level'      => 'debug',
                'permission' => 0777,
                'file'       => '/tmp/easywechat.log',
            ],

            /**
             * OAuth 配置
             *
             * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
             * callback：OAuth授权完成后的回调页地址
             */
            'oauth' => [
                'scopes'   => ['snsapi_userinfo'],
                'callback' => '/examples/oauth_callback.php',
            ],

            /**
             * 微信支付
             */
            'payment' => [
                'merchant_id'        => 'your-mch-id',
                'key'                => 'key-for-signature',
                'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
                'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！
                // 'device_info'     => '013467007045764',
                // 'sub_app_id'      => '',
                // 'sub_merchant_id' => '',
                // ...
            ],

            /**
             * Guzzle 全局设置
             *
             * 更多请参考： http://docs.guzzlephp.org/en/latest/request-options.html
             */
            'guzzle' => [
                'timeout' => 3.0, // 超时时间（秒）
                //'verify' => false, // 关掉 SSL 认证（强烈不建议！！！）
          ],
	];
    }
}

