<?php
/**
 * User: Xunm
 * Date: 2018/10/5
 * Time: 17:40
 */
namespace sms;

use sms\lib\Ucpaas;

class Send
{
    public static function SendSms($pin, $phone)
    {
        //填写在开发者控制台首页上的Account Sid
        $options['accountsid'] =  config('sms.sms_accountsid');

        //填写在开发者控制台首页上的Auth Token
        $options['token'] = config('sms.sms_authtoken');

        //初始化 $options必填
        $appid = config('sms.sms_appid');    //应用的ID，可在开发者控制台内的短信产品下查看
        $templateid = config('sms.sms_templateid');    //可在后台短信产品→选择接入的应用→短信模板-模板ID，查看该模板ID

        //以下是发送验证码的信息
        $param = $pin; //验证码 多个参数使用英文逗号隔开（如：param=“a,b,c”），如为参数则留空
        $mobile = $phone; // 手机号
        $uid =  config('sms.sms_uid');
        $ucpass = new Ucpaas($options);
        $result = $ucpass->SendSms($appid, $templateid, $param, $mobile, $uid);

        return $result;

    }
}