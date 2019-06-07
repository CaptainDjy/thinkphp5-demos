一、PHP实现打包zip并下载功能


$file_template = FCPATH.'canddata/cand_picture.zip';//在此之前你的项目目录中必须新建一个空的zip包（必须存在）
$downname = $card.'.zip';//你即将打包的zip文件名称
$file_name = FCPATH.'canddata/'.$card.'.zip';//把你打包后zip所存放的目录
$result = copy( $file_template, $file_name );//把原来项目目录存在的zip复制一份新的到另外一个目录并重命名（可以在原来的目录）
$zip = new ZipArchive();//新建一个对象
if ($zip->open($file_name, ZipArchive::CREATE) === TRUE) { //打开你复制过后空的zip包
　　$zip->addEmptyDir($card);//在zip压缩包中建一个空文件夹，成功时返回 TRUE， 或者在失败时返回 FALSE
　　//下面是我的场景业务处理，可根据自己的场景需要去处理（我的是将所有的图片打包）
　　$i = 1;
　　foreach ($cand_photo as $key3 => $value3) {
　　　　$file_ext = explode('.',$value3['cand_face']);//获取到图片的后缀名
　　　　$zip->addFromString($card.'/'.$card.'_'.$i.'.'.$file_ext[3] , file_get_contents($value3['cand_face']));//（图片的重命名，获取到图片的二进制流）
　　　　$i++;
　　}
　　$zip->close();
　　$fp=fopen($file_name,"r"); 
　　$file_size=filesize($file_name);//获取文件的字节
　　//下载文件需要用到的头 
　　Header("Content-type: application/octet-stream"); 
　　Header("Accept-Ranges: bytes"); 
　　Header("Accept-Length:".$file_size);
　　Header("Content-Disposition: attachment; filename=$downname"); 
　　$buffer=1024; //设置一次读取的字节数，每读取一次，就输出数据（即返回给浏览器） 
　　$file_count=0; //读取的总字节数 
　　//向浏览器返回数据 如果下载完成就停止输出，如果未下载完成就一直在输出。根据文件的字节大小判断是否下载完成
　　while(!feof($fp) && $file_count<$file_size){  
        $file_con=fread($fp,$buffer);  
        $file_count+=$buffer;  
        echo $file_con;  
    } 
　　fclose($fp); 
　　//下载完成后删除压缩包，临时文件夹 
　　if($file_count >= $file_size) { 
　　　　unlink($file_name); 
　　}
}

二、PHP实现大文件下载

此方法为大文件下载的实现，即使几个G也可实现本地的下载。可以测试一下，或者等你从中改进...

function downloadFile($filename){
   //获取文件的扩展名
   $allowDownExt=array ( 'rar','zip','png','txt','mp4','html');
   //获取文件信息
   $fileext=pathinfo($filename);
   //检测文件类型是否允许下载
   if(!in_array($fileext['extension'],$allowDownExt)) {
      return false;
   }
   //设置脚本的最大执行时间，设置为0则无时间限制
   set_time_limit(0);
   ini_set('max_execution_time', '0');
   //通过header()发送头信息
   //因为不知道文件是什么类型的，告诉浏览器输出的是字节流
   header('content-type:application/octet-stream');
   //告诉浏览器返回的文件大小类型是字节
   header('Accept-Ranges:bytes');
   //获得文件大小
   //$filesize=filesize($filename);//(此方法无法获取到远程文件大小)
   $header_array = get_headers($filename, true);
   $filesize = $header_array['Content-Length'];
   //告诉浏览器返回的文件大小
   header('Accept-Length:'.$filesize);
   //告诉浏览器文件作为附件处理并且设定最终下载完成的文件名称
   header('content-disposition:attachment;filename='.basename($filename));
   //针对大文件，规定每次读取文件的字节数为4096字节，直接输出数据
   $read_buffer=4096;
   $handle=fopen($filename, 'rb');
   //总的缓冲的字节数
   $sum_buffer=0;
   //只要没到文件尾，就一直读取
   while(!feof($handle) && $sum_buffer<$filesize) {
      echo fread($handle,$read_buffer);
      $sum_buffer+=$read_buffer;
   }
   //关闭句柄
   fclose($handle);
   exit;

}
三，PHP扩展类ZipArchive实现压缩Zip文件和文件打包下载

这是一个类，封装了方法，也可以尝试一下！

<?php
/**
 * 关于文件压缩和下载的类
 * @author  tycell
 * @version 1.0
 */
class zip_down{

    protected $file_path;
    /**
     * 构造函数
     * @param [string] $path [传入文件目录]  
     */
    public function __construct($path){
        $this->file_path=$path; //要打包的根目录
    }
    /**
     * 入口调用函数
     * @return [type] [以二进制流的形式返回给浏览器下载到本地]
     */
    public function index(){
        $zip=new ZipArchive();
        $end_dir=$this->file_path.date('Ymd',time()).'.zip';//定义打包后的包名
        $dir=$this->file_path;
        if(!is_dir($dir)){
            mkdir($dir);
        }
        if($zip->open($end_dir, ZipArchive::OVERWRITE) === TRUE){ ///ZipArchive::OVERWRITE 如果文件存在则覆盖
            $this->addFileToZip($dir, $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
            $zip->close(); 
        }
        if(!file_exists($end_dir)){   
            exit("无法找到文件"); 
        }
        header("Cache-Control: public"); 
        header("Content-Description: File Transfer"); 
        header("Content-Type: application/zip"); //zip格式的   
        header('Content-disposition: attachment; filename='.basename($end_dir)); //文件名   
        header("Content-Transfer-Encoding: binary"); //告诉浏览器，这是二进制文件    
        header('Content-Length:'.filesize($end_dir)); //告诉浏览器，文件大小   
        @readfile($end_dir);
        $this->delDirAndFile($dir,true);//删除目录和文件
        unlink($end_dir);////删除压缩包
    }
    /**
     * 文件压缩函数 需要开启php zip扩展
     * @param [string] $path [路径]
     * @param [object] $zip  [扩展ZipArchive类对象]
     */
    protected function addFileToZip($path, $zip){
        $handler = opendir($path);
        while (($filename=readdir($handler)) !== false) {
            if ($filename!= "." && $filename!=".."){
               if(!is_dir($filename)){ 
                     $zip->addFile($path."/".$filename,$filename); //第二个参数避免将目录打包，可以不加
                }
            }
        }
        @closedir($path);
    }
    /**
     * 删除文件函数 
     * @param  [string]  $dir    [文件目录]
     * @param  boolean $delDir [是否删除目录]
     * @return [type]          [description]
     */
    protected function delDirAndFile($path,$delDir=true){
        $handle=opendir($path);
        if($handle){
            while(false!==($item = readdir($handle))){
                if($item!="."&&$item!=".."){
                    if(is_dir($path.'/'.$item)){
                        $this->delDirAndFile($path.'/'.$item, $delDir);
                    }else{
                        unlink($path.'/'.$item);
                    }
                }
            }
            @closedir($handle);
            if($delDir){return rmdir($path);}
        }else{
            if(file_exists($path)){
                return unlink($path);
            }else{
                return FALSE;
            }
        }
    }

}
