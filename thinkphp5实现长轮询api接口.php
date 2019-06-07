项目需求,后端需要用到长轮询,设计思路就是默认请求数据,当有新的请求就执行新的数据,比如信息播报,

/**
     *  轮询获取数据
     * @param int $id
     * @return \think\response\Json
     */
    public function lucky_news($id = 0)
    {
        if ($id == 0) {
            $res = db('lottery_log')->field('id,type,user_id,num,prize_name')->order('id desc')->limit(20)->select();
            foreach ($res as &$v) {
                $v['nickname'] = db('user')->where('id', $v['user_id'])->value('nickname');
                unset($v['user_id']);
            }
            return json(['code' => 1, 'data' => $res]);
        }

        return  longPolling(function () use ($id) {   
            $data = db('lottery_log')->field('id,num,prize_name,user_id,type')->where('id', '>', $id)->select();
            foreach ($data as &$v) {
                $v['nickname'] = db('user')->where('id', $v['user_id'])->value('nickname');
                unset($v['user_id']);
            }
            return json(['code' => 1, 'data' => $data ?? (object)[]]);
        });
    }
回调方法核心
function longPolling($callback)
{
    session_write_close();  //前面的session数据存入或读取，然后关闭session.  防止session阻塞
    ignore_user_abort(false);  //停止脚本运行
    set_time_limit(30);   //设置脚本允许的时间 0:没有时间限制

    for ($i = 0; $i < 25; $i++) {
        // echo str_repeat(" ", 4000);   //把''重复4000次
        $return_data = $callback();
        if ($return_data) {
            return $return_data;
        }
        sleep(1);
        ob_flush();   //输出缓冲区的内容   //    必须和下面同时使用 flush()
        flush();  //刷新缓冲区的内容  该函数将当前为止程序的所有输出发送到用户的浏览器,必须同时使用 ob_flush() 和flush() 函数来刷新输出缓冲
    }
    ob_end_flush();  //输出缓冲区内容并关闭缓冲
    return json(['code' => 1, 'data' => (object)array()]);
}
