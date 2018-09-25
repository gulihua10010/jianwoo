function alert_fail(title='',msg=''){

    var index = layer.load(0, {shade: false, offset: '400px'},{time:1000}); //0代表加载的风格，支持0-2
    setTimeout(function() {
        layer.close(index);
        layer.msg(msg, {
            title:title
        //不自动关闭
        ,time:1000
        ,icon:5
           , offset: '400px'
    });

},1000);
setTimeout(function() {
    layer.closeAll();
},2000)

}


function alert_success_url(title='',msg='',url){


    var index = layer.load(0, {shade: false , offset: '400px'},{time:3000}); //0代表加载的风格，支持0-2
    setTimeout(function() {
        layer.close(index);
        layer.msg(msg, {
            title:title
            //不自动关闭
            ,time:1000
            ,icon:6
            , offset: '400px'
        });

    },1000)

    setTimeout(function () {
        window.location.href=url;
        layer.closeAll();
    },2000)

}
function alert_success(title='',msg=''){ var index = layer.load(0, {shade: false , offset: '400px'},{time:3000}); //0代表加载的风格，支持0-2
    setTimeout(function() {
        layer.close(index);
        layer.msg(msg, {
            title:title
            //不自动关闭
            ,time:1000
            ,icon:6
            , offset: '400px'
        });

    },1000)

}
function  alert_ask(msg='',yes) {
    //询问框
    layer.confirm(msg, {
        btn: ['确定','取消'] //按钮
        , offset: '400px'
    },  function () {
        yes();
        layer.closeAll()

    }, function () {

    })
}
function  alert_ask_btn(msg='',btn1='确定',btn2='自定义',btn3='取消',left, right) {
    //询问框
    var index=layer.confirm(msg, {
        extend:'myskin/alertask.css',
        skin:'layer-ext-myskin',
        btn: [btn1,btn2,btn3] //按钮
        , offset: '400px'
    },  function () {
        left();
        layer.closeAll()
    }, function () {
            right();
    })

}
function formatDate(time){
    var date = new Date(time);

    var year = date.getFullYear(),
        month = date.getMonth()+1,//月份是从0开始的
        day = date.getDate(),
        hour = date.getHours(),
        min = date.getMinutes(),
        sec = date.getSeconds();
    var newTime = year + '-' +
        (month < 10? '0' + month : month) + '-' +
        (day < 10? '0' + day : day) + ' ' +
        (hour < 10? '0' + hour : hour) + ':' +
        (min < 10? '0' + min : min) + ':' +
        (sec < 10? '0' + sec : sec);

    return newTime;
}

function  encodeUnicode(str) {
    var res=[];
    for (var i=0;i<str.length;i++){
        res[i]=('00'+str.charCodeAt(i).toString(16)).slice(-4)

    }
    return "\\u"+res.join('\\u')
}
function  decodeUnicode(str) {
    str=str.replace(/\\/g,'%');
    return unescape(str)
}
function GetRequest() {
    var url = location.search; //获取url中"?"符后的字串
    var theRequest = new Object();
    if (url.indexOf("?") != -1) {
        var str = url.substr(1);
        strs = str.split("&");
        for (var i = 0; i < strs.length; i++) {
            theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
        }
    }
    return theRequest;
}