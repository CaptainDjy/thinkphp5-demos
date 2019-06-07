这个的话，网上也有很多，但是为了我的量，我还是来SS吧

现在配置文件价格验证码配置：如下配置很多一下知识示例

  'captcha' => [
      // 验证码字符集合
      'codeSet' => '23456789',
      // 验证码字体大小(px)
      'fontSize' => 25,
      // 是否画混淆曲线
      'useCurve' => false,
      // 验证码位数
      'length'  => 4,
     // 验证成功后是否重置
     'reset'  => true
 ],
前段页面调用方法并点击图片刷新验证码：
 <div>
   <span><strong>请输入验证码</strong></span>
   <input display: inline;" class="form-control" name="code" type="text" required="required">
   <img src="{:captcha_src()}" onclick="this.src='{:captcha_src()}?'+Math.random();"/>
 </div>
后台php的验证方法


 public function check($code='')
 {
   if (!captcha_check($code)) {
     $this->error('验证码错误');
   } else {
     return true;
   }
 }
