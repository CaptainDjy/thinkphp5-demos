$dtime = $start_time - time();
            $timedata = '';
            $d = floor($dtime / (3600 * 24));//0.6  0    1.2  1  floor为tp5的一个向下取整函数
            if ($d) {
                $timedata .= $d . "天";
            }
            $h = floor($dtime % (3600 * 24) / 3600);
            if ($h) {
                $timedata .= $h . "小时";
            }
            $m = floor($dtime % (3600 * 24) % 3600 / 60);
            if ($m) {
                $timedata .= $m . "分";
            }
