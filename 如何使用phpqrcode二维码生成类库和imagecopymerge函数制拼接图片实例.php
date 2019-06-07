第一步： 
引入phpqrcode类库（下载地址：https://sourceforge.net/projects/phpqrcode/）

第二部：


PHP开启GD扩展库支持


1、使用phpqrcode生成二维码：


原理分析：


下载好的是一个压缩包文件我们只需要解压，只需要里面的phpqrcode.php这个文件里面的QRcode类的png方法就可以了，其他的演示什么的一概不管

使用案例


require_once("./phpqrcode.php");  //引入文件
function creatQrcode(){
    //设置二维码的链接地址
    $url = "http://www.baidu.com";
    //设置二维码的容错级别
    /*
     * 容错级别：容错级别百分比越高，就越容易识别，容错级别：
     * 按照效果排序依次是  H -> Q -> M -> L
     */
    $errorCorrectionLevel = 'H';    
    //设置生成二维码图片的大小
    $matrixPointSize = 7;
    //设置生成二维码的图片名称（路径名称根据项目需求而定）
    $filename = "test.png";
    QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 1);
    //以上已经生成了二维码了（同级目录下的test.png）
}
creatQrcode();
想要使用logo的话这么做


<?php
//引入phpqrcode类库
require_once("./phpqrcode.php");
function creatQrcode(){
    //设置二维码的链接地址
    $url = "http://www.liaotaoo.cn";
    //设置二维码的容错级别
    /*
     * 容错级别：容错级别百分比越高，就越容易识别，容错级别：
     * 按照效果排序依次是  H -> Q -> M -> L
     */
    $errorCorrectionLevel = 'H';    
    //设置生成二维码图片的大小
    $matrixPointSize = 7;
    //设置生成二维码的图片名称
    $filename = "test.png";
    QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 1);
    //以上已经生成了二维码了（同级目录下的test.png）
    $logo = './img/logo.png';
    $QR = $filename;
    $QRlogo = './img/qrlogo.png';
    if(file_exists($logo)){
        // 函数：imagecreatefromstring()：创建一块画布，并从字符串中的图像流新建一副图像
        $QR = imagecreatefromstring(file_get_contents($QR));        //目标图象连接资源。
        $logo = imagecreatefromstring(file_get_contents($logo));     //源图象连接资源。
        // php函数：imagesx(resource image):获取图像宽度
        // PHP函数：imagesy(resource image):获取图像高度
        $QR_width = imagesx($QR);
        $QR_height = imagesy($QR);
        $logo_width = imagesx($logo);//logo图片宽度 
        $logo_height = imagesy($logo);//logo图片高度 

        $logo_qr_width = $QR_width / 5;   //组合之后logo的宽度(占二维码的1/5)
        $scale = $logo_width/$logo_qr_width;  //logo的宽度缩放比(本身宽度/组合后的宽度)
        $logo_qr_height = $logo_height/$scale; //组合之后logo的高度
        $from_width = ($QR_width - $logo_qr_width) / 2;  //组合之后logo左上角所在坐标点

        //重新组合图片，并调整大小
        /**
         * 函数 imagecopyresampled():将一幅图像中的一块正方形区域拷贝到另一个图像中，平滑地插入像素值，因此，尤其是，减小了图像的大小而仍然保持了极大的清晰度。参数详解
         *
         * bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
         *
         * dst_image 目标图象连接资源。
         * src_image 源图象连接资源。
         * dst_x 目标 X 坐标点。
         * dst_y 目标 Y 坐标点。
         * src_x 源的 X 坐标点。
         * src_y 源的 Y 坐标点。
         * dst_w 目标宽度。
         * dst_h 目标高度。
         * src_w 源图象的宽度。
         * src_h 源图象的高度。
         */
        imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,$logo_qr_height, $logo_width, $logo_height);
        // PHP函数:imagepng ( resource image [, string filename] ):以 PNG 格式将图像输出到浏览器或文件
        imagepng($QR,$QRlogo);
        echo '<image src="'.$QRlogo.'"/>';
    }
}
creatQrcode();
3、如果加了logo的二维码要放在一张海报上的话：

