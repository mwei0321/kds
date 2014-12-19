/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : mw_kandianshu

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2014-12-19 18:36:06
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for mw_admin_system_menu
-- ----------------------------
DROP TABLE IF EXISTS `mw_admin_system_menu`;
CREATE TABLE `mw_admin_system_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL COMMENT '父级菜单',
  `name` varchar(20) DEFAULT NULL COMMENT '菜单名称',
  `key` varchar(20) DEFAULT NULL COMMENT '菜单别名 (一般为模型名)',
  `action` varchar(20) DEFAULT NULL COMMENT '执行动作，用去选中状态',
  `url` varchar(120) DEFAULT NULL COMMENT '链接',
  `status` tinyint(1) DEFAULT '1' COMMENT '菜单显示状态　1为显示　0为隐藏',
  `sort` tinyint(2) DEFAULT '1' COMMENT '菜单排序',
  `position` tinyint(20) DEFAULT NULL COMMENT '位置  顶部0　左则1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='后台菜单设置';

-- ----------------------------
-- Table structure for mw_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `mw_admin_user`;
CREATE TABLE `mw_admin_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` int(11) DEFAULT NULL COMMENT '权限角色',
  `name` varchar(30) DEFAULT NULL COMMENT '用户名',
  `passwd` varchar(40) DEFAULT NULL COMMENT '密码',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `uptime` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='后台用户';

-- ----------------------------
-- Table structure for mw_app
-- ----------------------------
DROP TABLE IF EXISTS `mw_app`;
CREATE TABLE `mw_app` (
  `id` int(11) NOT NULL,
  `app_name` varchar(25) DEFAULT NULL COMMENT 'app名称',
  `app_alias` varchar(25) CHARACTER SET utf8 DEFAULT NULL COMMENT 'App别名',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `app_entry` varchar(120) CHARACTER SET utf8 DEFAULT NULL COMMENT 'app入口，如 ‘Home/Index’',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for mw_book
-- ----------------------------
DROP TABLE IF EXISTS `mw_book`;
CREATE TABLE `mw_book` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cateid` int(11) DEFAULT NULL COMMENT '分类ID',
  `tagid` varchar(50) DEFAULT NULL COMMENT '标签ID,多个用，分开',
  `name` varchar(60) NOT NULL COMMENT '标题',
  `cover` varchar(120) DEFAULT NULL COMMENT '封面',
  `author` varchar(50) DEFAULT NULL COMMENT '作者',
  `words_num` int(11) DEFAULT NULL COMMENT '字数',
  `grade` int(11) DEFAULT NULL COMMENT '平均评分',
  `intro` text COMMENT '简介',
  `comment_num` int(11) DEFAULT '0' COMMENT '点评人数',
  `click_num` int(11) DEFAULT '0' COMMENT '查看次数',
  `status` tinyint(2) DEFAULT NULL COMMENT '文章状态　－1临时保存　0隐藏　1显示',
  `end_status` tinyint(2) DEFAULT NULL COMMENT '0连载中……　1完结',
  `uptime` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING HASH
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mw_book_attach
-- ----------------------------
DROP TABLE IF EXISTS `mw_book_attach`;
CREATE TABLE `mw_book_attach` (
  `book_id` int(11) NOT NULL,
  `path` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '附件路径',
  `size` int(11) DEFAULT '0' COMMENT '大小(单位：KB)',
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '名称',
  `uptime` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for mw_book_category
-- ----------------------------
DROP TABLE IF EXISTS `mw_book_category`;
CREATE TABLE `mw_book_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT '0' COMMENT '父ID',
  `path` varchar(60) DEFAULT NULL COMMENT '路径',
  `name` varchar(60) DEFAULT NULL COMMENT '分类名',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态:1显示,2隐藏',
  `icon` varchar(255) DEFAULT NULL COMMENT '图标',
  `recommend` tinyint(1) DEFAULT '0' COMMENT '推荐',
  `sort` tinyint(2) DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='分类表';

-- ----------------------------
-- Table structure for mw_book_chapter_t1
-- ----------------------------
DROP TABLE IF EXISTS `mw_book_chapter_t1`;
CREATE TABLE `mw_book_chapter_t1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) DEFAULT NULL COMMENT '书籍ID',
  `title` varchar(100) DEFAULT NULL COMMENT '章节名称',
  `content` text COMMENT '章节内容',
  `ctime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=63 DEFAULT CHARSET=utf8 COMMENT='书籍的具体章节';

-- ----------------------------
-- Table structure for mw_book_click_log
-- ----------------------------
DROP TABLE IF EXISTS `mw_book_click_log`;
CREATE TABLE `mw_book_click_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) DEFAULT NULL COMMENT '小说',
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `uptime` int(11) DEFAULT NULL COMMENT '时间',
  `cateid` int(11) DEFAULT NULL COMMENT '分类',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for mw_book_collect
