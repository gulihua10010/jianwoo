<?php

namespace app\index\controller;

use app\model\admin;
use app\model\home;
use think\Controller;
use think\Cookie;
use think\Db;
use think\Request;

class Index extends Controller
{
    public function getMenu( )
    {


        $menuList = home::getMenuList();
//            console_log($result);
        $menu = array();
        foreach ($menuList as $key => $value) {
            $type = home::getTypelist($value['menu_id']);
            $subMenu = array();
            foreach ($type as $subkey => $subvalue) {
                $subMenu += array(
                    $subvalue['types_id'] => array(
                        'types_id' => $subvalue['types_id'],
                        'types_content' => $subvalue['types_content']
                    )
                );
            }
//foreach ($subMenu as $k=>$v){
//    console_log($v['types_content']);

//}

            $menu += array(
                $value['menu_id'] => array(
                    'menu_id' => $value['menu_id'],
                    'menuName' => $value['menu_name'],
                    'subMenu' => $subMenu
                )

            );

        }
        return $menu;
    }

    function getFoots(){
        return home::getFoots();
    }
    function getWebrecord(){
        return home::getWebrecord();
    }
    public function index(Request $request)
    {
        $re = $request->param();
        $article = array();
//        console_log(sizeof($re));
          $GLOBALS[ 'bretextcount']=home::getArtBreTextCount();
        if (sizeof($re)==0||$re['page']>0){
            $arts=Db::name('article')
                ->where('article_status',1)
                ->order('article_modified desc,  article_pushdate  desc')
                ->paginate()
                ->each(function ($item,$key){
                    $tagsId = home::getTagsId($item['article_id']);
                    $goodsAdd = 0;
                    $tags = array();
                    $isgoods = Cookie::get('artgoods');
                    foreach ($tagsId as $t) {
                        $tags += array(
                            $t['tags_id'] => array(
                                'tags_content' => home::getTagsName($t['tags_id']),)
                        );
                    }
                    foreach ($isgoods as $goods) {
                        if ($goods == $item['article_id']) {
                            $goodsAdd = 1;
                        }
                    }

                    $item['article_bre'] = sub_str($item['article_text'],  $GLOBALS[ 'bretextcount']);
                    $item['article_type'] = home::getTypeName($item['article_typeid']);
                    $item['tags']=$tags;
                    $item['article_isgoods'] = $goodsAdd;
                    $item['article_commentCount'] =  home::getArtCount($item['article_id']);
                    $item['article_pushdate'] =   date("Y-m-d",strtotime($item['article_pushdate']));


                    return $item;

                });
            $flag = -1;
        }
       else if (($re['t'] != 0 || $re['t'] != null) && ($re['m'] != 0 || $re['m'] != null)) {
//            $article = home::getTypeArt($re['t']);
//            console_log("bb");
            $flag = 1;
            $menuName = home::getCurentMenu($re['t']);
            $typeName = home::getTypeName($re['t']);
//            console_log($menuName['menu_name']);
//            console_log($typeName['types_content']);

            $arts=Db::name('article')
                ->where('article_status',1)
                ->where('article_typeid',$re['t'])
                ->order('article_modified desc,  article_pushdate  desc')
                ->paginate()
                ->each(function ($item,$key){
                    $tagsId = home::getTagsId($item['article_id']);
                    $goodsAdd = 0;
                    $tags = array();
                    $isgoods = Cookie::get('artgoods');
                    foreach ($tagsId as $t) {
                        $tags += array(
                            $t['tags_id'] => array(
                                'tags_content' => home::getTagsName($t['tags_id']),)
                        );
                    }
                    foreach ($isgoods as $goods) {
                        if ($goods == $item['article_id']) {
                            $goodsAdd = 1;
                        }
                    }

                    $item['article_bre'] = sub_str($item['article_text'],  $GLOBALS[ 'bretextcount']);
                    $item['article_type'] = home::getTypeName($item['article_typeid']);
                    $item['tags']=$tags;
                    $item['article_isgoods'] = $goodsAdd;
                    $item['article_commentCount'] =  home::getArtCount($item['article_id']);
                    $item['article_pushdate'] =   date("Y-m-d",strtotime($item['article_pushdate']));


                    return $item;

                });

        } else if (($re['t'] == 0 || $re['t'] == null) && ($re['m'] != 0 || $re['m'] != null)) {
            $flag = 2;
            $type = home::getMenuArt($re['m']);
            $types=array();
            for ($i=0;$i<sizeof($type);$i++){
                $types[$i]=$type[$i]['types_id'];
            }
           // console_log($types);

                $arts=Db::name('article')
                    ->where('article_status',1)
                    ->where('article_typeid','in',$types)
                    ->whereOr('article_typeid',-1*$re['m'])
                    ->order('article_modified desc,  article_pushdate  desc')
                    ->paginate()
                    ->each(function ($item,$key){
                        $tagsId = home::getTagsId($item['article_id']);
                        $goodsAdd = 0;
                        $tags = array();
                        $isgoods = Cookie::get('artgoods');
                        foreach ($tagsId as $t) {
                            $tags += array(
                                $t['tags_id'] => array(
                                    'tags_content' => home::getTagsName($t['tags_id']),)
                            );
                        }
                        foreach ($isgoods as $goods) {
                            if ($goods == $item['article_id']) {
                                $goodsAdd = 1;
                            }
                        }

                        $item['article_bre'] = sub_str($item['article_text'],  $GLOBALS[ 'bretextcount']);
                        $item['article_type'] = home::getTypeName($item['article_typeid']);
                        $item['tags']=$tags;
                        $item['article_isgoods'] = $goodsAdd;
                        $item['article_commentCount'] =  home::getArtCount($item['article_id']);
                        $item['article_pushdate'] =   date("Y-m-d",strtotime($item['article_pushdate']));


                        return $item;

                    });
//            console_log($types);
            $menuName = home::getMenuName($re['m']);
           // console_log($menuName['menu_name']);

        } else if ($re['keyword'] != 0 || $re['keyword'] != null) {
            $keyword = $re['keyword'];
            $keyword = (urldecode($keyword));
            console_log($keyword);
            $flag = 0;
//            $article = home::searchArt($keyword);
            $arts=Db::name('article')
                ->where('article_status',1)
                ->where('article_text|article_title','like','%'.$keyword.'%')
                ->order('article_modified desc,  article_pushdate  desc')
                ->paginate()
                ->each(function ($item,$key){
                    $tagsId = home::getTagsId($item['article_id']);
                    $goodsAdd = 0;
                    $tags = array();
                    $isgoods = Cookie::get('artgoods');
                    foreach ($tagsId as $t) {
                        $tags += array(
                            $t['tags_id'] => array(
                                'tags_content' => home::getTagsName($t['tags_id']),)
                        );
                    }
                    foreach ($isgoods as $goods) {
                        if ($goods == $item['article_id']) {
                            $goodsAdd = 1;
                        }
                    }

                    $item['article_bre'] = sub_str($item['article_text'],  $GLOBALS[ 'bretextcount']);
                    $item['article_type'] = home::getTypeName($item['article_typeid']);
                    $item['tags']=$tags;
                    $item['article_isgoods'] = $goodsAdd;
                    $item['article_commentCount'] =  home::getArtCount($item['article_id']);
                    $item['article_pushdate'] =   date("Y-m-d",strtotime($item['article_pushdate']));


                    return $item;

                });
        }else if($re['tagid']!=null){
            $artid=home::getTagsArts($re['tagid']);
//            console_log($artids);
           $artids=array();
           for ($i=0;$i<sizeof($artid);$i++){
               $artids[$i]=$artid[$i]['article_id'];
           }
           $flag = 4;
           $arts=Db::name('article')
               ->where('article_status',1)
               ->where('article_id','in',$artids)
               ->order('article_modified desc,  article_pushdate  desc')
               ->paginate()
               ->each(function ($item,$key){
                   $tagsId = home::getTagsId($item['article_id']);
                   $goodsAdd = 0;
                   $tags = array();
                   $isgoods = Cookie::get('artgoods');
                   foreach ($tagsId as $t) {
                       $tags += array(
                           $t['tags_id'] => array(
                               'tags_content' => home::getTagsName($t['tags_id']),)
                       );
                   }
                   foreach ($isgoods as $goods) {
                       if ($goods == $item['article_id']) {
                           $goodsAdd = 1;
                       }
                   }

                   $item['article_bre'] = sub_str($item['article_text'],  $GLOBALS[ 'bretextcount']);
                   $item['article_type'] = home::getTypeName($item['article_typeid']);
                   $item['tags']=$tags;
                   $item['article_isgoods'] = $goodsAdd;
                   $item['article_commentCount'] =  home::getArtCount($item['article_id']);
                   $item['article_pushdate'] =   date("Y-m-d",strtotime($item['article_pushdate']));
                   return $item;

               });
           $tagName=home::getTagsName($re['tagid']);
        } else if($re['d']!=null){
           $flag = 5;
           if (strlen($re['d'])==8){

               $datestart=substr($re['d'],0,4).'-'.substr($re['d'],4,2).'-'.substr($re['d'],6,2);
               $dateend=substr($re['d'],0,4).'-'.substr($re['d'],4,2).'-'.((substr($re['d'],6,2)+1)<10?
                       '0'.(substr($re['d'],6,2)+1):(substr($re['d'],6,2)+1));
           }
             console_log($dateend);
           $arts=Db::name('article')
               ->where('article_status',1)
               ->whereTime('article_pushdate','between',[$datestart,$dateend])
               ->order('article_modified desc,  article_pushdate  desc')
               ->paginate()
               ->each(function ($item,$key){
                   $tagsId = home::getTagsId($item['article_id']);
                   $goodsAdd = 0;
                   $tags = array();
                   $isgoods = Cookie::get('artgoods');
                   foreach ($tagsId as $t) {
                       $tags += array(
                           $t['tags_id'] => array(
                               'tags_content' => home::getTagsName($t['tags_id']),)
                       );
                   }
                   foreach ($isgoods as $goods) {
                       if ($goods == $item['article_id']) {
                           $goodsAdd = 1;
                       }
                   }

                   $item['article_bre'] = sub_str($item['article_text'],  $GLOBALS[ 'bretextcount']);
                   $item['article_type'] = home::getTypeName($item['article_typeid']);
                   $item['tags']=$tags;
                   $item['article_isgoods'] = $goodsAdd;
                   $item['article_commentCount'] =  home::getArtCount($item['article_id']);
                   $item['article_pushdate'] =   date("Y-m-d",strtotime($item['article_pushdate']));
                   return $item;

               });
           $tagName=home::getTagsName($re['tagid']);
       }

        $menu = $this->getMenu();
        $page=$arts->render();

        /**
         * 文章推荐
         */
        //最新
        $newestArt=home::newestArt();
        //随机
        $randomtArt=home::randomArt();
        //热度推荐
        $hotArt=home::hotArt();

        //标签

        $taglist=home::getTags();
//        console_log($newestArt);
//        console_log($randomtArt);
//        console_log($hotArt);
        $webconf=admin::getWebConf();
        $artdays=home::getArtDays();
//         console_log($artdays);
        $this->assign('menulist', $menu);
        $this->assign('article', $arts);
        $this->assign('newestArt', $newestArt);
        $this->assign('randomtArt', $randomtArt);
        $this->assign('hotArt', $hotArt);
        $this->assign('page', $page);
        $this->assign('flag', $flag);
        $this->assign('menuname', $menuName);
        $this->assign('typename', $typeName);
        $this->assign('keyword', $keyword);
        $this->assign('tagName', $tagName);
        $this->assign('taglist',$taglist);
        $this->assign('webtitle',$webconf['webconf_title']);
        $this->assign('desc',$webconf['webconf_descriptions']);
        $this->assign('webconf',$webconf);
        $this->assign('artdays',json_encode($artdays));
        $this->assign('foot',$this->getFoots());
        $this->assign('webrecord',$this->getWebrecord());
        $this->assign('author',$webconf['webconf_author']);
        //找不到文章->
        if (sizeof($arts)==0){
            return $this->fetch('notfound');

        }
        return $this->fetch('index');
    }


