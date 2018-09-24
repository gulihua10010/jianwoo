# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.23)
# Database: jwblog
# Generation Time: 2018-09-15 07:38:42 +0000
# ************************************************************

 

# Dump of table jw_admin
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jw_admin`;

CREATE TABLE `jw_admin` (
  `admin_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '表自增唯一id',
  `admin_username` varchar(20) NOT NULL COMMENT '用户名',
  `admin_password` char(64) NOT NULL COMMENT '密码',
  `admin_phone` varchar(20) DEFAULT 'null' COMMENT '手机号',
  `admin_email` varchar(20) DEFAULT NULL COMMENT '电子邮件',
  `admin_sex` varchar(10) DEFAULT NULL COMMENT '性别',
  `admin_ip` varchar(20) DEFAULT NULL COMMENT '登录ip地址',
  `admin_lastloginip` varchar(20) DEFAULT NULL COMMENT '最后登录ip',
  `admin_lastlogintime` varchar(20) DEFAULT NULL COMMENT '最后登录时间',
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员表';
 

# Dump of table jw_article
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jw_article`;

CREATE TABLE `jw_article` (
  `article_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '表自增唯一id',
  `article_author` varchar(10) NOT NULL COMMENT '文章作者',
  `article_pushdate` datetime NOT NULL COMMENT '发布日期',
  `article_title` text NOT NULL COMMENT '文章标题',
  `article_content` longtext NOT NULL COMMENT '文章内容(html格式)',
  `article_modified` datetime DEFAULT NULL COMMENT '修改时间',
  `article_typeid` int NOT NULL DEFAULT 0 COMMENT '文章类型',
  `article_readcount` bigint(20) NOT NULL COMMENT '阅读次数',
  `article_goodscount` bigint(20) NOT NULL COMMENT '赞数量',
  `article_iscomment` int(11) DEFAULT NULL COMMENT '是否可以被评论',
  `article_imgSrc` varchar(64) DEFAULT NULL COMMENT '文章缩略图',
  `article_text` longtext NOT NULL COMMENT '文章(纯文本)',
  `article_public` int(11) DEFAULT '1' COMMENT '公开度 -1：密码  , 1：公开 ,私密:0 ,置顶:2',
  `article_comment_count` bigint(20) DEFAULT NULL COMMENT '评论次数',
  `article_status` int(11) NOT NULL COMMENT '文章状态 1已发布  0草稿    -1回收站',
  `article_password` int(11) DEFAULT NULL COMMENT '当公开度为-1时设置',
  `article_deldate` datetime DEFAULT NULL COMMENT '删除日期 当状态为-1时设置',
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章表';
 

INSERT INTO `jw_article` (`article_id`, `article_author`, `article_pushdate`, `article_title`, `article_content`, `article_modified`, `article_typeid`, `article_readcount`, `article_goodscount`, `article_iscomment`, `article_imgSrc`, `article_text`, `article_public`, `article_comment_count`, `article_status`, `article_password`, `article_deldate`)
VALUES 
	(1,'简窝博客','2018-08-13 23:15:30','欢迎来到简窝','<!DOCTYPE html>\n<html>\n<head>\n</head>\n<body>\n<h3>欢迎来到简窝！！</h3>\n<br/><p>网站由博主独立开发，基于thinkPHP框架，前端采用了bootstrap，vue，layui等框架</p></body>\n</html>','2018-08-13 23:15:30','-1',123,1,0,'','欢迎来到简窝！！',1,18,1,NULL,NULL);
 

# Dump of table jw_article_tags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jw_article_tags`;

