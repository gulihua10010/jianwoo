<?php
/**
 * Created by PhpStorm.
 * User: 顾力华
 * Date: 2018/6/28
 * Time: 22:31
 */

namespace app\admin\controller;


use app\model\admin;
use think\Config;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Request;
//引入七牛云的相关文件
use qiniu\Auth as Auth;
use qiniu\Storage\BucketManager;
use qiniu\Storage\UploadManager;

class Index extends adminController
{
    public function __construct(Request $request = null)
    {
        header("content-type:text/html;charset:UTF-8");
        parent::__construct($request);

    }

    function index()
    {
        if (session('islogin') != 1) {
            return alert_fail_url('您还未登陆！请登陆', 'admin/Login/index');
        }


        $this->setMemuList('index');
        $artsCount = admin::getArtsCount();
        $draftsCount = admin::getDraftsCount();
        $tagscount = admin::getTagsCount();
        $commCount = admin::getCommCount();
        $recentArts = admin::recentPub();
        $recentDrafts = admin::recentDraft();
        $recentComms = admin::recentComm();
//        console_log($recentComms);
        $this->assign('artsCount', $artsCount);
        $this->assign('draftsCount', $draftsCount);
        $this->assign('tagscount', $tagscount);
        $this->assign('commCount', $commCount);
        $this->assign('recentArts', $recentArts);
        $this->assign('recentDrafts', $recentDrafts);
        $this->assign('recentComms', $recentComms);
        return $this->fetch('index');
    }

    function dynamic(Request $request)
    {
        $re = $request->param();
        $this->setMemuList('dynamic');

        $visiters = admin::getvisiters();
        $notReadComms = Db::name('comment')
            ->alias('c')
            ->whereNotIn('comment_artdel', -1)
            ->whereNotIn('comment_artdel', '作者:')
            ->where('comment_isread', 0)
            ->join('article a', 'a.article_id=c.article_id', 'RIGHT')
            ->field('a.article_id,a.article_title,comment_id,comment_date,comment_user,comment_content')
            ->paginate(15, false, ['query' => ['v' => 1]]);
        $page = $notReadComms->render();

        if ($re['v'] == 1) {
            Db::name('comment')->where('comment_isread', 0)->update(['comment_isread' => 1]);
        }

        $this->assign('visit', $visiters);
        $this->assign('notreadcomms', $notReadComms);
        $this->assign('page', $page);
        return $this->fetch('dynamic');
    }

    function webConfig()
    {
        $this->setMemuList('webConfig');
        $webconf = admin::getWebConf();
        $this->assign('webconf', $webconf);
        // Config::set('paginate1','111',true) ;
        // console_log(Config::get('paginate1'));

        //  $d=setconfig("paginate1.list_rows","1");
        //var_dump($d);

        return $this->fetch('webConfig');
    }

    function webConSub()
    {
        $data['title'] = input('title');
        $data['author'] = input('author');
        $data['keywords'] = input('keywords');
        $data['description'] = input('description');
        $data['record'] = input('record');
        $data['domail'] = input('domail');
        $data['footer'] = input('footer');
        $data['logoimgsrc'] = input('logoimgsrc');
        $data['topimgsrc'] = input('topimgsrc');
        $data['iscomment'] = input('iscomment') == 1 ? 1 : 0;
        $data['page'] = input('page');
        $data['textcount'] = input('textcount');
        Config::set('paginate', $data['page'], false);
        $page = array("paginate1");
        $regdata = array('type' => 'Page',
            'var_page' => 'page',
            'list_rows' => 151);
        setconfig("paginate.list_rows", $data['page']);
        $result = admin::webConSub($data);
        return $result;
    }

    function clearCache()
    {
        $this->setMemuList('clearCache');
        return $this->fetch('clearCache');
    }