imagecopymerge函数制拼合（镶嵌，合并）图片 同样适合水印

<?php
//引入phpqrcode类库
require_once("./phpqrcode.php");
function creatQrcode(){
    //设置二维码的链接地址
    $url = "https://www.liaotaoo.cn";
    //设置二维码的容错级别
    /*
     * 容错级别：容错级别百分比越高，就越容易识别，容错级别：
     * 按照效果排序依次是  H -> Q -> M -> L
     */
    $errorCorrectionLevel = 'H';    
    //设置生成二维码图片的大小
    $matrixPointSize = 7;
    //设置生成二维码的图片名称
    $filename = "test.png";
    QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 1);
    //以上已经生成了二维码了（同级目录下的test.png）

    //————————————————————————————————————————————————————————————————
    //二维码上添加logo
    $logo = './img/logo.png';
    $QR = $filename;
    $QRlogo = './img/qrlogo.png';
    if(file_exists($logo)){
        // 函数：imagecreatefromstring()：创建一块画布，并从字符串中的图像流新建一副图像
        $QR = imagecreatefromstring(file_get_contents($QR));        //目标图象连接资源。
        $logo = imagecreatefromstring(file_get_contents($logo));     //源图象连接资源。
        // php函数：imagesx(resource image):获取图像宽度
        // PHP函数：imagesy(resource image):获取图像高度
        $QR_width = imagesx($QR);
        $QR_height = imagesy($QR);
        $logo_width = imagesx($logo);//logo图片宽度 
        $logo_height = imagesy($logo);//logo图片高度 

        $logo_qr_width = $QR_width / 5;   //组合之后logo的宽度(占二维码的1/5)
        $scale = $logo_width/$logo_qr_width;  //logo的宽度缩放比(本身宽度/组合后的宽度)
        $logo_qr_height = $logo_height/$scale; //组合之后logo的高度
        $from_width = ($QR_width - $logo_qr_width) / 2;  //组合之后logo左上角所在坐标点

        //重新组合图片，并调整大小
        imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,$logo_qr_height, $logo_width, $logo_height);
        // PHP函数:imagepng ( resource image [, string filename] ):以 PNG 格式将图像输出到浏览器或文件
        imagepng($QR,$QRlogo);



        //——————————————————————————————————————————
        // 加了logo的二维码放在海报之类的背景图上面

        $dst_path = './img/xuexi.jpg';     //底图
        $src_path = $QRlogo;     //覆盖图，我们就继续用上面的那张图QRlogo

        //创建图片实例
        $dst = imagecreatefromstring(file_get_contents($dst_path));
        $src = imagecreatefromstring(file_get_contents($src_path));
        //获取覆盖图的宽高
        list($src_w, $src_h) = getimagesize($src_path);
        /**
         *
         * PHP函数：imagecopymerge()/imagecopy()
         *
         * bool imagecopymerge ( resource $dst_im , resource $src_im , int $dst_x , int $dst_y , int $src_x , int $src_y , int $src_w , int $src_h , int $pct )
         * 将 src_im 图像中坐标从 src_x，src_y 开始，宽度为 src_w，高度为 src_h 的一部分拷贝到 dst_im 图像中坐标为 dst_x 和 dst_y 的位置上。两图像将根据 pct 来决定合并程度，其值范围从 0 到 100。当 pct = 0 时，实际上什么也没做，当为 100 时对于调色板图像本函数和 imagecopy() 完全一样，它对真彩色图像实现了 alpha 透明。
         *
         */
        imagecopymerge($dst, $src, 20, 120, 0, 0, $src_w, $src_h, 100);
        list($dst_w, $dst_h) = getimagesize($dst_path);
        imagepng($dst,'./img/aaa.png');
        imagedestroy($dst);
        imagedestroy($src);
    }
}
creatQrcode();
度娘很多县城的资料，拿来即用
