<?php

namespace app\api\controller;
use app\common\controller\Api;
use app\common\model\Area;
use app\common\model\Version;
use fast\Random;
use think\Config;
use addons\huyisms\library\Huyisms;

/**
 * 公共接口
 */
class Common extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = '*';

    public function message(){
        $data=$this->request->param();
        $content=$data['content'];
        $phone=$data['phone'];
        $huyi = new Huyisms();
        $res = $huyi->content($content)->mobile($phone)->send();
        if ($res){
            $this->success('发送短信成功!');
        }else{
            $this->error($huyi->getError());
        }
    }
}
