<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain:  系统设置
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: ONLY <1123265518@qq.com>
	*  +----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2014-8-3 	
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :  http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/

	namespace Admin\Controller;
	use Admin\Controller\IniController;
	use Library\SystemConfig;
	
	class SystemController extends IniController{
		protected $system;
		function _init(){
			parent::_init();
			$this->system = new SystemConfig();
			$this->assign('position',array('顶部','左则'));
		}
		
		function index(){
			$this->display();
		}
		
		function menu(){
			$menu = $this->system->getMenu();
			$this->assign('list',$menu);
			$this->display();
		}
		
		/**
		* 添加，修改编辑页
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-9-8  下午1:17:32
		*/
		function edit(){
			$type = $_REQUEST['type'] ? text($_REQUEST['type']) : exit(null);
			$id = intval($_REQUEST['id']);
			$info = array();
			switch ($type){
				case 'menu':
					if($id){
						$info = $this->system->getMenuInfo($id);
						$this->assign('info',$info);
					}
					$this->display('menuedit');
					break;
			}
		}
		
		/**
		* 删除操作
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-9-7  下午10:38:57
		*/
		function delete(){
			$type = $_REQUEST['type'] ? text($_REQUEST['type']) : exit(null);
			$_ids = intval($_REQUEST['ids']);
			switch ($type) {
				case 'menu' :
					$reid = delall($_ids, 'AdminSystemMenu');
					if($reid === false){
						echo null;
					}else{
						echo 1;
					}
					break;
			}
		}
		
		/**
		* 更新到数据库操作
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-9-7  下午8:47:33
		*/
		function add_updata(){
			$type = $_REQUEST['type'] ? text($_REQUEST['type']) : exit(null);
			$data = array();
			$_REQUEST['id'] && $id = $data['id'] = intval($_REQUEST['id']);
			switch ($type) {
				case 'menu' :
					$data['pid'] = intval($_REQUEST['pid']);
					$data['name'] = text($_REQUEST['name']);
					$data['key'] = text($_REQUEST['key']);
					$data['position'] = $data['pid'] == 0 ? 0 : 1;
// 					$data['position'] = intval($_REQUEST['position']);
					$data['sort'] = intval($_REQUEST['sort']);
					$data['action'] = intval($_REQUEST['action']);
					$data['url'] = $_REQUEST['url'];
					$data['status'] = $_REQUEST['status'] ? intval($_REQUEST['status']) : 1;
					$reid = add_updata($data,'AdminSystemMenu');
					if($reid === false){
						$this->error('添加失败！',U('Admin/System/menu',array('id'=>$id)));
					}else{
						$this->success('添加成功！',U('Admin/System/menu'));
					}
					break;
			}
			
		}
	}