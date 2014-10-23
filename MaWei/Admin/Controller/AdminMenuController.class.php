<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain: 后台菜单管理
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: MaWei <1123265518@qq.com>
	*	+----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2014-8-2 下午4:27:21
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :		http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/
	
	namespace Admin\Controller;
	use Admin\Controller\PubAdminController;
	use Library\AdminMenu;
		
	class AdminMenuController extends PubAdminController{
		protected $adminmenu;
		
		function _init(){
			parent::_init();
			$this->adminmenu = new AdminMenu();
			$position = array('top'=>'顶部菜单','left'=>'左则菜单');
			$this->assign('position',$position);
		}
		
		function index(){
			$menulist = $this->adminmenu->getMenu();
			$this->assign('menu',$menulist);
			$this->display();
		}
		
		/**
		* 菜单编辑、添加
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-2  下午10:23:57
		*/
		function edit(){
			$parentmenu = $this->adminmenu->getMenu();
			if($_REQUEST['menuid']){
				$info = $this->adminmenu->getMenuInfo(intval($_REQUEST['menuid']));
				$this->assign('info',$info);
			}
			$this->assign('menu',$parentmenu);
			$this->display();
		}
		
		/**
		* 数据添加、修改
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-3  上午2:05:46
		*/
		function addupdata(){
			$data = array();
			$_REQUEST['id'] && $data['id'] = intval($_REQUEST['id']);
			$data['key'] = h(text($_REQUEST['key']));
			$data['value'] = h(text($_REQUEST['value']));
			$data['position'] = h(text($_REQUEST['position']));
			$data['position'] = h(text($_REQUEST['url']));
			$data['status'] = intval($_REQUEST['status']);
			$data['sort'] = intval($_REQUEST['sort']);
			$data['pid'] = intval($_REQUEST['pid']);
			$reid = add_updata($data,'AdminSystemMenu| ');
			if($reid === false){
				$this->error('保存失败！',U('Admin/AdminMenu/edit',array('menuid'=>intval($_REQUEST['id']))));
			}else{
				$this->success('保存成功！',U('Admin/AdminMenu/index'));
			}
		}
	}