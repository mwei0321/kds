<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain:  后台初始公共控制器
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: ONLY <1123265518@qq.com>
	*  +----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2014-7-31 	
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :	http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/
	namespace Admin\Controller;
	use Think\Controller;
	use Library\SystemConfig;
	use Library\BookCateTag;
	
	class PubAdminController extends Controller{
		protected $Sourc,$uid,$CateTag,$System;
		function _init(){
			$this->uid = $_SESSION['AdminID'] = 1;
			if(! $this->uid){
				header("Location:".U('Login/index'));
			}
			$this->CateTag = new BookCateTag();
			//后台菜单列表
// 			if(!S('Menu')){
				$this->System = new SystemConfig();
				$menu = $this->System->getAdminMenu();
				S('Menu',$this->System->_menu($menu));
// 			}
			if(!S('CateList')){
				$catelist = $this->CateTag->level();
				S('CateList',$catelist);
			}
			$this->diy_menu();
			
// 			dump(S('Menu'));
			$this->assign('menu',S('Menu'));
			$this->diy_menu();
			$this->assign('catelist',S('CateList'));
		}
		
		
		/**
		 * 菜单设置
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-8-13 下午12:24:24
		 */
		function diy_menu(){
			$diymenu = array(
				'topmenu' => array(
					'System' => array('系统设置',U('Admin/System/index'))
	 			),
				'leftmenu' => array(
					'System' => array(
							array('系统设置',U('Admin/System/index'),'index'),
							array('后台菜单设置',U('Admin/System/adminmenu'),'adminmenu'),
					)
				)
			);
			$this->assign('diymenu',$diymenu);
		}
		
	}