-- ----------------------------
DROP TABLE IF EXISTS `mw_book_collect`;
CREATE TABLE `mw_book_collect` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `book_id` int(11) DEFAULT NULL COMMENT '书ID',
  `ctime` int(11) DEFAULT NULL COMMENT '收藏时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户收藏书籍列表';

-- ----------------------------
-- Table structure for mw_book_comment
-- ----------------------------
DROP TABLE IF EXISTS `mw_book_comment`;
CREATE TABLE `mw_book_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `book_id` int(11) DEFAULT NULL COMMENT '书ID',
  `reply_id` int(11) DEFAULT '0' COMMENT '回复评论ID',
  `content` tinyint(4) DEFAULT NULL COMMENT '点评内容',
  `yes` int(11) DEFAULT '0' COMMENT '点评有用统计',
  `bad` int(11) DEFAULT '0' COMMENT '坏',
  `reply_num` int(11) DEFAULT '0' COMMENT '点评回复数',
  `uptime` int(11) DEFAULT NULL COMMENT 'ctime',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='书籍评论';

-- ----------------------------
-- Table structure for mw_book_download_log
-- ----------------------------
DROP TABLE IF EXISTS `mw_book_download_log`;
CREATE TABLE `mw_book_download_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) DEFAULT NULL COMMENT '小说ID',
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `name` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '下载小说名',
  `ctime` int(11) DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for mw_book_extend_field
-- ----------------------------
DROP TABLE IF EXISTS `mw_book_extend_field`;
CREATE TABLE `mw_book_extend_field` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(20) DEFAULT NULL COMMENT '扩展名称 key （英文）',
  `name` varchar(20) DEFAULT NULL COMMENT '扩展名称　name (中文）',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态　1.显示　0.隐藏',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='书籍扩展属性字段表';

