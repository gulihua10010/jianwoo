<?php
/**
 * Created by PhpStorm.
 * User: 顾力华
 * Date: 2018/6/29
 * Time: 16:14
 * zidong
 */

namespace app\admin\controller;


use app\model\admin;
use think\Controller;
use think\Request;

class adminController extends Controller
{
    public function __construct(Request $request = null)
    {
        header("content-type:text/html;charset:UTF-8");
        parent::__construct($request);

    }

    function setMemuList($cursubmenu = '')
    {
        $menulist = $this->getMenulist();
        $notReadComms=admin::getNotReadComms();
//        console_log($notReadComms);

            $this->assign('menulist', $menulist);
          $this->assign('cursubmenu', $cursubmenu);
        $this->assign('notreadcm', $notReadComms);


    }


    function getMenulist()
    {
        $menulist = array(
            'index' => array(
                'name' => 'index',
                'text' => '首页',
                'icon' => '&#xe601;',
                'submenu' => array(
                    array('name' => 'index', 'text' => '首页', 'url' => 'index', 'icon' => '&#xe601;'),
                    array('name' => 'dynamic', 'text' => '动态', 'url' => 'dynamic?v=1', 'icon' => '&#xe616;')
                )
            ),
            'articleManagement' => array(
                'name' => 'articleMangement',
                'text' => '文章管理',
                'icon' => '&#xe605;',
                'submenu' => array(
                    array('name' => 'articlePublished', 'text' => '文章发布', 'url' => 'articlePublished', 'icon' => '&#xe636; '),
                    array('name' => 'myArticle', 'text' => '我的文章', 'url' => 'myArticle?m=all', 'icon' => '&#xe633;'),
                    array('name' => 'commentManagement', 'text' => '评论管理', 'url' => 'commentManagement', 'icon' => '&#xe535;'),
                    array('name' => 'articleTags', 'text' => '文章标签', 'url' => 'articleTags', 'icon' => '&#xe6b1;'),
                    array('name' => 'recycleBin', 'text' => '文章回收站', 'url' => 'recycleBin', 'icon' => '&#xe661;')

                )
            ),
            'menuManagement' => array(
                'name' => 'menuManagement',
                'text' => '菜单管理',
                'icon' => '&#xe665;',
                'submenu' => array(
                    array('name' => 'menuMg', 'text' => '菜单管理', 'url' => 'menuMg', 'icon' => '&#xe665;')
                )
            ),
//            'moduleManagement' => array(
//                'name' => 'moduleManagement',
//                'text' => '模块管理',
//                'icon' => '&#x34a9;',
//                'submenu' => array(
//                    array('name' => 'userModule', 'text' => '用户模块', 'url' => 'userModule', 'icon' => '&#x34a9;'),
//
//                )
//            ),
            'systemManagement' => array(
                'name' => 'systemManagement',
                'text' => '系统管理',
                'icon' => '&#xe602;',
                'submenu' => array(
                    array('name' => 'webConfig', 'text' => '网站配置', 'url' => 'webConfig', 'icon' => '&#xe60b;'),
//                    array('name' => 'pptManagement', 'text' => '首页幻灯片', 'url' => 'pptManagement', 'icon' => '&#xe604;'),
                    array('name' => 'clearCache', 'text' => '清除缓存', 'url' => 'clearCache', 'icon' => '&#xe61e;')

                )
            ),
//            'photoAlbum'=>array(
//                'name'=>"photoAlbn",
//                'text'=>'我的相册',
//                'icon'=>'&#xe66e;',
//                'submenu'=>array(
//                    array('name'=>"watchAlbum",'text'=>'查看相册','url'=>'watchAlbum','icon'=>'&#xe65a;'),
//                    array('name'=>'addAlbum','text'=>'添加相册','url'=>'addAlbum','icon'=>'&#xe613;'),
//                )
//            )
        );

        return $menulist;

    }

}