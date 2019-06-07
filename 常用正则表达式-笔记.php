一:匹配整数小数:

    1.除了个位，十位以上不能以0开头

    2.小数部分可有可元  3.小数点后可以一位或者二位

      /^([1-9]\d*|0)(\.\d{1,2})?$/     




/**

* 车牌号验证

*

* @access  public

* @return  boolean

*/

function check_carnumber($carnumber){

return preg_match('/^[\u4e00-\u9fa5][A-Za-z0-9]{6}$/u',$carnumber);

}


/**

* VIN号验证

*

* @access  public

* @return  boolean

*/

function check_carvin($vinnumber){

return preg_match('/^[A-Za-z0-9]{17}$/u',$vinnumber);

}

/**

分转时

*/

function secondsToHour($seconds){

   if ($seconds >3600){

       $hours =intval($seconds/3600);

       $minutes = $seconds % 3600;

       $time = $hours.":".gmstrftime('%M:%S',$minutes);

   }else{

       $time = gmstrftime('%H:%M:%S',$seconds);

   }

   $data = explode(':',$time);

   return['h'=>$data[0],'m'=>$data[1],'s'=>$data[2]];

}
