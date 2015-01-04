<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain:  后台会员管理类
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: ONLY <1123265518@qq.com>
	*  +----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2014-8-1 	
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :	http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/
	namespace Library;

	class AdminUser{
		protected $admin;
		function __construct(){
			$this->admin = M('AdminUser');
		}
		
		/**
		 * 返回管理员列表
		 * @param array
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-10-8 下午4:07:51
		 */
		function getAdminUser($_where = array()){
			$list = $this->admin->where($_where)->select();
			return $list;
		}
		
		/**
		 * 管理员登入
		 * @param string $_name 用户名
		 * @param string $_passwd 密码
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-10-8 下午4:11:42
		 */
		function login($_name,$_passwd){
			$info = $this->admin->where(array('name'=>$_name,'status'=>1))->find();
			if($info){
				if($info['passwd'] == $this->encrypt($_passwd)){
					unset($info['passwd']);
					return $info;
				}else{
					return -1;
				}
			}else{
				return null;
			}
		}
		
		/**
		 * 密码加密
		 * @param string $_passwd
		 * @return string
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-9-9 上午10:16:16
		 */
		function encrypt($_passwd){
			return sha1('Ma'.sha1(md5($_passwd).'Wei')); 
		}
	}