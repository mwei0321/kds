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
	use Admin\Controller\PubAdminController;
	use Library\SystemConfig;
	use Library\App;
	
	class SystemController extends PubAdminController{
		protected $system,$app;
		function _init(){
			parent::_init();
			$this->system = new SystemConfig();
			$this->app = new App();
			$this->assign('position',array('顶部','左则'));
		}
		
		function index(){
			$this->display();
		}
		
		/**
		* 后台菜单设置
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-10-5  上午10:53:46
		*/
		function adminmenu(){
			$menu = $this->system->getAdminMenu();
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
			$info = $pmenu = array();
			$template = null;
			switch ($type){
				case 'AdminMenu':
					if($id){
						$info = $this->system->getMenuInfo($id);
					}
					$pmenu = $this->system->getAdminMenu(array('position'=>0,'status'=>1));
					$template = 'menuedit';
					$this->assign('pmenu',$pmenu);
					break;
				default:
					exit();
			}
			$this->assign('type',$type);
			$this->assign('info',$info);
			$this->display($template);
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
		 * 已安装APP列表
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-10-8 下午4:52:13
		 */
		function installapp(){
			$list = $this->app->getAppList();
			$this->assign('list',$list);
			$this->display();
		}
		
		/**
		 * 未安装APP列表
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-10-8 下午4:52:31
		 */
		function uninstallapp(){

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
			$reid = $url = false;
			switch ($type) {
				case 'AdminMenu' :
					$data['pid'] = intval($_REQUEST['pid']);
					$data['name'] = text($_REQUEST['name']);
					$data['key'] = text($_REQUEST['key']);
					$data['position'] = $data['pid'] == 0 ? 0 : 1;
// 					$data['position'] = intval($_REQUEST['position']);
					$data['sort'] = intval($_REQUEST['sort']);
					$data['action'] = text($_REQUEST['action']);
					$data['url'] = $_REQUEST['url'];
					$data['status'] = $_REQUEST['status'] ? intval($_REQUEST['status']) : 1;
					$reid = add_updata($data,'AdminSystemMenu');
					$url = U('Admin/System/adminmenu');
					S('Menu',null);
					break;
				default:
					exit();
			}	
			if($reid === false){
				$this->error('添加修改失败！');
			}else{
				$this->success('添加修改成功！');
			}
		}
	}