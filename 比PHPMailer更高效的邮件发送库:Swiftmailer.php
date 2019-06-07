Swiftmailer是一个类似PHPMailer邮件发送组件，它也支持HTML格式、附件发送，但它发送效率相当高，成功率也非常高，很多PHP框架都集成了Swiftmailer。

注意:Swiftmailer需要PHP 7.0或更高版本，（proc_*函数可用。）

安装:


composer require "swiftmailer/swiftmailer:^6.0"
基本使用: 只需填写邮箱服务器相关配置，然后填写邮件标题、发送对象和邮件内容，运行即可完成发送邮件任务：

require_once '/path/to/vendor/autoload.php';

$transport = (new Swift_SmtpTransport('smtp.163.com', 25)) // 邮箱服务器
  ->setUsername('your username')  // 邮箱用户名
  ->setPassword('your password')   // 邮箱密码，有的邮件服务器是授权码
;

$mailer = new Swift_Mailer($transport);

$message = (new Swift_Message('Wonderful Subject')) // 邮件标题
  ->setFrom(['john@doe.com' => 'John Doe']) // 发送者
  ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name']) //发送对象，数组形式支持多个
  ->setBody('Here is the message itself') //邮件内容
  ;

$result = $mailer->send($message);
如果发送成功，会返回$result的值为1，即true。
高级操作:

发送邮件时最关键的是创建消息体，在Swift Mailer中创建消息是通过使用库提供的各种MIME实体完成的，因此我们不需要花太多时间去了解如何处理MIME实体，只需拿来使用即可。


setSubject()：邮件主题

setFrom()：发件人地址，数组形式，可以是多个发件人

setTo()：收件人地址，数组形式，可以是多个收件人

setBody()：邮件内容

addPart()：邮件内容指定输出类型，支持html内容输出

attach()：添加附件

setCc()：抄送，支持多个邮箱地址

setBcc()：密送，支持多个邮箱地址

常见错误信息

1. 报错信息：Fatal error: Uncaught Swift_TransportException: Failed to authenticate on SMTP server with username xxx...

很显然是邮件服务的账号密码不正确导致验证不能通过。目前163免费邮和QQ邮箱等提供给第三方客户端使用的SMTP/POP等服务需要设置一个授权码，具体可以到邮箱里设置。然后将正确的邮箱账号和授权码配置到Swift Mailer中即可。

2. 报错信息：PHP Fatal error: Uncaught Swift_TransportException: Connection could not be established with host smtp.163.com

不能连接上邮件服务器。如果出现这个情况，建议尝试改成ssl协议。笔者在本地使用官方的25端口发送邮件一切正常，到放到公网服务器上就提示如上错误信息了，折腾了好久，改下协议和端口，成功了：

$transport = (new Swift_SmtpTransport('ssl://smtp.163.com', 465))
