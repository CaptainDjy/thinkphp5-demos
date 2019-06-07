第一种直接for循环重复数据

function get_repeat_data_name($array) {
        $len = count ( $array );
        for($i = 0; $i < $len; $i ++) {
            for($j = $i + 1; $j < $len; $j ++) {
                if ($array [$i] == $array [$j]) {
                    $arr [] = $array [$i];
                    break;
                }
            }
        }
        return $arr;
    }
第二种使用数组函数array_unique和array_diff_assoc
function get_repeat_data($array) {
  $len = count ( $array );
  for($i = 0; $i < $len; $i ++) {
      for($j = $i + 1; $j < $len; $j ++) {
          if ($array [$i] == $array [$j]) {
              $repeat_arr [] = $array [$i];
              break;
          }
      }
  }
  return $repeat_arr;
}
