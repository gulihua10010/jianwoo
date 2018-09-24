<?php
/**
 * Created by PhpStorm.
 * User: 顾力华
 * Date: 2018/7/21
 * Time: 22:14
 */

namespace app\model;


use think\Db;
use think\Model;

class home extends Model
{
    /**获取菜单列表
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getMenuList()
    {
        $result = Db::name('menu')->order('menu_index')->select();

//        print_r($result);
        return $result;
    }

    /**获取菜单子类别
     * @param $menuId
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getTypelist($menuId)
    {
        $result = Db::name('types')->where('menu_id', $menuId)->select();
        return $result;
    }

    /***获取文章
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getArticleBre()
    {
        $result = Db::name('article')->select();
        return $result;
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

    /**根据类别id获取当前菜单
     * @param $typeId
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getCurentMenu($typeId)
    {
        $menuId = Db::name('types')->where('types_id', $typeId)->find();
        $menuName = Db::name('menu')->where('menu_id', $menuId['menu_id'])->find();

        return $menuName ;
    }

    /**根据菜单id获取菜单名
     * @param $typeId
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getMenuName($menuid)
    {
        $menuName = Db::name('menu')->where('menu_id', $menuid)->find();

        return $menuName;
    }

    /**根据文章id获取标签
     * @param $artId 标签id
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getTagsId($artId)
    {
        $result = Db::name('article_tags')->where('article_id', $artId)->select();
        return $result;
    }

    /**根据标签id获取标签名
     * @param $tagsId
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getTagsName($tagsId)
    {
        $result = Db::name('tags')->where('tags_id', $tagsId)->find();
        return $result['tags_content'];
    }

    /**赞+1
     * @param $artId
     * @return int|string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    static function goodsAdd($artId)
    {
        $goodsCount = Db::name('article')->where('article_id', $artId)->find();
        $result = Db::name('article')->where('article_id', $artId)->update(['article_goodscount' => $goodsCount['article_goodscount'] + 1]);
        return $result;
    }

    /**添加评论
     * @param $data post数据
     * @return int|string
     */
    static function addComment($data)
    {
        $where['article_id'] = $data['artId'];
        $where['comment_user'] = $data['username'];
        $where['comment_ip'] = get_client_ip(0);;
        $where['comment_date'] = date("Y-m-d H:i:s");
        $where['comment_content'] = $data['commenttext'];
        $where['comment_goodscount'] = 0;
        $where['comment_qq'] = $data['qq'];
        $where['comment_isread'] = 0;
        $where['comment_parent'] = $data['comment_parent'];
//        $where['comment_headimg']=SCRIPT_DIR.'/static/headimg/'.rand(1,10).'.jpg';
        $where['comment_headimg'] = $data['headimgUrl'];
        $result = Db::name('comment')->insert($where);
        $commCount = Db::name('article')->where('article_id', $data['artId'])->field('article_comment_count')->find();
        Db::name('article')->where('article_id', $data['artId'])->update(['article_comment_count' => $commCount['article_comment_count'] + 1]);
        return $result;

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

    /**评论赞
     * @param $commId 评论id
     * @return int|string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */

    static function addCommentGoods($commId)
    {
        $comm = Db::name('comment')->where('comment_id', $commId)->find();
        $result = Db::name('comment')->where('comment_id', $commId)->update(['comment_goodscount' => $comm['comment_goodscount'] + 1]);
        return $result;
    }

    /***获取评论最后一个id
     * @return int|string
     */
    static function getCommentLastId()
    {
        $result = Db::name('comment')->order('comment_id desc')->limit(1)->find();
        return $result;
    }

    /**根据文章id获取文章评论
     * @param $artId
     * @return int|string
     */
    static function getArtCount($artId)
    {
        $result = Db::name('comment')->where('article_id', $artId)->count();
        return $result;

    }

    /**文章访问量
     * @param $artId
     * @return int|string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    static function addPv($artId)
    {   $date=date('Y-m-d H:i:s');
        $where['visit_ip'] = get_client_ip(0);
        $where['article_id'] = $artId;
        $where['visit_date'] = $date;
        $art = Db::name('article')->where('article_id', $artId)->find();
        $result = Db::name('article')->where('article_id', $artId)->update(['article_readcount' => $art['article_readcount'] + 1]);
        Db::name('visit')->insert($where);
        return $result;
    }

    /**根据类型获取文章
     * @param $type
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getTypeArt($type)
    {

        $art = Db::name('article')->where('article_typeid', $type)->select();
        return $art;
    }

    /**根据菜单获取子菜单类别
     * @param $menu
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getMenuArt($menu)
    {
        $types = Db::name('types')->where('menu_id', $menu)->field(['types_id'])->select();
        return $types;
    }

    /**根据关键词搜索文章
     * @param $keyword
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function searchArt($keyword)
    {
        $art = Db::name('article')->where('article_status',1)->where('article_text', 'like', '%' . $keyword . '%')
            ->whereOr('article_title', 'like', '%' . $keyword . '%')->select();
        return $art;
    }

    static function newestArt()
    {
        $art = Db::name('article')->where('article_status', 1)
            ->order('article_pushdate desc')
            ->limit(0, 10)
            ->field('article_id,article_title')
        ->select();
        return $art;
    }

    static function hotArt()
    {
        $art = Db::name('article')->where('article_status', 1)
            ->order('article_comment_count desc,article_goodscount desc,article_readcount desc,article_pushdate desc')
            ->limit(0, 10)
            ->field('article_id,article_title')
            ->select();
        return $art;
    }

    static function randomArt()
    {

        $artLastId = Db::name('article')->order('article_id desc')->limit(1)->find();
        $artLastId = $artLastId['article_id'];
        $artids = array();
        $artCount = Db::name('article')->where('article_status', 1)->count();
        $n = 10;
        if ($artCount < 10) {
            $n = $artCount;
        }
        for ($i = 0; $i < $n; $i++) {
            $artid = rand(1, $artLastId);
            $arttemp = Db::name('article')->where('article_id', $artid)->find();
//            $artidtemp=Db::name('article')->where('article_id',$artid)->find();
//            console_log(!$arttemp);

            if (!$arttemp || $arttemp['article_status'] == -1||$arttemp['article_status']==0) {
                $i--;

                continue;
            }
            $artids[$i] = $artid;
            for ($j = 0; $j < $i; $j++) {
                if ($artids[$i] == $artids[$j]) {
                    break;
                }
            }
            if ($j < $i) {
                $i--;
                continue;
            }
        }
        $art = Db::name('article')
            ->where('article_id', 'in', $artids)
            ->limit(0, 10)
            ->field('article_id,article_title')
            ->select();
        return $art;
    }

    /**获取所有标签
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getTags()
    {
//        $tsql = Db::name('article_tags')
//            ->alias('at')
//            ->join('tags t', 'at.tags_id=t.tags_id')
//            ->select(false);

        $result=sqlQuery('select article_id,a.tags_id,tags_content ,count(*) as count from article_tags a,tags t 
where  a.tags_id=t.tags_id group by a.tags_id');
        return $result;
    }

    /**获取文章标签
     * @param $tagid
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getTagsArts($tagid)
    {
        $result = Db::name('article_tags')->where('tags_id', $tagid)->select();
        return $result;
    }

    /**获取首页文章缩略字数
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static function getArtBreTextCount()
    {
        $result = Db::name('webconf')->limit(1)->find();
        return $result['webconf_indexartbreco'];
    }


    /**
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    static  function  getArtDays(){
        $result=Db::name('article')->field('article_id, article_pushdate')->select();
        return $result;

    }
    static  function  getFoots(){
        $result = Db::name('webconf')->limit(1)->find();
        return $result['webconf_foothtml'];
    }

    static function getWebrecord()
    {
        $result = Db::name('webconf')->limit(1)->find();
        return $result['webconf_record'];
    }

}
