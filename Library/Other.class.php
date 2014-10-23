<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain:  网站其它相关操作
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: ONLY <1123265518@qq.com>
	*  +----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2014-10-5 	
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :		http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/

	namespace Library;
	
	class Other {
		protected $HomeMenu;
		
		function __construct(){
			$this->HomeMenu = M('HomeMenu');
		}
		
		/**
		 *　前台导航菜单
		 * @param  array　$_where
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-10-1  下午7:34:42
		 */
		function getHomeMenu($_where = array()){
			$menulist = $this->HomeMenu->where($_where)->order('sort DESC')->select();
			return $menulist;
		}
		
		/**
		* 返回菜单信息
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-10-5  上午10:52:32
		*/
		function getMenuInfo($_menuid){
			$menuinfo = $this->HomeMenu->where(array('id'=>$_menuid))->find();
			return $menuinfo;
		}
		
		/**
		* 返回友情链接
		* @param  array $_where
		* @param  string $_limit
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-10-5  下午12:09:29
		*/
		function getFriendUrl($_where = array(),$_limit = 'all'){
			$m = M('FriendUrl');
			if($_limit == 'all'){
				$list = $m->where($_where)->order('sort DESC,id DESC')->select();
			}else{
				$list = $m->where($_where)->order('sort DESC,id DESC')->limit($_limit)->select();
			}
			return $list;
		}
		
		/**
		* 根据ID友情详情
		* @param  int $_id 
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-10-5  下午12:11:01
		*/
		function getFriendUrlInfo($_id){
			$m = M('FriendUrl');
			$info = $m->where(array('id'=>$_id))->find();
			return $info;
		}
	}

	