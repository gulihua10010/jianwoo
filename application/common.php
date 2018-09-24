<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Request;
use think\Url;
// 应用公共文件
error_reporting(E_ERROR | E_PARSE );
/**
 * $msg 待提示的消息
 * $url 待跳转的链接
 * $icon 这里主要有两个，5和6，代表两种表情（哭和笑）
 * $time 弹出维持时间（单位秒）
 */
function alert_success($title='',$msg='',$url=''){

    if (is_null($url) && !is_null(Request::instance()->server('HTTP_REFERER'))) {
        $url = Request::instance()->server('HTTP_REFERER');
    } elseif ('' !== $url && !strpos($url, '://') && 0 !== strpos($url, '/')) {
        $url = Url::build($url);
    }
    $str='<script type="text/javascript" src="'.SCRIPT_DIR.'/static/js/jquery.min.js"></script>  
<script src="'.SCRIPT_DIR.'/static/layer/layer.js"></script>  ';//加载jquery和layer
    $str.='<script>
 var index = layer.load(0, {shade: false , offset: \'400px\'},{time:3000}); //0代表加载的风格，支持0-2
setTimeout(function() {
  layer.close(index);
layer.open({
  title:\' '.$title.
        '\',content:\''.$msg.'\'
 , anim:1
  , offset: \'400px\'
 ,yes: function(layero, index){
     window.location.href=\''.$url.'\';
     layer.close(index); 
  }
});     
  
},1000)

    </script>';//主要方法
    return $str;
}

function alert_url($msg='',$url=''){

    if (is_null($url) && !is_null(Request::instance()->server('HTTP_REFERER'))) {
        $url = Request::instance()->server('HTTP_REFERER');
    } elseif ('' !== $url && !strpos($url, '://') && 0 !== strpos($url, '/')) {
        $url = Url::build($url);
    }

    $str='<script type="text/javascript" src="'.SCRIPT_DIR.'/static/js/jquery.min.js"></script>  
<script src="'.SCRIPT_DIR.'/static/layer/layer.js"></script>  ';//加载jquery和layer
    $str.='<script>
  var index = layer.load(0, {shade: false , offset: \'400px\'},{time:2000}); //0代表加载的风格，支持0-2
setTimeout(function() {
  layer.close(index);
  layer.msg(\''.$msg.'\', {
   //不自动关闭
   time:1000 , offset: \'400px\'
});

},1000);
setTimeout(function() {
    window.location.href=\''.$url.'\';
},2000)
 

    </script>';//主要方法

    return $str;
}

//layer.msg('不开心。。', {icon: 5});

function alert_fail_url($msg='',$url=''){

    if (is_null($url) && !is_null(Request::instance()->server('HTTP_REFERER'))) {
        $url = Request::instance()->server('HTTP_REFERER');
    } elseif ('' !== $url && !strpos($url, '://') && 0 !== strpos($url, '/')) {
        $url = Url::build($url);
    }
    $str='<script type="text/javascript" src="'.SCRIPT_DIR.'/static/js/jquery.min.js"></script>  
<script src="'.SCRIPT_DIR.'/static/layer/layer.js"></script>  ';//加载jquery和layer
    $str.='<script>  
  var index = layer.load(0, {shade: false , offset: \'400px\'},{time:2000}); //0代表加载的风格，支持0-2
setTimeout(function() {
  layer.close(index);
  layer.msg(\''.$msg.'\', {
   //不自动关闭
   time:1000
   ,icon:5 , offset: \'400px\'
});

},1000);
setTimeout(function() {
    window.location.href=\''.$url.'\';
},2000)
 

    </script>';//主要方法

    return $str;
}

function get_client_ip($type = 0,$adv=false)
{
    $type = $type ? 1 : 0;
    static $ip = NULL;
    if ($ip !== NULL) return $ip[$type];
    if ($adv) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos) unset($arr[$pos]);
            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

/**
 * @param $data
 */
function console_log($data)
{
    if (is_array($data) || is_object($data)) {
        echo("<script>console.log('" . json_encode($data) . "');</script>");
    } else {
        echo("<script>console.log('" . $data . "');</script>");
    }
}

/**
 * @param $confkey配置名
 * @param $data配置值
 * @return bool
 */
function setconfig($confkey, $data)
{
    /**
     * 原理就是 打开config配置文件 然后使用正则查找替换 然后在保存文件.
     * 传递的参数为2个数组 前面的为配置 后面的为数值.  正则的匹配为单引号  如果你的是分号 请自行修改为分号
     *  $string 配置名 支持.分割
     *  $data配置值
     *$strcing
     */

    if (is_string($confkey) and is_string($data)) {
        if (!!strpos($confkey,'.')){
            $confkey=explode('.',$confkey,2);
        }

        $pat0 = '/\'' . $confkey[0] . '\'([\s\S]*?)],/';
        $pat1 = '/\'' . $confkey[1] . '\'([\s\S]*?),/';
       // $confkey = "'". $confkey. "'". "=>" . "'".$data ."',";


        $fileurl = APP_PATH . "config.php";

        $confString = file_get_contents($fileurl); //加载配置文件
        preg_match_all($pat0,$confString,$pats);
        //preg_match_all($pat1, $pats[0][0],$dd);
        $newConfString=preg_replace($pat1,'\''.$confkey[1].'\'=>\''.$data.'\',',$pats[0][0]);
        $oldConfString='';
        $confString = preg_replace($pat0, $newConfString, $confString); // 正则查找然后替换
         file_put_contents($fileurl, $confString); // 写入配置文件
        return  true;
    } else {
        return false;
    }
}


function sub_str($str, $length = 0, $append = true)
{
    $str = trim($str);
    $strlength = strlen($str);
    if ($length == 0|| $length >= $strlength)
    {
        return $str; //截取长度等于或大于等于本字符串的长度，返回字符串本身
    }
    elseif ($length <0 ) //如果截取长度为负数
  {
      $length = $strlength + $length;//那么截取长度就等于字符串长度减去截取长度
      if ($length < 0)
    {
        $length = $strlength;//如果截取长度的绝对值大于字符串本身长度，则截取长度取字符串本身的长度
    }
  }
    if (function_exists('mb_substr'))
    {
        $newstr = mb_substr($str, 0, $length, 'UTF-8');
    }
    elseif (function_exists('iconv_substr'))
    {
        $newstr = iconv_substr($str,0 , $length, 'UTF-8');
    }
    else
    {
        //$newstr = trim_right(substr($str, , $length));
        $newstr = substr($str, 0, $length);
    }
    if ($append && $str != $newstr)
    {
        $newstr .= '...';
    }
    return $newstr;
}

function sqlQuery($sql){

    $sql=preg_replace('/(update\s+)([\w\d_]+)/is',
        '$1 '.config(  'database.prefix').'$2 ',$sql);
    // var_dump($sql1);
    // var_dump($sql);
    preg_match_all('/from[\s\S]+?where/is',$sql,$t);
    for ($i=0;$i<sizeof($t[0]);$i++){
        $subsql=preg_replace('/(from\s+|,\s*)([\w\d_]+)/is','$1 '.config(  'database.prefix').'$2',$t[0][$i]);
        $sql=preg_replace('/'.$t[0][$i].'/is',
            $subsql,$sql);
    }
    return \think\Db::query($sql);



}

function sqlExecute($sql){
    $sql=preg_replace('/(update\s+)([\w\d_]+)/is',
        '$1 '.config(  'database.prefix').'$2 ',$sql);
    // var_dump($sql1);
    // var_dump($sql);
    preg_match_all('/from[\s\S]+?where/is',$sql,$t);
    for ($i=0;$i<sizeof($t[0]);$i++){
        $subsql=preg_replace('/(from\s+|,\s*)([\w\d_]+)/is','$1 '.config(  'database.prefix').'$2',$t[0][$i]);
        $sql=preg_replace('/'.$t[0][$i].'/is',
            $subsql,$sql);
    }
return \think\Db::execute($sql);

}
