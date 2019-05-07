$currentTime=time();//当前时间
$cnt=$currentTime-strtotime("2014-01-01");//与已知时间的差值
$days = floor($cnt/(3600*24));//算出天数



public function count_down(){     //已收货后48天可进行结算申请
        $data=$this->request->param();
        $time=$data['time'];
       //halt(strtotime($time));
        $currentTime=time();
        $cnt=$currentTime-strtotime($time);
        $days=floor($cnt/(3600*24));
        if($days>48){
            $this->success('距收货日期超过48天可进行结算申请!');
        }else{
            $this->error('距收货日期还不到48天不得进行结算申请!');
        }

    }
