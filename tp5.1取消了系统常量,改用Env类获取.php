5.1版本取消了所有的系统常量，原来的系统路径变量改为使用Env类获取（需要引入namespace think\facade\Env）


echo "app_path=========".Env::get('app_path')."</br>";
echo "root_path=========".Env::get('root_path')."</br>";
echo "think_path=========".Env::get('think_path')."</br>";
echo "config_path=========".Env::get('config_path')."</br>";
echo "extend_path=========".Env::get('extend_path')."</br>";
echo "vendor_path=========".Env::get('vendor_path')."</br>";
echo "runtime_path=========".Env::get('runtime_path')."</br>";
echo "route_path=========".Env::get('route_path')."</br>";
echo "module_path=========".Env::get('module_path')."</br>";

app_path=========/home/wwwroot/default/citygame/dragonfly/app/
root_path=========/home/wwwroot/default/citygame/dragonfly/
think_path=========/home/wwwroot/default/citygame/dragonfly/thinkphp/
config_path=========/home/wwwroot/default/citygame/dragonfly/config/
extend_path=========/home/wwwroot/default/citygame/dragonfly/extend/
vendor_path=========/home/wwwroot/default/citygame/dragonfly/vendor/
runtime_path=========/home/wwwroot/default/citygame/dragonfly/runtime/
route_path=========/home/wwwroot/default/citygame/dragonfly/route/
module_path=========/home/wwwroot/default/citygame/dragonfly/app/admin/
