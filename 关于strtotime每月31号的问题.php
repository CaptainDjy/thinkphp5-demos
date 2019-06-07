遇到个问题当我们使用strtotime会出点问题


strtotime('-1 month');//每月31号会出错

strtotime('last day of -1 month');//从PHP5.3开始呢, date新增了一系列修正短语, 来明确这个问题, 那就是”first day of” 和 “last day of”, 也就是你可以限定好不要让date自动”规范化”:

实例：
var_dump(date("Y-m-d", strtotime("-1 month", strtotime("2019-03-31"))));
//输出2019-03-03
var_dump(date("Y-m-d", strtotime("+1 month", strtotime("2019-08-31"))));
//输出2019-10-01
var_dump(date("Y-m-d", strtotime("next month", strtotime("2019-01-31"))));
//输出2019-03-03
var_dump(date("Y-m-d", strtotime("last month", strtotime("2019-03-31"))));
//输出2019-03-03

var_dump(date("Y-m-d", strtotime("last day of -1 month", strtotime("2019-03-31"))));
//输出2019-02-28
var_dump(date("Y-m-d", strtotime("first day of +1 month", strtotime("2019-08-31"))));
////输出2019-09-01
var_dump(date("Y-m-d", strtotime("first day of next month", strtotime("2019-01-31"))));
////输出2019-02-01
var_dump(date("Y-m-d", strtotime("last day of last month", strtotime("2019-03-31"))));
////输出2019-02-28
