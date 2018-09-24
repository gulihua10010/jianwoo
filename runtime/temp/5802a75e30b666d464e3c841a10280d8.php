<?php if (!defined('THINK_PATH')) exit(); /*a:9:{s:77:"/Volumes/MS-MACOS/phpStudy/WWW/jwblog/application/index/view/index/index.html";i:1537512032;s:80:"/Volumes/MS-MACOS/phpStudy/WWW/jwblog/application/index/view/baseView/index.html";i:1537773482;s:77:"/Volumes/MS-MACOS/phpStudy/WWW/jwblog/application/index/view/commom/menu.html";i:1537783346;s:82:"/Volumes/MS-MACOS/phpStudy/WWW/jwblog/application/index/view/commom/usermodel.html";i:1537428290;s:84:"/Volumes/MS-MACOS/phpStudy/WWW/jwblog/application/index/view/usermodel/timeball.html";i:1537428290;s:84:"/Volumes/MS-MACOS/phpStudy/WWW/jwblog/application/index/view/usermodel/calendar.html";i:1537428290;s:94:"/Volumes/MS-MACOS/phpStudy/WWW/jwblog/application/index/view/usermodel/articleRecommended.html";i:1537428290;s:83:"/Volumes/MS-MACOS/phpStudy/WWW/jwblog/application/index/view/usermodel/artTags.html";i:1537428290;s:79:"/Volumes/MS-MACOS/phpStudy/WWW/jwblog/application/index/view/commom/footer.html";i:1537428290;}*/ ?>
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
    
<?php if(($flag != -1)): ?>
<div class="index-position">
        <span>当前位置:<a href="<?php echo Url('index/index/index'); ?>' ">首页>></a>
            <?php if(($flag==1||$flag==2)): ?>
            <a href="<?php echo Url('index/index/index',['m'=>$menuname['menu_id']]); ?>"><?php echo $menuname['menu_name']; ?>>></a>
                <?php if(($flag ==1)): ?>
                    <a href="<?php echo Url('index/index/index',['m'=>$menuname['menu_id'],'t'=>$typename['types_id']]); ?>"><?php echo $typename['types_content']; ?></a>
                <?php endif; endif; if(($flag ==4)): ?>
                标签>><?php echo $tagName; endif; if(($flag ==0)): ?>
            搜索结果:<?php echo $keyword; endif; ?>
        </span>
</div>
<?php endif; ?>
<div class="index-main">

    <div class="article-list">
        <?php if(is_array($article) || $article instanceof \think\Collection || $article instanceof \think\Paginator): if( count($article)==0 ) : echo "" ;else: foreach($article as $key=>$article): ?>
        <div class="article-breviary">
            <div class="art-bre-tit">
            <?php if(($article['article_type']!=null)): ?>    <span class="art-type">
                <?php if(($article['article_type']['types_content']!=null)): ?>
                <?php echo $article['article_type']['types_content']; endif; if(($article['article_type']['menu_name']!=null)): ?>
                <?php echo $article['article_type']['menu_name']; endif; ?>
                 </span><?php endif; ?>
                <span class="art-title" onclick="window.location.href='<?php echo Url('index/index/detail',['id'=>$article['article_id']]); ?>'"> <?php echo $article['article_title']; ?></span>
            </div>
            <div class="art-bre-top">
                <div class="art-bre-left"
                     onclick="window.location.href='<?php echo Url('index/index/detail',['id'=>$article['article_id']]); ?>'">
                    <img src="<?php if(($article['article_imgSrc'] == null)): ?>//res.jianwoo.cn/jw.jpg
                    <?php else: ?>//<?php echo $article['article_imgSrc']; endif; ?>" width="200" alt="<?php echo $article['article_title']; ?>">
                    <div></div>
                </div>
                <div class="art-bre-right">
                    <span class="article-content-bre"><?php echo $article['article_bre']; ?></span>
                </div>
            </div>
            <div class="art-bre-info">
                <div>
                        <span class="art-author"><i class="iconfont">&#xe67a;</i>&nbsp;<?php echo $article['article_author']; ?></span>
                        <span class="art-date"><i class="iconfont">&#xe65d;</i>&nbsp;<?php echo $article['article_pushdate']; ?></span>
                        <span class="art-readers"><i class="iconfont">&#xe603;</i>&nbsp;<?php echo $article['article_readcount']; ?></span>
                        <span class="art-comment"
                              onclick="window.location.href='<?php echo Url('index/index/detail',['id'=>$article['article_id']]); ?>#comments'">
                            <i class="iconfont">&#xe682;</i>&nbsp;<?php echo $article['article_commentCount']; ?>条评论</span>
                        <?php if(($article['article_isgoods'] == 1)): ?>
                        <span class="art-goods " style="color: red">
                            <i class="iconfont" style="color: red">&#xe502;</i>&nbsp;<?php echo $article['article_goodscount']; ?>
                        </span>
                        <?php else: ?>
                        <span class="art-goods before-goods-add"><i class="iconfont">&#xe64c;</i>
                            <?php echo $article['article_goodscount']; ?>
                        </span>
                        <span style="display: none" class="artid"><?php echo $article['article_id']; ?></span>

                        <?php endif; ?>
                </div>
                <div><a href="<?php echo Url('index/index/detail',['id'=>$article['article_id']]); ?>">阅读全文>></a></div>
            </div>
        </div>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        <div><?php echo $page; ?></div>
    </div>
</div>
<script>
    $('.before-goods-add').click(function () {
        console.log('good')
        var goods = $(this).clone();
        goods.find('i').remove();
        // console.log(parseInt(goods.text()) + 1)
        // $(this).parent().css('display', 'none');
        // $('.goods-add').find('i').remove();
        $(this).html('<i class="iconfont" style="color: red">&#xe502;</i>' + (parseInt(goods.text()) + 1)).css('color', 'red');
        $(this).removeClass('before-goods-add');
        var artid = $(this).parent().find('.artid').text();
        console.log("id" + artid)
        $.ajax({
            type: 'post',
            data: {'data': JSON.stringify({goods: 1, article_id: parseInt(artid)})},
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
    $(function () {
        $('.art-title').mouseover(function (e) {
            $(this).mousemove(function (e) {
                var x=e.pageX;
                var y=e.pageY;
                var xx=e.screenX;
                var yy=e.screenY;
                $(this).after('<span style="color:red" class="tit_alt">'+ $(this).text()+'</span>');
                $(this).parent().find('.tit_alt').css('position','absolute').css('left',x).css('top',y-50)
            })
        });
        $(document).on('mouseleave','.art-title',function () {
            // $(this).parent
            $(this).parent().find('.tit_alt').remove();
        });
    })
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