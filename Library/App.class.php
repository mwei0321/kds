<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain: App 管理类
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: MaWei <1123265518@qq.com>
	*	+----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2014-10-8 下午4:33:37
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :		http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/

	namespace Library;
	
	class App {
		protected $app;
		function __construct(){
			$this->app = M('App');
		}
		
		/**
		 * 返回app列表
		 * @param array $_where
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-10-8 下午4:37:21
		 */
		function getAppList($_where = array()){
			$list = $this->app->where($_where)->select();
			return $list;
		}
		
		/**
		 * 返回app详情
		 * @param int $_aid App ID
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-10-8 下午4:38:51
		 */
		function getAppInfo($_aid){
			$info = $this->app->where(array('id'=>$_aid))->find();
			return $info;
		}
		
		/**
		 * 返回未安装的APP名
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-10-8 下午5:42:38
		 */
		function getUninstallAppName(){
			//检测app安装目录里的APP
			$file = getDirFile(C('INSTALLAPP_APP'));
			$file = arrtolower($file);
			//已安装的APP
			$is_exsit = $this->getAppList();
			$is_exsit = arr2to1($is_exsit,'app_name');
			//取差集
			$unexsit = array_diff($file, $is_exsit);
			return $unexsit;
		}
	}