$currentTime=time();//当前时间
$cnt=$currentTime-strtotime("2014-01-01");//与已知时间的差值
$days = floor($cnt/(3600*24));//算出天数
