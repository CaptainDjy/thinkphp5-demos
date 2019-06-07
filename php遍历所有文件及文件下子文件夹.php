<?php
function open_dir($dir)
{
     $files = [];
     if(@han = opendir($dir) !==false){ //注意这里要加一个@，不然会有warning错误提示：）
         while($file != ".." && $file !=="."){  //排除根目录
               if(is_dir($dir."/".$file)){ //如果是子文件夹就进行递归
                    $files[$file] = open_dir($dir."/".$file) 
               }else{   //不然就存进数组
                    $files[] = $file;
               }
          }
     }
      closedir($han);
     return $files;
}
