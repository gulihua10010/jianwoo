<?php
/**
 * Created by PhpStorm.
 * User: 顾力华
 * Date: 2018/7/7
 * Time: 14:26
 */

namespace app\model;


use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\exception\PDOException;
use think\Model;
use think\Session;

class admin extends Model
{
    /**管理员注册
     * @param $useranme 用户名
     * @param $password 密码
     * @return bool 注册结果
     */
    static function register($useranme, $password)
    {
        $where['admin_username'] = $useranme;
        $where['admin_password'] = md5($password);
        print_r($where);
        $reg_result = Db::name('admin')->insert($where);
        if ($reg_result) {
            Session::set('islogin', 1);
            return true;
        } else {
            return false;
        }
    }

    /**管理员登录
     * @param $username 用户名
     * @param $password 密码
     * @param $ip 登录ip
     * @return bool 登录结果
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function login($username, $password, $ip,$date)
    {
        $login['admin_username'] = $username;
        $login['admin_password'] = md5($password);
        $where['admin_ip'] = $ip;
        $where['admin_lastlogintime'] = $date;
        //print_r($where);
        $lg_result = Db::name('admin')->where($login)->find();
        if ($lg_result) {
            Db::name('admin')->where($login)->update(['admin_ip'=>$ip,'admin_lastlogintime'=>$date]);
            return true;
        } else {
            return false;
        }
    }

//   static function  articleInsert($title,$author,$content,$typeid,$goods=0,$commentcount=0,$iscomment,$isdel,$publics,$password){
//        $where['article_author']=$author;
//        $where['article_title']=$title;
//        $where['article_content']=$content;
//        $where['article_typeid']=$typeid;
//        $where['article_goodscount']=$goods;
//        $where['article_comment_count']=$commentcount;
//        $where['article_iscomment']=$iscomment;
//        $where['article_isdel']=$isdel;
//        $where['article_public']=$publics;
//
//    }
    /**文章写入数据库
     * @param $data 文章数据
     * @return int|string 结果
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function articleSubmit($data, $artText, $status)
    {
        $date = date("Y-m-d H:i:s");
        $where['article_author'] = $data['author'];
        $where['article_title'] = $data['title'];
        $where['article_content'] = $data['articleContent'];
        $where['article_text'] = $artText;
        $where['article_typeid'] = $data['type']==null ? 0 : $data['type'];
        $where['article_iscomment'] == null ? 1 : $data['iscomment'];
        $where['article_public'] == null ? 1 : $data['published'];
        $where['article_pushdate'] = $date;
        $where['article_modified'] = $date;
        $where['article_imgSrc'] = $data['imgSrc'];
        $where['article_readcount'] = 0;
        $where['article_goodscount'] = 0;
        $where['article_status'] = $status;
        $where['article_comment_count'] = 0;
        if ($data['published'] == -1) {
            $where['article_password'] = $data['password'];
        }
        $result = Db::name('article')->insert($where);

        if ($result) {
            foreach ($data['tags'] as $v) {
                $tags['tags_id'] = $v;
                $art = Db::name('article')->where('article_pushdate', $date)->find();
                $tags['article_id'] = $art['article_id'];
                Db::name('article_tags')->insert($tags);
            }

        }

        return $result;

    }

//   static function insertTags($date,$tag)
//    {  $art=Db::name('article')->where('article_pushdate',$date);
//        $tags['tags_id']= $tag;
//        $tags['article_id']=$art['article_id'];
//        $result=Db::name('article_tags')->insert($tags);
//        return $result;
//    }

    //菜单
    /**获取菜单并对索引排序
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getmenuMg()
    {
        $result = Db::name('menu')->order('menu_index')->select();

//        print_r($result);

        return $result;

    }

    /**获取菜单数量
     * @return int|string
     */

    static function menuCount()
    {
        $count = Db::name('menu')->count();
        return $count;
    }

