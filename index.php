<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
if(file_exists("./public/install") && !file_exists("./public/install/install.lock")){
    // 组装安装url
    $url=$_SERVER['HTTP_HOST'].trim($_SERVER['SCRIPT_NAME'],'index.php').'public/install/index.php';
    // 使用http://域名方式访问；避免./public/install 路径方式的兼容性和其他出错问题
    header("Location:http://$url");
    die;
}
// 定义应用目录
define('APP_PATH', __DIR__ . '/application/');
define('RUNTIME', __DIR__ . '/runtime/');
$arr    =    explode('/',$_SERVER["REQUEST_URI"]);
//if(count($arr)>2&&$arr[2]!=='Admin'&&$arr[2]!=='admin'){
//    define('BIND_MODULE','index');
//}
// 加载框架引导文件。
 define('SCRIPT_DIR', rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/\\').'/public');
require __DIR__ . '/thinkphp/start.php';