    public $reply = array();

    function getReplyComments($comId)
    {

        $replys = home::getReplyComment($comId);
        $commgoods = Cookie::get('commgoods');
        foreach ($replys as $re) {
//                console_log($re);
            $isgoods = 0;
            $parName = home::getCommentUser($re['comment_parent']);
            foreach ($commgoods as $commgood) {
                if ($re['comment_id'] == $commgood) {
                    $isgoods = 1;
                }
            }
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
                    'comment_isgoods' => $isgoods,

                )

            );
            $this->getReplyComments($re['comment_id']);
        }
    }

//Error establishing a database connection du e be be
    function detail(Request $request)
    {
        $goodsAdd = 0;
        $menu = $this->getMenu();
        $art = $request->param();
        $article = home::getArticle($art['id']);
        $artcontext= preg_replace("/<!DOCTYPE html><html><head><\/head><body>/i", "<div>", $article['article_content']);
         $artcontext= preg_replace("/<\/body><\/html>/i", "</div>",  $artcontext);
        $typeid=$article['article_typeid'];
        $nav=array();
        if ($typeid>0){
            $currentMenu = home::getCurentMenu($typeid);
            $type=home::getTypeName($typeid);
            $nav[0] = array(
                'name'=>$currentMenu['menu_name'],
                'href'=>'index?m='.$currentMenu['menu_id']
            );
            $nav[1] = array(
                'name'=>$type['types_content'],
                'href'=>'index?m='.$currentMenu['menu_id'].'&&?t='.$type['types_id']
            );
            $typecon=$type['types_content'];

        }else if ($typeid==0){
           $nav=null;
           $type='暂无类别';
        }else{
            $currentMenu=home::getTypeName($typeid);
            $nav[1] = array(
                'name'=>$currentMenu['menu_name'],
                'href'=>'index?m='.$currentMenu['menu_id']
            );
            $typecon=$currentMenu['menu_name'];

        }


        $comment = home::getComment($art['id']);
        $commentLastId = home::getCommentLastId();
//        console_log( $commentLastId );
//        console_log($typecon);
//        console_log($currentMenu);
        $tags = array();
        $tagsId = home::getTagsId($article['article_id']);
        foreach ($tagsId as $t) {
            $tags += array(
                $t['tags_id'] => array(
                    'tags_content' => home::getTagsName($t['tags_id']),)
            );
        }
        $commentlist = array();
//        console_log($this->reply);
        $commgoods = Cookie::get('commgoods');

        $floors = 1;
        foreach ($comment as $v) {

            if ($v['comment_parent'] == -1) {
                $this->reply = array();
                $replys = home::getReplyComment($v['comment_id']);
                $this->getReplyComments($v['comment_id']);
//                console_log($reply);
                $isgoods = 0;
                $commgoods = Cookie::get('commgoods');
                foreach ($commgoods as $commgood) {
                    if ($v['comment_id'] == $commgood) {
                        $isgoods = 1;
                    }
                }
                $commentlist += array(
                    $v['comment_id'] => array(
//                $re['comment_id ']  =>array(
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
                        'comment_floors' => $floors++,
                        'comment_reply' => $this->reply,
                        'comment_isgoods' => $isgoods,
                    )

                );
            }

        }
//        foreach ($commentlist as $value) {
//            console_log($value['comment_isgoods'] . "id" . $value['comment_id']);
//        }

//console_log($commentRoot);
//        console_log($request->param());
//
//        console_log($tags);
        $isgoods = Cookie::get('artgoods');
     //   console_log($isgoods);
        foreach ($isgoods as $v) {
            if ($v == $article['article_id']) {
                $goodsAdd = 1;
            }

        }
        $artdays=home::getArtDays();
        /**
         * 文章推荐
         */
        //最新
        $newestArt=home::newestArt();
        //随机
        $randomtArt=home::randomArt();
        //热度推荐
        $hotArt=home::hotArt();
        $taglist=home::getTags();
        $webconf=admin::getWebConf();
//        console_log($goodsAdd);
//        console_log($commentLastId['comment_id']);
      //  console_log($typecon);
        $this->assign('menulist', $menu);
        $this->assign('nav', $nav);
        $this->assign('typecon', $typecon);
        $this->assign('menuname', $currentMenu);
        $this->assign('commentlist', $commentlist);
        $this->assign('articleCon', $article);
        $this->assign('tags', $tags);
        $this->assign('artcontext', $artcontext);
        $this->assign('goodsAdd', $goodsAdd);
        $this->assign('goodsAdd', $goodsAdd);
        $this->assign('webconf', $webconf);
        $this->assign('artdays',json_encode($artdays));
        $this->assign('webtitle', $article['article_title']);
        $this->assign('desc', $article['article_title']);
        $this->assign('commlastid', $commentLastId['comment_id']);
        $this->assign('foot',$this->getFoots());
        $this->assign('newestArt', $newestArt);
        $this->assign('randomtArt', $randomtArt);
        $this->assign('taglist',$taglist);
        $this->assign('hotArt', $hotArt);
        $this->assign('webrecord',$this->getWebrecord());
        $this->assign('author',$article['article_author']);

        if (sizeof($art['id'])==0||$article==null){
            return $this->fetch('notfound');

        }
        return $this->fetch('detail');
    }

    function goodsAdd()
    {

        $artId = json_decode($_POST['data'], true);
//        console_log($artId);
        $result = home::goodsAdd($artId['article_id']);
        $artgoods = Cookie::get('artgoods');

        if ($result) {
            $i = sizeof($artgoods);
            $artgoods[$i] = $artId['article_id'];
//            $art_goods['isgoods']=1;
            Cookie::set('artgoods', $artgoods);
        }


        return $result;

    }

    function addComment()
    {
        $data = json_decode($_POST['data'], true);
        $result = home::addComment($data);
        return json($result);

    }

    function addCommentGoods()
    {
        $comId = json_decode($_POST['data'], true);
        $result = home::addCommentGoods($comId['comId']);
        $commgoods = Cookie::get('commgoods');

        if ($result) {
            $i = sizeof($commgoods);
            $commgoods[$i] = $comId['comId'];
            Cookie::set('commgoods', $commgoods);
        }
        return $result;

    }

    function addPv(Request $request)
    {
        $artid = $request->param();
        $result = home::addPv($artid['id']);

    }

    //文章预览
    function articlePreview(Request $request){
        $menu = $this->getMenu();
        $webconf=admin::getWebConf();
        //最新
        $newestArt=home::newestArt();
        //随机
        $randomtArt=home::randomArt();
        //热度推荐
        $hotArt=home::hotArt();
        $taglist=home::getTags();
        $artdays=home::getArtDays();
        $article['title']=$_POST['title'];
        $article['author']=$_POST['author'];
        $article['content']=$_POST['content'];
        $article['type']=$_POST['type'];
        $this->assign('menulist',$menu);
        $this->assign('article',$article);
        $this->assign('foot',$this->getFoots());
        $this->assign('webrecord',$this->getWebrecord());
        $this->assign('taglist',$taglist);

        $this->assign('newestArt', $newestArt);
        $this->assign('randomtArt', $randomtArt);
        $this->assign('hotArt', $hotArt);
        $this->assign('webconf', $webconf);
        $this->assign('webtitle', $article['title']);
        $this->assign('desc', $article['title']);
        $this->assign('artdays',json_encode($artdays));
        return $this->fetch('articlePreviewl');
    }
}


