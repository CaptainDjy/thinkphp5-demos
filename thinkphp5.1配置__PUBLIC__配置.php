每次都要忘,还是记下来把

config/templete.php 增加下面这句

'tpl_replace_string' => [
        '__STATIC__' =>    '/static'
    ]
然后清除缓存runtime就行了
