ThinkPHP 5.0------简窝博客1，0
===============

闲暇之时，开发了一个个人博客用来整理技术知识，写写∫生活日记什么的。如今个人博客漫天都是，我提供了一个开源博客程序员们参考使用。亦为thinkPHP的初学者们学习。

本博客包括前台视图、后台登录，逻辑代码都是由本人利用空余时间耗时数月独立开发完成；没有版权限制，可以随意折腾。

项目模块化分层明确，代码规范，便于后期维护等工作。

前端页面采用Bootstrap响应式布局，并且集成了一些好看的动画特效，整体界面简洁，美观大方，与后台界面交互操作。

本次博客为第一版，后续会持续更新中。。 

> ThinkPHP5的运行环境要求PHP5.4以上。

详细开发文档参考 [ThinkPHP5完全开发手册](http://www.kancloud.cn/manual/thinkphp5)

项目开发环境：
Mac OS Sierra10.13.4
Mysql 5.7
php 7.2.7
thinkphp 5.1
CentOS7 


技术选型：
thinkPHP
JQuery
Bootstrap
Vue
Layui
Layer
Geetest（图片验证码）
qiniu（图片对象存储服务器）
tinymce（文本编辑器）
项目结构



## 目录结构

初始的目录结构如下：

~~~
project 应用部署目录 
├─application 应用目录
│ ├─admin 后台模块目录 
│ │ │ ├─controller 控制器 
│ │ │ │ ├─ adminController admin基类 
│ │ │ │ ├─ index 后台控制器逻辑代码 
│ │ │ │ ├─ Loginr 登录逻辑代码 
│ │ │ │ └─Upload 上传逻辑代码 
│ │ │ ├─view 视图 
│ │ │ │ │ ├─ baseview 基类视图模板目录 
│ │ │ │ │ ├─ common 公共模板目录 
│ │ │ │ │ │ ├─ articleEdit 文章编辑 
│ │ │ │ │ │ └─ articleEdit 文章发布 
│ │ │ │ │ └─ index 后台各个功能视图目录 
├─index 前台主页 
│ │ │ ├─controller 控制器 
│ │ │ │ └─index 前台控制器逻辑代码 
│ │ │ ├─view 视图 
│ │ │ │ │ ├─ baseview 基类视图模板目录 
│ │ │ │ │ ├─ common 公共模板目录 
│ │ │ │ │ ├─ index 前台各个功能视图目录 
│ │ │ │ │ └─usermodel 前台右侧用户模块 
│ ├─command.php 命令行工具配置文件 
│ ├─common.php 应用公共（函数）文件 
│ ├─config.php 应用（公共）配置文件 
│ ├─database.php 数据库配置文件 
│ ├─tags.php 应用行为扩展定义文件 
│ └─route.php 路由配置文件 
├─extend 扩展类库目录（可定义） 
├─public WEB 部署目录（对外访问目录） 
│ ├─static 静态资源存放目录(css,js,image) 
│ ├─upload 上传存储目录 
│ ├─router.php 快速测试文件 
│ └─.htaccess 用于 apache 的重写 
├─runtime 应用的运行时目录（可写，可设置） 
├─vendor 第三方类库目录（Composer） 
├─thinkphp 框架系统目录 
│ ├─lang 语言包目录 
│ ├─library 框架核心类库目录 
│ │ ├─think Think 类库包目录 
│ │ └─traits 系统 Traits 目录 
│ ├─tpl 系统模板目录 
│ ├─.htaccess 用于 apache 的重写 
│ ├─.travis.yml CI 定义文件 
│ ├─base.php 基础定义文件 
│ ├─composer.json composer 定义文件 
│ ├─console.php 控制台入口文件 
│ ├─convention.php 惯例配置文件 
│ ├─helper.php 助手函数文件（可选） 
│ ├─LICENSE.txt 授权说明文件 
│ ├─phpunit.xml 单元测试配置文件 
│ ├─README.md README 文件 
│ └─start.php 框架引导文件 
├─vendor 第三方类库目录
│ ├─geetest 极验验证码sdk 
│ └─qiniu 七牛文件上传sdk 
├─build.php 自动生成定义文件（参考） 
├─index.php 应用入口文件 
├─composer.json composer 定义文件 
├─LICENSE.txt 授权说明文件 
├─README.md README 文件 
└─think 命令行入口文件
~~~

> router.php用于php自带webserver支持，可用于快速测试
> 切换到public目录后，启动命令：php -S localhost:8888  router.php
> 上面的目录结构和名称是可以改变的，这取决于你的入口文件和配置参数。
 
 
 
 使用说明：
请将项目内文件直接放在根目录下，不要多层目录
后台登录：域名/admin/index/index
前台：域名/index 

服务器配置：
php：
upload_max_filesize = 64M
allow_url_fopen = On
allow_url_fopen = On 
max_execution_time = 600  
max_input_time = 600  
 memory_limit = 64M    
memory_limit = 64M


Apache：
  Options +FollowSymlinks -Multiviews
  RewriteEngine On
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule	^(.*)$	index.php	


Nginx：
location / {     

   	 if (!-e $request_filename) {  
	 
     	   rewrite ^(.*)$ /index.php?s=$1 last;   
	   
 	     break;    
 	   }    
	   
	}    
	
 #pathinfo配置 使支持tp5的标准url
 
    location ~ .+\.php($|/) {
    
        fastcgi_pass  unix:/dev/shm/php-fpm-default.sock;
	
        fastcgi_split_path_info ^((?U).+.php)(/?.+)$;
	
        fastcgi_param PATH_INFO $fastcgi_path_info;
	
        fastcgi_param PATH_TRANSLATED $document_root$fastcgi_path_info;
	
         fastcgi_param  SCRIPT_FILENAME /data/wwwroot/jwblog/$fastcgi_script_name;
	 
        include fastcgi_params;
	
    }
    
 

项目介绍：

前台基于boostrap的响应式页面布局适配手机和平板；
tinymce富文本编辑器
七牛云对象存储图片（免费10g）
geetest图片验证码
font-awesome；

## 版权信息

简窝博客遵循Apache2开源协议发布，并提供免费使用。

 