-- ----------------------------
-- Table structure for mw_book_extend_info
-- ----------------------------
DROP TABLE IF EXISTS `mw_book_extend_info`;
CREATE TABLE `mw_book_extend_info` (
  `book_id` int(10) unsigned NOT NULL COMMENT '书ID',
  `extend_id` int(11) DEFAULT NULL COMMENT '书集扩展信息ID',
  `value` varchar(30) DEFAULT NULL COMMENT '值',
  PRIMARY KEY (`book_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='书籍扩展属性信息';

-- ----------------------------
-- Table structure for mw_book_grade
-- ----------------------------
DROP TABLE IF EXISTS `mw_book_grade`;
CREATE TABLE `mw_book_grade` (
  `book_id` int(10) unsigned NOT NULL COMMENT '书ID',
  `uid` int(10) unsigned DEFAULT NULL COMMENT '用户ID',
  `value` tinyint(3) unsigned DEFAULT NULL COMMENT '10分制',
  PRIMARY KEY (`book_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户对书籍评分表';

-- ----------------------------
-- Table structure for mw_book_tag
-- ----------------------------
DROP TABLE IF EXISTS `mw_book_tag`;
CREATE TABLE `mw_book_tag` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cateid` int(11) DEFAULT '0' COMMENT '分类ID',
  `name` varchar(60) DEFAULT NULL COMMENT '标签名称',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态:1显示　2隐藏',
  `count` int(11) unsigned DEFAULT '0' COMMENT '标签下的书数量',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='标签表';

-- ----------------------------
-- Table structure for mw_friend_url
-- ----------------------------
DROP TABLE IF EXISTS `mw_friend_url`;
CREATE TABLE `mw_friend_url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) CHARACTER SET utf8 DEFAULT NULL COMMENT '名称',
  `url` varchar(120) CHARACTER SET utf8 DEFAULT NULL COMMENT '链接地址',
  `sort` tinyint(3) DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for mw_gather_web_cate
-- ----------------------------
DROP TABLE IF EXISTS `mw_gather_web_cate`;
CREATE TABLE `mw_gather_web_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cateid` int(11) DEFAULT NULL COMMENT '分类',
  `web_url` varchar(120) DEFAULT NULL COMMENT '基本网址',
  `cate_url` varchar(120) DEFAULT NULL COMMENT '分类url',
  `page_param` varchar(50) DEFAULT NULL COMMENT '小说名',
  `name_area` varchar(200) DEFAULT NULL COMMENT '采集的区域',
  `name_filter` varchar(300) DEFAULT NULL COMMENT '采集规则',
  `chapter_area` varchar(50) DEFAULT NULL COMMENT '章节采集区域',
  `chapter_filter` varchar(300) DEFAULT NULL COMMENT '章节采集规则',
  `content_area` varchar(50) DEFAULT NULL COMMENT '内容采集区域',
  `content_filter` varchar(300) DEFAULT NULL COMMENT '内容采集规则',
  `status` tinyint(1) DEFAULT '0' COMMENT '采集状态（1.每天，0.手动）',
  `uptime` int(11) DEFAULT NULL COMMENT '时间',
  `charset` varchar(50) DEFAULT NULL COMMENT '字符编码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mw_gather_web_chapter
-- ----------------------------
DROP TABLE IF EXISTS `mw_gather_web_chapter`;
CREATE TABLE `mw_gather_web_chapter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) DEFAULT '0' COMMENT '小说ID',
  `name` varchar(50) DEFAULT NULL COMMENT '小说名称',
  `title` varchar(200) DEFAULT NULL COMMENT '章节名称',
  `content` text COMMENT '内容',
  `uptime` int(11) DEFAULT NULL COMMENT '时间',
  `is_seed` tinyint(1) DEFAULT '0' COMMENT '是否发布',
  `filter` varchar(100) DEFAULT NULL COMMENT '内容过滤规则',
  `url` varchar(120) DEFAULT NULL COMMENT '章节链接地址',
  `charset` varchar(50) DEFAULT NULL COMMENT '字符编码',
  PRIMARY KEY (`id`),
  KEY `chapter-name` (`name`,`title`)
) ENGINE=MyISAM AUTO_INCREMENT=10174 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mw_gather_web_name
-- ----------------------------
DROP TABLE IF EXISTS `mw_gather_web_name`;
CREATE TABLE `mw_gather_web_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) DEFAULT '0' COMMENT '小说ID',
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `url` varchar(120) DEFAULT NULL COMMENT '小说url',
  `author` varchar(50) DEFAULT NULL COMMENT '作者',
  `chapter_filter` varchar(300) DEFAULT NULL COMMENT '采集规则',
  `chapter_area` varchar(50) DEFAULT NULL COMMENT '采集章节区域',
  `content_filter` varchar(300) DEFAULT NULL COMMENT '内容过滤',
  `content_area` varchar(50) DEFAULT NULL COMMENT '内容',
  `status` tinyint(1) DEFAULT '1' COMMENT '1.采集，0.不采集',
  `cateid` int(11) DEFAULT NULL COMMENT '分类',
  `charset` varchar(50) DEFAULT NULL COMMENT '字符编码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=121 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mw_grade
