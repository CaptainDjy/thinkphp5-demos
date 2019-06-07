今天来说一说阿里云的邮件推送功能,效果不错,弄下来做个记录

先下载阿里云的邮件推送SDK:

下载地址: http://aliyundm.oss-cn-hangzhou.aliyuncs.com/example/aliyun-php-sdk-dmV3.1.1.zip?spm=a2c4g.11186623.2.15.554556b7THN8QB&file=aliyun-php-sdk-dmV3.1.1.zip

在下载的压缩包解压文件夹中找到 aliyun-php-sdk-core 和 aliyun-php-sdk-dm 文件夹，将其放置在您的网站的本地目录下，您就可以使用阿里云邮件推送的 PHP SDK 了

<?php    
    include_once 'aliyun-php-sdk-core/Config.php';
    use Dm\Request\V20151123 as Dm;            
    //需要设置对应的region名称，如华东1（杭州）设为cn-hangzhou，新加坡Region设为ap-southeast-1，澳洲Region设为ap-southeast-2。
    $iClientProfile = DefaultProfile::getProfile("cn-hangzhou", "<your accessKey>", "<your accessSecret>");        
    //新加坡或澳洲region需要设置服务器地址，华东1（杭州）不需要设置。
    //$iClientProfile::addEndpoint("ap-southeast-1","ap-southeast-1","Dm","dm.ap-southeast-1.aliyuncs.com");
    //$iClientProfile::addEndpoint("ap-southeast-2","ap-southeast-2","Dm","dm.ap-southeast-2.aliyuncs.com");
    $client = new DefaultAcsClient($iClientProfile);    
    $request = new Dm\SingleSendMailRequest();     
    //新加坡或澳洲region需要设置SDK的版本，华东1（杭州）不需要设置。
    //$request->setVersion("2017-06-22");
    $request->setAccountName("控制台创建的发信地址");
    $request->setFromAlias("发信人昵称");
    $request->setAddressType(1);
    $request->setTagName("控制台创建的标签");
    $request->setReplyToAddress("true");
    $request->setToAddress("目标地址");
    //可以给多个收件人发送邮件，收件人之间用逗号分开,若调用模板批量发信建议使用BatchSendMailRequest方式
    //$request->setToAddress("邮箱1,邮箱2");
    $request->setSubject("邮件主题");
    $request->setHtmlBody("邮件正文");        
    try {
        $response = $client->getAcsResponse($request);
        print_r($response);
    }
    catch (ClientException  $e) {
        print_r($e->getErrorCode());   
        print_r($e->getErrorMessage());   
    }
    catch (ServerException  $e) {        
        print_r($e->getErrorCode());   
        print_r($e->getErrorMessage());
    }
?>
