QueryList是基于phpQuery的
1.下载`QueryList.php`和`phpQuery.php`这两个文件。
2.在`extend`下新建`QL`目录。
3.将下载好的`QueryList.php`和`phpQuery.php`这两个文件复制到`extend/QL`目录。
4.在QueryList.php头部加入
在需要使用的控制器中加入

use QL\QueryList;
下面给出一个示例代码：

//需要采集的目标页面
       $page = 'http://cms.querylist.cc/news/566.html';
//采集规则
       $reg = array(
           //采集文章标题
           'title' => array('h1','text'),
           //采集文章发布日期,这里用到了QueryList的过滤功能，过滤掉span标签和a标签
           'date' => array('.pt_info','text','-span -a',function($content){
               //用回调函数进一步过滤出日期
               $arr = explode(' ',$content);
               return $arr[0];
           }),
           //采集文章正文内容,利用过滤功能去掉文章中的超链接，但保留超链接的文字，并去掉版权、JS代码等无用信息
           'content' => array('.post_content','html','a -.content_copyright -script',function($content){
               //利用回调函数下载文章中的图片并替换图片路径为本地路径
               //使用本例请确保当前目录下有image文件夹，并有写入权限
               //由于QueryList是基于phpQuery的，所以可以随时随地使用phpQuery，当然在这里也可以使用正则或者其它方式达到同样的目的

               $doc=\phpQuery::newDocumentHTML($content);
               $imgs = pq($doc)->find('img');
               foreach ($imgs as $img) {
                   $src = 'http://cms.querylist.cc'.pq($img)->attr('src');
                   $localSrc = md5($src).'.jpg';
                   $stream = file_get_contents($src);
                   file_put_contents($localSrc,$stream);
                   pq($img)->attr('src',$localSrc);
               }
               return $doc->htmlOuter();
           })
       );
       $rang = '.content';
       $ql = QueryList::Query($page,$reg,$rang);
       $data = $ql->getData();
//打印结果
       print_r($data);
