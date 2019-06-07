tp5版本相对于5.0升级了很多的地方

比如在5.0里面获取这些名称是这样的

use think\Request;
/*
代码段
*/
$module = Request::instance()->module();
$controller = Request::instance()->controller();
$action = Request::instance()->controller();
然而在5.1里面Request没有instance方法，所以我们直接facade来获取模块，控制器，方法名
use think\facade\Request;
/*
代码段
*/
$module = Request::module();
$controller = Request::controller();
$action = Request::controller();
