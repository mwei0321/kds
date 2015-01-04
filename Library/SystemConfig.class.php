<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain:  后台系统配置类
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: ONLY <1123265518@qq.com>
	*  +----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2014-8-26 	
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :		http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/

	namespace Library;
	
	class SystemConfig {
		protected $AdminMenu,$HomeMenu,$menu;
		function __construct(){
			$this->AdminMenu = M('AdminSystemMenu');
			$this->HomeMenu = M('HomeMenu');
			$this->menu = array();
		}
		
		
		/**
		 * 返回返回菜单列表
		 * @param  int $_pid　父级ID
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-8-2  下午9:45:07
		 */
		function getAdminMenu($_where = array()){
			$where = array();
			$menulist = $this->AdminMenu->where($_where)->order('sort DESC')->select();
			$this->menu = array();
			$this->level($menulist);
			return $this->menu;
		}
		
		/**
		 * 跟ID返回菜单信息
		 * @param  int $_menuid 菜单ID
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-8-2  下午9:57:44
		 */
		function getMenuInfo($_menuid){
			$menuinfo = $this->AdminMenu->where(array('id'=>$_menuid))->find();
			return $menuinfo;
		}
		
		/**
		* 返回菜单层级
		* @param  array　$_menu 
		* @param  int $_pid
		* @param  int $_level
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-9-12  下午10:19:12
		*/
		function level($_menu,$_pid = 0,$_level=0){
			foreach ($_menu as $k => $v){
				if($v['pid'] == $_pid){
					$this->menu[$k] = $v;
					$this->menu[$k]['level'] = $_level;
					$this->menu[$k]['str'] = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;|----', $_level);
					unset($_menu[$k]);
					$this->level($_menu,$v['id'],$_level+1);
				}
			}
			return $this->menu;
		}
		
		/**
		* 
		* @param  array 
		* @param  string 
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-9-12  下午10:19:46
		*/
		function _menu($_menu,$_level = 0){
			$temp = array();
			foreach ($_menu as $k => $v){
				if($v['pid'] == 0){
					$temp['topmenu'][$v['key']] = $v;
				}else{
					$temp['leftmenu'][$v['key']][] = $v;
				}
			}
			return $temp;
		}
	}