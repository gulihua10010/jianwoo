<?php if (!defined('THINK_PATH')) exit(); /*a:9:{s:78:"/Volumes/MS-MACOS/phpStudy/WWW/jwblog/application/index/view/index/detail.html";i:1537686066;s:80:"/Volumes/MS-MACOS/phpStudy/WWW/jwblog/application/index/view/baseView/index.html";i:1537773482;s:77:"/Volumes/MS-MACOS/phpStudy/WWW/jwblog/application/index/view/commom/menu.html";i:1537783346;s:82:"/Volumes/MS-MACOS/phpStudy/WWW/jwblog/application/index/view/commom/usermodel.html";i:1537428290;s:84:"/Volumes/MS-MACOS/phpStudy/WWW/jwblog/application/index/view/usermodel/timeball.html";i:1537428290;s:84:"/Volumes/MS-MACOS/phpStudy/WWW/jwblog/application/index/view/usermodel/calendar.html";i:1537428290;s:94:"/Volumes/MS-MACOS/phpStudy/WWW/jwblog/application/index/view/usermodel/articleRecommended.html";i:1537428290;s:83:"/Volumes/MS-MACOS/phpStudy/WWW/jwblog/application/index/view/usermodel/artTags.html";i:1537428290;s:79:"/Volumes/MS-MACOS/phpStudy/WWW/jwblog/application/index/view/commom/footer.html";i:1537428290;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $webtitle; ?></title>
    <meta name="renderer" content="webkit">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="applicable-device" content="pc,mobile">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="keywords" content="<?php echo $webconf['webconf_keywords']; ?>">
    <meta name="author" content="<?php echo $webconf['webconf_author']; ?>">
    <meta name="description" content="<?php echo $desc; ?>">
    <meta property="og:title" content="<?php echo $webtitle; ?>">
    <meta property="og:author" content="<?php echo $webconf['webconf']['author']; ?>">
    <meta property="og:url" content="<?php echo $webconf['webconf']['demain']; ?>">
    <meta property="og:site_name" content="<?php echo $webtitle; ?>">
    <meta property="og:description" content="<?php echo $desc; ?>">
    <meta name="twitter:title" content="<?php echo $webtitle; ?>">
    <meta name="twitter:description" content="<?php echo $desc; ?>">
    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="/jwblog/public/static/css/bootstrap.css">
    <script type="text/javascript" src="/jwblog/public/static/tinymce/tinymce.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/jwblog/public/static/css/prism.css">
    <link rel="stylesheet" type="text/css" href="/jwblog/public/static/layui/css/layui.css">
    <link rel="stylesheet" type="text/css" href="/jwblog/public/static/css/prism-toolbar.css">
    <link rel="stylesheet" type="text/css" href="/jwblog/public/static/css/jw_main.css">

    <script type="text/javascript" src="/jwblog/public/static/js/jquery.min.js"></script>
    <script type="text/javascript" src="/jwblog/public/static/layer/layer.js"></script>
    <script type="text/javascript" src="/jwblog/public/static/layui/layui.js"></script>
    <script type="text/javascript" src="/jwblog/public/static/js/common.js"></script>
    <script type="text/javascript" src="/jwblog/public/static/js/prism.js"></script>
    <script type="text/javascript" src="/jwblog/public/static/js/prism-line-numbers.js"></script>
    <script type="text/javascript" src="/jwblog/public/static/js/prism-toolbar.js"></script>
    <script type="text/javascript" src="/jwblog/public/static/js/clipboard.min.js"></script>
</head>
<body>
<?php if(($webconf['webconf_indextopimg']!=null)): ?>
<style>
    .menu,.main,.footer{
        position: relative;
        bottom: 50px;
    }
</style>
<div class="topimg" style="min-width: 1250px" >
    <img src="//<?php echo $webconf['webconf_indextopimg']; ?>"  width="100%" height="260px">

</div>
<?php endif; ?>
<div class="menu">
    
    <div class="index-menu">
    <div>
        <ul>

            <li onclick="window.location.href='<?php echo Url('index/index/index'); ?>'">
                <span  <?php if(($menuname['menu_name'] ==null)): ?> class="menu-activity" <?php endif; ?>>首页</span>
            </li>
            <?php if(is_array($menulist) || $menulist instanceof \think\Collection || $menulist instanceof \think\Paginator): if( count($menulist)==0 ) : echo "" ;else: foreach($menulist as $key=>$menulist): ?>
            <li>
                <span onclick="window.location.href='<?php echo Url('index/index/index',['m'=>$menulist['menu_id']]); ?>' "
                      <?php if(($menuname['menu_name'] == $menulist['menuName'])): ?> class="menu-activity" <?php endif; ?>><?php echo $menulist['menuName']; ?></span>
                <ul class="submenu">
                    <?php if(is_array($menulist['subMenu']) || $menulist['subMenu'] instanceof \think\Collection || $menulist['subMenu'] instanceof \think\Paginator): if( count($menulist['subMenu'])==0 ) : echo "" ;else: foreach($menulist['subMenu'] as $key=>$subMenu): ?>
                    <li>
                    <span onclick="window.location.href='<?php echo Url('index/index/index',['m'=>$menulist['menu_id'],'t'=>$subMenu['types_id']]); ?>' ">
                        <?php echo $subMenu['types_content']; ?>
                    </span>

                    </li>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>
    <div class="input-group  input-search">
        <input class="form-control" type="text" id="keywords" placeholder="搜索文章" style="height: 40px">
        <span class="input-group-btn" id="search-inputs">
            <button class="btn btn-default" style="height: 40px;position: relative;right: 2px;"> <i class="iconfont">&#xe615;</i> </button>
        </span>
    </div>
</div>
<script>
    // console.log(decodeURI(encodeURI('的')))

    $('#search-inputs').click(function () {
        var keywords=$('#keywords').val();
        if (keywords!=''){

            var url="<?php echo Url('index/index/index',['keyword'=>keywords]); ?>";
            url=url.replace('keywords',(keywords))
            console.log(encodeURIComponent(url))
            window.location.href=encodeURI(encodeURI(url))
        }
    });

</script>
    
</div>
<div class="main">
    <div class="main-conternt">
    
<div class="index-main">
    <div class="detail">
        <div class="position">
            <span>当前位置:<a href="<?php echo Url('index/index/index'); ?>' ">首页>></a>
                <?php if(is_array($nav) || $nav instanceof \think\Collection || $nav instanceof \think\Paginator): if( count($nav)==0 ) : echo "" ;else: foreach($nav as $key=>$nav): ?>
                    <a href="<?php echo $nav['href']; ?>"><?php echo $nav['name']; ?></a>>>
                <?php endforeach; endif; else: echo "" ;endif; ?>
                <?php echo $articleCon['article_title']; ?>
            </span>
        </div>
        <div class="art-con">
            <div class="art-det-info" id="art-det-info">
                <div><span><?php echo $articleCon['article_title']; ?></span></div>
                <div>

                    <span class="art-author"><i class="iconfont">&#xe67a;</i> :<?php echo $articleCon['article_author']; ?></span>
                    <span class="art-date"><i class="iconfont">&#xe65d;</i>:<?php echo $articleCon['article_pushdate']; ?></span>
                    <span class="art-type"><i class="iconfont">&#xe618;</i>:<?php echo $typecon; ?></span>
                    <span class="art-readers"><i class="iconfont">&#xe603;</i>:<?php echo $articleCon['article_readcount']; ?></span>

                </div>
            </div>

            <div class="art-txt">
                <?php echo $artcontext; ?>
            </div>
        </div>
    </div>
    <div class="art-foot">
        <?php if(($tags!=null)): ?>
        <div class="artd-tags">
            标签:
            <?php if(is_array($tags) || $tags instanceof \think\Collection || $tags instanceof \think\Paginator): if( count($tags)==0 ) : echo "" ;else: foreach($tags as $key=>$tags): ?>
            <span><?php echo $tags['tags_content']; ?></span>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <?php endif; ?>
        <div class="art-copyright">
            <span><b>版权声明:</b>本站原创文章，于<?php echo $article['article_modified']; ?>最后修改，由<?php echo $articleCon['article_author']; ?>发表</span>
            <span><b>转载请注明:</b><?php echo $articleCon['article_title']; ?>| 简窝博客</span>
        </div>

    </div>
    <div class="art-goods">
        <?php if(($goodsAdd == 1)): ?>
        <div class="goods-add " style="color: red">
            <i class="iconfont" style="color: red">&#xe672;</i></br><?php echo $articleCon['article_goodscount']; ?>
        </div>
        <?php else: ?>
        <div class="goods-add before-goods-add"><i class="iconfont" id="goodsbefore">&#xe63a;</i></br>
            <?php echo $articleCon['article_goodscount']; ?>
        </div>

        <?php endif; ?>
    </div>
    <div class="comment" id="comments">
        <div class="comment-input">
            <div>
                <div><span>评论</span></div>
            </div>
            <div>
            <textarea rows="5" cols="80">

            </textarea>
            </div>
            <div class="comment-user">
                <div>
                    <div>
                        <div class="input-group ">
                            <span class="input-group-addon " id="usernames"><i class="iconfont">&#xe501;</i></span>
                            <input type="text" class="form-control  username" placeholder="请输入昵称" id="username"
                                   aria-describedby="usernames">
                        </div>
                    </div>
                    <div>
                        <div class="input-group ">
                            <span class="input-group-addon " id="qqq"><i class="iconfont">&#xe619;</i></span>
                            <input type="text" class="form-control qq" placeholder="您的qq" id="qq"
                                   aria-describedby="qqq">
                        </div>
                    </div>
                </div>

                <div>
                    <button class="btn comment-btn">提交评论</button>
                </div>
            </div>

        </div>
        <div class="comment-list">
            <?php if(($commentlist==null)): ?>
            暂无评论！
            <?php endif; if(is_array($commentlist) || $commentlist instanceof \think\Collection || $commentlist instanceof \think\Paginator): if( count($commentlist)==0 ) : echo "" ;else: foreach($commentlist as $key=>$comment): ?>
            <div class="comment-unit">
                <div class="comment-info">
                    <span><img src="<?php echo $comment['comment_headimg']; ?>" width="30"></span><span><?php echo $comment['comment_user']; ?></span><span><?php echo $comment['comment_date']; ?></span>
                    <span><?php echo $comment['comment_floors']; ?>楼</span><span style="display: none"><?php echo $comment['comment_id']; ?></span>
                </div>
                <div class="comment-con">
                    <span><?php echo $comment['comment_content']; ?></span>
                </div>
                <div class="comment-op">
                    <?php if(($comment['comment_isgoods'] == 1)): ?><span class="after-goods" style="color:red"><i
                        class="iconfont" style="color: red">&#xe502;</i>&nbsp;赞(<?php echo $comment['comment_goodscount']; ?>)</span>
                    <?php else: ?><span><i class="iconfont">&#xe64c;</i>&nbsp;赞(<?php echo $comment['comment_goodscount']; ?>)</span>
                    <?php endif; ?>
                    <span><i class="iconfont">&#xe682;</i>&nbsp;评论</span>
                </div>


                <div class="reply-comment">
                    <?php if(is_array($comment['comment_reply']) || $comment['comment_reply'] instanceof \think\Collection || $comment['comment_reply'] instanceof \think\Paginator): if( count($comment['comment_reply'])==0 ) : echo "" ;else: foreach($comment['comment_reply'] as $key=>$reply): ?>
                    <div class="comment-unit">
                        <div class="comment-info">
                        <span><img
                                src="<?php echo $reply['comment_headimg']; ?>" width="30"></span><span><?php echo $reply['comment_user']; ?>回复<?php echo $reply['comment_parent_user']; ?></span><span><?php echo $reply['comment_date']; ?></span>
                                                    <span style="display: none"><?php echo $reply['comment_id']; ?></span>
                                                </div>
                                                <div class="comment-con">
                        <span><?php echo $reply['comment_content']; ?>
                        </span>
                        </div>
                        <div class="comment-op">
                            <?php if(($reply['comment_isgoods'] ==1)): ?> <span class="after-goods" style="color:red"><i
                                class="iconfont" style="color: red">&#xe502;</i>&nbsp;赞(<?php echo $reply['comment_goodscount']; ?>)</span>
                            <?php else: ?> <span><i class="iconfont">&#xe64c;</i>&nbsp;赞(<?php echo $reply['comment_goodscount']; ?>)</span>
                            <?php endif; ?>
                            <span><i class="iconfont">&#xe682;</i>&nbsp;评论</span></div>
                    </div>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
            <?php endforeach; endif; else: echo "" ;endif; ?>

        </div>
    </div>

    <div class="page-foot"></div>
</div>

<script>
    window.onload = function () {
        // if (<?php echo $goodsAdd; ?> == 1) {
        //     $(".goods-add").css('color', 'red');
        //     $('.goods-add').html('<i class="iconfont" id="goodsafter">&#xe672;</i></br><?php echo $articleCon['article_goodscount']; ?>').css('display', 'block')
        // }
        var xmlbj;
        if (window.XMLHttpRequest) {
            xmlbj = new XMLHttpRequest();
        } else {
            xmlbj = new ActiveXObject('Microsoft.XMLHTTP');
        }
        xmlbj.open('GET','<?php echo Url("index/index/addPv",["id"=>$articleCon['article_id']]); ?>',true);
        xmlbj.send()

    }


    spans = $('.artd-tags').find('span');
    // spans=document.getElementsByTagName("span")
    for (var i = 0; i < spans.length; i++) {
        var color = Math.random();
        var r = parseInt((color * (i + 1) * 1234) % 254);
        var g = parseInt((color * (i + 1) * 4321) % 254);
        var b = parseInt((color * (i + 1) * 2222) % 254);
        $(spans[i]).css("background-color", "rgba(" + r + "," + g + "," + b + ",0.1)")
        $(spans[i]).css("border", "  1px rgba(" + r + "," + g + "," + b + ",1) solid")

    }
    $('#goodsbefore').click(function () {
        var goods = $('.goods-add').clone();
        goods.find('i').remove();
        // $(this).parent().css('display', 'none');
        // $('.goods-add').find('i').remove();
        $('.goods-add').html('<i style="color:red!important;" class="iconfont" id="goodsafter">&#xe672;</i></br>' + (parseInt(goods.text()) + 1)).css('color', 'red');
        $('.goods-add').removeClass('before-goods-add');
        $.ajax({
            type: 'post',
            data: {'data': JSON.stringify({goods: 1, article_id: "<?php echo $articleCon['article_id']; ?>"})},
            dataType: 'json',
            async: true,
            url: "<?php echo Url('index/index/goodsAdd'); ?>",
            success: function (datas) {
                console.log(datas)


            },
            error: function () {
                console.log("error")
            }
        })
    })
    // console.log(<?php echo $goodsAdd; ?>)

    var flag = 0;
    var $comment_input = '        <div class="comment-input" >' +
        '                    <div style="display: none">' +
        '                        <div><span></span></div>' +
        '                    </div>' +
        '                    <div>' +
        '                            <textarea id="comment-replay-text" rows="5" cols="80">' +
        '' +
        '                            </textarea>' +
        '                    </div>' +
        '                    <div class="comment-user">' +
        '                        <div>' +
        '                            <div>' +
        '                                <div class="input-group ">' +
        '                                    <span class="input-group-addon" id="replay-username1"><i' +
        '                                            class="iconfont">&#xe501;</i></span>' +
        '                                    <input type="text" class="form-control username" placeholder="请输入昵称"' +
        '                                           aria-describedby="replay-username1">' +
        '                                </div>' +
        '                            </div>' +
        '                            <div>' +
        '                                <div class="input-group ">' +
        '                                        <span class="input-group-addon" id="replay-qq1"><i' +
        '                                                class="iconfont">&#xe619;</i></span>' +
        '                                    <input type="text" class="form-control qq" placeholder="您的qq"' +
        '                                           aria-describedby="replay-qq1">' +
        '                                </div>' +
        '                            </div>' +
        '                        </div>' +
        '' +
        '                        <div>' +
        '                            <button class="btn comment-btn">提交评论</button>' +
        '                        </div>' +
        '                    </div>' +
        '' +
        '                </div>';
    comment_input = $.parseHTML($comment_input);
    $('.comment-list').on('click', 'div.comment-op span:nth-child(2)', function () {
        if ($(this).parent().next().attr('class') != 'comment-input' ) {
            $(this).parent().after($comment_input);
            // flag = 1;
        } else {
            // $comment_input.remove();
            $(this).parent().parent().find('.comment-input').remove();
            // flag = 0;
        }
    })


    //评论提交
    var commid = <?php echo $commlastid; ?> + 1;
    $('.comment').on('click', '.comment-btn', function () {
        var commentext = $(this).parent().parent().parent().find('textarea').val();
        var username = $(this).parent().parent().parent().find('.username').val();
        var qq = $(this).parent().parent().parent().find('.qq').val();
        var artId = "<?php echo $articleCon['article_id']; ?>";
        var curtime = formatDate(new Date().getTime());
        var addcom = null;
        var info = null;
        var replyId = null;
        var index = null;
        var headImgUrl = "/jwblog/public/static/headimg/" + Math.ceil(Math.random() * 10) + ".jpg";
        var $thisfourpars = $(this).parent().parent().parent().parent();
        var tarlink;
        console.log("imgurl" + headImgUrl);
        if (commentext == null || commentext.trim(' ') == '' || commentext == '' || commentext == ' ') {
            alert_fail('提示', '评论内容不能为空！');
            return false;
        }
        if (username == null || username.trim(' ') == '' || username == '' || username == ' ') {
            alert_fail('提示', '用户名不能为空！');
            return false;
        }

        var parentName = $(this).parent().parent().parent().parent().find('.comment-info>span:nth-child(2)').text();
        if ((index = parentName.indexOf('回')) >= 0) {
            parentName = parentName.substring(0, index)
        }
        if ($thisfourpars.attr('class') == 'comment') {
            tarlink = $('.comment>.comment-list>.comment-unit:last-child');
            tarlink.attr('id', 'cur-comment')
            addcom = $('.comment-list');
            info = ' <div class="comment-unit">' +
                '                    <div class="comment-info">' +
                '                            <span><img src="' + headImgUrl + '" width="30"></span><span>' + username + '</span>' +
                '                               <span> ' + curtime + '</span> ' +
                '                               <span style="display: none">' + commid + '</span>' +
                '                    </div>' +
                '                    <div class="comment-con">' +
                '                        <span>' + commentext + '</span>' +
                '                    </div>' +
                '                <div class="comment-op"><span><i class="iconfont">&#xe64c;</i>&nbsp;赞(0)</span><span><i' +
                '                            class="iconfont">&#xe682;</i>&nbsp;评论</span></div>' +
                '                <div class="reply-comment">' +
                '                                    </div> </div> ';

            console.log('commlist');

            replyId = -1;
            commid++;
            ;
        } else {
            if ($thisfourpars.parent().attr('class') == "comment-list") {
                addcom = $(this).parent().parent().parent().next()
                console.log('if' + addcom.attr('class'))
            } else {
                addcom = $thisfourpars.parent();
                console.log('else' + addcom.attr('class'))
            }
            tarlink = $('.reply-comment>.comment-unit:last-child');
            tarlink.attr('id', 'cur-comment')
            // 1763754
            info = ' <div class="comment-unit " id="cur-comment">' +
                '                    <div class="comment-info">' +
                '                             <span><img src="' + headImgUrl + '" width="30"></span>' + '<span>' + username + '回复 ' + parentName + '</span>' +
                '<span> ' + curtime + '</span> ' +
                '<span style="display: none">' + commid + '</span>' +
                '                    </div>' +
                '                    <div class="comment-con">' +
                '                        <span>' + commentext + '</span>' +
                '                    </div>' +
                '                <div class="comment-op"><span><i class="iconfont">&#xe64c;</i>&nbsp;赞(0)</span><span><i' +
                '                            class="iconfont">&#xe682;</i>&nbsp;评论</span></div>' +
                '                     </div>   ';
            replyId = $(this).parent().parent().parent().parent().find('.comment-info span:last-child ').eq(0).text();
          //  console.log(replyId)
           // console.log($(this).parent().parent().parent().parent().attr('class'))
            // console.log($(this).parent().parent().parent().parent().html())
            commid++;
        }


        // console.log(commentext+username)
        $commentId = $thisfourpars.find('.comment-info span:last-child')
        console.log("comid   :" + replyId)
        // if(!(parseInt($commentId.text())>0)){
        //     console.log("aa")v
        // }
        $.ajax({
            type: 'post',
            data: {
                'data': JSON.stringify({
                    commenttext: commentext,
                    username: username,
                    qq: qq,
                    artId: artId,
                    comment_parent: replyId,
                    headimgUrl: headImgUrl
                })
            },
            dataType: 'json',
            url: "<?php echo Url('index/index/addComment'); ?>",
            success: function (data) {
                console.log("com" + JSON.stringify(data));

                var html = $.parseHTML(info);
                addcom.append(html)
                alert_success_url('提示', '评论成功', '#cur-comment');
                setTimeout(function () {
                    tarlink.removeAttr('id');
                }, 4000)
                $('textarea').val('');
                $('.qq').val('');
                $('.username').val('');
            },
            error: function () {
                alert_fail('提示', '未知错误')
            }


        })
        // console.log(commentext+qq+username)
        if ($thisfourpars.attr('class') != 'comment') {
            $tmpthispars = $(this).parent().parent().parent();
            setTimeout(function () {
                $tmpthispars.remove();

            }, 2000)

        }
    })

    //评论点赞
    $('.comment-list').on('click', 'div.comment-op span:nth-child(1)', function () {
        var $goodscommentId = $(this).parent().parent().find('div.comment-info>span:last-child').eq(0).text();
        var re = $(this).parent().parent().find('div.comment-info:first-child>span:last-child');
        // console.log("aa" + $(this).parent().parent().html())
        console.log($goodscommentId)
        var $thisclone = $(this).clone();
        $thisclone.find('i').remove();
        var thistext = $thisclone.text().trim(' ');
        thistext = parseInt(thistext.substring(2, thistext.length - 1)) + 1;
        // console.log(thistext)
        $(this).html('<i class="iconfont" style="color: red">&#xe502;</i>&nbsp;赞(' + thistext + ')').css('color', 'red')
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: {'data': JSON.stringify({comId: $goodscommentId})},
            url: '<?php echo url("index/index/addCommentGoods"); ?>',
            success: function (data) {
                console.log(data)
            }, error: function () {

            }


        })
    })
    Prism.plugins.toolbar.registerButton('select-all', function(env) {
        var button = document.createElement('button');
        button.innerHTML = '全选';

        button.addEventListener('click', function () {
            // Source: http://stackoverflow.com/a/11128179/2757940
            if (document.body.createTextRange) { // ms
                var range = document.body.createTextRange();
                range.moveToElementText(env.element);
                range.select();
            } else if (window.getSelection) { // moz, opera, webkit
                var selection = window.getSelection();
                var range = document.createRange();
                range.selectNodeContents(env.element);
                selection.removeAllRanges();
                selection.addRange(range);
            }
        });

        return button;
    });
    Prism.plugins.toolbar.registerButton('copy-to-clipboard', function (env) {
        var linkCopy = document.createElement('a');
        linkCopy.textContent = '复制';

        if (!ClipboardJS) {
            callbacks.push(registerClipboard);
        } else {
            registerClipboard();
        }

        return linkCopy;

        function registerClipboard() {
            var clip = new ClipboardJS(linkCopy, {
                'text': function () {
                    return env.code;
                }
            });

            clip.on('success', function() {
                linkCopy.textContent = '已复制';

                resetText();
            });
            clip.on('error', function () {
                linkCopy.textContent = 'Press Ctrl+C to copy';

                resetText();
            });
        }

        function resetText() {
            setTimeout(function () {
                linkCopy.textContent = '复制';
            }, 5000);
        }
    });
</script>

    <div class="usermodel">
        
        <div class="timeball">
 <canvas id="timeball"   width="310" height="300" ></canvas>
</div>
<script>

    var digit =
        [
            [
                [1, 1, 1, 1],
                [1, 0, 0, 1],
                [1, 0, 0, 1],
                [1, 0, 0, 1],
                [1, 0, 0, 1],
                [1, 0, 0, 1],
                [1, 1, 1, 1]
            ],//0
            [
                [0, 0, 0, 1],
                [0, 0, 0, 1],
                [0, 0, 0, 1],
                [0, 0, 0, 1],
                [0, 0, 0, 1],
                [0, 0, 0, 1],
                [0, 0, 0, 1]
            ],//1
            [
                [1, 1, 1, 1],
                [0, 0, 0, 1],
                [0, 0, 0, 1],
                [1, 1, 1, 1],
                [1, 0, 0, 0],
                [1, 0, 0, 0],
                [1, 1, 1, 1]
            ],//2
            [
                [1, 1, 1, 1],
                [0, 0, 0, 1],
                [0, 0, 0, 1],
                [1, 1, 1, 1],
                [0, 0, 0, 1],
                [0, 0, 0, 1],
                [1, 1, 1, 1]
            ],//3
            [
                [1, 0, 0, 1],
                [1, 0, 0, 1],
                [1, 0, 0, 1],
                [1, 1, 1, 1],
                [0, 0, 0, 1],
                [0, 0, 0, 1],
                [0, 0, 0, 1]
            ],//4
            [
                [1, 1, 1, 1],
                [1, 0, 0, 0],
                [1, 0, 0, 0],
                [1, 1, 1, 1],
                [0, 0, 0, 1],
                [0, 0, 0, 1],
                [1, 1, 1, 1]
            ],//5
            [
                [1, 1, 1, 1],
                [1, 0, 0, 0],
                [1, 0, 0, 0],
                [1, 1, 1, 1],
                [1, 0, 0, 1],
                [1, 0, 0, 1],
                [1, 1, 1, 1]
            ],//6
            [
                [1, 1, 1, 1],
                [0, 0, 0, 1],
                [0, 0, 0, 1],
                [0, 0, 0, 1],
                [0, 0, 0, 1],
                [0, 0, 0, 1],
                [0, 0, 0, 1]
            ],//7
            [
                [1, 1, 1, 1],
                [1, 0, 0, 1],
                [1, 0, 0, 1],
                [1, 1, 1, 1],
                [1, 0, 0, 1],
                [1, 0, 0, 1],
                [1, 1, 1, 1]
            ],//8
            [
                [1, 1, 1, 1],
                [1, 0, 0, 1],
                [1, 0, 0, 1],
                [1, 1, 1, 1],
                [0, 0, 0, 1],
                [0, 0, 0, 1],
                [1, 1, 1, 1]
            ],//9
            [
                [0],
                [0],
                [1],
                [0],
                [1],
                [0],
                [0]
            ],//:
            [
                [0, 0, 0, 0],
                [0, 0, 0, 0],
                [0, 0, 0, 0],
                [0, 0, 0, 0],
                [0, 0, 0, 0],
                [0, 0, 0, 0],
                [0, 0, 0, 0]
            ],//null

        ];
    var date = [];
    var balls = [];
    var Color='#940BB7';
    var canvas = document.getElementById('timeball');
    if (canvas.getContext) {
        var ctx = canvas.getContext('2d');
        ctx.translate(5,55)
        var W = 300;
        var H = 100;
        var R = 3.5;
        showTime();
        clearInterval(inter);
        var inter = setInterval(function () {
            render();
            updateballs();
            showTime();
        }, 16);
    }

    function render() {

        ctx.clearRect(0,-H,W,H*2+20)//清除原始图形
        for (var i = 0; i < balls.length; i++) {
            ctx.beginPath();
            ctx.arc(balls[i].x, balls[i].y, R, 0, Math.PI * 2);
            ctx.fillStyle = Color;
            ctx.closePath();
            ctx.fill();
        }
    }


    function showTime() {
        var newdate = [];
        var d = new Date();
        var h = d.getHours();
        var m = d.getMinutes();
        var s = d.getSeconds();
        // console.log(Math.floor(h/10)+" "+m%10+" "+s)
        newdate.push(Math.floor(h / 10), h % 10, 10, Math.floor(m / 10), m % 10, 10, Math.floor(s / 10), s % 10);
        showDigitGroup(newdate, 0, 0);
        //showDigitGroup(1,2,3,4,5,6)131654
    }

    function showDigitGroup(d, x, y) {
        var xx = x;
        var changedate = [];
        //console.log(date.length)
        for (var i = 0; i < d.length; i++) {
            //console.log(xx);
            var dd = d[i];
            if (dd === 0 && i === 0) {
                dd = 11;
            }
            showDigitCtx(dd, xx, y,'#ddd',Color);



            if (d[i] === 10) {
                xx += 15;
            } else {
                xx += 45;
            }

        }
        // console.log(d +'--'+date)
        for (var k = 0; k < d.length; k++) {
            if (d[k] != date[k] && d[k] != undefined && date[k] != undefined) {
                changedate.push(k + '#' + date[k]);

            }
        }
        changedates(changedate);
        date = [];
        date = d.concat();

    }

    function changedates(changedate) {
        var t = []
        for (var i = 0; i < changedate.length; i++) {
            // console.log(changedate[i]);
            t = changedate[i].split('#');
            // console.log(t)
            doballs(t[0], t[1], 0, 0);
        }
    }

    function doballs(index, num, x, y) {
        var xx = x, yy = y;
        switch (Number(index)) {
            case 0:
                xx = x;
                break;
            case 1:
                xx = x + 45;
                break;
            case 2:
                xx = x + 90;
                break;
            case 3:
                xx = x + 105;
                break;
            case 4:
                xx = x + 150;
                break;
            case 5:
                xx = x + 195;
                break;
            case 6:
                xx = x + 210;
                break;
            case 7:
                xx = x + 255;
                break;
        }

        var ramNumY=[1,2,3,4];
        var ramNumX=[1,2,3,4];
        var t=[-1,1];
        for (var i=2;i<12;i++){
            ramNumX.push(i*-1);
        }
        for (var i = 0; i < 7; i++) {
            for (var j = 0; j < 4; j++) {
                if (digit[num][i][j]===1){

                    var tt=t[Math.floor(Math.random()*t.length)];
                    var ball = {
                        x: xx+j*9,
                        y: yy+i*9,
                        stepX: 0.8*(ramNumX[Math.floor(Math.random()*ramNumX.length)]),
                        stepY: -4*(ramNumY[Math.floor(Math.random()*ramNumY.length)]),
                        k: 0.6,//摩擦系数
                        lastY:tt===1?Math.random()*(H-30)+30:Math.random()*(yy+i*9-R-50)+R-50,//小球向上边界
                        t:tt//小球方向
                    }

                    balls.push(ball);
                }
            }
        }

        // console.log(balls )

    }

    function updateballs() {

        for (var i = 0; i < balls.length; i++) {
            // if (balls[i].y === H - R) {
            //     balls[i].y = balls[i].lastY ;
            //     balls[i].x= balls[i].x+balls[i].stepX*10;
            //
            //
            // }
            balls[i].x = balls[i].x + balls[i].stepX ;
            balls[i].y = balls[i].y + balls[i].stepY ;
            if (balls[i].t===1){
                balls[i].stepY = balls[i].stepY * balls[i].k +2;
            }else if(balls[i].t===-1){
                balls[i].stepY = balls[i].stepY * balls[i].k -2;
            }

            if (balls[i].y > H - R) {
                balls[i].y =H-R;
                balls[i].stepY = -balls[i].stepY* balls[i].k;
                balls[i].t=-1;

            }else  if (balls[i].y <balls[i].lastY) {
                balls[i].y =balls[i].lastY;
                balls[i].stepY =   -balls[i].stepY* balls[i].k;
               // console.log(balls[i].stepY)
                balls[i].t=1;

            }
            if (Math.abs(balls[i].x )> W - R ||Math.abs(balls[i].x )<R*1.5 ) {
                balls.splice(i,1);
                i--;
            }


        }

    }

    function showDigitCtx(num, posx = 0, poxy = 0, bc = '#ddd', color = 'green') {
        for (var i = 0; i < 7; i++) {
            for (var j = 0; j < (num===10?1:4); j++) {
                ctx.beginPath();
                ctx.arc(posx+j*9,poxy+i*9,R,0,Math.PI*2,true);
                ctx.fillStyle=digit[num][i][j] === 1?color:bc;
                ctx.closePath();
                ctx.fill();


            }
        }


    }
</script>

 <div class="calendar">
     <table>
         <thead><tr><td colspan="7"  > </td></tr></thead>
         <tbody id="cal-tb">
            <tr class="cal-week">
                <td><a>日</a></td>
                <td><a>一</a></td>
                <td><a>二</a></td>
                <td><a>三</a></td>
                <td><a>四</a></td>
                <td><a>五</a></td>
                <td><a>六</a></td>
            </tr>
         </tbody>
         <tfoot id="cal-foot"><tr><td colspan="7"></td></tr></tfoot>
     </table>

 </div>

 <script>
     $(document).ready(function () {
        var  artdays=<?php echo $artdays; ?>;


         var d=new Date();
         var year=d.getFullYear();
         var mon=d.getMonth();
         var day=d.getDate();
         var week=d.getDay();

         calShow(year,mon,day);
        function calShow(year,mon) {
            var df=new Date(year,mon,1);
            var firstdayweek=df.getDay();
            var dayadd=1;
            var  mondays=getMondays(year,mon+1);
            $('.calendar table>thead>tr>td').append( '<a>'+ (year+"年"+(mon+1)+"月") +"</a>" );
            for (var i=0;i<=5;i++){
                $('.calendar table>tbody').append('<tr></tr>');
                for (var j = 0; j < 7; j++) {
                    if (firstdayweek > j && i === 0) {
                        $('.calendar table>tbody>tr:last-child').append('<td></td>');
                    } else {
                        if (dayadd<=mondays){
                            $('.calendar table>tbody>tr:last-child').append('<td><a>' + (dayadd++) + '</a></td>');
                            if (dayadd-1===new Date().getDate()&&mon===new Date().getMonth()&&year===new Date().getFullYear()){
                                $('.calendar table>tbody>tr:last-child>td:last-child>a').addClass('cal-today');
                            }
                            for (index in artdays){
                                var pubtime=new Date(artdays[index]['article_pushdate']);
                                if (pubtime.getFullYear()===year&&pubtime.getMonth()===mon&&pubtime.getDate()===dayadd-1){
                                    $('.calendar table>tbody>tr:last-child>td:last-child>a').addClass('cal-artpub');
                                    var url='<?php echo Url("index/index/index",["d"=>ddddd]); ?>';
                                    url=url.replace('ddddd',year+""+(mon+1<10?'0'+(mon+1):(mon+1))+(dayadd-1<10?'0'+(dayadd-1):(dayadd-1)));
                                    $('.calendar table>tbody>tr:last-child>td:last-child>a'). attr('href',url);
                                }
                            }

                        }else{
                            $('.calendar table>tbody>tr:last-child').append('<td></td>');
                        }
                    }
                }

            }
            $('#cal-tb').trigger('create');
            $('#cal-foot>tr>td').append("<a class='pre-mon'><<"+(mon===0?12:mon)+"月</a>");
            $('#cal-foot>tr>td').append("<a class='next-mon'>>>"+(mon+2===13?1:mon+2)+"月</a>");



        }
         var tmon=mon;
         var tyear=year;
         $('.calendar').on('click','.pre-mon',function () {
             $('.calendar table>thead>tr>td>a').remove();
             $('.calendar table>tfoot>tr>td>a').remove();
             $('.calendar table>tbody>tr:not(.cal-week)').remove();
             if (--tmon<0){
                 tmon=11;
                 tyear--;
             }
             calShow(tyear,tmon);
         });
         $('.calendar').on('click','.next-mon',function () {
             $('.calendar table>thead>tr>td>a').remove();
             $('.calendar table>tfoot>tr>td>a').remove();
             $('.calendar table>tbody>tr:not(.cal-week)').remove();

             if (++tmon>11){
                 tmon=0;
                 tyear++;
             }
             calShow(tyear,tmon);
         });
        function  isloop(year) {
            if(year%4===0&&year%100!==0||year%400===0){
                return true
            }else{
                return false;
            }
        }
        function  getMondays(year,mon) {
            var day=new Date(year,mon,0);
            return day.getDate();
        }

     })


 </script>
<div class="art-box">
    <div class="artb-title">
        <span>最新文章</span>
        <span>随机推荐</span>
        <span>热门文章</span>
    </div>
    <div class="newest-art display">
        <ul>
            <?php if(is_array($newestArt) || $newestArt instanceof \think\Collection || $newestArt instanceof \think\Paginator): if( count($newestArt)==0 ) : echo "" ;else: foreach($newestArt as $key=>$news): ?>
            <li><a href="<?php echo Url('index/index/detail',['id'=>$news['article_id']]); ?>"><?php echo sub_str($news['article_title'],22); ?></a>
                <a style="display: none" class="tit-dis-no"><?php echo $news['article_title']; ?></a>
            </li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>
    <div class="random-art hidden">
        <ul>
            <?php if(is_array($randomtArt) || $randomtArt instanceof \think\Collection || $randomtArt instanceof \think\Paginator): if( count($randomtArt)==0 ) : echo "" ;else: foreach($randomtArt as $key=>$random): ?>
            <li>
                <a href="<?php echo Url('index/index/detail',['id'=>$random['article_id']]); ?>"><?php echo sub_str($random['article_title'],22); ?></a>
                    <a style="display: none" class="tit-dis-no"><?php echo $random['article_title']; ?></a>
            </li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>
    <div class="hot-art hidden">
        <ul>
            <?php if(is_array($hotArt) || $hotArt instanceof \think\Collection || $hotArt instanceof \think\Paginator): if( count($hotArt)==0 ) : echo "" ;else: foreach($hotArt as $key=>$hot): ?>
            <li><a href="<?php echo Url('index/index/detail',['id'=>$hot['article_id']]); ?>"><?php echo sub_str($hot['article_title'],22); ?></a>
                <a style="display: none" class="tit-dis-no"><?php echo $hot['article_title']; ?></a>
            </li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>
</div>
<script>
    $(function () {

        var i = 0;
        $('.artb-title>span').eq(0).addClass('art-titlehover');
        showArtbox();

        function showArtbox() {
            var d = i % 3;
            $('.artb-title>span').eq(d).addClass('art-titlehover');
            $('.artb-title>span').eq((d + 1) % 3).removeClass('art-titlehover');
            $('.artb-title>span').eq((d + 2) % 3).removeClass('art-titlehover');
            $('.art-box>div:not(.artb-title)').eq(d).removeClass('hidden');
            $('.art-box>div:not(.artb-title)').eq(d).addClass('display');
            $('.art-box>div:not(.artb-title)').eq((d + 1) % 3).addClass('hidden');
            $('.art-box>div:not(.artb-title)').eq((d + 2) % 3).addClass('hidden');
            i++;
            $('.artb-title>span').mouseover(function () {
                clearInterval(inter);
            })
        }

        var inter = setInterval(function () {
            showArtbox();
        }, 2000)

        $('.artb-title>span').each(function (index) {
            $(this).mouseover(function () {
                $('.artb-title>span').eq(index).addClass('art-titlehover');
                $('.artb-title>span').eq((index + 1) % 3).removeClass('art-titlehover');
                $('.artb-title>span').eq((index + 2) % 3).removeClass('art-titlehover');
                $('.art-box>div:not(.artb-title)').eq(index).removeClass('hidden');
                $('.art-box>div:not(.artb-title)').eq(index).addClass('display');
                $('.art-box>div:not(.artb-title)').eq((index + 1) % 3).addClass('hidden');
                $('.art-box>div:not(.artb-title)').eq((index + 2) % 3).addClass('hidden');
            });
        })
        $('.artb-title>span').mouseleave(function () {
            clearInterval(inter);
            inter = setInterval(function () {
                showArtbox();
            }, 2000);

        })
            $('.art-box ul>li').mouseover(function (e) {
                $(this).mousemove(function (e) {
                    var x=e.pageX;
                    var y=e.pageY;
                    var xx=e.screenX;
                    var yy=e.screenY;
                    $(this).after('<span style="color:red" class="tit_alt">'+ $(this).find('.tit-dis-no').text()+'</span>');
                    $(this).parent().find('.tit_alt').css('position','absolute').css('left',x).css('top',y-250)
                })
            });
            $(document).on('mouseleave','.art-box ul>li',function () {
                // $(this).parent
                $(this).parent().find('.tit_alt').remove();
            });

    });


</script>
<?php if(($taglist!=null)): ?>
<div class="art-tags">
    <p>标签云集</p>
     <div>
         <?php if(is_array($taglist) || $taglist instanceof \think\Collection || $taglist instanceof \think\Paginator): if( count($taglist)==0 ) : echo "" ;else: foreach($taglist as $key=>$tags): ?>
         <span>
             <a href="<?php echo Url('index/index/index',['tagid'=>$tags['tags_id']]); ?>"><?php echo $tags['tags_content']; ?></a>
             <a style="display: none" class="tags-id"><?php echo $tags['count']; ?></a>
         </span>
         <?php endforeach; endif; else: echo "" ;endif; ?>
     </div>
 </div>
<?php endif; ?>
 <script>
     spans = $('.art-tags>div').find('span');
     for (var i = 0; i < spans.length; i++) {
         var color = Math.random();
         var r = parseInt((color * (i + 1) * 1234) % 254);
         var g = parseInt((color * (i + 1) * 4321) % 254);
         var b = parseInt((color * (i + 1) * 2222) % 254);
         $(spans[i]).css("background-color", "rgba(" + r + "," + g + "," + b + ",0.1)")
         $(spans[i]).css("border", "  1px rgba(" + r + "," + g + "," + b + ",1) solid")
     }


     $(function () {
         $('.art-tags span ').mouseover(function (e) {
             $(this).mousemove(function (e) {
                 var x=e.pageX;
                 var y=e.pageY;
                 var xx=e.screenX;
                 var yy=e.screenY;
                 $(this).after('<span style="color:red" class="tit_alt">'+ $(this).find('.tags-id').text()+'个话题</span>');
                 $(this).parent().find('.tit_alt').css('position','absolute').css('left',x).css('top',y-50)
             })
         });
         $(document).on('mouseleave','.art-tags span ',function () {
             // $(this).parent
             $(this).parent().find('.tit_alt').remove();
         });
     })
 </script>
        
    </div>
    <div class="clear"></div>
    </div>
</div>
<div class="footer">
    
    <div>
    <?php echo $foot; ?>
    <br/>
    <span id="copyright">Copyright © 2018-<span></span></span>
    <span><a href="http://jianwoo.cn">简窝世界</a> </span>
    <span><a href="http://www.miitbeian.gov.cn/"> <?php echo $webrecord; ?></a></span>
</div>
<script>

    $('#copyright>span').text(new Date().getFullYear())

</script>
    
</div>
</body>
</html>