闲来无事,研究哈冒泡 以下是代码

public function index()
    {
        $array = [25, 68, 78, 59, 60, 100, 5000, 601, 333];
        $result = $this->sortMaoPao($array);
        halt($result);
    }

    /**
     *  冒泡排序,从小到大排列
     * @param array $array
     * @return array
     */
    function sortMaoPao($array = [])
    {

        for ($j = 0; $j < count($array) - 1; $j++) {
            for ($i = 0; $i < count($array) - 1 - $j; $i++) {
                if ($array[$i] > $array[$i + 1]) {
                    //交换位置
                    $temp = $array[$i];
                    $array[$i] = $array[$i + 1];
                    $array[$i + 1] = $temp;
                }
            }
        }

        //从大到小排列
        return array_reverse($array);
    }
