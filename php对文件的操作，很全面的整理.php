	我喜欢整理有代表性的文章，也喜欢分享，虽然需要时间，但个人觉得有努力的付出，才有相应的回报，希望大家能喜欢吧！以下整理了PHP对文件的操作的方法详解。打包成一个PHP文件，直接引用就可以了。

<?php
$file = "./test/f1.php";
//===============判断文件能不能读取(权限问题),或者存不存在
if (is_readable($file) == false) {
echo "<br/>";
die('no');
}
//===============判断文件存不存在
if (file_exists($file) == false) {
echo "<br/>";
die('no file');
}
//======================================================读取文件内容

//方法一
$dataString = file_get_contents($file);
echo "<br/>1";
var_dump($dataString);
echo htmlentities($dataString);
//方法二，该方法如果文件内容空会报错
$fJuBing = fopen($file, 'r'); //创建指定文件读操作的句柄
$dataString = fread($fJuBing, filesize($file));
fclose($fJuBing);
echo "<br/>2";
var_dump($dataString);
echo htmlentities($dataString);
/*
fopen()方法的第二个参数可以选择以下值
"r" 只读方式打开，将文件指针指向文件头。
"r+" 读写方式打开，将文件指针指向文件头。
"w" 写入方式打开，将文件指针指向文件头并将文件大小截为零。如果文件不存在则尝试创建之。
"w+" 读写方式打开，将文件指针指向文件头并将文件大小截为零。如果文件不存在则尝试创建之。
"a" 写入方式打开，将文件指针指向文件末尾。如果文件不存在则尝试创建之。
"a+" 读写方式打开，将文件指针指向文件末尾。如果文件不存在则尝试创建之。
"x" 创建并以写入方式打开，将文件指针指向文件头。如果文件已存在，则 fopen() 调用失败并返回 FALSE，并生成一条 E_WARNING 级别的错误信息。如果文件不存在则尝试创建之。
这和给底层的 open(2) 系统调用指定 O_EXCL|O_CREAT 标记是等价的。
此选项被 PHP 4.3.2 以及以后的版本所支持，仅能用于本地文件。
"x+" 创建并以读写方式打开，将文件指针指向文件头。如果文件已存在，则 fopen() 调用失败并返回 FALSE，并生成一条 E_WARNING 级别的错误信息。如果文件不存在则尝试创建之。
这和给底层的 open(2) 系统调用指定 O_EXCL|O_CREAT 标记是等价的。
此选项被 PHP 4.3.2 以及以后的版本所支持，仅能用于本地文件。
*/

//方法三
$dataString = implode('', file($file));
echo "<br/>3";
var_dump($dataString);
echo htmlentities($dataString);
//===============判断文件能不能写入

if (is_writable($file) == false) {
echo "<br/>";
die("can`t write");
}

//======================================================往文件中写入内容，原内容将被覆盖，并且如果文件不存在将尝试创建
$writeData = 'i want';
//方法一
file_put_contents($file, $writeData);
//或者加参数，无该文件创建，有则在文件内容结尾处写入新内容
//file_put_contents($file, $writeData, FILE_APPEND);

//方法二
$fJuBing = fopen($file, 'w'); //创建指定文件写操作的句柄
fwrite($fJuBing, $writeData);
fclose($fJuBing);

//===============有时执行写入操作时需要锁定文件
$fJuBing = fopen($file, 'w');
if (flock($fJuBing, LOCK_EX)) {
fwrite($fJuBing, $writeData);
//释放锁定
flock($fJuBing, LOCK_UN);
} else {
echo "<br/>";
echo "can`t locking file!";
}
fclose($fJuBing);
/*
flock()方法的第二个参数可以选择以下值
LOCK_SH,（PHP 4.0.1 以前的版本中设置为 1）共享锁定（读取的程序）。
LOCK_EX,（PHP 4.0.1 以前的版本中设置为 2）独占锁定（写入的程序）。
LOCK_UN,（PHP 4.0.1 以前的版本中设置为 3）释放锁定（无论共享或独占）。
LOCK_NB,（PHP 4.0.1 以前的版本中设置为 4）使flock()在锁定时不堵塞。
*/

//======================================================删除文件
/*

$file2 = "./test/f2.php";
if (unlink($file2)) {//有时文件不存在删除时会报错，最好加上@变成@unlink($file2)
echo "<br/>";
echo "file has been delect";
} else {
echo "<br/>";
echo "file can`t been delect";
}
*/

//======================================================复制文件
$file = "./test/f1.php";
$newfile = "./test/f2.php"; #这个文件父文件夹必须能写，如果该文件不存在则尝试创建再复制
if (copy($file, $newfile)) {
echo "<br/>";
echo 'ok';
}

//===============获取文件最近修改时间，返回的是unix的时间戳,这在缓存技术常用
echo "<br/>";
//echo date('r', filemtime($file));

//===============(非windows系统),fileperms()获取文件的权限
echo "<br/>";
echo substr(sprintf('%o', fileperms($file)), -4);

//===============filesize()返回文件大小的字节数:
echo "<br/>";
echo filesize($file);

//===============以数组形式返回文件的全部信息stat()函数:
echo "<br/>";
var_dump(stat($file));

$dir = "./test/t1";

//===============判断该路径文件夹是否存在
if (is_dir($dir) == false) {
echo "<br>";
echo "no";
}

//======================================================创建文件夹
//第二个参数可以不写，默认是最大权限 0777
//不能一次连续创建，即要创建的路径文件夹的上级文件夹必须存在
mkdir($dir, 0777);
//或者创建完再赋权限，有时Linux需要如此创建文件夹
//mkdir($dir);
//chmod($dir, 0777);

//===============返回当前路径文件夹的文件夹名
echo "<br>";
echo basename($dir);

//===============返回去掉路径中最后一个文件夹名或文件名后的路径，成功返回字符串，否则返回 FALSE 。
echo "<br>";
echo dirname($dir);

//===============返回给定目录文件夹或文件的绝对路径
echo "<br>";
echo realpath($dir);

//===============获取指定路径文件夹下所有文件夹名，文件名
$dirJuBing = opendir($dir); //创建一个打开指定文件夹的句柄
while ($fileName = readdir($dirJuBing)) { //循环沥遍返回该目录文件夹下每一个文件名文件夹名的字符串，前两个返回的字符串是"."和".." ，就算是空文件夹也返回这两个字符串
echo "<br/>";
echo $fileName;
}
closedir($dirJuBing);

//======================================================重命名文件夹(或文件)
//或者将指定路径文件夹(或文件)移动并重命名成另一路径文件夹(或文件)，只能文件夹对文件夹，文件对文件
$dir = "./test/t1";
$newDirName = "./test/t2/t3";
rename($dir, $newDirName); //原"./test"下的t1文件夹移动并重命名成"./test/t2"下的t3文件夹

//======================================================删除指定路径文件夹
//rmdir($dir);
?>