-- ----------------------------
DROP TABLE IF EXISTS `mw_grade`;
CREATE TABLE `mw_grade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 DEFAULT NULL COMMENT '会员等级名称',
  `level` tinyint(2) DEFAULT '0' COMMENT '等级',
  `grade` int(11) DEFAULT NULL COMMENT '需要的分数',
  `type` tinyint(2) DEFAULT NULL COMMENT '类型',
  `face` varchar(120) CHARACTER SET utf8 DEFAULT NULL COMMENT '会员等级头像',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for mw_grade_type
-- ----------------------------
DROP TABLE IF EXISTS `mw_grade_type`;
CREATE TABLE `mw_grade_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '类型名称',
  `alias` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '别名(一般为英文）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for mw_member
-- ----------------------------
DROP TABLE IF EXISTS `mw_member`;
CREATE TABLE `mw_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uname` varchar(35) NOT NULL COMMENT '用户名',
  `email` varchar(120) DEFAULT NULL COMMENT '邮箱',
  `passwd` varchar(40) NOT NULL COMMENT '密码',
  `role` tinyint(2) DEFAULT NULL COMMENT '角色',
  `avatar` varchar(120) DEFAULT NULL COMMENT '头像　150*150',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态　0未激活  ,.1激活 , -1禁用',
  `sex` tinyint(1) DEFAULT '1' COMMENT '性别　1男　2.女　3.无性别',
  `qq` varchar(15) DEFAULT NULL COMMENT 'QQ',
  `home` varchar(120) DEFAULT NULL COMMENT '个人主页',
  `lasttime` int(11) DEFAULT NULL COMMENT '最后登入时间',
  `lastip` varchar(20) DEFAULT NULL COMMENT '最后登入IP',
  `registertime` int(11) DEFAULT NULL COMMENT '注册时间',
  `grade` int(11) DEFAULT '0' COMMENT '积分',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='会员表';

-- ----------------------------
-- Table structure for mw_member_grade
-- ----------------------------
DROP TABLE IF EXISTS `mw_member_grade`;
CREATE TABLE `mw_member_grade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `type` tinyint(2) NOT NULL,
  `integral` int(11) NOT NULL DEFAULT '0' COMMENT '分数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for mw_system_init_config
-- ----------------------------
DROP TABLE IF EXISTS `mw_system_init_config`;
CREATE TABLE `mw_system_init_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT '0' COMMENT '父ID',
  `name` varchar(50) NOT NULL COMMENT '配置名称',
  `key` varchar(255) DEFAULT NULL COMMENT '设置key',
  `value` varchar(50) NOT NULL COMMENT '配置值',
  `explain` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `model` varchar(15) DEFAULT NULL COMMENT '配置模块(全局配置　Public)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统初始化配置';

-- ----------------------------
-- Table structure for mw_txt_chapter_tmp
-- ----------------------------
DROP TABLE IF EXISTS `mw_txt_chapter_tmp`;
CREATE TABLE `mw_txt_chapter_tmp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txtid` int(11) DEFAULT NULL COMMENT '小说ID',
  `name` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '小说名称',
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '章节名称',
  `content` text CHARACTER SET utf8 COMMENT '内容',
  `uptime` int(11) DEFAULT NULL COMMENT '更新时间',
  `size` int(11) DEFAULT NULL COMMENT '大小',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for mw_txt_file
-- ----------------------------
DROP TABLE IF EXISTS `mw_txt_file`;
CREATE TABLE `mw_txt_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '小说ID',
  `chapter` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '章节正则',
  `filter` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '关键字过滤',
  `name` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '小说名称',
  `path` varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '路径',
  `cateid` int(11) DEFAULT NULL COMMENT '分类ID',
  `author` varchar(25) CHARACTER SET utf8 DEFAULT NULL COMMENT '作者',
  `uptime` int(11) DEFAULT NULL COMMENT '时间',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态，（1.已添加，0.未添加）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
