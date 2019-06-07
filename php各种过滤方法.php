    /**
     * 防sql注入字符串转义
     * @param $content 要转义内容
     * @return array|string
     */
    public static function escapeString($content) {
        $pattern = "/(select[\s])|(insert[\s])|(update[\s])|(delete[\s])|(from[\s])|(where[\s])|(drop[\s])/i";
        if (is_array($content)) {
            foreach ($content as $key=>$value) {
                $content[$key] = addslashes(trim($value));
                if(preg_match($pattern,$content[$key])) {
                    $content[$key] = '';
                }
            }
        } else {
            $content=addslashes(trim($content));
            if(preg_match($pattern,$content)) {
                $content = '';
            }
        }
        return $content;
    }
 
防XSS攻击代码：
 
/**
 * 安全过滤函数
 *
 * @param $string
 * @return string
 */
function safe_replace($string) {
    $string = str_replace('%20','',$string);
    $string = str_replace('%27','',$string);
    $string = str_replace('%2527','',$string);
    $string = str_replace('*','',$string);
    $string = str_replace('"','&quot;',$string);
    $string = str_replace("'",'',$string);
    $string = str_replace('"','',$string);
    $string = str_replace(';','',$string);
    $string = str_replace('<','&lt;',$string);
    $string = str_replace('>','&gt;',$string);
    $string = str_replace("{",'',$string);
    $string = str_replace('}','',$string);
    $string = str_replace('\\','',$string);
    return $string;
}
 
代码实例：
 
<?php
$user_name = strim($_REQUEST['user_name']);
 
function strim($str)
{
    //trim() 函数移除字符串两侧的空白字符或其他预定义字符。
    //htmlspecialchars() 函数把预定义的字符转换为 HTML 实体(防xss攻击)。
    //预定义的字符是：
    //& （和号）成为 &amp;
    //" （双引号）成为 &quot;
    //' （单引号）成为 '
    //< （小于）成为 &lt;
    //> （大于）成为 &gt;
    return quotes(htmlspecialchars(trim($str)));
}
//防sql注入
function quotes($content)
{
    //if $content is an array
    if (is_array($content))
    {
        foreach ($content as $key=>$value)
        {
            //$content[$key] = mysql_real_escape_string($value);
            /*addslashes() 函数返回在预定义字符之前添加反斜杠的字符串。
            预定义字符是：
            单引号（'）
            双引号（"）
            反斜杠（\）
            NULL */
            $content[$key] = addslashes($value);
        }
    } else
    {
        //if $content is not an array
        //$content=mysql_real_escape_string($content);
        $content=addslashes($content);
    }
    return $content;
}
 
?>
 
//过滤sql注入
function filter_injection(&$request)
{
    $pattern = "/(select[\s])|(insert[\s])|(update[\s])|(delete[\s])|(from[\s])|(where[\s])/i";
    foreach($request as $k=>$v)
    {
                if(preg_match($pattern,$k,$match))
                {
                        die("SQL Injection denied!");
                }
 
                if(is_array($v))
                {
                    filter_injection($request[$k]);
                }
                else
                {
                    if(preg_match($pattern,$v,$match))
                    {
                        die("SQL Injection denied!");
                    }
                }
    }
 
}
 
防sql注入： 
mysql_real_escape_string() 函数转义 SQL 语句中使用的字符串中的特殊字符。 
下列字符受影响： 
\x00 
\n 
\r 
\ 
’ 
” 
\x1a 
如果成功，则该函数返回被转义的字符串。如果失败，则返回 false。 
语法 
mysql_real_escape_string(string,connection) 
参数 描述 
string 必需。规定要转义的字符串。 
connection 可选。规定 MySQL 连接。如果未规定，则使用上一个连接。
 
对于纯数字或数字型字符串的校验可以用 
is_numeric()检测变量是否为数字或数字字符串 
实例：
 
<?php 
function get_numeric($val) { 
  if (is_numeric($val)) { 
    return $val + 0; 
  } 
  return 0; 
} 
?>
 
is_array — 检测变量是否是数组 
bool is_array ( mixed $var ) 
如果 var 是 array，则返回 TRUE，否则返回 FALSE。
 
is_dir 判断给定文件名是否是一个目录 
bool is_dir ( string $filename ) 
判断给定文件名是否是一个目录。 
如果文件名存在，并且是个目录，返回 TRUE，否则返回FALSE。
 
is_file — 判断给定文件名是否为一个正常的文件 
bool is_file ( string $filename ) 
判断给定文件名是否为一个正常的文件。 
如果文件存在且为正常的文件则返回 TRUE，否则返回 FALSE。 
Note: 因为 PHP 的整数类型是有符号整型而且很多平台使用 32 位整型，对 2GB 以上的文件，一些文件系统函数可能返回无法预期的结果 。
 
is_bool — 检测变量是否是布尔型 
bool is_bool ( mixed $var )如果 var 是 boolean 则返回 TRUE。
 
is_string — 检测变量是否是字符串 
bool is_string ( mixed $var ) 
如果 var 是 string 则返回 TRUE，否则返回 FALSE。
 
is_int — 检测变量是否是整数 
bool is_int ( mixed $var ) 
如果 var 是 integer 则返回 TRUE，否则返回 FALSE。 
Note: 
若想测试一个变量是否是数字或数字字符串（如表单输入，它们通常为字符串），必须使用 is_numeric()。
 
is_float — 检测变量是否是浮点型 
bool is_float ( mixed $var ) 
如果 var 是 float 则返回 TRUE，否则返回 FALSE。 
Note: 
若想测试一个变量是否是数字或数字字符串（如表单输入，它们通常为字符串），必须使用 is_numeric()。
 
is_null — 检测变量是否为 NULL 
bool is_null ( mixed $var ) 
如果 var 是 null 则返回 TRUE，否则返回 FALSE。
 
is_readable — 判断给定文件名是否可读 
bool is_readable ( string $filename )判断给定文件名是否存在并且可读。如果由 filename 指定的文件或目录存在并且可读则返回 TRUE，否则返回 FALSE。
 
is_writable — 判断给定的文件名是否可写 
bool is_writable ( string $filename ) 
如果文件存在并且可写则返回 TRUE。filename 参数可以是一个允许进行是否可写检查的目录名。
 
file_exists — 检查文件或目录是否存在 
bool file_exists ( string $filename ) 
检查文件或目录是否存在。 
在 Windows 中要用 //computername/share/filename 或者 \computername\share\filename 来检查网络中的共享文件。 
如果由 filename 指定的文件或目录存在则返回 TRUE，否则返回 FALSE。
 
is_executable — 判断给定文件名是否可执行 
bool is_executable ( string $filename )判断给定文件名是否可执行。如果文件存在且可执行则返回 TRUE，错误时返回FALSE。
