/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : mw_kandianshu

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2014-11-19 00:41:32
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
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='后台菜单设置';

-- ----------------------------
-- Records of mw_admin_system_menu
-- ----------------------------
INSERT INTO `mw_admin_system_menu` VALUES ('1', '0', '分类管理', 'CateTag', 'index', 'Admin/CateTag/index?type=cate', '1', '98', '0');
INSERT INTO `mw_admin_system_menu` VALUES ('2', '1', '分类列表', 'CateTag', 'cate', 'Admin/CateTag/index?type=cate', '1', '97', '1');
INSERT INTO `mw_admin_system_menu` VALUES ('5', '0', '会员管理', 'Member', 'index', 'Admin/Member/index', '1', '93', '0');
INSERT INTO `mw_admin_system_menu` VALUES ('4', '1', '标签管理', 'CateTag', 'tag', 'Admin/CateTag/index?type=tag', '1', '95', '1');
INSERT INTO `mw_admin_system_menu` VALUES ('6', '0', '小说管理', 'Book', 'index', 'Admin/Book/index', '1', '99', '0');
INSERT INTO `mw_admin_system_menu` VALUES ('7', '6', '小说列表', 'Book', 'index', 'Admin/Book/index', '1', '99', '1');
INSERT INTO `mw_admin_system_menu` VALUES ('8', '0', '其它设置', 'Other', 'index', 'Admin/Other/index', '1', '50', '0');
INSERT INTO `mw_admin_system_menu` VALUES ('9', '8', '前台菜单', 'Other', 'index', 'Admin/Other/index', '1', '99', '1');
INSERT INTO `mw_admin_system_menu` VALUES ('10', '8', '友情链接', 'Other', 'friendurl', 'Admin/Other/friendurl', '1', '97', '1');
INSERT INTO `mw_admin_system_menu` VALUES ('11', '0', '采集管理', 'Gather', 'index', 'Admin/Gather/index', '1', '30', '0');
INSERT INTO `mw_admin_system_menu` VALUES ('12', '11', '添加采集', 'Gather', 'index', 'Admin/Gather/index', '1', '99', '1');
INSERT INTO `mw_admin_system_menu` VALUES ('13', '11', '采集处理', 'Gather', 'edit', 'Admin/Gather/edit', '1', '96', '1');

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
-- Records of mw_admin_user
-- ----------------------------

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
-- Records of mw_app
-- ----------------------------

-- ----------------------------
-- Table structure for mw_book
-- ----------------------------
DROP TABLE IF EXISTS `mw_book`;
CREATE TABLE `mw_book` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cateid` int(11) DEFAULT NULL COMMENT '分类ID',
  `tagid` varchar(50) DEFAULT NULL COMMENT '标签ID,多个用，分开',
  `title` varchar(60) DEFAULT NULL COMMENT '标题',
  `cover` varchar(120) DEFAULT NULL COMMENT '封面',
  `author` varchar(50) DEFAULT NULL COMMENT '作者',
  `words_num` int(11) DEFAULT NULL COMMENT '字数',
  `point` int(11) DEFAULT NULL COMMENT '平均评分',
  `intro` text COMMENT '简介',
  `comment_num` int(11) DEFAULT NULL COMMENT '点评人数',
  `status` tinyint(4) DEFAULT NULL COMMENT '文章状态　－1临时保存　0隐藏　1显示',
  `end_status` tinyint(4) DEFAULT NULL COMMENT '0连载中……　1完结',
  `uptime` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mw_book
-- ----------------------------

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
-- Records of mw_book_attach
-- ----------------------------

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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='分类表';

-- ----------------------------
-- Records of mw_book_category
-- ----------------------------
INSERT INTO `mw_book_category` VALUES ('1', '0', '0', '玄幻　奇幻', '1', '', '1');
INSERT INTO `mw_book_category` VALUES ('2', '0', '0', '科幻　灵异', '1', '', '1');
INSERT INTO `mw_book_category` VALUES ('3', '0', '0', '仙侠　武侠', '1', '', '1');
INSERT INTO `mw_book_category` VALUES ('4', '0', '0', '都市　言情', '1', '', '1');
INSERT INTO `mw_book_category` VALUES ('5', '0', '0', '历史　军事', '1', '', '1');
INSERT INTO `mw_book_category` VALUES ('6', '0', '0', '网游　竞技', '1', '', '1');
INSERT INTO `mw_book_category` VALUES ('7', '0', '0', '悬疑　侦探', '1', '', '1');
INSERT INTO `mw_book_category` VALUES ('8', '0', '0', '美文　同人', '1', '', '1');

-- ----------------------------
-- Table structure for mw_book_chapter_t1
-- ----------------------------
DROP TABLE IF EXISTS `mw_book_chapter_t1`;
CREATE TABLE `mw_book_chapter_t1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) DEFAULT NULL COMMENT '书籍ID',
  `chapter` varchar(120) DEFAULT NULL COMMENT '第几章',
  `title` varchar(100) DEFAULT NULL COMMENT '章节名称',
  `content` text COMMENT '章节内容',
  `ctime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='书籍的具体章节';

-- ----------------------------
-- Records of mw_book_chapter_t1
-- ----------------------------

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
-- Records of mw_book_collect
-- ----------------------------

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
  `reply_count` int(11) DEFAULT NULL COMMENT '点评有用统计',
  `reply_num` int(11) DEFAULT NULL COMMENT '点评回复数',
  `ctime` int(11) DEFAULT NULL COMMENT 'ctime',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='书籍评论';

-- ----------------------------
-- Records of mw_book_comment
-- ----------------------------

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
-- Records of mw_book_extend_field
-- ----------------------------

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
-- Records of mw_book_extend_info
-- ----------------------------

-- ----------------------------
-- Table structure for mw_book_gather_tmp
-- ----------------------------
DROP TABLE IF EXISTS `mw_book_gather_tmp`;
CREATE TABLE `mw_book_gather_tmp` (
  `id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL COMMENT '小说ID',
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '章节名称',
  `chapter` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '章节',
  `content` text CHARACTER SET utf8 COMMENT '内容',
  `uptime` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of mw_book_gather_tmp
-- ----------------------------

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
-- Records of mw_book_grade
-- ----------------------------

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
-- Records of mw_book_tag
-- ----------------------------

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of mw_friend_url
-- ----------------------------
INSERT INTO `mw_friend_url` VALUES ('1', 'phpyrb', 'http://www.phpyrb.com', '99');

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
-- Records of mw_grade
-- ----------------------------

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
-- Records of mw_grade_type
-- ----------------------------

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
-- Records of mw_member
-- ----------------------------
INSERT INTO `mw_member` VALUES ('1', 'mw', 'mw@qq.com', 'cc2b9d7f8e654abde2accd55f62ca37f87295fc8', null, 'Uploads/avatar/2014-11/www.phpyrb.com2014110111114641e77-20141101181146408.jpg', '1', '1', '0', '', '1414839286', '127.0.0.1', '1414839286', '12312');

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
-- Records of mw_member_grade
-- ----------------------------

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
-- Records of mw_system_init_config
-- ----------------------------

-- ----------------------------
-- Table structure for mw_txt_filter
-- ----------------------------
DROP TABLE IF EXISTS `mw_txt_filter`;
CREATE TABLE `mw_txt_filter` (
  `book_id` int(11) NOT NULL COMMENT '小说ID',
  `chapter` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '章节正则',
  `filter` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '关键字过滤',
  PRIMARY KEY (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of mw_txt_filter
-- ----------------------------
