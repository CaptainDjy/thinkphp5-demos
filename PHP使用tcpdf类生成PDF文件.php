使用pctdf生成的pdf文件 

可以插入图片、HTML、链接、表格、柱状图折线图等PHP动态生成PDF的功能。

<?php


require_once './tcpdf/tcpdf.php';


//实例化

$content = '我是一个pdf';  //你要生成的PDF内容

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);


// 设置文档信息


$pdf->SetCreator('Lane');


$pdf->SetAuthor('Lane');


$pdf->SetTitle('PHP生成PDF');


$pdf->SetSubject('PHP动态生成PDF文件');


$pdf->SetKeywords('PHP PDF TCPDF');




//设置页眉信息 参数分别是LOGO地址，LOGO大小，两行标题，标题颜色，分割线颜色。。颜色是RGB


$pdf->SetHeaderData('/var/www/tcpdf/examples/images/tcpdf_logo.jpg', 30, 'PHP生成PDF', 'PHP如何生成PDF文件', array(0,0,0), array(0,0,0));


//设置页脚信息


$pdf->setFooterData(array(0,0,0), array(0,0,0));


// 设置页眉和页脚字体


$pdf->setHeaderFont(Array('stsongstdlight', '', '12'));


$pdf->setFooterFont(Array('helvetica', '', '8'));


//设置默认等宽字体


$pdf->SetDefaultMonospacedFont('courier');


//设置间距


$pdf->SetMargins(15, 27, 15);


$pdf->SetHeaderMargin(5);


$pdf->SetFooterMargin(10);


//设置分页


$pdf->SetAutoPageBreak(TRUE, 15);


//设置图片比例


$pdf->setImageScale(1.25);


//将页眉页脚的信息输出出来。


$pdf->AddPage();




//设置字体 - 正文标题的哦。B是加粗，15是大小


$pdf->SetFont('stsongstdlight', 'B', 15);


$pdf->Write(20, 'PHP生成动态pdf', '', 0, 'C', true, 0, false, false, 0);




//设置字体 - 正文内容的哦。B是加粗，15是大小


$pdf->SetFont('stsongstdlight', '', 10);


//L是左对齐，R是右对齐，C是居中对齐。


$pdf->Write(0, $content,'', 0, 'L', true, 0, false, false, 0);




//输出PDF。第二个参数默认是I，是浏览器预览。D是下载


$pdf->Output('PHP_TO_PDF.pdf', 'I');


pcpdf类下载地址 ： https://github.com/tecnickcom/tcpdf