    /**添加菜单
     * @param $menuName 菜单名
     * @return bool 结果
     */
    static function addmenuMg($menuName)
    {
        $where['menu_name'] = $menuName;
        $count = Db::name('menu')->count();
        $where['menu_index'] = $count + 1;
        $result = Db::name('menu')->insert($where);


        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $menuId
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    static function menuDel($menuId)
    {
        $where['menu_id'] = $menuId['menuId'];
        $result = Db::name('menu')->where($where)->delete();

        if ($result) {
                sqlExecute('update    article as a, (select  article_id  from  article where article_typeid='.($menuId['menuId']*-1).' )   as b  
                    set   article_typeid =0  where  a.article_id =b.article_id');
            sqlExecute('update   article as a, 
                    (  select  article_id  from  article where article_typeid in 
                        (select types_id from  types  where menu_id='.$menuId['menuId'].')            
                        )  as b  set  a.article_typeid =0  where  a.article_id =b.article_id');
            Db::name('types')->where($where)->delete();
//              $aid=Db::name('article')->where('article_typeid',($menuId['menuId']*(-1)))->field('article_id')->select();
//              Db::name('article')->where('article_id','in',$aid['article_id'])->update(['article_typeid'=>0]);
//              $typeid=Db::name('types')->where('menu_id', $menuId['menuId'])->field('types_id  ')->select();
//            $aid1=Db::name('article')->where('article_typeid','in',$typeid['types_id'])->field('article_id')->select();
//            Db::name('article')->where('article_id','in',$aid1['article_id'])->update(['article_typeid'=>0]);
            return true;
        } else {
            return false;
        }
    }

    /**菜单重新排序
     * @param $index
     * @return int|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    static function menuUpdateIndex($index)
    {
//        $index=json_encode($index,JSON_UNESCAPED_SLASHES);
//        $index=stripslashes($index);
        $j = 0;
//        $wh['test']=$index['beforesort'];
//        Db::name('test')->insert($wh);
        $count = Db::name('menu')->count();
        for ($i = 0; $i < $count; $i++) {
//            if(intval($index['beforesort'][$i])!=intval($index['endsort'][$i])){
//                $j++;
//                $menuId=Db::name('menu')->where('menu_index',intval($index[$i]))->select();
            $result = Db::name('menu')->where('menu_id', intval($index['update'][$i]))->update(['menu_index' => $i + 1]);

//            }
        }
        return $result;
//        if($result){
//            return true;
//        }else{
//            return false;
//        }

    }

    /**更新菜单名字
     * @param $data 菜单数据
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    static function updateMenuName($data)
    {
        $result = Db::name('menu')->where('menu_id', ($data['menu_id']))->update(['menu_name' => $data['menu_name']]);
        if ($result) {
            return true;
        } else {
            return false;
        }


    }

//类别

    /**获取类别
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getType()
    {
        $result = Db::name('types')->select();

//        print_r($result);
        return $result;
    }

    /**获取类别数量
     * @return int|string
     */
    static function typeCount()
    {
        $count = Db::name('types')->count();
        return $count;
    }

    /**添加类别
     * @param $typeName
     * @param $menuId
     * @return bool
     */
    static function addType($typeName, $menuId)
    {
        $where['types_content'] = $typeName;
        $where['menu_id'] = $menuId;
        $result = Db::name('types')->insert($where);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**删除类别
     * @param $typeId
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    static function typeDel($typeId)
    {
        $where['types_id'] = $typeId['typeId'];
        $result = Db::name('types')->where($where)->delete();
//        Db::execute("alter table tags auto_increment=".$typeId['typeId'].";");
        if ($result) {
            sqlExecute('update    article as a, (select `article_id` from  article where article_typeid='.$typeId['typeId'].' )   as b  
          set   article_typeid =0  where  a.article_id =b.article_id');
            return true;
        } else {
            return false;
        }
    }

    /**更新类别名
     * @param $data
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    static function updateTypeName($data)
    {
        $result = Db::name('types')->where('types_id', ($data['types_id']))->update(['types_content' => $data['types_content']]);
        if ($result) {
            return true;
        } else {
            return false;
        }


    }

    /**添加标签
     * @param $tagsName
     * @return bool
     */
    static function addtagsresult($tagsName)
    {
        $where['tags_content'] = $tagsName;
        $result = Db::name('tags')->insert($where);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**获取标签
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getTags()
    {
        $result = Db::name('tags')->select();
        return $result;
    }

    /**标签删除
     * @param $tagsId 标签id
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    static function tagsDel($tagsId)
    {
        $where['tags_id'] = $tagsId['tagsId'];
        $result = Db::name('tags')->where($where)->delete();
//        Db::execute("alter table tags auto_increment=".$tagsId['tagsId'].";");
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**获取文章
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getArts()
    {
        $result = Db::name('article')->select();
        return $result;
    }

    /***获取评论数量
     * @return int|string
     */
    static function getArtComm($artid)
    {
        $result = Db::name('comment')->where('article_id', $artid)->count();
        return $result;
    }

    /**根据类别id获取类别
     * @param $typeId 类别id   0:没有类别  正数 ：子类别  负数：菜单类别
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getTypeName($typeId)
    {
        if ($typeId>0){

            $result = Db::name('types')->where('types_id', $typeId)->find();
        }else if ($typeId<0){
            $result = Db::name('menu')->where('menu_id', $typeId*-1)->find();
        }
        return $result;
    }

    /**移动至回收站
     * @param $artid
     * @return int|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    static function recycle($artid)
    {
        $date = date("Y-m-d H:i:s");
        $result = Db::name('article')->where('article_id', $artid)->update(['article_status' => -1, 'article_deldate' => $date]);
        Db::name('comment')->where('article_id', $artid)->update(['comment_artdel' => -1]);
        return $result;
    }

    /**删除文章
     * @param $artid
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    static function delArt($artid)
    {
        $result = Db::name('article')->where('article_id', $artid)->delete();
//        Db::execute("alter table article auto_increment=$artid;");
        $resultTags = Db::name('article_tags')->where('article_id', $artid)->delete();
        $resultcomm = Db::name('comment')->where('article_id', $artid)->delete();
        return $result;


    }

    /**移动至草稿
     * @param $artid
     * @return int|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    static function removeDraft($artid)
    {
        $result = Db::name('article')->where('article_id', $artid)->update(['article_status' => 0]);
        return $result;
    }

    /**文章发布
     * @param $artid
     * @return int|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    static function published($artid)
    {
        $result = Db::name('article')->where('article_id', $artid)->update(['article_status' => 1]);
        return $result;
    }

    /**统计全部文章数量
     * @return int|string
     */
    static function countAll()
    {
        $result = Db::name('article')->whereNotIn('article_status', -1)->count();
        return $result;
    }

    /**统计发布文章数量
     * @return int|string
     */
    static function countpub()
    {
        $result = Db::name('article')->where('article_status', 1)->count();
        return $result;
    }

    /**统计草稿文章数量
     * @return int|string
     */
    static function countdraft()
    {
        $result = Db::name('article')->where('article_status', 0)->count();
        return $result;
    }

    /**huq获取文章标签pg
     * @param $artId
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getArtTags($artId)
    {
        $result = Db::name('article_tags')->where('article_id', $artId)->select();

        return $result;
    }

    /**获取标签名
     * @param $artId
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getTagsName($tagsId)
    {
        $result = Db::name('tags')->where('tags_id', $tagsId)->find();
        return $result['tags_content'];
    }

    /**快速更新文章
     * @param $data
     * @return int|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    static function udArtBaseInfo($data)
    {
        $artresult = Db::name('article')->where('article_id', $data['artid'])
            ->update(['article_title' => $data['title'],
                'article_author' => $data['author'],
                'article_public' => $data['ispublic'],
                'article_password' => $data['pwd'],
                'article_typeid' => $data['type'],
                'article_iscomment' => $data['iscomment']]);
        Db::name('article_tags')->where('article_id', $data['artid'])->delete();
        foreach ($data['tags'] as $v) {
            $tags['tags_id'] = $v;
            $tags['article_id'] = $data['artid'];
            Db::name('article_tags')->insert($tags);
        }
        return $artresult;
    }

    /**根据文章id获取文章
     * @param $artId 文章id
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getArticle($artId)
    {
        $result = Db::name('article')->where('article_id', $artId)->find();
        return $result;
    }

    /**文章更新
     * @param $data 文章post数据
     * @param $artText 文章纯文本
     * @param $artStatus 文章状态 1已发布 0 草稿
     * @return int|string
     */
    static function artUpdate($data, $artText, $artStatus)
    {

        $date = date("Y-m-d H:i:s");;
        try {
            $artresult = Db::name('article')->where('article_id', $data['artid'])
                ->update(['article_title' => $data['title'],
                    'article_author' => $data['author'],
                    'article_public' => $data['published'],
                    'article_password' => $data['password'],
                    'article_typeid' => $data['type'],
                    'article_content' => $data['articleContent'],
                    'article_iscomment' => $data['iscomment'],
                    'article_imgSrc' => $data['imgSrc'],
                    'article_text' => $artText,
                    'article_modified' => $date,
                    'article_status' => $artStatus,
                ]);
        } catch (PDOException $e) {
        } catch (Exception $e) {
        }
        try {
            Db::name('article_tags')->where('article_id', $data['artid'])->delete();
        } catch (PDOException $e) {
        } catch (Exception $e) {
        }
        foreach ($data['tags'] as $v) {
            $tags['tags_id'] = $v;
            $tags['article_id'] = $data['artid'];
            Db::name('article_tags')->insert($tags);
        }
        return $artresult;
    }

    /**根据文章id获取评论
     * @param $artId
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getComment($artId)
    {
        $result = Db::name('comment')->where('article_id', $artId)->select();
        return $result;
    }

    /**获取评论用户名
     * @param $commId
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getCommentUser($commId)
    {
        $result = Db::name('comment')->where('comment_id', $commId)->find();
        return $result;
    }

    /**根据父评论获取回复评论
     * @param $commentId
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getReplyComment($commentId)
    {
        $result = Db::name('comment')->where('comment_parent', $commentId)->select();
        return $result;


    }

    /**
     * @param $data
     * @return int
     * @throws Exception
     * @throws PDOException
     */
    static function delComment($commid)
    {
        $result = Db::name('comment')->where('comment_id', $commid)->delete();
        return $result;
    }

    /**获取所有评论
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getAllComment()
    {

        $comms = Db::name('comment')->select();
        return $comms;
    }

    /**获取文章标题
     * @param $artid
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getArtTitle($artid)
    {
        $result = Db::name('article')->where('article_id', $artid)->field('article_title')->find();
        return $result['article_title'];
    }

    /**获取最后评论id
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getLastCommId()
    {
        $result = Db::name('comment')->order('comment_id desc')->limit(1)->find();
        return $result;
    }

    /**回收站恢复
     * @param $artid
     * @return int|string
     * @throws Exception
     * @throws PDOException
     */
    static function restore($artid)
    {
        $result = Db::name('article')->where('article_id', $artid)->update(['article_status' => 0]);
        return $result;
    }

    /**回收站的文章
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function recycleBin()
    {
        $result = Db::name('article')->where('article_status', -1)->select();
        return $result;
    }

    /**
     * 文章数量
     */
    static function getArtsCount()
    {
        $result = Db::name('article')->whereNotIn('article_status', -1)->count();
        return $result;
    }

    /**草稿数量
     * @return int|string
     */
    static function getDraftsCount()
    {
        $result = Db::name('article')->where('article_status', 0)->count();
        return $result;
    }

    /**标签数量
     * @return int|string
     */
    static function getTagsCount()
    {
        $result = Db::name('tags')->count();
        return $result;
    }

    /**评论数量
     * @return int|string
     */
    static function getCommCount()
    {
        $result = Db::name('comment')->whereNotIn('comment_artdel', -1)->count();
        return $result;
    }

    /**最近十篇文章
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function recentPub()
    {
        $result = Db::name('article')->where('article_status', 1)->order('article_pushdate desc')->limit(10)->select();
        return $result;
    }

    /**最近十篇草稿
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function recentDraft()
    {
        $result = Db::name('article')->where('article_status', 0)->order('article_pushdate desc')->limit(10)->select();
        return $result;
    }

    /**最近评论
     * @return false|\PDOStatement|string|\think\Collection
     */
    static function recentComm()
    {
        try {
            $result = Db::name('comment ')->alias('c')
                ->join('article a', 'a.article_id=c.article_id', 'LEFT')
                ->whereNotIn('comment_artdel', -1)->order('comment_date desc')
                ->limit(10)
                ->field('a.article_id,article_title ,comment_user,comment_content,comment_date')
                ->select();
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
        return $result;
    }

    /**最近访问
     * @return false|\PDOStatement|string|\think\Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    static function getvisiters()
    {
        $result = Db::name('visit')->alias('v')->join('article a', 'a.article_id=v.article_id', 'LEFT')->order('visit_date desc')
            ->field('visit_ip,a.article_title,visit_date,a.article_id')->limit(10)->select();
        return $result;
    }

    /**评论未读评论数
     * @return int|string
     */
    static function getNotReadComms()
    {
        $notReadCommsCount = Db::name('comment')
            ->alias('c')
            ->whereNotIn('comment_artdel', -1)
            ->whereNotIn('comment_artdel', '作者:')
            ->where('comment_isread', 0)->count();
        return $notReadCommsCount;
    }

    /**设置网站信息
     * @param $data
     * @return int|string
     */
    static function webConSub($data)
    {

            $result = Db::name('webconf')->where('webconf_id', 1)
                ->update(['webconf_title' => $data['title'],
                    'webconf_author' => $data['author'],
                    'webconf_keywords' => $data['keywords'],
                    'webconf_descriptions' => $data['description'],
                    'webconf_record' => $data['record'],
                    'webconf_domain' => $data['domail'],
                    'webconf_foothtml' => $data['footer'],
                    'webconf_logo' => $data['logoimgsrc'],
                    'webconf_iscomment' => $data['iscomment'],
                    'webconf_page' => $data['page'],
                    'webconf_indextopimg'=>$data['topimgsrc'],
                    'webconf_indexartbreco'=>$data['textcount']
                ]);



        return $result;

    }

    static function getWebConf()
    {
        try {
            return Db::name('webconf')->where('webconf_id', 1)->find();
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }

    }
}