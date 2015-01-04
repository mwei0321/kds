<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain:  会员管理
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: ONLY <1123265518@qq.com>
	*  +----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2014-8-3 	
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :  http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/
	namespace Library;
	
	class Member{
		protected $member;
		function __construct(){
			$this->member = M('Member');
		}
		
		/**
		* 会员列表
		* @param  string $_limit 条数
		* @param  array $_where 附加条件
		* @param  string $_order 排序
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-3  下午12:36:50
		*/
		function getMemberList($_limit = 'count',$_where = array(),$_order = 'lasttime DESC'){
			if($_limit == 'count'){
				$count = $this->member->where($_where)->count();
				return $count;
			}
			$list = $this->member->where($_where)->order($_order)->limit($_limit)->select();
// 			echo  $this->member->getlastsql();
			return $list;
		}
		
		/**
		 * 设置会员状态
		 * @param int $_uid 用户ID
		 * @param int $_status 1为启用，0为禁用
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-8-4 下午6:08:06
		 */
		function setUserStatus($_uid,$_status = 1){
			$reid = $this->member->where(array('id'=>$_uid))->setField('status',$_status);
// 			echo $this->member->getlastsql();
			return $reid;
		}
		
		/**
		 * 会员详情
		 * @param int|array $_uid 会员ID 
		 * @param string $_field 字段 
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-8-4 下午3:05:11
		 */
		function getMemberInfo($_uid,$_field = '*'){
			$where = array();
			if(is_array($_uid)){
				$where['id'] = array('IN',implode(',', $_uid));
			}else{
				$where['id'] = $_uid;
			}
			$info = $this->member->field($_field)->where($where)->select();
			$info = fieldtokey($info);
// 			echo $this->member->getlastsql();
			return $info;
		}
		
		
		/**
		 * 检测用户
		 * @param string $_name
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-8-13 下午5:13:33
		 */
		function checkname($_name){
			$reid = $this->member->field('id')->where(array('uname'=>$_name))->find();
			return $reid;
		}
		
		/**
		 * 检测邮箱
		 * @param string $_email
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-8-13 下午5:13:53
		 */
		function checkemail($_email){
			$where = array();
			$where['email'] = array('LIKE',"%".$_email."%");
			$reid = $this->member->field('id')->where($where)->find();
			return $reid;
		}
		
		/**
		 * 用户的收藏书集
		 * @param int $_uid 用户ID
		 * @param string $_limit 条数
		 * @param string $_order 排序
		 * @param array $_where 附加条件
		 * @return array 用户的收藏书集
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-7-30 下午6:00:53
		 */
		function userCollect($_uid,$_limit = 'count',$_order = 'point DESC',$_where = array()){
			$m = M('NovelCollect');
			$where = array();
			$where = empty($_where) ? array('status'=>1) : array_merge($where,$_where);
			if($_limit == 'count'){
				$count = $m->field('id')->where($where)->count();
			}
			$colloect = $m->where($where)->order('count DESC')->select();
			return $colloect;
		}
		
		/**
		 * 收藏该书的用户
		 * @param int $_uid 用户ID
		 * @param string $_limit 条数
		 * @param string $_order 排序
		 * @param array $_where 附加条件
		 * @return array 用户的收藏书集
		 * @date 2014-7-30 下午6:09:15
		 */
		function collectBookUser($_bookid,$_limit = 'count',$_order = 'point DESC',$_where = array()){
			$m = M('NovelCollect');
			$where = array();
			$where['book_id'] = $_bookid;
			$where = empty($_where) ? array('status'=>1) : array_merge($where,$_where);
			$colloect = $m->where($where)->order('count DESC')->select();
			return $colloect;
		}
		
		/**
		 * 密码加密
		 * @param string $_passwd
		 * @return string
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-9-9 上午10:16:16
		 */
		function encrypt($_passwd){
			return sha1('Ma'.md5($_passwd).'Wei'); 
		}
		
		/**
		* 用户登入验证
		* @param  string $_name 用户名 
		* @param  string $_passwd 密码
		* @return array $info
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-9-20  下午3:05:30
		*/
		function login($_name,$_passwd){
			$where = array();
			$where['status'] = 1;
			$passwd = $this->encrypt($_passwd);
			$where['_string'] = '`uname`="'.$_name.'" or `email`="'.$_name.'"';
// 			$where['_string'] = '(`uname`="'.$_name.'" AND `passwd`="'.$passwd.'") or (`email`="'.$_name.'" AND `passwd`="'.$passwd.'")';
			$info = $this->member->where($where)->find();
// 			echo $this->member->getlastsql();
			if($info && $info['passwd'] == $passwd){
				return $info;
			}else{
				return null;
			}
		}
	}