    function delDir($path)
    {
        if (!is_dir($path)) {
            return false;
        }
        $hand = opendir($path);
        while (($file = readdir($hand)) != false) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (is_dir($path . '/' . $file)) {
                $this->delDir($path . '/' . $file);
            } else {
                @unlink($path . '/' . $file);
            }

        }

        closedir($hand);
        $cacheDir = substr($path, strripos($path, '/') + 1);
        if ($cacheDir != 'temp' && $cacheDir != 'log' && $cacheDir != 'cache') {
            @rmdir($path);
        }

        return true;
    }

    function clearCacheSub()
    {
        $data['temp'] = input('temp');
        $data['cache'] = input('cache');
        $data['log'] = input('log');
        $path = RUNTIME;
        foreach ($data as $item) {
            if ($item != null) {
                $result = $this->delDir($path . $item);
            }

        }
        if ($result) {
            return true;

        } else {
            return false;
        }
    }

    function articlePublished()
    {
        $this->setMemuList('articlePublished');
        $tags = admin::getTags();
        $types = admin::getType();
        $menus = admin::getmenuMg();
        $selType = array();

        foreach ($menus as $v) {
            $c = $i = 0;

            $submenu = array();
            foreach ($types as $vt) {
                if ($vt['menu_id'] == $v['menu_id']) {
                    $c++;
                    $submenu[$i++] = $vt;
                }

            }
            $selType += array(
                $v['menu_id'] => array(
                    'menu_id' => $v['menu_id'],
                    'menu_name' => $v['menu_name'],
                    'subtype' => $submenu
                )
            );
            if ($c == 0) {
                $selType += array(
                    $v['menu_id'] => array(
                        'menu_id' => $v['menu_id'],
                        'menu_name' => $v['menu_name'],
                    )
                );
            }
        }

        // var_dump($selType);
        $this->assign('tags', $tags);
        $this->assign('types', $selType);
//        console_log("types:".$types);
        return $this->fetch('articlePublished');
    }

    function articleSubmit()
    {
        $data = json_decode($_POST['data'], true);
        $articletxt = $data['articleContent'];
        $articletxt = htmlspecialchars_decode($articletxt);
        $articletxt = preg_replace("/<[^>]*>(.*?)/is", "$1", $articletxt);
        $articletxt = preg_replace("/&[\w]*;/is", "$1", $articletxt);
        $articletxt = preg_replace("/[\n]/is", "$1", $articletxt);
        $tags = $data['tags'];

//        console_log($data);
//        $result=date_modify()
        $result = admin::articleSubmit($data, $articletxt, 1);

        return json($result);
    }

    function articleSaveDraft()
    {
        $data = json_decode($_POST['data'], true);
        $articletxt = $data['articleContent'];
        $articletxt = htmlspecialchars_decode($articletxt);
        $articletxt = preg_replace("/<[^>]*>(.*?)/is", "$1", $articletxt);
        $articletxt = preg_replace("/&[\w]*;/is", "$1", $articletxt);
        $articletxt = preg_replace("/[\n]/is", "$1", $articletxt);
        $tags = $data['tags'];

//        console_log($data);
//        $result=date_modify()
        $result = admin::articleSubmit($data, $articletxt, 0);

        return json($result);
    }

    function articleRemoveDel()
    {
        $data = json_decode($_POST['data'], true);
        $articletxt = $data['articleContent'];
        $articletxt = htmlspecialchars_decode($articletxt);
        $articletxt = preg_replace("/<[^>]*>(.*?)/is", "$1", $articletxt);
        $articletxt = preg_replace("/&[\w]*;/is", "$1", $articletxt);
        $articletxt = preg_replace("/[\n]/is", "$1", $articletxt);
        $tags = $data['tags'];

//        console_log($data);
//        $result=date_modify()
        $result = admin::articleSubmit($data, $articletxt, -1);

        return json($result);
    }

    function artUpdate()
    {
        $data = json_decode($_POST['data'], true);
        $articletxt = $data['articleContent'];
        $articletxt = htmlspecialchars_decode($articletxt);
        $articletxt = preg_replace("/<[^>]*>(.*?)/is", "$1", $articletxt);
        $articletxt = preg_replace("/&[\w]*;/is", "$1", $articletxt);
        $articletxt = preg_replace("/[\n]/is", "$1", $articletxt);

//        console_log($data['tags']);
//        $result=date_modify()
        $result = admin::artUpdate($data, $articletxt, 1);

        return json($result);

    }

    function artDraftSave()
    {
        $data = json_decode($_POST['data'], true);
        $articletxt = $data['articleContent'];
        $articletxt = htmlspecialchars_decode($articletxt);
        $articletxt = preg_replace("/<[^>]*>(.*?)/is", "$1", $articletxt);
        $articletxt = preg_replace("/&[\w]*;/is", "$1", $articletxt);
        $articletxt = preg_replace("/[\n]/is", "$1", $articletxt);

//        console_log($data);
//        $result=date_modify()
        $result = admin::artUpdate($data, $articletxt, 0);

        return json($result);
    }

    function artDraftPublish()
    {
        $data = json_decode($_POST['data'], true);
        $articletxt = $data['articleContent'];
        $articletxt = htmlspecialchars_decode($articletxt);
        $articletxt = preg_replace("/<[^>]*>(.*?)/is", "$1", $articletxt);
        $articletxt = preg_replace("/&[\w]*;/is", "$1", $articletxt);
        $articletxt = preg_replace("/[\n]/is", "$1", $articletxt);

//        console_log($data);
//        $result=date_modify()
        $result = admin::artUpdate($data, $articletxt, 1);

        return json($result);
    }

    function getArtTags($artid)
    {
        $tags = admin::getArtTags($artid);
        $artTags = array();
        foreach ($tags as $tag) {
            $artTags += array(
                $tag['article_tags_id'] => array(
                    'tags_id' => $tag['tags_id'],
                    'tags_content' => admin::getTagsName($tag['tags_id']),
                )
            );
        }
        return $artTags;
    }

    function myArticle(Request $request)
    {
        $this->setMemuList('myArticle');
//        $art=array();
        $re = $request->param();
        console_log($re);
        $date = $re['date'];
        $type = $re['type'];
        $dateorder = $re['dateOrder'];
        $top = '=';
//        console_log($type);
        if ($type == null) {
            $type = -99999;
            $top = '>=';
        }
        if ($dateorder == 2) {
            $orderOp = 'article_pushdate desc';
        } else {
            $orderOp = 'article_pushdate';
        }
        if ($date == null) {
            $date = '';
        }
//        console_log($artTags);
//        console_log(date(" Y-m-d H:i:s",strtotime("-99 years")));
        switch ($date) {
            case 1:
                $tdadte = date(" Y-m-d H:i:s", strtotime("-7 days"));
                $dop = '>=';
                break;
            case 2:
                $tdadte = date("Y-m-d H:i:s", strtotime("-1 months"));
                $dop = '>=';
                break;
            case 3:
                $tdadte = date(" Y-m-d H:i:s", strtotime("-3 months"));
                $dop = '>=';
                break;
            case 4:
                $tdadte = date(" Y-m-d H:i:s", strtotime("-3 months"));
                $dop = '<';
                break;
            default:
                $tdadte = date(" Y-m-d H:i:s", strtotime("-99 years"));
                $dop = '>=';
                break;
        }
        if ($re['m'] == 'all') {
            $list = Db::name('article')->whereNotIn('article_status', -1)
                ->where('article_typeid', $top, $type)
                ->whereTime('article_pushdate', $dop, $tdadte)
                ->order($orderOp)
                ->paginate(11, false, ['query' => ['m' => 'all', 'date' => $date, 'type' => $type, 'dateOrder' => $dateorder]])
                ->each(function ($item, $key) {
                    $item['article_type'] = admin::getTypeName($item['article_typeid']);
//            console_log($item['article_type']);
                    $item['article_commentCount'] = admin::getArtComm($item['article_id']);
                    $item['article_tags'] = admin::getArtTags($item['article_id']);
                    return $item;
                });
            $page = $list->render();
            $m = 2;

        } else if ($re['m'] == 'published') {
            $list = Db::name('article')->where('article_status', 1)
                ->where('article_typeid', $top, $type)
                ->whereTime('article_pushdate', $dop, $tdadte)
                ->order($orderOp)
                ->paginate(11, false, ['query' => ['m' => 'all', 'date' => $date, 'type' => $type, 'dateOrder' => $dateorder]])
                ->each(function ($item, $key) {
                    $item['article_type'] = admin::getTypeName($item['article_typeid']);
//            console_log($item['article_type']);
                    $item['article_commentCount'] = admin::getArtComm($item['article_id']);
                    $item['article_tags'] = admin::getArtTags($item['article_id']);


                    return $item;
                });
            $page = $list->render();
            $m = 1;
        } else if ($re['m'] == 'draft') {
            $list = Db::name('article')->where('article_status', 0)
                ->where('article_typeid', $top, $type)
                ->whereTime('article_pushdate', $dop, $tdadte)
                ->order($orderOp)
                ->paginate(11, false, ['query' => ['m' => 'all', 'date' => $date, 'type' => $type, 'dateOrder' => $dateorder]])
                ->each(function ($item, $key) {
                    $item['article_type'] = admin::getTypeName($item['article_typeid']);
//            console_log($item['article_type']);
                    $item['article_commentCount'] = admin::getArtComm($item['article_id']);
                    $item['article_tags'] = admin::getArtTags($item['article_id']);


                    return $item;
                });
            $page = $list->render();
            $m = 0;
        }
        $countall = admin::countAll();
        $countpub = admin::countpub();
        $countdraft = admin::countdraft();
        $tags = admin::getTags();
//        console_log($tags);
        $types = admin::getType();
        $menus = admin::getmenuMg();
        $selType = array();

        foreach ($menus as $v) {
            $c = $i = 0;

            $submenu = array();
            foreach ($types as $vt) {
                if ($vt['menu_id'] == $v['menu_id']) {
                    $c++;
                    $submenu[$i++] = $vt;
                }

            }
            $selType += array(
                $v['menu_id'] => array(
                    'menu_id' => $v['menu_id'],
                    'menu_name' => $v['menu_name'],
                    'subtype' => $submenu
                )
            );
            if ($c == 0) {
                $selType += array(
                    $v['menu_id'] => array(
                        'menu_id' => $v['menu_id'],
                        'menu_name' => $v['menu_name'],
                    )
                );
            }
        }
        $this->assign('article', $list);
        $this->assign('page', $page);
        $this->assign('m', $m);
        $this->assign('types', $selType);
        $this->assign('types1', $selType);
        $this->assign('tags', $tags);
        $this->assign('countall', $countall);
        $this->assign('countpub', $countpub);
        $this->assign('countdraft', $countdraft);
        return $this->fetch('myArticle');
    }

    function recycle()
    {
        $data = json_decode($_POST['data'], true);
        $result = admin::recycle($data['artid']);
        return $result;

    }

    function removeDraft()
    {
        $data = json_decode($_POST['data'], true);
        $result = admin::removeDraft($data['artid']);
        return $result;
    }

    function delArt()
    {
        $data = json_decode($_POST['data'], true);
        $result = admin::delArt($data['artid']);
        return $result;
    }

    function restore()
    {
        $data = json_decode($_POST['data'], true);
        $result = admin::restore($data['artid']);
        return $result;
    }

    function published()
    {
        $data = json_decode($_POST['data'], true);
        $result = admin::published($data['artid']);
        return $result;

    }

    function udArtBaseInfo()
    {
        $data = json_decode($_POST['data'], true);
        $result = admin::udArtBaseInfo($data);
        return $result;
    }

    function commentManagement(Request $request)
    {
        $this->setMemuList('commentManagement');
        $re = $request->param();
        $dateorder = $re['dateOrder'];
        if ($re['reply'] >= -1 || $re['reply'] != null) {
            $reply = $re['reply'];
            $replyop = '=';
        } else {
            $reply = -2;
            $replyop = '>';
        }
//        console_log($replyop.$reply);
        if ($dateorder == 2) {
            $orderOp = 'comment_date desc';
        } else {
            $orderOp = 'comment_date';
        }
        if ($re['artid'] <= 0) {
            $artid = -1;
            $op = '>';
        } else {
            $artid = $re['artid'];
            $op = '=';
        }
        try {
            $commentlist = Db::name('comment')
                ->where('article_id', $op, $artid)
                ->whereNotIn('comment_artdel', -1)
                ->where('comment_parent', $replyop, $reply)
                ->order('article_id,' . $orderOp)
                ->paginate(13, false, ['query' => ['artid' => $artid, 'dateOrder' => $dateorder, 'reply' => $reply]])
                ->each(function ($item, $key) {
                    $item['comment_title'] = admin::getArtTitle($item['article_id']);
                    $res = (admin::getCommentUser($item['comment_parent']));
                    $item['comment_parents'] = ($item['comment_parent'] == -1 ? "文章" : $res['comment_content']);
                    return $item;
                });
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
        $commentlastid = admin::getLastCommId();
        $page = $commentlist->render();
//        foreach ($comm as $co){
//            $commentlist+=array(
//                $co['comment_id']=>array(
//                    'comment_id'=>$co['comment_id'],
//                    'comment_title'=>admin::getArtTitle($co['comment_id']),
//                    'comment_username'=>$co['comment_username'],
//                    'comment_qq'=>$co['comment_qq'],
//                    'comment_date'=>$co['comment_date'],
//                    'comment_content'=>$co['comment_content'],
//                    'comment_parent'=>admin::getCommentUser($co['comment_parent']),
//                )
//            );
//        }
        $this->assign('comment', $commentlist);
        $this->assign('page', $page);
        $this->assign('commlastid', $commentlastid['comment_id']);

        return $this->fetch('commentManagement');
    }

    public $reply = array();

    function getReplyComments($comId)
    {
        //得到所有parent父评论id为comid的子评论
        $replys = admin::getReplyComment($comId);
        //遍历子评论
        foreach ($replys as $re) {
            $parName = admin::getCommentUser($re['comment_parent']);
            $this->reply += array(
                $re['comment_id'] => array(
                    'comment_id' => $re['comment_id'],
                    'article_id ' => $re['article_id'],
                    'comment_user' => $re['comment_user'],
                    'comment_ip ' => $re['comment_ip'],
                    'comment_date' => $re['comment_date'],
                    'comment_content' => $re['comment_content'],
                    'comment_goodscount' => $re['comment_goodscount'],
                    'comment_qq' => $re['comment_qq'],
                    'comment_headimg' => $re['comment_headimg'],
                    'comment_parent_user' => $parName['comment_user'],

                )

            );
            //递归
            $this->getReplyComments($re['comment_id']);
        }
    }

    function articleEdit(Request $request)
    {
        $this->setMemuList('myArticle');
        $re = $request->param();
//        console_log($re['artid']);
        $art = admin::getArticle($re['artid']);
        $tags = admin::getArtTags($re['artid']);
        $arttags = array();
        foreach ($tags as $key => $tag) {
            $arttags += array(
                $tag['tags_id'] => array(
                    'tags_content' => admin::getTagsName($tag['tags_id']),
                    'tags_id' => $tag['tags_id'],

                )
            );
        }
        $artcomment = admin::getComment($re['artid']);
        $commentlist = array();
        $commentlastid = admin::getLastCommId();
//        console_log($this->reply);
//        console_log('s'.$commentlastid['comment_id']);

        foreach ($artcomment as $v) {

            if ($v['comment_parent'] == -1) {
                $this->reply = array();
                $this->getReplyComments($v['comment_id']);
                $commentlist += array(
                    $v['comment_id'] => array(
                        'comment_id' => $v['comment_id'],
                        'article_id ' => $v['article_id'],
                        'comment_parent' => $v['comment_parent'],
                        'comment_user' => $v['comment_user'],
                        'comment_ip ' => $v['comment_ip'],
                        'comment_date' => $v['comment_date'],
                        'comment_content' => $v['comment_content'],
                        'comment_goodscount' => $v['comment_goodscount'],
                        'comment_qq' => $v['comment_qq'],
                        'comment_headimg' => $v['comment_headimg'],
                        'comment_reply' => $this->reply,
                    )

                );
            }

        }
        $tags = admin::getTags();
        $types = admin::getType();
        $menus = admin::getmenuMg();
        $selType = array();

        foreach ($menus as $v) {
            $c = $i = 0;

            $submenu = array();
            foreach ($types as $vt) {
                if ($vt['menu_id'] == $v['menu_id']) {
                    $c++;
                    $submenu[$i++] = $vt;
                }

            }
            $selType += array(
                $v['menu_id'] => array(
                    'menu_id' => $v['menu_id'],
                    'menu_name' => $v['menu_name'],
                    'subtype' => $submenu
                )
            );
            if ($c == 0) {
                $selType += array(
                    $v['menu_id'] => array(
                        'menu_id' => $v['menu_id'],
                        'menu_name' => $v['menu_name'],
                    )
                );
            }
        }
        //console_log($selType);

        $this->assign('tags', $tags);
        $this->assign('types', $selType);
        $html = htmlspecialchars_decode($art['article_content']);
        $html = preg_replace("/'/i", "\"", $html);
        $html = preg_replace("/\\\\/i", "\\\\\\\\", $html);
        $html = preg_replace("/\n/i", "\\n'+\n'", $html);
        $this->assign('article', $art);
        $this->assign('tags', $tags);
        $this->assign('arttags', $arttags);
        //  $this->assign('types', $types);
        $this->assign('arttypes', $types);
        $this->assign('htmlcontent', $html);
        $this->assign('artcomment', $commentlist);
        $this->assign('commlastid', $commentlastid['comment_id']);
        return $this->fetch('articleEdit');
    }

    function delComment()
    {
        $data = json_decode($_POST['data'], true);
        $result = $this->delReplyComments($data['commid']);
        return $result;

    }

    function delReplyComments($comId)
    {
        //得到所有parent父评论id为comid的子评论
        $replys = admin::getReplyComment($comId);
        //遍历子评论
        foreach ($replys as $re) {
            admin::delComment($re['comment_id']);
            //递归
            $this->getReplyComments($re['comment_id']);
        }
        $result = admin::delComment($comId);
        return $result;
    }

//标签管理
    function articleTags()
    {
        $this->setMemuList('articleTags');
        try {
            $result = admin::getTags();
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
//        console_log("tags:".$result);
        $this->assign('tags', $result);
        return $this->fetch('articleTags');
    }

    function addtagsresult()
    {
        $tagsName = input('addtagsinput');
        $result = admin::addtagsresult($tagsName);
        if ($result) {
            $resultjson['v'] = 1;
            return json($resultjson);
        } else {
            $resultjson['v'] = 0;
            return json($resultjson);
        }
    }

    function tagsDel()
    {

        $result = admin::tagsDel(json_decode($_POST['data'], true));
        if ($result) {
            $resultjson['v'] = 1;
            return json($resultjson);
        } else {
            $resultjson['v'] = 0;
            return json($resultjson);
        }
    }

    function recycleBin(Request $request)
    {
        $this->setMemuList('recycleBin');
        $re = $request->param();
        $dateorder = $re['dateOrder'];
        if ($dateorder == 2) {
            $orderOp = 'article_deldate desc';
        } else {
            $orderOp = 'article_deldate';
        }
        $result = Db::name('article')->where('article_status', -1)->order($orderOp)->paginate(13)->each(function ($item, $key) {
            $item['article_type'] = admin::getTypeName($item['article_typeid']);
            return $item;
        });
        $this->assign('article', $result);
        return $this->fetch('recycleBin');

    }

    //菜单管理
    function menuMg()
    {
        $this->setMemuList('menuMg');
        $result = admin::getmenuMg();
        $menuCount = admin::menuCount();
        $typeresult = admin::getType();
        $typecount = admin::typecount();
        $homemenu = array();
        // console_log($result);
//        $typeresult = iconv('GBK','utf-8',$typeresult);
//        $typeresult = stripslashes($typeresult);
//        $typeresult = htmlspecialchars_decode($typeresult);
//        $tmp=stripcslashes(html_entity_decode($typeresult));
//        $subment = json_decode(trim($typeresult,chr(239).chr(187).chr(191)),true);
        /// $typeresult = json_decode($typeresult, true);
//        console_log(json_last_error());
//        foreach ($typeresult as $key=>$value){
//            $subment[$key]=$value;
//        }
//        console_log($typeresult[0]);
//        console_log($subment);
        foreach ($result as $key => $value) {
            $i = 0;
//           console_log($value);
            $submenu = array();
//            foreach ($typeresult as $type) {
//                console_log($type);
//                $subment=array(
//                    'types_id'=>$type['types_id'],
//                    'types_content'=>$type['types_content']
//                );
            foreach ($typeresult as $keyt => $valuet) {
//                console_log($valuet);
                if ($valuet['menu_id'] == $value['menu_id']) {
                    $submenu[$i++] = $valuet;
                }
            }
            //  console_log($subment);
            $homemenu += array(
                $value['menu_id'] => array(
                    'menu_id' => $value['menu_id'],
                    'menu_name' => $value['menu_name'],
                    'menu_index' => $value['menu_index'],
                    'submenu' => $submenu
                )
            );
//                console_log($submenu);
//            }


        }
        //    console_log($homemenu);

//        console_log($homemenu);
//        console_log($menuCount);
        $this->assign('menuCount', $menuCount);
        $this->assign('menu', $result);;
        $this->assign('type', $typeresult);
        $this->assign('homemenu', $homemenu);
        $this->assign('typeCount', $typecount);
//        if(\request()->post()){
//            $result=admin::addmenuMg($menuName);
//            if($result){
//                alert_success('提示','添加成功');
//            }else{
//                alert_fail_url('添加失败');
//            }
//        }
//        $result=admin::addmenuMg();
        return $this->fetch('menuMg');
    }

    function updateMenuName()
    {
        $where['menu_name'] = input('menuinput');
        $where['menu_id'] = input('menuid');
        $result = admin::updateMenuName($where);
        if ($result) {
            $resultjson['v'] = 1;
            return json($resultjson);
        } else {
            $resultjson['v'] = 0;
            return json($resultjson);
        }

    }

    function menuUpdateindex()
    {
//        $menuIndex=input('menuli');
        $result = admin::menuUpdateindex(json_decode($_POST['index'], true));
//        if ($result) {
//            $resultjson['v'] = 1;
//            return json($resultjson);
//        } else {
//            $resultjson['v'] = 0;
//            return json($resultjson);
//        }
        return ($result);
    }

    function updateTypeName()
    {
        $where['types_content'] = input('typeinput');
        $where['types_id'] = input('typeid');
        $result = admin::updateTypeName($where);
        if ($result) {
            $resultjson['v'] = 1;
            return json($resultjson);
        } else {
            $resultjson['v'] = 0;
            return json($resultjson);
        }

    }

    function menuDel()
    {
        $result = admin::menuDel(json_decode($_POST['data'], true));
        if ($result) {
            $resultjson['v'] = 1;
            return json($resultjson);
        } else {
            $resultjson['v'] = 0;
            return json($resultjson);
        }

    }

    function addmenuresult()
    {
        $addmenuinput = input('addmenuinput');
        $result = admin::addmenuMg($addmenuinput);
        if ($result) {
            $resultjson['error'] = false;
            $resultjson['text'] = $addmenuinput;
//                 $this->success($resultjson);
            return json($resultjson);

        } else {
            $resultjson['error'] = true;
            $resultjson['text'] = $addmenuinput;
//                 $this->success($resultjson);
            return json($resultjson);

        }

    }

    function typeDel()
    {
        $result = admin::typeDel(json_decode($_POST['data'], true));
        if ($result) {
            $resultjson['v'] = 1;
            return json($resultjson);
        } else {
            $resultjson['v'] = 0;
            return json($resultjson);
        }

    }

    function addtypeResult()
    {
        $addtypeinput = input('addtypeinput');
        $menuId = input('menuid');
        $result = admin::addType($addtypeinput, $menuId);
        if ($result) {
            $resultjson['error'] = false;
            $resultjson['text'] = $addtypeinput;
//                 $this->success($resultjson);
            return json($resultjson);

        } else {
            $resultjson['error'] = true;
            $resultjson['text'] = $addtypeinput;
//                 $this->success($resultjson);
            return json($resultjson);

        }
    }

    public function uploadImg()
    {
        //	获取表单上传文件	例如上传了001.jpg
        $file = request()->file('image');
        //	移动到框架应用根目录/public/uploads/	目录下
        if ($file) {
            $info = $file->rule('date')->validate(['ext' => 'jpg,png,gif,jpeg'])->move(ROOT_PATH . 'public' . DS . 'upload');
            if ($info) {
                //	成功上传后	获取上传信息
                //			//	输出	jpg
//                echo $info->getExtension();
//                //	输出	20160820/42a79759f284b767dfcb2a0197904287.jpg
//                echo $info->getSaveName();
//                //	输出	42a79759f284b767dfcb2a0197904287.jpg
//                echo $info->getFilename();

                $infos = array(
                    'error' => false,
                    'path' => $info->getPath(),
                    'name' => $info->getFilename()
                );

                return $infos;
            } else {                                                //	上传失败获取错误信息
                $infos = array(
                    'error' => true,
                    'errorinfo' => $file->getError()
                );
                return $infos;
            }
        } else {
            return -1;
        }
    }

    public function uploadVideo()
    {
        //	获取表单上传文件	例如上传了001.jpg
        $file = request()->file('file');
        //	移动到框架应用根目录/public/uploads/	目录下
        if ($file) {
            $info = $file->rule('date')->validate(['size' => '30971520', 'ext' => 'jpg,mp4,rm,rmvb,mpeg,mov,mtv,dat,wmv,avi ,3gp,amv,dmv,flv'])->move(ROOT_PATH . 'public' . DS . 'upload');
            if ($info) {
                //	成功上传后	获取上传信息
                //			//	输出	jpg
//                echo $info->getExtension();
//                //	输出	20160820/42a79759f284b767dfcb2a0197904287.jpg
//                echo $info->getSaveName();
//                //	输出	42a79759f284b767dfcb2a0197904287.jpg
//                echo $info->getFilename();

                $infos = array(
                    'status' => 1,
                    'cdnurl' => '4',
                    'message' => $info->getFilename()
                );

                return $infos;
            } else {                                                //	上传失败获取错误信息
                $infos = array(
                    'status' => 0,
                    'message' => $file->getError()
                );
                return $infos;
            }
        } else {
            return $file->getError();
        }
    }


}