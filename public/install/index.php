<?php
/**
 * 安装向导
 */
header('Content-type:text/html;charset=utf-8');
// 检测是否安装过
if (file_exists('./install.lock')) {
    echo '你已经安装过该系统，重新安装需要先删除./public/install/install.lock 文件';
    die;
}
// 同意协议页面
if(@!isset($_GET['c']) || @$_GET['c']=='agreement'){
    require './agreement.html';
}
// 检测环境页面
if(@$_GET['c']=='test'){
    require './test.html';
}
// 创建数据库页面
if(@$_GET['c']=='create'){
    require './create.html';
}
// 安装成功页面
if(@$_GET['c']=='db'){
    // 判断是否为post
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $data=$_POST;
        // 连接数据库
        $link=@new mysqli("{$data['DB_HOST']}:{$data['DB_PORT']}",$data['DB_USER'],$data['DB_PWD']);
        // 获取错误信息
        $error=$link->connect_error;
        if (!is_null($error)) {
            // 转义防止和alert中的引号冲突
            $error=addslashes($error);
            die("<script>alert('数据库链接失败:$error');history.go(-1)</script>");
        }
        // 设置字符集
        $link->query("SET NAMES 'utf8'");
        $link->server_info>5.0 or die("<script>alert('请将您的mysql升级到5.0以上');history.go(-1)</script>");
        // 创建数据库并选中
        if(!$link->select_db($data['DB_NAME'])){
            $create_sql='CREATE DATABASE IF NOT EXISTS '.$data['DB_NAME'].' DEFAULT CHARACTER SET utf8;';
            $link->query($create_sql) or die('创建数据库失败');
            $link->select_db($data['DB_NAME']);
        }
        // 导入sql数据并创建表
        $jwbolg_str=file_get_contents('./jwblog.sql');
        $sql_array=preg_split("/;[\r\n]+/", str_replace('jw_',$data['DB_PREFIX'],$jwbolg_str));
        foreach ($sql_array as $k => $v) {
            if (!empty($v)) {
                $link->query($v);
            }
        }
        $link->close();
        $db_str=<<<php
<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
 
return [
    // 数据库类型
    'type'            => 'mysql',
    // 服务器地址
    'hostname'        => '{$data['DB_HOST']}',
    // 数据库名
    'database'        => '{$data['DB_NAME']}',
    // 用户名
    'username'        => '{$data['DB_USER']}',
    // 密码
    'password'        => '{$data['DB_PWD']}',
    // 端口
    'hostport'        => '{$data['DB_PORT']}',
    // 连接dsn
    'dsn'             => '',
    // 数据库连接参数
    'params'          => [],
    // 数据库编码默认采用utf8
    'charset'         => 'utf8',
    // 数据库表前缀
    'prefix'          => '{$data['DB_PREFIX']}',
    // 数据库调试模式
    'debug'           => true,
    // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'deploy'          => 0,
    // 数据库读写是否分离 主从式有效
    'rw_separate'     => false,
    // 读写分离后 主服务器数量
    'master_num'      => 1,
    // 指定从服务器序号
    'slave_no'        => '',
    // 是否严格检查字段是否存在
    'fields_strict'   => true,
    // 数据集返回类型
    'resultset_type'  => 'array',
    // 自动写入时间戳字段
    'auto_timestamp'  => false,
    // 时间字段取出后的默认时间格式
    'datetime_format' => 'Y-m-d H:i:s',
    // 是否需要进行SQL性能分析
    'sql_explain'     => false,

];

php;
        // 创建数据库链接配置文件
        file_put_contents('../../application/database.php', $db_str);
//        var_dump(is_writable('../../public/install'));
//        var_dump(is_writable('../../application/'));
//         touch('../../application/install.lock');
        require './admin.html';
    }



}
if(@$_GET['c']=='createadmin'){

    if ($_SERVER['REQUEST_METHOD']){
        $data=$_POST;
        $filepath='../../application/database.php';
        $sqlstr=file_get_contents($filepath);
        preg_match_all('/\'hostname\'\s*=>\s*\'(.*?)\',/is',$sqlstr,$hostname);
        preg_match_all('/\'database\'\s*=>\s*\'(.*?)\',/is',$sqlstr,$database);
        preg_match_all('/\'username\'\s*=>\s*\'(.*?)\',/is',$sqlstr,$username);
        preg_match_all('/\'password\'\s*=>\s*\'(.*?)\',/is',$sqlstr,$password);
        preg_match_all('/\'hostport\'\s*=>\s*\'(.*?)\',/is',$sqlstr,$hostport);
        preg_match_all('/\'prefix\'\s*=>\s*\'(.*?)\',/is',$sqlstr,$prefix);
        $link=@new mysqli("{$hostname[1][0]}:{$hostport[1][0]}",$username[1][0],$password[1][0]);
        // 获取错误信息
        $error=$link->connect_error;
        if (!is_null($error)) {
            // 转义防止和alert中的引号冲突
            $error=addslashes($error);
            die("<script>alert('数据库链接失败:$error');history.go(-1)</script>");
        }
        $link->query("SET NAMES 'utf8'");
        if ($link->select_db($database[1][0])){
            $sql='insert into  '.$prefix[1][0].'admin(admin_username,admin_password) values (\''.$data['admin'].'\',\''.md5($data['admin_pwd']).'\')';
            $link->query($sql) or die('创建用户失败'.$link->error);
        }
        $link->close();

        @touch('./install.lock');
        require './success.html';
    }

}