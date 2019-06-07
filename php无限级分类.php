<?php
// 原文：https://blog.csdn.net/tiansidehao/article/details/79025359
// 原文：https://blog.csdn.net/qishouzhang/article/details/47204359

$array = array(
    array('id' => 2, 'pid' => 0, 'name' => 'b'),
    array('id' => 3, 'pid' => 1, 'name' => 'a-1'),
    array('id' => 1, 'pid' => 0, 'name' => 'a'),
    array('id' => 4, 'pid' => 2, 'name' => 'b-1'),
    array('id' => 5, 'pid' => 2, 'name' => 'b2'),
    array('id' => 6, 'pid' => 5, 'name' => 'b-2-1'),
    array('id' => 7, 'pid' => 5, 'name' => 'b-2-2'),
    array('id' => 8, 'pid' => 3, 'name' => 'a-1-1'),
    array('id' => 9, 'pid' => 1, 'name' => 'a-2'),
);

/**
 * 递归实现无限极分类（二维数组）
 * @param $array 数据
 * @param $pid 父ID
 * @param $level 分类级别
 * @return $list 分好类的数组 直接遍历即可 $level可以用来遍历缩进
 */

function getTree0($array, $pid = 0, $level = 1){

    //声明静态数组,避免递归调用时,多次声明导致数组覆盖
    static $list = [];
    foreach ($array as $key => $value){
        //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
        if ($value['pid'] == $pid){
            //父节点为根节点的节点,级别为1，也就是第一级
            $value['level'] = $level;
            //把数组放到list中
            $list[] = $value;
            //把这个节点从数组中移除,减少后续递归消耗
            unset($array[$key]);
            //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
            getTree($array, $value['id'], $level+1);

        }
    }
    return $list;
}

/**
 * 递归实现无限极分类 （多维数组）
 * @param $array 数据
 * @param $pid 父ID
 * @return $tree
 */

function getTree1($array,$pid = 0){
    // 存放排序数组
    $tree = array();
    foreach($array as $k => $v){
        if($v['pid'] == $pid){
            //递归获取子记录
            $v['child'] = getTree($array,$v['id']);
            if($v['child'] == null){ // 没有子类
                // 删除 空子类
                unset($v['child']);
            }
            // 删除已处理节点，增加性能
            unset($array[$k]);
            $tree[] = $v;
        }
    }
    return $tree;
}

/**
 * 引用实现无限极分类  （多维数组）
 * @param $array 数据
 * @return $tree
 */

function getTree2($array)
{
    // 格式化数组，让数组索引 = 对应值的 ID
    $items  = array();
    foreach ($array as $key => $val) {
        $items [$val['id']] = $val;
    }
    // 接收处理后的数据
    $tree = array();
    foreach ($items as $k => $v) {
        // 当前元素是否有父亲
        if(isset($items [$v['pid']])){
            // 有就把他放到父亲下面  且此元素还能被修改（引用传值）
            // $arr[$item['pid']]['child'][] = &$arr[$item['id']];
            $items[$v['pid']]['child'][] = &$items[$k];
        }else{
            // 没有就放入数组 且还能被修改（引用传值）
            // $tree[] = &$arr[$item['id']];
            $tree[] = &$items[$k];
        }
    }
    return $tree;
}

echo "<pre>";
print_r(getTree2($array));
