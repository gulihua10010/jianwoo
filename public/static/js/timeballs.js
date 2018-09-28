
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
var canvas;
var ctx;
var H,W,R;
var inter;
$(document).ready(function () {
      canvas = document.getElementById('timeball');
    if (canvas.getContext) {
          ctx = canvas.getContext('2d');
        ctx.translate(5,55)
          W = 300;
          H = 100;
          R = 3.5;
        showTime();
        clearInterval(inter);
         inter = setInterval(function () {
            render();
            updateballs();
            showTime();
        }, 16);
    }

})

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