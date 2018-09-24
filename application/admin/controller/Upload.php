<?php
/**
 * Created by PhpStorm.
 * User: 顾力华
 * Date: 2018/5/13
 * Time: 22:12
 */

namespace app\admin\controller;


use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use think\Config;
use think\Controller;
use think\Request;

class Upload extends Controller
{

    public function index()
    {
        $ret = array();  //返回的上传文件状态数组
        if ($_FILES["file"]["error"] > 0) {
            $ret["message"] = $_FILES["file"]["error"];
            $ret["status"] = 0;
            $ret["src"] = "";
            return json($ret);
        } else {
            $pic = $this->uploadImg();
            if ($pic['err'] == 1) {
                $ret["message"] = $pic['msg'];
                $ret["status"] = 0;
            } else {
                $ret["message"] = "图片上传成功！";
                $ret["status"] = 1;
                $webpath = '/upload/' . $pic['webpath'];
                $cndurl = $pic['data'];
            }

            $ret["webpath"] = $webpath;
            $ret["cdnurl"] = $cndurl;
            //  infoset::getImgUrl($url);
            return json($ret);
        }

    }

//图片上传代码
//    private  function upload(){
//        $file = request()->file('file');
//        // 移动到框架应用根目录/public/upload/ 目录下
//        if(empty($file)){
//            echo (alert_fail_url("请选择上传文件",''));
//
//        }
//        $info=$file->move(ROOT_PATH.'public'.DS.'upload');
//        $reubfo = array();  //定义一个返回的数组
//        if($info){
//            $reubfo['info']= 1;
//            $reubfo['savename'] = $info->getSaveName();
//        }else{
//            // 上传失败获取错误信息
//            $reubfo['info']= 0;
//            $reubfo['err'] = $file->getError();;
//        }
//      // print_r($reubfo);
//        return $reubfo;
//    }
    public function uploadImg()
    {
        $file = request()->file('file');
        if (empty($file) || $file == null) {
            echo('<script>alert("请选择上传文件",\'\')</script>');
            return ["err" => 1, "msg" => '上传出错', "data" => "", "localpath" => '', "webpath" => ''];

        }
        $info = $file->rule('date')->validate(['ext' => 'jpg,png,gif,jpeg'])->move(ROOT_PATH . 'public' . DS . 'upload');


        // 要上传图片的本地路径
        $webpath = $info->getSaveName();
        $filePath = $info->getRealPath();
        $ext = pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);  //后缀
        // 上传到七牛后保存的文件名
        $key = substr(md5($file->getRealPath()), 0, 5) . date('YmdHis') . rand(0, 9999) . '.' . $ext;
        require_once APP_PATH . '../vendor/qiniu/autoload.php';
        // 需要填写你的 Access Key 和 Secret Key
        $accessKey = Config::get('qiniu.accessKey');
        $secretKey = Config::get('qiniu.secretKey');
        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);
        // 要上传的空间
        $bucket = Config::get('qiniu.bucket');
        $domain = Config::get('qiniu.DOMAIN');
        $token = $auth->uploadToken($bucket);
        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            return ["err" => 1, "msg" => $err, "data" => "", "localpath" => '', "webpath" => ''];
        } else {
            //返回图片的完整URL
            return ["err" => 0, "msg" => "上传完成", "data" => ($domain . '/' . $ret['key']), "localpath" => $filePath, "webpath" => $webpath];
        }
    }

    public function indexvideo()
    {
        $ret = array();  //返回的上传文件状态数组
        if ($_FILES["file"]["error"] > 0) {
            $ret["message"] = $_FILES["file"]["error"];
            $ret["status"] = 0;
            $ret["src"] = "";
            return json($ret);
        } else {
            $pic = $this->uploadVideo();
            if ($pic['err'] == 1) {
                $ret["message"] = $pic['msg'];
                $ret["status"] = 0;
            } else {
                $ret["message"] = "视频上传成功！";
                $ret["status"] = 1;
                $webpath = '/upload/' . $pic['webpath'];
                $cndurl = $pic['data'];
            }

            $ret["webpath"] = $webpath;
            $ret["cdnurl"] = $cndurl;
            //  infoset::getImgUrl($url);
            return json($ret);
        }

    }

    public function uploadVideo()
    {
        $file = request()->file('file');
        if (empty($file) || $file == null) {
            echo('<script>alert("请选择上传文件",\'\')</script>');
            return ["err" => 1, "msg" => '上传出错', "data" => "", "localpath" => '', "webpath" => ''];

        }
        $info = $file->rule('date')->validate(['size' => '64971520', 'ext' => 'mp4,rm,rmvb,mpeg,mov,mtv,dat,wmv,avi ,3gp,amv,dmv,flv'])->move(ROOT_PATH . 'public' . DS . 'upload');


        // 要上传图片的本地路径
        $webpath = $info->getSaveName();
        $filePath = $info->getRealPath();
        $ext = pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);  //后缀
        // 上传到七牛后保存的文件名
        $key = substr(md5($file->getRealPath()), 0, 5) . date('YmdHis') . rand(0, 9999) . '.' . $ext;
        require_once APP_PATH . '../vendor/qiniu/autoload.php';
        // 需要填写你的 Access Key 和 Secret Key
        $accessKey = Config::get('qiniu.accessKey');
        $secretKey = Config::get('qiniu.secretKey');
        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);
        // 要上传的空间
        $bucket = Config::get('qiniu.bucket');
        $domain = Config::get('qiniu.DOMAIN');
        $token = $auth->uploadToken($bucket);
        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            return ["err" => 1, "msg" => $err, "data" => "", "localpath" => '', "webpath" => ''];
        } else {
            //返回图片的完整URL
            return ["err" => 0, "msg" => "上传完成", "data" => ($domain . '/' . $ret['key']), "localpath" => $filePath, "webpath" => $webpath];
        }
    }

    function uploadPasteImg(Request $request)
    {
        $accepted_origins = array("http://localhost", "http://127.0.0.1");

        /*********************************************
         * Change this line to set the upload folder *
         *********************************************/
        $imageFolder = "images/";

        reset($_FILES);
        $temp = current($_FILES);
        if (is_uploaded_file($temp['tmp_name'])) {
            if (isset($_SERVER['HTTP_ORIGIN'])) {
                // same-origin requests won't set an origin. If the origin is set, it must be valid.
                if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
                    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
                } else {
                    header("HTTP/1.1 403 Origin Denied");
                    return;
                }
            }

            if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
                header("HTTP/1.1 400 Invalid file name.");
                return;
            }

            // Verify extension
            if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
                header("HTTP/1.1 400 Invalid extension.");
                return;
            }

            // Accept upload if there was no origin, or if it is an accepted origin
            $filetowrite = $imageFolder . $temp['name'];
            move_uploaded_file($temp['tmp_name'], $filetowrite);

            // Respond to the successful upload with JSON.
            // Use a location key to specify the path to the saved image resource.
            // { location : '/your/uploaded/image/file'}
            echo json_encode(array('location' => $filetowrite));
        } else {
            // Notify editor that the upload failed
            header("HTTP/1.1 500 Server Error");
        }
    }

}