CREATE TABLE `jw_article_tags` (
  `article_tags_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表自增唯一id',
  `tags_id` int(11) NOT NULL COMMENT '标签id',
  `article_id` bigint(20) NOT NULL COMMENT '文章id',
  PRIMARY KEY (`article_tags_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章对应标签表';

 

# Dump of table jw_comment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jw_comment`;

CREATE TABLE `jw_comment` (
  `comment_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '表自增唯一id',
  `article_id` bigint(20) unsigned DEFAULT NULL COMMENT '文章id',
  `comment_user` varchar(10) NOT NULL COMMENT '评论用户',
  `comment_ip` varchar(10) NOT NULL COMMENT '评论ip',
  `comment_date` datetime NOT NULL COMMENT '评论日期',
  `comment_content` text NOT NULL COMMENT '评论内容',
  `comment_parent` bigint(20) NOT NULL COMMENT '父评论',
  `comment_goodscount` bigint(20) DEFAULT NULL COMMENT '评论所得赞数量',
  `comment_qq` varchar(20) DEFAULT NULL COMMENT '评论者qq',
  `comment_headimg` varchar(64) DEFAULT NULL COMMENT '头像',
  `comment_artdel` int(11) NOT NULL DEFAULT '1' COMMENT '文章删除时为-1',
  `comment_isread` int(11) NOT NULL DEFAULT '0' COMMENT '是否阅读',
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='评论表';

 


# Dump of table jw_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jw_menu`;

CREATE TABLE `jw_menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '表自增唯一id',
  `menu_name` varchar(10) NOT NULL COMMENT '菜单名称',
  `menu_index` int(11) NOT NULL COMMENT '菜单索引',
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='菜单表';

 
/*!40000 ALTER TABLE `jw_menu` DISABLE KEYS */;

INSERT INTO `jw_menu` (`menu_id`, `menu_name`, `menu_index`)
VALUES
	(1,'欢迎页',1);

/*!40000 ALTER TABLE `jw_menu` ENABLE KEYS */;
 


# Dump of table jw_module
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jw_module`;

CREATE TABLE `jw_module` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '表自增唯一id',
  `module_name` varchar(20) NOT NULL COMMENT '模块名称',
  `module_isDisplay` int(11) NOT NULL COMMENT '是否显示',
  `module_index` int(11) NOT NULL COMMENT '模块索引',
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='首页模块表';



# Dump of table jw_tags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jw_tags`;

CREATE TABLE `jw_tags` (
  `tags_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '表自增唯一id',
  `tags_content` varchar(20) NOT NULL COMMENT '标签内容',
  PRIMARY KEY (`tags_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='标签表';

 
/*!40000 ALTER TABLE `jw_tags` DISABLE KEYS */;
 
/*!40000 ALTER TABLE `jw_tags` ENABLE KEYS */;
 

 

# Dump of table jw_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jw_types`;

CREATE TABLE `jw_types` (
  `types_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '表自增唯一id',
  `types_content` varchar(20) NOT NULL COMMENT '类型内容',
  `menu_id` int(11) NOT NULL COMMENT '菜单id',
  PRIMARY KEY (`types_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章类型表';

 
/*!40000 ALTER TABLE `jw_types` DISABLE KEYS */; 
/*!40000 ALTER TABLE `jw_types` ENABLE KEYS */;
 


# Dump of table jw_visit
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jw_visit`;

CREATE TABLE `jw_visit` (
  `visit_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表自增唯一id',
  `visit_ip` varchar(20) DEFAULT NULL COMMENT '访问ip',
  `article_id` bigint(20) NOT NULL COMMENT '文章id',
  `visit_date` datetime NOT NULL COMMENT '访问时间',
  PRIMARY KEY (`visit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='访问表';
 


# Dump of table jw_webconf
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jw_webconf`;

CREATE TABLE `jw_webconf` (
  `webconf_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '表自增唯一id',
  `webconf_title` varchar(50) DEFAULT NULL COMMENT '网站标题',
  `webconf_keywords` varchar(50) DEFAULT NULL COMMENT '网站关键字',
  `webconf_descriptions` varchar(200) DEFAULT NULL COMMENT '网站描述',
  `webconf_record` varchar(50) DEFAULT NULL COMMENT '网站备案',
  `webconf_author` varchar(30) DEFAULT NULL COMMENT '网站作者',
  `webconf_logo` varchar(100) DEFAULT NULL COMMENT '网站logo',
  `webconf_domain` varchar(100) DEFAULT NULL COMMENT '网站域名',
  `webconf_page` int(11) NOT NULL DEFAULT '15' COMMENT '首页分页数量',
  `webconf_iscomment` int(11) NOT NULL DEFAULT '1' COMMENT '开启评论',
  `webconf_foothtml` longtext COMMENT '网站底部信息',
  `webconf_indextopimg` varchar(100) DEFAULT NULL COMMENT '网站首页头部图片',
  `webconf_indexartbreco` int(11) NOT NULL DEFAULT '100' COMMENT '首页文章缩略字数',
  PRIMARY KEY (`webconf_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='网站配置表';

 
/*!40000 ALTER TABLE `jw_webconf` DISABLE KEYS */;

INSERT INTO `jw_webconf` (`webconf_id`, `webconf_title`, `webconf_keywords`, `webconf_descriptions`, `webconf_record`, `webconf_author`, `webconf_logo`, `webconf_domain`, `webconf_page`, `webconf_iscomment`, `webconf_foothtml`, `webconf_indextopimg`, `webconf_indexartbreco`)
VALUES
	(1,'简窝博客','','简窝博客','','','','',18,1,' \r\n<span><a href=\"http://jianwoo.cn\">简窝博客</a> 版权所有</span>\r\n    <span>采用<a href=\"http://www.thinkphp.cn\">ThinkPHP5.1</a>开发</span>\r\n    <span>前端:<a href=\"http://www.bootcss.com\">Bootstrap</a>、<a href=\"http://https://cn.vuejs.org\">Vue</a>\r\n        <a href=\"https://www.layui.com/ \">LayUI</a>等框架\r\n    </span>\r\n    <span>托管于<a href=\"https://cloud.tencent.com/\">腾讯云</a>和<a href=\"https://www.qiniu.com/\">七牛云存储</a> </span>\r\n    <br/> ','',500);
 

 