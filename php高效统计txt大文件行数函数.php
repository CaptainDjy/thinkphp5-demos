php中提供了专门的file函数来读取文件，通过file函数可以一次性获取一个txt文件的行数：

<?php
$line = count(file($filepath));
echo $line;
?>
但是file函数不适用于大文件，执行缓慢并且会造成严重的内存问题。


网上还有一种通过fopen函数以及while逐行统计的代码，如下：

<?php
line = 0 ;
$fp = fopen($filepath , 'r') or die("open file failure!");
if($fp){
while(stream_get_line($fp,8192,"\n")){
   $line++;
}
fclose($fp);
}
echo $line;
?>
这种方法在读取大文件行数时，同样面临着效率太慢的问题。


经过实践，我们采用以下方法可以超高效率的读取txt大文件行数，并且内存占用也很低

<?php
function count_line($file){
  $fp=fopen($file, "r");
  $i=0;
  while(!feof($fp)) {
   //每次读取2M
   if($data=fread($fp,1024*1024*2)){
   //计算读取到的行数
   $num=substr_count($data,"\n");
   $i+=$num;
  }
}
fclose($fp);
return $i;
}
?>
通过多行统计，每次读取N个字节，然后再统计读取的行数累加。


测试情况,文件大小 3.14 GB


第1次:line: 13214810 , time:56.2779 s;


第2次:line: 13214810 , time:49.6678 s;
